<?php namespace App\Models\Travel;

use App\Models\Travel\Geography;
use App\Models\Travel\CachedPrice;

use DB;

class Hotel extends SximoTravel {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'hotels';
	protected $fillable = ['id'];
	
	/*
	 * Hotel entity attributes
	 */

	public $timestamps = false;

	public function roomCategories(){
		return $this->hasMany('App\Models\Travel\RoomCategory','id_hotel')->where('room_categories.soap_client','=',$this->soap_client);
	}

	public function roomAmenities(){
		return $this->hasMany('App\Models\Travel\RoomAmenity','id_hotel')->where('room_amenities.soap_client','=',$this->soap_client);
	}

	public function hotelAmenities(){
		return $this->hasMany('App\Models\Travel\HotelAmenity','id_hotel')->where('hotel_amenities.soap_client','=',$this->soap_client);
	}

	public function hotelThemes(){
		return $this->hasMany('App\Models\Travel\HotelTheme','id_hotel')->where('hotel_themes.soap_client','=',$this->soap_client);
	}

	public function images(){
		return $this->hasMany('App\Models\Travel\FileInfo','id_hotel','id')->where('file_infos.soap_client','=',$this->soap_client);
	}

	public function detailedDescriptions(){
		return $this->hasMany('App\Models\Travel\DetailedDescription','id_hotel')->where('detailed_descriptions.soap_client','=',$this->soap_client);
	}

	public function destination(){
		return $this->hasOne('App\Models\Travel\Geography','id','location');
	}

	public function packages(){
		return $this->hasMany('App\Models\Travel\PackageInfo','id_hotel','id')->where('packages.soap_client','=',$this->soap_client);
	}

	public static function checkIfHasImages($id_hotel,$soap_client){
		$hotel = Hotel::where('id','=',$id_hotel)->where('soap_client','=',$soap_client)->first();
		
		if(count($hotel->images) > 0){
			return true;
		}
		return false;
	}

	public function getFormatedLocation(){
		$formatedLocation = "";
		switch($this->destination->tree_level){
			case 3:
				$formatedLocation = $this->destination->name . ", " . $this->destination->parent->name;
			break;
			case 4:
				$formatedLocation = $this->destination->name . ", " . $this->destination->parent->name . ", " . $this->destination->parent->parent->name;
			break;
			default:
				$formatedLocation = $this->destination->name;
		}
		return $formatedLocation;
	}

	public function getFormatedLocationMin(){
		$formatedLocation = "";
		switch($this->destination->tree_level){
			case 3:
				$formatedLocation = $this->destination->name . ", " . $this->destination->parent->name;
			break;
			case 4:
				$formatedLocation =  $this->destination->parent->name . ", " . $this->destination->parent->parent->name;
			break;
			default:
				$formatedLocation = $this->destination->name;
		}
		return $formatedLocation;
	}

	public function getLocationForLink(){
		$formatedLocation = "";

		switch($this->destination->tree_level){
			case 3:
		//			dd('da');
				$formatedLocation = $this->destination->parent->name;
			break;
			case 4:
				$formatedLocation = $this->destination->parent->parent->name;
			break;
			default:
				$formatedLocation = $this->destination->name;
		}
		$formatedLocation = str_replace(" ","-",$formatedLocation);
		return $formatedLocation;
	}

	public function getBasePhotoLink(){
		//dd($this->images);
		if(isset($this->images[0])){
			$mimeArray = explode('/', $this->images[0]->mime_type);
            $type = $mimeArray[count($mimeArray)-1];
      if($this->soap_client == "HO"){
				$link = '/images/offers/'.$this->images[0]->name;
				if(!file_exists(public_path().$link)){
					$link = '/images/offers/'.$this->images[0]->id.'.'.$type;
				}
			} else {
				$link = '/images/offers/'.$this->soap_client.'/'.$this->images[0]->name;
				if(!file_exists(public_path().$link)){
					$link = '/images/offers/'.$this->soap_client.'/'.$this->images[0]->id.'.'.$type;
				}
			}
		} else {
			//$link = '/images/210x140.jpg';
			$link = 'sximo/themes/default/img/logo_mini.jpg';
		}
		$link = asset($link);
		return $link;
	}

