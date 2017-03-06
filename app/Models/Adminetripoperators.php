<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class adminetripoperators extends Sximo  {
	
	protected $table = 'etrip_operators';
	protected $primaryKey = 'id_operator';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT etrip_operators.* FROM etrip_operators  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE etrip_operators.id_operator IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
