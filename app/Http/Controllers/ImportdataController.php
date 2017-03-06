<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Importdata;
use Illuminate\Http\Request;
use Input, Redirect ; 
use DB;


class ImportdataController  extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'importdata';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Importdata();
		
		$this->data = array(
			'pageTitle'	=> 'Import',
			'pageNote'	=>  'Import',
			'pageModule'=> 'importdata',
			'return'	=> self::returnUrl()
			
		);
		
	}
	
	
	protected function rowsdb( $table=null ) {
		$columns = array();
		foreach(\DB::select("SHOW COLUMNS FROM $table") as $column)
			$columns[$column->Field] = ' ';
		return $columns;
	}
	
	
	public function getImportgeographies( Request $request, $step = null) {
		$this->data['step'] = $step;

		return view('importdata.geographies',$this->data);
	}
	
	
	public function postImportgeographies( Request $request, $step = null) {
		$this->data['step'] = $step;
		
		// select database
		$this->table = 'geographies_temp';
		$this->data['columns'] = $this->rowsdb($this->table);
		reset($this->data['columns']);
		$key = key($this->data['columns']);

		if($step == 'one') {
			
			if(!is_null(Input::file('csv_file')))
			{
				$data = array();
				$data['name'] = $_POST['name'];
				$data['soap_client'] = $_POST['soap_client'];
				$data['description'] = $_POST['description'];
				$id = $this->model->insertRow($data,null); 
					
				$file = $request->file('csv_file'); 
				$destinationPath = './uploads/imports/';
				$filename = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension(); //if you need extension of the file
				$newfilename = $id.'.'.$extension;
				$uploadSuccess = $request->file('csv_file')->move($destinationPath, $newfilename);				 
				if( $uploadSuccess ) {
					$data = array();
				    $data['file'] = $newfilename;
					$this->model->insertRow($data,$id); 
				}else{
					$messagetext = 'Introduceti CSV';
					return Redirect::to($return)->with('messagetext', $messagetext)->with('msgstatus','error');
				}
			}else{
				$messagetext = 'Introduceti CSV';
				return Redirect::to($return)->with('messagetext', $messagetext)->with('msgstatus','error');
			}

			$csv = $destinationPath.$newfilename;
		 	if($csv) {
				$file = fopen($csv, 'r');
				while (($line = fgetcsv($file)) !== FALSE) {
					$db_array[] = $line;
				}
				$this->data['column_scv'] = $db_array[0];
				$this->data['columns_scv'] = $db_array;
				$this->data['soap_client'] = $_POST['soap_client'];
			}
		}

		if($step == 'two') {
			$count_rows_before = DB::table($this->table)->count();
			$data = $_POST;
			
			$data['json_csv'] = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {      
    return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
},$data['json_csv'] );

			$data_csv = unserialize($data['json_csv']);
			
			unset($data['import']);
			unset($data['_token']);
			unset($data['json_csv']);
			
			foreach ($data_csv as $key => $column) {
				foreach ($data as $key_row => $row) {
					if($row!=1000){
						if(substr($row,0,4) == 'IMP_'){
							$array_for_insert['soap_client'] = substr($row,4);
						}else{
							$str_key = substr($key_row, 7);
							$array_for_insert[$str_key] = $column[$row];	
						}
					}
				}
				
				if(!empty($key)){
					$this->model->insertData($this->table,$array_for_insert);
				}
				
			}

			$count_rows_after = DB::table($this->table)->count();
			$count_rows_last_insert = $count_rows_after - $count_rows_before;
			$messagetext = 'Au fost inserate '.$count_rows_last_insert.' de randuri!' ;
			$return = 'importdata/importgeographies/zero';
			return Redirect::to($return)->with('messagetext', $messagetext)->with('msgstatus','success');
		}
		
		return view('importdata.geographies',$this->data);
		
	}
	
	
	
	
	public function getImporthotels( Request $request, $step = null) {

		$this->data['step'] = $step;

		return view('importdata.hotels',$this->data);
	}
	
	
	public function postImporthotels( Request $request, $step = null) {
		
		$this->data['step'] = $step;
		
		// select database
		$this->table = 'hotels';
		$this->data['columns'] = $this->rowsdb($this->table);
		reset($this->data['columns']);
		$key = key($this->data['columns']);

		if($step == 'one') {
			
			if(!is_null(Input::file('csv_file')))
			{
				$data = array();
				$data['name'] = $_POST['name'];
				$data['soap_client'] = $_POST['soap_client'];
				$data['description'] = $_POST['description'];
				$id = $this->model->insertRow($data,null); 
					
				$file = $request->file('csv_file'); 
				$destinationPath = './uploads/imports/';
				$filename = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension(); //if you need extension of the file
				$newfilename = $id.'.'.$extension;
				$uploadSuccess = $request->file('csv_file')->move($destinationPath, $newfilename);				 
				if( $uploadSuccess ) {
					$data = array();
				    $data['file'] = $newfilename;
					$this->model->insertRow($data,$id); 
				}else{
					$messagetext = 'Introduceti CSV';
					return Redirect::to($return)->with('messagetext', $messagetext)->with('msgstatus','error');
				}
			}else{
				$messagetext = 'Introduceti CSV';
				return Redirect::to($return)->with('messagetext', $messagetext)->with('msgstatus','error');
			}

			$csv = $destinationPath.$newfilename;
		 	if($csv) {
				$file = fopen($csv, 'r');
				while (($line = fgetcsv($file)) !== FALSE) {
					$db_array[] = $line;
				}
				$this->data['column_scv'] = $db_array[0];
				$this->data['columns_scv'] = $db_array;
				$this->data['soap_client'] = $_POST['soap_client'];
			}
		}

		if($step == 'two') {
			$count_rows_before = DB::table($this->table)->count();
			$data = $_POST;
			
			$data['json_csv'] = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {      
    return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
},$data['json_csv'] );

			$data_csv = unserialize($data['json_csv']);
			
			unset($data['import']);
			unset($data['_token']);
			unset($data['json_csv']);
			
			foreach ($data_csv as $key => $column) {
				foreach ($data as $key_row => $row) {
					if($row!=1000){
						if(substr($row,0,4) == 'IMP_'){
							$array_for_insert['soap_client'] = substr($row,4);
						}else{
							$str_key = substr($key_row, 7);
							$array_for_insert[$str_key] = $column[$row];	
						}
						$array_for_insert['is_local'] = 1;
					}
				}
				
				if(!empty($key)){
					$this->model->insertData($this->table,$array_for_insert);
				}
				
			}

			$count_rows_after = DB::table($this->table)->count();
			$count_rows_last_insert = $count_rows_after - $count_rows_before;
			$messagetext = 'Au fost inserate '.$count_rows_last_insert.' de randuri!' ;
			$return = 'importdata/importhotels/zero';
			return Redirect::to($return)->with('messagetext', $messagetext)->with('msgstatus','success');
		}
		
		return view('importdata.hotels',$this->data);
		
	}


}