	public static function getBasePhotoLinkFor($hotel,$soapClient){
			//dd($hotel,$soapClient);
		$hotel = Hotel::where('id','=',$hotel)->where('soap_client','=',$soapClient)->first();
	
		if(isset($hotel->images[0])){
			$mimeArray = explode('/', $hotel->images[0]->mime_type);
            $type = $mimeArray[count($mimeArray)-1];
            if($soapClient == "HO"){
				//$link = '/images/offers/'.$hotel->images[0]->id.'.'.$type;
				$link = '/images/offers/'.$hotel->images[0]->name;
				if(!file_exists(public_path().$link)){
					$link = '/images/offers/'.$hotel->images[0]->id.'.'.$type;
					if(!file_exists(public_path().$link)){
						$link = 'sximo/themes/default/img/logo_mini.jpg';
					}
				}
			}else {
				
				$link = '/images/offers/'.$soapClient.'/'.$hotel->images[0]->name;
				if(!file_exists(public_path().$link)){
					$link = '/images/offers/'.$soapClient.'/'.$hotel->images[0]->id.'.'.$type;
					
					if(!file_exists(public_path().$link)){
						$link = 'sximo/themes/default/img/logo_mini.jpg';
					}
				}
			}
		} else {
			//$link = '/images/210x140.jpg';
			$link = 'sximo/themes/default/img/logo_mini.jpg';
		}
		$link = asset($link);
		return $link;
	}


	public function getDepartureDatesForPackages($isTour,$isBus,$isFlight){
		return DB::table('packages_departure_dates')->distinct('packages_departure_dates.departure_date')
													->leftJoin('packages','packages_departure_dates.id_package','=','packages.id')
													->where('packages.id_hotel','=',$this->id)
													->where('packages.soap_client','=',$this->soap_client)
													->where('packages.is_tour','=',$isTour)
													->where('packages.is_bus','=',$isBus)
													->where('packages.is_flight','=',$isFlight)
													->whereRaw('packages_departure_dates.departure_date >= CURDATE()')
													->orderBy('packages_departure_dates.departure_date','ASC')
													->get(array('packages_departure_dates.departure_date'));
	}

	public function getDeparturePointsForPackages($isTour,$isBus,$isFlight){
		return DB::table('packages_departure_points')->distinct('packages_departure_points.id_geography')
													->leftJoin('packages',function($join){
														$join->on('packages_departure_points.id_package','=','packages.id');
														$join->on('packages_departure_points.soap_client','=','packages.soap_client');
													})
													->leftJoin('geographies','geographies.id','=','packages_departure_points.id_geography')
													->where('packages.id_hotel','=',$this->id)
													->where('packages.soap_client','=',$this->soap_client)
													->where('packages.is_tour','=',$isTour)
													->where('packages.is_bus','=',$isBus)
													->where('packages.is_flight','=',$isFlight)
													->get(array('packages_departure_points.id_geography','geographies.name'));
	}

	public function getDurationsForPackages($isTour,$isBus,$isFlight){
		return DB::table('packages')->distinct('package.duration as duration')
						  			->where('packages.id_hotel','=',$this->id)
						  			->where('packages.soap_client','=',$this->soap_client)
						  			->where('packages.is_tour','=',$isTour)
									->where('packages.is_bus','=',$isBus)
									->where('packages.is_flight','=',$isFlight)
									->orderBy('packages.duration','ASC')
									->get(array('duration'));
	}

	public static function getDestinations(){

		$results = Geography::distinct('geographies.id ')
							->select(DB::raw('geographies.id as id, geographies.name as name'))
							->rightJoin('hotels','geographies.id','=','hotels.location');
		$results = $results->groupBy('geographies.id')->orderBy('geographies.id')->get();
		$tmpArray = array();
		foreach($results as $result){
			if($result->name != ""){
				$tmpArray[] = $result->id;
			}
		}
		return Geography::whereIn('id',$tmpArray)->get();
	}

	private static function listingResultsByFilteringOptionsForHotelSearch($searchId,$mealPlansArray,$stars){
		$results =  DB::table('hotels')->select(array('hotels.id as id',
									 		DB::raw('MIN(hotels_search_cached_prices.gross + hotels_search_cached_prices.tax) as min_price'),
									 	   'hotels.name as hotel_name',
									 	   'hotels.description as hotel_description',
									 	   'hotels.location as location',
									 	   'hotels.soap_client as soap_client',
											 'hotels.class as stars',
											 'hotels.address as hotel_address'))
					   		 	    ->leftJoin('hotels_search_cached_prices',function($join){
					   		 	    	$join->on('hotels.id','=','hotels_search_cached_prices.id_hotel');
					   		 	    	$join->on('hotels.soap_client','=','hotels_search_cached_prices.soap_client');
					   		 	    })
					   		 	    ->whereRaw("hotels_search_cached_prices.id_hotel_search = ".$searchId.$mealPlansArray[0]);
		if($stars != 0){
			$results = $results->whereRaw("hotels.class = ".$stars);
		}
		$results = $results->groupBy('hotels.id')
						 		   	->havingRaw('MIN(hotels_search_cached_prices.gross + hotels_search_cached_prices.tax) = (SELECT MIN(hscp.gross + hscp.tax) '.
														 						  									  			'FROM hotels h '.
														 						  									  			'LEFT JOIN hotels_search_cached_prices hscp ON h.id = hscp.id_hotel AND h.soap_client = hscp.soap_client '.
														 						  									  			'WHERE h.id = hotels.id AND hscp.id_hotel_search = '.$searchId.$mealPlansArray[1].')');
		return $results;
	}

