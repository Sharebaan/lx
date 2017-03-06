<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Travel\Geography;

class admingeographies extends Sximo  {
	
	protected $table = 'geographies';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT geographies.* FROM geographies  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE geographies.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
	public static function getIdsAvailability($availability){
		if($availability != ""){
			$availability = explode('|', $availability);
			$availabilityArray = array();
			foreach ($availability as $key => $value) {
				$row = explode(':', $value);
				$select = \DB::table('geographies_temp')->where('soap_client', $row[0])->where('id', $row[1])->first();
				$availabilityArray[] = $select;		
			}
			$availability = $availabilityArray;
		} else {
			return '';
		}
		
		$result = array();
		foreach ($availability as $key => $value) {
			$result[] = $value->idx;
		}
		return implode(',', $result);
	}
	public static function putIdsAvailability($availability){
		
		$availability = \DB::table('geographies_temp')->whereIn('idx', explode(',',$availability))->get();
		if(empty($availability)) return '';
		
		$result = array();
		foreach ($availability as $key => $value) {
			$result[] = $value->soap_client.':'.$value->id;
		}
		return implode('|', $result);
	}
	
	public function getLocations($id){
		$geography = Geography::find($id);
		$location = new \stdClass();
		switch($geography->tree_level){
			case 1:
				$location->continent_id = $geography->id;
				$location->country_id = 0;
				$location->area_id = 0;
				$location->city_id = 0;
			break;
			case 2:
				$location->continent_id = $geography->parent->id;
				$location->country_id = $geography->id;
				$location->area_id = 0;
				$location->city_id = 0;
			break;
			case 3:
				$location->continent_id = $geography->parent->parent->id;
				$location->country_id = $geography->parent->id;
				$location->area_id = $geography->id;
				$location->city_id = 0;
			break;
			case 4:
				$location->continent_id = $geography->parent->parent->parent->id;
				$location->country_id = $geography->parent->parent->id;
				$location->area_id = $geography->parent->id;
				$location->city_id = $geography->id;
			break;
			default:
				$location->continent_id = 0;
				$location->country_id = 0;
				$location->area_id = 0;
				$location->city_id = 0;
		} 
		return $location;
	}

	public static function getLocationPost($post){
		if($post['city_id'] != 0){
			$location = $post['city_id'];
		} else if($post['area_id'] != 0){
			$location = $post['area_id'];
		} else if($post['country_id'] != 0){
			$location = $post['country_id'];
		} else {
			$location = $post['continent_id'];
		}
		return $location;
	}
	

}
