<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class adminamenities extends Sximo  {
	
	protected $table = 'admin_amenities';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT admin_amenities.* FROM admin_amenities  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE admin_amenities.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