	private static function getPackageListingTableByFilteringOptionsForHotelSearch($searchId,$mealPlans,$stars){
		$mealPlansArray = array();
		if($mealPlans != 0){
			$mealPlansArray[0] = " AND hotels_search_cached_prices.id_meal_plan IN (".str_replace(";",",",$mealPlans).")";
			$mealPlansArray[1] = " AND hscp.id_meal_plan IN (".str_replace(";",",",$mealPlans).")";
		} else {
			$mealPlansArray[0] = "";
			$mealPlansArray[1] = "";
		}
		return DB::table(DB::raw('('.Hotel::listingResultsByFilteringOptionsForHotelSearch($searchId,$mealPlansArray,$stars)->toSql().') as hl'));
	}

	public static function getListingResultsByFilteringOptionsForHotelSearch($searchId,$page,$mealPlans,$priceFrom,$priceTo,$sortBy,$sortOrder,$stars){
		$hotelsTable = Hotel::getPackageListingTableByFilteringOptionsForHotelSearch($searchId,$mealPlans,$stars);
		$hotels = $hotelsTable;
		$minPrice = $hotels->min('hl.min_price');
 		$maxPrice = $hotels->max('hl.min_price');
 		if($priceFrom != 0){
 			$hotels = $hotels->where('hl.min_price','>=',$priceFrom);
 		}
 		if($priceTo != 0){
 			$hotels = $hotels->where('hl.min_price','<=',$priceTo);
 		}
		$noHotels = $hotels->count('hl.id');
		if($sortBy == "price"){
			$sortByText = "hl.min_price";
		} else if ($sortBy == "date"){
			$sortByText = "hl.id";
		} else if ($sortBy == "name"){
			$sortByText = "hl.hotel_name";
		}
		$hotels = $hotels->orderBy($sortByText,$sortOrder);

		$hotels = $hotels->skip(($page - 1)*10)->take(10);
		$hotels = $hotels->get();

		$returnData['hotels'] = $hotels;
		$returnData['noHotels'] = $noHotels;
		$returnData['minPrice'] = $minPrice;
		$returnData['maxPrice'] = $maxPrice;
		return $returnData;
	}

	public static function getDestinationsForHotelSearch(){

		$results = Geography::distinct('geographies.id ')
							->select(DB::raw('geographies.id as id, geographies.name as name'))
							->rightJoin('hotels','geographies.id','=','hotels.location');
		$results = $results->groupBy('geographies.id')->orderBy('geographies.id')->get();
		$tmpArray = array();
		foreach($results as $result){
			if($result->name != ""){
				$tmpArray[] = $result->id;
			}
		}
		return Geography::whereIn('id',$tmpArray)->get();
	}

	public function estPriceForPackages($isTour,$isFlight,$isBus){
		$price = CachedPrice::selectRaw('MIN(cached_prices.gross + cached_prices.tax) as min_price')
												->rightJoin('packages',function($join){
													$join->on('packages.id','=','cached_prices.id_package');
													$join->on('packages.soap_client','=','cached_prices.soap_client');
												})
											  ->rightJoin('hotels',function($join){
													$join->on('hotels.id','=','packages.id_hotel');
													$join->on('hotels.soap_client','=','packages.soap_client');
												})
												->where('hotels.id','=',$this->id)
												->where('hotels.soap_client','=',$this->soap_client)
												->where('packages.is_tour','=',$isTour)
												->where('packages.is_flight','=',$isFlight)
												->where('packages.is_bus','=',$isBus)
												->whereRaw('cached_prices.departure_date >= CURDATE()')
												->get();
		return isset($price[0]->min_price) ? (int)$price[0]->min_price : 0;
	}
//isset($this->latitude) && isset($this->longitude
	public function getGoogleLocationUrl(){
		if($this->latitude != 0.00 && $this->longitude != 0.00){
			return "https://www.google.com/maps/embed/v1/place?key=AIzaSyAJTbbZkMl8CCT8E3r9kxNO3gj8bIWiM0E&q={$this->latitude},{$this->longitude}";
		} else if($this->address != null){
			return "https://www.google.com/maps/embed/v1/place?key=AIzaSyAJTbbZkMl8CCT8E3r9kxNO3gj8bIWiM0E&q={$this->address}";
		} else {

			return "https://www.google.com/maps/embed/v1/place?key=AIzaSyAJTbbZkMl8CCT8E3r9kxNO3gj8bIWiM0E&q={$this->name}";
		}
	}

