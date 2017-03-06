<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Adminetripoperators;
use App\Models\Travel\EtripOperator;
use ET_SoapClient\SoapObjects\GeographySoapObject;
use ET_SoapClient\ET_SoapClient;
use App\Models\Travel\GeographyTemp;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;


class AdminetripoperatorsController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();
	public $module = 'adminetripoperators';
	static $per_page	= '10';

	public function __construct()
	{

		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Adminetripoperators();

		$this->info = $this->model->makeInfo( $this->module);
		//dd($this->info);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'adminetripoperators',
			'return'	=> self::returnUrl()

		);

	}

	public function getIndex( Request $request )
	{
//dd($this->access);
		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id_operator');
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
		$pagination->setPath('adminetripoperators');

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
		return view('adminetripoperators.index',$this->data);
		//return view('adminetripoperators.index');
	}



	function getUpdate(Request $request, $id = null)
	{

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

		$row = $this->model->find($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('etrip_operators');
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['forms']);

		$this->data['id'] = $id;
		return view('adminetripoperators.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('etrip_operators');
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);

		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('adminetripoperators.view',$this->data);
	}

	function postSave( Request $request)
	{
		//dd($request);
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$data = $this->validatePost('tb_adminetripoperators');
			$data['wsdl'] = $data['url'].'/ws.php?op=etrip_webservice&wsdl';
			$data['cached_prices_url'] = $data['url'].'/wscsv.php';
			$data['file_url'] = $data['url'].'/file.php?file=';
			$check = EtripOperator::where('id_operator','=',$data['id_operator'])->first();
			$etripOperator = $check ? $check : new EtripOperator;
			$etripOperator->id_operator = $data['id_operator'];
			$etripOperator->name_operator = $data['name_operator'];
			$etripOperator->url = $data['url'];
			$etripOperator->username = $data['username'];
			$etripOperator->password = $data['password'];
			$etripOperator->wsdl = $data['wsdl'];
			$etripOperator->cached_prices_url = $data['cached_prices_url'];
			$etripOperator->file_url = $data['file_url'];
			$etripOperator->save();
			$id = $etripOperator->id_operator;

			if(!is_null($request->input('apply')))
			{
				$return = 'adminetripoperators/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'adminetripoperators?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('id_operator') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');

		} else {

			return Redirect::to('adminetripoperators/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('adminetripoperators')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success');

		} else {
			return Redirect::to('adminetripoperators')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');
		}

	}

	private function send_message($id, $message, $progress){
	    $d = array('message' => $message , 'progress' => $progress);
	    echo "id: $id" . PHP_EOL;
	    echo "data: " . json_encode($d) . PHP_EOL;
	    echo PHP_EOL;
	    ob_flush();
	    flush();
	}


	public function getImportgeographies($idOperator){
		$data = array();
		$data['idOperator'] = $idOperator;
		return view('adminetripoperators.import_temp_geographies',$data);
	}

	public function getImportgeographiesaction($idOperator){
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		$this->send_message(0, "LOG: Import started\n",0);
		set_time_limit(60*60);
		ini_set('memory_limit','-1');
		$SOAPClient = new ET_SoapClient($idOperator);
		$this->send_message(2, "LOG: Deleting old temp geographies\n",10);
		GeographyTemp::where('soap_client','=',$idOperator)->delete();
		$this->send_message(3, "LOG: Old geographies deleted succesfully\n",40);
		$this->send_message(4, "LOG: Importing temp geographies\n",50);
		$geography = GeographySoapObject::get($SOAPClient);
		$geography->saveToTempDB($SOAPClient->id_operator);
		$this->send_message(5, "LOG: Import finished succesfully\n",100);
		$this->send_message(6, 'TERMINATE',100);
	}


}
