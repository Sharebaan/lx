<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class adminmealplans extends Sximo  {
	
	protected $table = 'meal_plans';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT meal_plans.* FROM meal_plans  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE meal_plans.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
