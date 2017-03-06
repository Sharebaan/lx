<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class adminamenitiesroom extends Sximo  {
	
	protected $table = 'admin_amenities_room';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT admin_amenities_room.* FROM admin_amenities_room  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE admin_amenities_room.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
