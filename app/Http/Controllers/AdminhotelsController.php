<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Adminhotels;
use App\Models\Admingeographies;
use App\Models\Travel\Hotel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;


class AdminhotelsController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();
	public $module = 'adminhotels';
	static $per_page	= '10';

	public function __construct()
	{

		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Adminhotels();

		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'adminhotels',
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
		$pagination->setPath('adminhotels');

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
		return view('adminhotels.index',$this->data);
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
		if(!empty($row->id))
		{
			if(!empty($row->location)){
				//locatie
				$this->geographies = new Admingeographies();
				$location = $this->geographies->getLocations($row->location);

				$row->continent_id = $location->continent_id;
				$row->country_id = $location->country_id;
				$row->area_id = $location->area_id;
				$row->city_id = $location->city_id;
			} else {
				$row->continent_id = 0;
				$row->country_id = 0;
				$row->area_id = 0;
				$row->city_id = 0;
			}
			$row->is_local = $row->soap_client == "LOCAL" ? true : false;
			//teme
			$row->themes = $this->model->getThemesHotel($row->id,$row->soap_client,$row->update);
			//facilitati
			$row->amenities = $this->model->getAmenitiesHotel($row->id,$row->soap_client,$row->update);
			//descriere detaliata
			$row->detailed_descriptions = $this->model->getDetailedDescriptionHotel($row->id,$row->soap_client,$row->update);
			//print_r($row->detailed_descriptions);die;
			$row->room_categories = $this->model->getRoomCategories($row->id,$row->soap_client);

			//imagini
			$row->images = $this->model->getImagesHotel($row->id,$row->soap_client);

			$this->data['row'] =  (array)$row;

		} else {

			$row = $this->model->getColumnTable('hotels');
			$row['continent_id'] = 0;
			$row['country_id'] = 0;
			$row['city_id'] = 0;
			$row['area_id'] = 0;
			$row['available'] = 1;
			$row['is_local'] = true;
			$row['themes'] = '';
			$row['amenities'] = '';
			$row['detailed_descriptions'] = array();
			$row['images'] = array();
			$row['room_categories'] = array();
			$this->data['row'] = $row;
		}
		if(isset($request->create)){
				$this->data['create'] = true;
		}
		$this->data['id'] = $id;
		//dd($this->data);
		return view('adminhotels.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('hotels');
		}
		$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);

		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('adminhotels.view',$this->data);
	}

	public static function prelucrarePost($post){
		if(!isset($post['available'])) $post['available'] = "0";
		if(empty($post['soap_client'])) $post['soap_client'] = 'LOCAL';
		//dd($post['id']);
		if(empty($post['id'])){
			//if($post['id']<2000) $post['id'] = 20000;
			//dd(Adminhotels::getIdHotelLocal());
			$post['id'] = Adminhotels::getIdHotelLocal();
		}

		//prelucrare details
		$details = array();
		$item = 1;
		foreach ($post as $key => $val) {
			$field = substr($key, 0,6);
			$field2 = substr($key, 0,10);
			if($field=='label_' && $field2 != 'label_new_'){
				$id = substr($key, 6);

				if(empty($post['delete_'.$id])){
					$detail = array();
					$detail['id'] = $id;
					$detail['text'] = $post['detail_'.$id];
					$detail['index'] = $item;
					$detail['label'] = $post['label_'.$id];
					$details[$item] = (object)$detail;
					$item++;
				}

			}else if($field2 == 'label_new_'){
				$id = substr($key, 10);
				$detail = array();
				$detail['text'] = $post['detail_new_'.$id];
				$detail['index'] = $item;
				$detail['label'] = $post['label_new_'.$id];
				$details[$item] = (object)$detail;
				$item++;
			}
		}

		$post['details'] = $details;
		return $post;
	}

	public function getDeleteimage($id){
		\DB::table('file_infos')->where('id',$id)->delete();
		return redirect()->back();
	}

	public function postUpload(Request $req){
		$row = $this->model->find($req->hotelid);
		$images = $this->model->getImagesHotel($row->id,$row->soap_client);


		$file = $req->file;
		$mimeType = $file->getMimeType();
		//dd($mimeType);
		//$filename = $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension();
		//dd($extension);
	/*	if(empty($images)){
			dd($images);
		}else{
			dd($images);
			$newfilename = $images[count($images)-1]->id+1 .'.'.explode('/',$mimeType)[1];
		}*/
		$newfilename = $file->getClientOriginalName();
		//dd($newfilename);
		if($row->soap_client == 'HO'){
			$destinationPath = './images/offers/';
		}else{
			$destinationPath = './images/offers/'.$row->soap_client .'/';
		}
		//dd($destinationPath, $newfilename);
		$uploadSuccess = $file->move($destinationPath, $newfilename);

		$nr = $this->model->getNewIdImage();
		if($extension == "jpg"){
			$extension = "jpeg";
		}

		$data['id_hotel'] = $row->id;
		$data['name'] = $newfilename;
		$data['mime_type'] = $mimeType;
		$data['soap_client'] = $row->soap_client;
		$data['id'] = $nr;

		if( $uploadSuccess ){
				$this->model->updateHotelImage($data['id'],$data);
		}


	}

	function postSave( Request $request)
	{

		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {

			$post = $this->prelucrarePost($_POST);

		
			//$data = $this->validatePost('tb_adminhotels');
			//dd($post);
			$id = $this->model->updateHotel($post , $request->input('idx'));

			$row = $this->model->find($id);
			$images = $this->model->getImagesHotel($row->id,$row->soap_client);
			if(!empty($images)){
				foreach ($images as $key => $image) {

					$data = array();
					$file = 'image_'.$image->id;

					if(!is_null(Input::file($file)))
					{
						$file = $request->file($file);
						$destinationPath = './images/offers/';
						$mimeType = $file->getMimeType();
						$filename = $file->getClientOriginalName();
						$extension = $file->getClientOriginalExtension(); //if you need extension of the file
						$newfilename = $image->id.'.'.$extension;
						$uploadSuccess = $file->move($destinationPath, $newfilename);

						$data['id_hotel'] = Hotel::where('idx','=',$id)->first()->id;
						$data['name'] = $filename;
						$data['mime_type'] = $mimeType;

						if( $uploadSuccess ) {
						    $this->model->updateHotelImage($image->id,$data,1);
						}
					}


					if(!empty($request->input('image_'.$image->id.'_delete'))){
						$this->model->updateHotelImage($image->id,$data,2);
					}

				}
			}

			if(!empty($request->input('new_image'))){
				for ($i=1; $i <= $request->input('new_image'); $i++) {

					$data = array();
					$file = 'image_new_'.$i;

					if(!is_null(Input::file($file)))
					{
						$file = $request->file($file);
						if($row->soap_client == 'HO'){
							$destinationPath = './images/offers/';
						} else {
							$destinationPath = './images/offers/'.$row->soap_client.'/';
						}
						$mimeType = $file->getMimeType();
						$filename = $file->getClientOriginalName();
						$extension = $file->getClientOriginalExtension(); //if you need extension of the file

						$nr = $this->model->getNewIdImage();
						if($extension == "jpg"){
							$extension = "jpeg";
						}
						$newfilename = $nr.'.'.$extension;

						$uploadSuccess = $file->move($destinationPath, $newfilename);

						$data['id_hotel'] = Hotel::where('idx','=',$id)->first()->id;
						$data['name'] = $filename;
						$data['mime_type'] = $mimeType;
						$data['soap_client'] = $row->soap_client;
						$data['id'] = $nr;

						if( $uploadSuccess ) {
						    $this->model->updateHotelImage($data['id'],$data,0);
						}
					}
				}
			}


			if(!is_null($request->input('apply')))
			{
				$return = 'adminhotels/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'adminhotels?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('idx') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to('adminhotels/update/'.$id)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');

		} else {

			return Redirect::to('adminhotels/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('adminhotels')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success');

		} else {
			return Redirect::to('adminhotels')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');
		}

	}


	# custom delete
	public function getDeletedetail( $id = null ) {

		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}

		$id_hotel = $this->model->deletedetail($id);
		return Redirect::to('adminhotels/update/'.$id_hotel);

		if($_GET['del_address']) {
			if($_GET['primary'] != '1') {
				DB::table('adrese_medici')->where('id', '=', $_GET['del_address'])->update(['active' => '0']);;
			return Redirect::to('medici/update/'.$_GET['medic_id'])
			->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success');
			} else {
				return Redirect::to('medici/update/'.$_GET['medic_id'])
			->with('messagetext', \Lang::get('sunkompas.note_restric_delete'))->with('msgstatus','error');
			}

		}
	}





}
