<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use DB;

class importdata extends Sximo  {
	
	protected $table = 'tb_import';
	protected $primaryKey = 'id';

	public function __construct() {
		
		parent::__construct();
		
	}
	
	public static function rowsdb( $table=null ) {
		$columns = array();
		foreach(\DB::select("SHOW COLUMNS FROM $table") as $column)
			$columns[$column->Field] = ' ';
		return $columns;
	}
	
	public static function insertData( $table,$data ) {
		
		\DB::table($table)->insert($data);
		return true;	
	}
		

}