	public static function getListingForHotelSearch($input,$searchId,$mealPlans,$priceFrom,$priceTo,$sortBy,$sortOrder,$stars,$page){
		$hotelsTable = Hotel::getPackageListingForHotelSearch($input,$searchId,$mealPlans,$stars);
		$hotels = $hotelsTable;
		$minPrice = $hotels->min('hl.min_price');
 		$maxPrice = $hotels->max('hl.min_price');
 		if($priceFrom != 0){
 			$hotels = $hotels->where('hl.min_price','>=',$priceFrom);
 		}
 		if($priceTo != 0){
 			$hotels = $hotels->where('hl.min_price','<=',$priceTo);
 		}
		$noHotels = $hotels->count('hl.id');
		if($sortBy == "price"){
			$sortByText = "hl.min_price";
		} else if ($sortBy == "date"){
			$sortByText = "hl.id";
		} else if ($sortBy == "name"){
			$sortByText = "hl.hotel_name";
		}
		$hotels = $hotels->orderBy($sortByText,$sortOrder);

		$hotels = $hotels->skip(($page - 1)*10)->take(10);
		$hotels = $hotels->get();

		$returnData['hotels'] = $hotels;
		$returnData['noHotels'] = $noHotels;
		$returnData['minPrice'] = $minPrice;
		$returnData['maxPrice'] = $maxPrice;
		return $returnData;
	}

	private static function getPackageListingForHotelSearch($input,$searchId,$mealPlans,$stars){
		$mealPlansArray = array();
		if($mealPlans != 0){
			$mealPlansArray[0] = " AND hotels_search_cached_prices.id_meal_plan IN (".str_replace(";",",",$mealPlans).")";
			$mealPlansArray[1] = " AND hscp.id_meal_plan IN (".str_replace(";",",",$mealPlans).")";
		} else {
			$mealPlansArray[0] = "";
			$mealPlansArray[1] = "";
		}
		return DB::table(DB::raw('('.Hotel::listingResultsForHotelSearch($input,$searchId,$mealPlansArray,$stars)->toSql().') as hl'));
	}

	private static function listingResultsForHotelSearch($input,$searchId,$mealPlansArray,$stars){
	/*	$sIds =[]; 
		foreach(DB::table('hotels_search_cached_prices')->select('id_hotel_search')->distinct()->get() as $v){
			array_push($sIds,$v->id_hotel_search);	
		}
	$sIds2 = implode(",",$sIds);*/

		$results =  DB::table('hotels')->select(array('hotels.id as id',
									 		DB::raw('MIN(hotels_search_cached_prices.gross + hotels_search_cached_prices.tax) as min_price'),
									 	   'hotels.name as hotel_name',
									 	   'hotels.description as hotel_description',
									 	   'hotels.location as location',
									 	   'hotels.soap_client as soap_client',
											 'hotels.class as stars',
											 'hotels.address as hotel_address'))
					   		 	    ->leftJoin('hotels_search_cached_prices',function($join){
					   		 	    	$join->on('hotels.id','=','hotels_search_cached_prices.id_hotel');
					   		 	    	$join->on('hotels.soap_client','=','hotels_search_cached_prices.soap_client');
					   		 	    });
					   		 	    //->whereRaw('hotels_search_cached_prices.id_hotel_search IN ('.$sIds2.')');//='.$searchId.$mealPlansArray[0]
		$results = $results->whereRaw("hotels.name like '%".$input."%'");	
		if($stars != 0){
			$results = $results->whereRaw("hotels.class = ".$stars);
		}
		$results = $results->groupBy('hotels.id')
						 		   	->havingRaw('MIN(hotels_search_cached_prices.gross + hotels_search_cached_prices.tax) = (SELECT MIN(hscp.gross + hscp.tax) '.
														 						  									  			'FROM hotels h '.
														 						  									  			'LEFT JOIN hotels_search_cached_prices hscp ON h.id = hscp.id_hotel AND h.soap_client = hscp.soap_client '.
														 						  									  			'WHERE h.id = hotels.id)');
		return $results;
	}
}
