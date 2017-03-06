<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Adminpackages;
use App\Models\Admingeographies;
use App\Models\Travel\FareType;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\Geography;
use App\Models\Travel\Hotel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;


class AdminpackagesController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();
	public $module = 'adminpackages';
	static $per_page	= '10';

	public function __construct()
	{

		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Adminpackages();

		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'adminpackages',
			'return'	=> self::returnUrl()

		);

	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'idx');
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
		// End Filter sort and order for query
		// Filter Search for query
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');


		$page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query
		$results = $this->model->getRows( $params );

		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
		$pagination->setPath('adminpackages');

		$this->data['rowData']		= $results['rows'];
		// Build Pagination
		$this->data['pagination']	= $pagination;
		// Build pager number and append current param GET
		$this->data['pager'] 		= $this->injectPaginate();
		// Row grid Number
		$this->data['i']			= ($page * $params['limit'])- $params['limit'];
		// Grid Configuration
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);
		// Group users permission
		$this->data['access']		= $this->access;
		// Detail from master if any

		// Master detail link if any
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
		// Render into template
		return view('adminpackages.index',$this->data);
	}



	function getUpdate(Request $request, $id = null)
	{

		$isLocal = false;
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}

		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}

		$row = $this->model->where('idx','=',$id)->first();
		//dd($row);
		if($row)
		{
			if($row->soap_client == "LOCAL"){
				$isLocal = true;
			} else {
				$row->destination_name = Geography::find($row->destination)->name;
				$row->hotel_name = Hotel::where('id','=',$row->id_hotel)->where('soap_client','=',$row->soap_client)->first()->name;
			}
			//dd($row);
			$row->detailed_descriptions = $this->model->getDetailedDescriptionsPackage($row->id,$row->soap_client);
			$row->prices = $this->model->getPricesForPackage($row->id,$row->soap_client);
			//dd($row);
			$row->pricesJSON = json_encode($this->model->getPricesForPackageArray($row->id,$row->soap_client));
			$row->fareTypes = FareType::all();
			$selectedFareTypes = \DB::table('packages_fare_types')->where('id_package','=',$row->id)->where('soap_client','=',$row->soap_client)->get();
			$row->selectedFareTypes = "";
			foreach($selectedFareTypes as $fareType){
				$row->selectedFareTypes .= $fareType->id_fare_type.',';
			}
			$selectedCategories = \DB::table('package_categories')->where('id_package','=',$row->id)->where('soap_client','=',$row->soap_client)->get();
			$row->selectedCategories = "";
			foreach($selectedCategories as $category){
				$row->selectedCategories .= $category->id_category.',';
			}
			$row->isLocal = $isLocal;
			$this->data['row'] = $row;

		} else {
			$isLocal = true;
			$row = $this->model->getColumnTable('packages');

			$row['detailed_descriptions'] = array();
			$row['prices'] = array();
			$row['pricesJSON'] = json_encode(array());
			$row['fareTypes'] = FareType::all();
			$row['selectedFareTypes'] = '';
			$row['selectedCategories'] = '';
			$row['isLocal'] = $isLocal;
			$this->data['row'] = $row;
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);

		$this->data['id'] = $id;
		return view('adminpackages.form',$this->data);
	}

	public function getShow( $id = null)
	{

		if($this->access['is_detail'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('packages');
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);

		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('adminpackages.view',$this->data);
	}

	function postSave( Request $request)
	{
		//dd($request->all());
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);
		//dd($request->all());
		if ($validator->passes()) {

			$id = $this->model->updatePackage($request);

			if(!is_null($request->input('apply')))
			{
				$return = 'adminpackages/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'adminpackages?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('idx') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');

		} else {
			//dd('da');
			return Redirect::to('adminpackages/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}


	}

	public function postDelete( Request $request)
	{

		if($this->access['is_remove'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows
		if(count($request->input('ids')) >=1)
		{
			$this->model->destroy($request->input('ids'));

			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('adminpackages')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success');

		} else {
			return Redirect::to('adminpackages')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');
		}

	}


}
