<?php namespace App\Models\Travel;

//use Illuminate\Database\Eloquent\Model;
//
class Geography extends  SximoTravel{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'geographies';
	protected $fillable = ['availability'];

	/*
	 * Hotel entity attributes
	 */

	public $timestamps = false;

	public static function getRoot(){
		return Geography::where('id_parent',null)->first();
	}

	public function parent(){
		return $this->belongsTo('App\Models\Travel\Geography','id_parent');
	}

	public function children(){
		return $this->hasMany('App\Models\Travel\Geography','id_parent');
	}

	public static function getLocationsFromBaseLocation($location,&$locationIds){
		$locationIds[] = $location->id;
		if(isset($location->children[0])){
			foreach($location->children as $children){
				self::getLocationsFromBaseLocation($children,$locationIds);
			}
		}
	}

	public function getAdjacentLocations(){
		$locationTemp = $this;
		//dd($locationTemp);
		while($locationTemp->tree_level > 5){
			$locationTemp = $locationTemp->parent;
		}

		while($locationTemp != null && $locationTemp->tree_level >= 2){
			$tmpObject = isset($obj) ? $obj : null;
			$obj = new \stdClass();
			$obj->id = $locationTemp->id;
			$obj->name = $locationTemp->name;
			$obj->childrens = array();
			if(!is_null($tmpObject)){
				$obj->childrens[] = $tmpObject;
			}
			$locationTemp = $locationTemp->parent;
		}
		return !isset($obj)?null:$obj;
	}

	
	public static function getCountryForPackageSearch($locations){

		foreach($locations as $k=>$location){
			if($location->getAdjacentLocations() != null){
				$c=new \stdClass();
				$c->id=$location->getAdjacentLocations()->id;
				$c->name=$location->getAdjacentLocations()->name;
				$c->children=null;
				$a[$k] = $c;
			}
		}
		return $a;
	}

	public static function getLocationsForPackageSearch($locations){

		$tmpArray = array();
		foreach($locations as $location){
			$t = $location->getAdjacentLocations();
			if($t != null){
				$check1 = false;
					foreach($tmpArray as $tmp){
						if($tmp->id == $t->id){
							$check2 = false;
							foreach($tmp->childrens as $c1){
								if(!empty($t->childrens) && $c1->id == $t->childrens[0]->id){
									$check3 = false;
									foreach($c1->childrens as $c2){
										if(!empty($t->childrens[0]->childrens) && $c2->id == $t->childrens[0]->childrens[0]->id){
											$check3 = true;
										}
									}
									if(!$check3){
										$c1->childrens[] = $t->childrens[0]->childrens[0];

									}
									$check2 = true;
								}
							}
							if(!$check2){
								$tmp->childrens[] = $t->childrens[0];
							}
							$check1 = true;
						}
					}
					if(!$check1){
						$tmpArray[] = $t;
					}
				}
			}

		return $tmpArray;
	}

	public static function getFormatedLocation($location){
		$location = Geography::find($location);
		$formatedLocation = "";
		if($location != null){
			switch($location->tree_level){
				case 3:
					$formatedLocation = $location->name . ", " . $location->parent->name;
				break;
				case 4:
					$formatedLocation = $location->name . ", " . $location->parent->name . ", " . $location->parent->parent->name;
				break;
				default:
					$formatedLocation = $location->name;
			}
		}
			
		return $formatedLocation;
	}

	public static function getCountryForHotel($hotelId,$soapClient){
		$hotel = Hotel::where('id','=',$hotelId)->where('soap_client','=',$soapClient)->first();
		//dd($hotel);
		return $hotel->getLocationForLink();
	}

	public static function getLocationIdForSoapClient($soapClient,$locationId){
		$location = Geography::find($locationId);
		if($location){
			$locations = $location->availability;
			$locations = explode("|",$locations);
			foreach($locations as $loc){
				$tmp = explode(":",$loc);
				if($tmp[0] == $soapClient){
					return $tmp[1];
				}
			}
	 	}
		return 0;
	}

	public static function getLocationIdsBySoapClient($locationId){
		$location = Geography::find($locationId);
	//	dd($location);
	/*	$location->getChildren();
		$locChildAv=[];
		foreach($location['children'] as $k=>$v){
			$locChildAv[$k]=$v->availability;	
		}*/
		//$locationsAv = $location->availability;
		//is_array($locationsAv)==true?$locations=array_merge($locationsAv,$childrenAv):$locationsAv = array_push($childrenAv,$locationsAv);
		//$locationsAv = array_push($childrenAv,$location->availability);
		//dd(explode("|",$childrenAv[0]),$childrenAv[0]);
		//$locations = array_merge(explode("|",$location->availability),$locChildAv);
		$locationsArray = array();
		//dd($locations);
		if($location->availability != ""){
			foreach(explode("|",$location->availability) as $k=>$loc){
				if(empty($loc)){continue;}
				$tmp = explode(":",$loc);
				$locationsArray[$tmp[0]][$k] = $tmp[1];
			}
		}else{
			$locationsArray['HH'] = [$location->id];	
		}	
		return $locationsArray;
	}

	public function getChildren(){
		$c = Geography::where('id_parent','=',$this->id)->get();
		if(!empty($c)){
			foreach($c as $k=>$v){
				$this->children[$k]=$v;			
			}	
		}
		return true;
	}

}
