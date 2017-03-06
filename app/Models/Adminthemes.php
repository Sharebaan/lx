<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class adminthemes extends Sximo  {
	
	protected $table = 'admin_themes';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT admin_themes.* FROM admin_themes  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE admin_themes.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
