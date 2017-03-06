<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Admintoolsmapgtoc;
use App\Models\Travel\Geographies;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class AdmintoolsmapgtocController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'admintoolsmapgtoc';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Admintoolsmapgtoc();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'admintoolsmapgtoc',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		$categories = \DB::table('categories')->orderBy('name','asc')->get();
		$geographies = \DB::table('geographies')->orderBy('tree_level','asc')->get();
		$this->data['categories'] = $categories;
		$this->data['geographies'] = $geographies;
		
		
		return view('admintoolsmapgtoc.index',$this->data);
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
				
		$this->data['access']		= $this->access;
		return view('admintoolsmapgtoc.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		
		$this->data['access']		= $this->access;
		return view('admintoolsmapgtoc.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		$packages = \DB::table('packages')->whereIn('destination',$request->input('geographies'))->get();
		foreach($packages as $package){
			foreach($request->input('categories') as $category){
				$check = \DB::table('package_categories')->where('id_package','=',$package->id)->where('id_category','=',$category)->where('soap_client','=',$package->soap_client)->first();
				if($check == null){
					\DB::table('package_categories')->insert(array(
														 	"id_package" => $package->id,
														 	"id_category" => $category,
														 	"soap_client" => $package->soap_client
														 ));
				}
			}
		}
		\SiteHelpers::auditTrail( $request , 'Packages categories have been updated');

		return Redirect::to("/admintoolsmapgtoc")->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
	
	}	

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		
	}			


}