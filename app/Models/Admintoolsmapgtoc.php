<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class admintoolsmapgtoc extends Sximo  {
	
	protected $table = 'categories';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT categories.* FROM categories  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE categories.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
