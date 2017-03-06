<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class admingeographiestemp extends Sximo  {
	
	protected $table = 'geographies_temp';
	protected $primaryKey = 'idx';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT temp.*,par.name as parrent FROM geographies_temp as temp LEFT JOIN geographies_temp as par ON temp.id_parent=par.id  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE temp.idx IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
}
