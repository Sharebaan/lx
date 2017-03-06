<?php namespace App\Models\Travel;

use App\Models\Travel\CachedPrice;

use DB;
use Cache;
use \stdClass;
use App\Models\Adminpackages;

class PackageInfo extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'packages';
	protected $fillable = ['id_hotel'];

	public $timestamps = false;

	public function departureDates(){
		return $this->hasMany('App\Models\Travel\DepartureDate','id_package')->orderBy('departure_date');
	}

	

	public function departurePoints(){
		return $this->hasMany('App\Models\Travel\DeparturePoint','id_package');
	}

	public function priceSets(){
		return $this->belongsToMany('PriceSet','packages_price_sets','id_package','id_price_set');
	}

	public function fareTypes(){
		return $this->belongsToMany('FareType','packages_fare_types','id_package','id_fare_type');
	}

	public function detailedDescriptions(){
		return $this->hasMany('App\Models\Travel\DetailedDescription','id_package')->where('detailed_descriptions.soap_client','=',$this->soap_client);
	}

	public function prices(){
		return $this->hasMany('App\Models\Travel\CachedPrice','id_package')->where('cached_prices.soap_client','=',$this->soap_client);
	}

	public function cachedPrices(){
		$prices = CachedPrice::where('id_package','=',$this->id)->get();
		$data = array();
		foreach($prices as $price){
			$data[$price->id_room_category][$price->id_price_set][$price->id_meal_plan][$price->departure_date] = array($price->gross,$price->tax);
		}
		return $data;
	}

	public function hotel(){
		return $this->hasOne('App\Models\Travel\Hotel','id','id_hotel');
	}

	public function estPrice(){
		$minPrice = CachedPrice::where('id_package','=',$this->id)->where('soap_client','=',$this->soap_client)->min(DB::raw('gross + tax'));
		return $minPrice;
	}

	public function currentPriceSets(){
		return $this->priceSets()->where('valid_from','<',date('Y-m-d H:i:s'))->where('valid_to','>',date('Y-m-d H:i:s'))->get();
	}

	public function getOffersForFirstDateAndMinPrice(){
		$minPrice = CachedPrice::where('id_package','=',$this->id)->min('gross');
		$min = CachedPrice::where('id_package','=',$this->id)->where('gross','=',$minPrice)->first();
		$minDate = CachedPrice::where('id_package','=',$this->id)->min('departure_date');
		$offers = CachedPrice::where('id_package','=',$this->id)->where('departure_date','=',$minDate)->where('id_price_set','=',$min->id_price_set)->orderBy('gross')->get();
		return $offers;

	}

	public function minPrice(){
		$minPrice = CachedPrice::where('id_package','=',$this->id)->where('soap_client','=',$this->soap_client)->min(DB::raw('gross + tax'));
		return $minPrice;
	}

	public static function getTransportTypes($holidayType){
		$results = array();
		if(PackageInfo::where('is_tour','=',$holidayType == 2 ? 1 : 0)->where('is_flight','=',1)->where('is_bus','=',0)->count() > 0){
			$transportType = new stdClass();
			$transportType->id = 1;
			$transportType->name = "Avion";
			$results[] = $transportType;
		}
		if(PackageInfo::where('is_tour','=',$holidayType == 2 ? 1 : 0)->where('is_flight','=',0)->where('is_bus','=',1)->count() > 0){
			$transportType = new stdClass();
			$transportType->id = 2;
			$transportType->name = "Autocar";
			$results[] = $transportType;
		}
		if(PackageInfo::where('is_tour','=',$holidayType == 2 ? 1 : 0)->where('is_flight','=',0)->where('is_bus','=',0)->count() > 0){
			$transportType = new stdClass();
			$transportType->id = 3;
			$transportType->name = "Transport Individual";
			$results[] = $transportType;
		}
		return $results;

	}

	public static function getDestinationsFromTranportType($holidayType,$transportType){
		$results = Geography::distinct('geographies.id ')
							->select(DB::raw('geographies.id as id, geographies.name as name'))
							->rightJoin('packages','geographies.id','=','packages.destination')
							->rightJoin('packages_departure_points','packages.id','=','packages_departure_points.id_package');

		$results = $results->where('packages.is_tour','=',$holidayType == 2 ? 1 : 0)->groupBy('geographies.id')->orderBy('geographies.id')->get();

		$tmpArray = array();
		foreach($results as $result){
			if($result->name == "<root>"){continue;}
			if($result->name != ""){
				$tmpArray[] = $result->id;
			}
		}
	//	dd(Geography::whereIn('id',$tmpArray)->get());
		return Geography::whereIn('id',$tmpArray)->get();
	}


	public static function getDestinations($holidayType,$transportType,$departurePoint){

		$results = Geography::distinct('geographies.id ')
							->select(DB::raw('geographies.id as id, geographies.name as name'))
							->rightJoin('packages','geographies.id','=','packages.destination')
							->rightJoin('packages_departure_points','packages.id','=','packages_departure_points.id_package');
						//	dd($results->where('packages.is_flight','=',1)->count(),$departurePoint);
		switch($transportType){
			case 1 :
				$results = $results->where('packages.is_flight','=',1);
			break;
			case 2 :
				$results = $results->where('packages.is_bus','=',1);
			break;
			case 3:
				$results = $results->where('packages.is_bus','=',0)
								   ->where('packages.is_flight','=',0);
			break;
			default:
			die;
		}


		$results = $results->where('packages_departure_points.id_geography','=',$departurePoint)->where('packages.is_tour','=',$holidayType == 2 ? 1 : 0)->groupBy('geographies.id')->orderBy('geographies.id')->get();


		$tmpArray = array();
		foreach($results as $result){
			if($result->name == "<root>"){continue;}
			if($result->name != ""){
				$tmpArray[] = $result->id;
			}
		}
		return Geography::whereIn('id',$tmpArray)->get();
	}

	public static function getDeparturePoints($holidayType,$transportType){

		$results = Geography::distinct('geographies.id ')
							->select(DB::raw('geographies.id as id, geographies.name as name'))
							->rightJoin('packages_departure_points','packages_departure_points.id_geography','=','geographies.id')
							->rightJoin('packages','packages_departure_points.id_package','=','packages.id');
							//dd($results->get());
		switch($transportType){
			case 1 :
				$results = $results->where('packages.is_flight','=',1);
			break;
			case 2 :
				$results = $results->where('packages.is_bus','=',1);
			break;
			case 3:
				$results = $results->where('packages.is_bus','=',0)
								   ->where('packages.is_flight','=',0);
			break;
			default:
			die;
		}
		$results = $results->where('packages.is_tour','=',$holidayType == 2 ? 1 : 0)->groupBy('geographies.id')->orderBy('geographies.id')->get();
		$tmpArray = array();
		foreach($results as $result){
			if($result->name != ""){
				$tmpArray[] = $result->id;
			}
		}
		return Geography::whereIn('id',$tmpArray)->get();
	}

	public static function getDepartureDates($holidayType,$transportType,$city,$departurePoint){
		$results = DB::table('packages')
					->select(DB::raw('packages.destination, packages_departure_dates.departure_date, g1.id, g2.id'))
				    ->leftJoin(DB::raw('geographies as g1'),'packages.destination','=','g1.id')
					->leftJoin(DB::raw('geographies as g2'),'g1.id_parent','=','g2.id')
					->leftJoin(DB::raw('geographies as g3'),'g2.id_parent','=','g3.id')
					->leftJoin('packages_departure_points','packages.id','=','packages_departure_points.id_package')
					->leftJoin('packages_departure_dates','packages.id','=','packages_departure_dates.id_package')
					->whereRaw('packages_departure_dates.departure_date >= CURDATE()')
					->where(function($q) use ($city){
										 $q->where('g1.id','=',$city)
										   ->orWhere('g2.id','=',$city)
										   ->orWhere('g3.id','=',$city);
					});
		switch($transportType){
			case 1 :
				$results = $results->where('packages.is_flight','=',1);
			break;
			case 2 :
				$results = $results->where('packages.is_bus','=',1);
			break;
			case 3:
				$results = $results->where('packages.is_bus','=',0)
								   ->where('packages.is_flight','=',0);
			break;
			default:
			die;
		}
		$results = $results->where('packages.is_tour','=',$holidayType == 2 ? 1 : 0);
		if($departurePoint != 0){
			$results = $results->where('packages_departure_points.id_geography','=',$departurePoint);
		}

		$results = $results->groupBy('packages_departure_dates.departure_date')
				    ->orderBy('packages_departure_dates.departure_date','ASC')
				    ->get();
		return $results;
	}

	public static function getDurations($holidayType,$transportType,$city,$departureDate,$departurePoint){
		$results = DB::table('packages')
					->select(DB::raw('packages.destination, packages.duration ,packages.day_night , packages_departure_dates.departure_date, g1.id, g2.id'))
				    ->leftJoin(DB::raw('geographies as g1'),'packages.destination','=','g1.id')
					->leftJoin(DB::raw('geographies as g2'),'g1.id_parent','=','g2.id')
					->leftJoin(DB::raw('geographies as g3'),'g2.id_parent','=','g3.id')
					->leftJoin('packages_departure_points','packages.id','=','packages_departure_points.id_package')
					->leftJoin('packages_departure_dates','packages.id','=','packages_departure_dates.id_package')
					->where(function($q) use ($city){
										 $q->where('g1.id','=',$city)
										   ->orWhere('g2.id','=',$city)
										   ->orWhere('g3.id','=',$city);
					})
					->where('packages_departure_dates.departure_date','=',$departureDate);


		switch($transportType){
			case 1 :
				$results = $results->where('packages.is_flight','=',1);
			break;
			case 2 :
				$results = $results->where('packages.is_bus','=',1);
			break;
			case 3:
				$results = $results->where('packages.is_bus','=',0)
								   ->where('packages.is_flight','=',0);
			break;
			default:
			die;
		}
		$results = $results->where('packages.is_tour','=',$holidayType == 2 ? 1 : 0);
		if($departurePoint != 0){
			$results = $results->where('packages_departure_points.id_geography','=',$departurePoint);
		}
		$results = $results->groupBy('packages.duration')
				    ->orderBy('packages.duration','ASC')
				    ->get();
						
		return $results;
	}

	public function estPriceForPackageSearch($searchId){
		$minPrice = PackageSearchCachedPrice::where('id_package','=',$this->id)->where('id_package_search',$searchId)->min(DB::raw('gross + tax'));
		return $minPrice;
	}

	public function getTransportTypeText(){
		if($this->is_flight){
			return "Avion";
		} else if($this->is_bus) {
			return "Autocar";
		} else {
			return "Individual";
		}
	}

	private static function transportTypePackages($is_flight,$is_bus){
		return DB::table('packages')->select(array('packages.*',
											 		DB::raw('MIN(cached_prices.gross + cached_prices.tax) as min_price'),
											 	   'hotels.name as hotel_name',
											 	   'hotels.description as hotel_description',
											 	   'hotels.location as location',
											 	   'hotels.soap_client as hotel_soap_client',
											 	   'hotels.class as stars',
											 	   'hotels.address as hotel_address'))
							 		->leftJoin('packages_fare_types',function($join){
							 			$join->on('packages.id','=','packages_fare_types.id_package');
							 			$join->on('packages.soap_client','=','packages_fare_types.soap_client');
							 		})
							 		->leftJoin('cached_prices',function($join){
					   		 	    	$join->on('packages.id','=','cached_prices.id_package');
					   		 	    	$join->on('packages.soap_client','=','cached_prices.soap_client');
					   		 	    })
					   		 	    ->leftJoin('hotels',function($join){
					   		 	    	$join->on('packages.id_hotel','=','hotels.id');
					   		 	    	$join->on('packages.soap_client','=','hotels.soap_client');
					   		 	    })
						 		   	->whereRaw('packages.is_tour = 0')
						 		   	->whereRaw('packages.is_flight = '.$is_flight)
						 		   	->whereRaw('packages.is_bus = '.$is_bus)
						 		   	->groupBy(array('id_hotel','soap_client'))
						 		   	->havingRaw('MIN(cached_prices.gross + cached_prices.tax) = (SELECT MIN(cp.gross + cp.tax) '.
						 						  									  			'FROM packages p '.
						 						  									  			'LEFT JOIN cached_prices cp ON p.id = cp.id_package AND p.soap_client = cp.soap_client '.
						 						  									  			'WHERE p.id_hotel = packages.id_hotel AND p.is_flight = '.$is_flight.' AND p.is_tour = 0 AND p.is_bus = '.$is_bus.')');
	}



	public static function getFlightPackages(){
		return PackageInfo::transportTypePackages(1,0)->get();
	}

	public static function getBusPackages(){
		return PackageInfo::transportTypePackages(0,1)->get();
	}

	public static function getIndividualPackages(){
		return PackageInfo::transportTypePackages(0,0)->get();
	}

	private static function listingResults(){
		return PackageInfo::transportTypePackages(1,0)
		                  ->union(PackageInfo::transportTypePackages(0,1))
		                  ->union(PackageInfo::transportTypePackages(0,0));
	}


	private static function listingResultsByFilteringOptionsForPackageSearch($searchId,$offerTypesArray,$mealPlansArray,$stars,$search=null){

		$results = DB::table('packages')->select(array('packages.*',
									 		DB::raw('MIN(packages_search_cached_prices.gross + packages_search_cached_prices.tax) as min_price'),
									 	   'hotels.name as hotel_name',
									 	   'hotels.description as hotel_description',
									 	   'hotels.location as location',
										   'hotels.class as stars',
										   'hotels.address as hotel_address'));
							 		$results->leftJoin('packages_fare_types',function($join){
							 			$join->on('packages.id','=','packages_fare_types.id_package');
							 			$join->on('packages.soap_client','=','packages_fare_types.soap_client');
							 		})
					   		 	    ->leftJoin('packages_search_cached_prices',function($join){
					   		 	    	$join->on('packages.id','=','packages_search_cached_prices.id_package');
					   		 	    	$join->on('packages.soap_client','=','packages_search_cached_prices.soap_client');
					   		 	    })
					   		 	    ->leftJoin('hotels',function($join){
					   		 	    	$join->on('packages.id_hotel','=','hotels.id');
					   		 	    	$join->on('packages.soap_client','=','hotels.soap_client');
					   		 	    });
			if($stars != 0){
				$results = $results->whereRaw("hotels.class = ".$stars);
			}

			$results = $results->whereRaw("packages_search_cached_prices.id_package_search = ".$searchId.$offerTypesArray[0].$mealPlansArray[0])
						 		   	->groupBy('id_hotel')
						 		   	->havingRaw('MIN(packages_search_cached_prices.gross + packages_search_cached_prices.tax) = (SELECT MIN(pscp.gross + pscp.tax) '.
														 						  									  			'FROM packages p '.
														 						  									  			'LEFT JOIN packages_search_cached_prices pscp ON p.id = pscp.id_package AND pscp.soap_client = p.soap_client '.
														 						  									  			'LEFT JOIN packages_fare_types pft ON pft.id_package = p.id AND pft.soap_client = p.soap_client '.
														 						  									  			'WHERE p.id_hotel = packages.id_hotel AND pscp.id_package_search = '.$searchId.$offerTypesArray[1].$mealPlansArray[1].')');

		 return $results;
	}

	private static function getPackageListingTable(){
		return DB::table(DB::raw('('.PackageInfo::listingResults()->toSql().') as pl'));
	}



	private static function getPackageListingTableByFilteringOptionsForPackageSearch($searchId,$offerTypes,$mealPlans,$stars,$search = null){
		
		$offerTypesArray = array();
		$mealPlansArray = array();
		if($offerTypes != 0){
			$offerTypesArray[0] = " AND packages_fare_types.id_fare_type IN (".str_replace(";",",",$offerTypes).")";
			$offerTypesArray[1] = " AND pft.id_fare_type IN (".str_replace(";",",",$offerTypes).")";
		} else {
			$offerTypesArray[0] = "";
			$offerTypesArray[1] = "";
		}
		if($mealPlans != 0){
			$mealPlansArray[0] = " AND packages_search_cached_prices.id_meal_plan IN (".str_replace(";",",",$mealPlans).")";
			$mealPlansArray[1] = " AND pscp.id_meal_plan IN (".str_replace(";",",",$mealPlans).")";
		} else {
			$mealPlansArray[0] = "";
			$mealPlansArray[1] = "";
		}
		//dd(PackageInfo::listingResultsByFilteringOptionsForPackageSearch($searchId,$offerTypesArray,$mealPlansArray,$stars,$search)->toSql());
		return DB::table(DB::raw('('.PackageInfo::listingResultsByFilteringOptionsForPackageSearch($searchId,$offerTypesArray,$mealPlansArray,$stars,$search)->toSql().') as pl'));
	}

	public static function getListingResults(){
		$packagesTable = PackageInfo::getPackageListingTable();
		$noPackages = $packagesTable->count('pl.id');
		$packages = $packagesTable->orderBy('pl.min_price','ASC')->get();
		$returnData['packages'] = $packages;
		$returnData['noPackages'] = $noPackages;
		return $returnData;
	}



	public static function getListingResultsByFilteringOptionsForPackageSearch($searchId,$page,$offerTypes,$mealPlans,$stars,$priceFrom,$priceTo,$sortBy,$sortOrder,$search=null){
		//dd($searchId,$page,$offerTypes,$mealPlans,$stars,$priceFrom,$priceTo,$sortBy,$sortOrder,$search=null);
		$packagesTable = PackageInfo::getPackageListingTableByFilteringOptionsForPackageSearch($searchId,$offerTypes,$mealPlans,$stars,$search);
		//dd($packagesTable->get());
		$packages = $packagesTable;
		$minPrice = $packages->min('pl.min_price');
 		$maxPrice = $packages->max('pl.min_price');
 		if($priceFrom != 0){
 			$packages = $packages->where('pl.min_price','>=',$priceFrom);
 		}
 		if($priceTo != 0){
 			$packages = $packages->where('pl.min_price','<=',$priceTo);
 		}
		$noPackages = $packages->count('pl.id');
		if($sortBy == "price"){
			$sortByText = "pl.min_price";
		} else if ($sortBy == "date"){
			$sortByText = "pl.id";
		} else if ($sortBy == "name"){
			$sortByText = "pl.hotel_name";
		}
		$packages = $packages->orderBy($sortByText,$sortOrder);

		$packages = $packages->skip(($page - 1)*10)->take(10);
		$packages = $packages->get();

		$returnData['packages'] = $packages;
		$returnData['noPackages'] = $noPackages;
		$returnData['minPrice'] = $minPrice;
		$returnData['maxPrice'] = $maxPrice;
		return $returnData;
	}

	private static function transportTypePackagesWithFilteringOptions($is_flight,$is_bus,$is_tour,$offerTypesArray,$mealPlansArray,$categoryId,$stars){
		$results = DB::table('packages')->select(array('packages.*',
											 		DB::raw('MIN(cached_prices.gross + cached_prices.tax) as min_price'),
													 'cached_prices.currency as currency',
													 'cached_prices.tax as tax',
											 	   'hotels.name as hotel_name',
											 	   'hotels.description as hotel_description',
											 	   'hotels.location as location',
											 	   'hotels.class as stars',
											 	   'hotels.address as hotel_address',
												   'hotels.available as available'))
							 		->leftJoin('packages_fare_types',function($join){
							 			$join->on('packages.id','=','packages_fare_types.id_package');
							 			$join->on('packages.soap_client','=','packages_fare_types.soap_client');
							 		})
							 		->leftJoin('cached_prices',function($join){
					   		 	    	$join->on('packages.id','=','cached_prices.id_package');
					   		 	    	$join->on('packages.soap_client','=','cached_prices.soap_client');
					   		 	    })
					   		 	    ->leftJoin('hotels',function($join){
					   		 	    	$join->on('packages.id_hotel','=','hotels.id');
					   		 	    	$join->on('packages.soap_client','=','hotels.soap_client');
					   		 	    });
		//dd($results->get());
		if($categoryId != 0){
			$results = $results->leftJoin('package_categories',function($join){
				$join->on('package_categories.id_package','=','packages.id');
				$join->on('package_categories.soap_client','=','packages.soap_client');
			});
		}

		if($stars == 0){
			$results = $results->whereRaw('cached_prices.departure_date > CURDATE()')
			->whereRaw('packages.is_tour = '.$is_tour)
			->whereRaw('packages.is_flight = '.$is_flight)
			->whereRaw('packages.is_bus = '.$is_bus.$offerTypesArray[0].$mealPlansArray[0]);
		}else{
			$results = $results->whereRaw('cached_prices.departure_date > CURDATE()')
			->whereRaw('packages.is_tour = '.$is_tour)
			->whereRaw('packages.is_flight = '.$is_flight)
			->whereRaw('packages.is_bus = '.$is_bus.$offerTypesArray[0].$mealPlansArray[0])
			->whereRaw('hotels.class = '.$stars);
		}
		//$results = $results->where('hotels.available','=',1);
		//$results = $results->where('hotels.class','=',3);
		//dd();
		if($categoryId != 0){
			$results = $results->whereRaw('package_categories.id_category = '.$categoryId);
		}

		$results = $results->groupBy(array('id_hotel','soap_client'))
						 		   	->havingRaw('MIN(cached_prices.gross + cached_prices.tax) = (SELECT MIN(cp.gross + cp.tax) '.
						 						  									  			'FROM packages p '.
						 						  									  			'LEFT JOIN cached_prices cp ON p.id = cp.id_package AND p.soap_client = cp.soap_client '.
						 						  									  			'LEFT JOIN packages_fare_types pft ON pft.id_package = p.id AND pft.soap_client = p.soap_client '.
						 						  									  			'WHERE cp.departure_date > CURDATE() AND p.id_hotel = packages.id_hotel AND p.is_flight = '.$is_flight.' AND p.is_tour = '.$is_tour.' AND p.is_bus = '.$is_bus.$offerTypesArray[1].$mealPlansArray[1].')');
		return $results;
	}

	private static function listingResultsByFilteringOptions($is_tour,$offerTypes,$mealPlans,$categoryId,$stars){
		//dd($is_tour,$offerTypes,$mealPlans,$categoryId);
		//dd($offerTypes);
		$offerTypesArray = array();
		$mealPlansArray = array();
		if($offerTypes != 0){
			$offerTypesArray[0] = " AND packages_fare_types.id_fare_type IN (".str_replace(";",",",$offerTypes).")";
			$offerTypesArray[1] = " AND pft.id_fare_type IN (".str_replace(";",",",$offerTypes).")";
		} else {
			$offerTypesArray[0] = "";
			$offerTypesArray[1] = "";
		}
		if($mealPlans != 0){
			$mealPlansArray[0] = " AND cached_prices.id_meal_plan IN (".str_replace(";",",",$mealPlans).")";
			$mealPlansArray[1] = " AND cp.id_meal_plan IN (".str_replace(";",",",$mealPlans).")";
		} else {
			$mealPlansArray[0] = "";
			$mealPlansArray[1] = "";
		}
		//dd($offerTypesArray);
		//[10,01,00]
		return self::transportTypePackagesWithFilteringOptions(1,0,$is_tour,$offerTypesArray,$mealPlansArray,$categoryId,$stars)
		                  ->union(PackageInfo::transportTypePackagesWithFilteringOptions(0,1,$is_tour,$offerTypesArray,$mealPlansArray,$categoryId,$stars))
		                  ->union(PackageInfo::transportTypePackagesWithFilteringOptions(0,0,$is_tour,$offerTypesArray,$mealPlansArray,$categoryId,$stars));
	}


//====================================


	private static function getPackageListingTableByFilteringOptions($is_tour,$offerTypes,$mealPlans,$categoryId,$stars){
		//return self::listingResultsByFilteringOptions($is_tour,$offerTypes,$mealPlans,$categoryId)->toSql();
		//dd(self::listingResultsByFilteringOptions($is_tour,$offerTypes,$mealPlans,$categoryId,$stars)->toSql());
		return DB::table(DB::raw('('.self::listingResultsByFilteringOptions($is_tour,$offerTypes,$mealPlans,$categoryId,$stars)->toSql().') as pl'));
	}

	public static function getListingResultsByFilteringOptions($is_tour,$page,$locationFiltering,$locationId,$offerTypes,$transportType,$mealPlans,$stars,$priceFrom,$priceTo,$categoryId,$sortBy,$sortOrder){
		
		$locationIds = array();
		if($locationId != 0){
			Geography::getLocationsFromBaseLocation(Geography::where('id','=',$locationId)->first(),$locationIds);
		}
		
		$transportTypesArray = $transportType == "" ? array() : explode(";",$transportType);
		$packagesTable = self::getPackageListingTableByFilteringOptions($is_tour,$offerTypes,$mealPlans,$categoryId,$stars);
		//dd($packagesTable->get());
		$packages = $packagesTable;
		//dd($packages->get());
		if($locationId != 0){
 			$packages = $packages->whereRaw('pl.destination IN ('.implode(',',$locationIds).')');
 		}
		//=====================
		$packages = $packages->whereRaw('pl.available = 1');

 		$transportTypesArray = $transportType == "" ? array() : explode(";",$transportType);
		//dd($transportTypesArray);
 		if(count($transportTypesArray) == 1){
			//dd(self::listingResultsByFilteringOptions($is_tour,$offerTypes,$mealPlans,$categoryId,$stars)->toSql());
 			if($transportTypesArray[0] == 1){
 				$packages = $packages->whereRaw('pl.is_flight = 1');
 			} else if ($transportTypesArray[0] == 2) {
 				$packages = $packages->whereRaw('pl.is_bus = 1');
 			} else {
				$packages = $packages->whereRaw('pl.is_bus = 0')->whereRaw('pl.is_flight = 0');
 			}
 		} else if(count($transportTypesArray) == 2){
 			if( ($transportTypesArray[0] == 1 && $transportTypesArray[1] == 2) || ($transportTypesArray[0] == 2 && $transportTypesArray[1] == 1) ){
 				$packages = $packages->where(function($q){
 												$q->whereRaw('pl.is_flight = 1')
 												  ->orWhereRaw('pl.is_bus = 1');
 											});
 			} else if ( ($transportTypesArray[0] == 2 && $transportTypesArray[1] == 3) || ($transportTypesArray[0] == 3 && $transportTypesArray[1] == 2) ){
 				$packages = $packages->where(function($q){
 												$q->whereRaw('pl.is_bus = 1')
 												  ->orWhere(function($q2){
 												  				$q2->whereRaw('pl.is_bus = 0')->whereRaw('pl.is_flight = 0');
 												  			});
 											});
 			} else if ( ($transportTypesArray[0] == 1 && $transportTypesArray[1] == 3) || ($transportTypesArray[0] == 3 && $transportTypesArray[1] == 1) ){
 				$packages = $packages->where(function($q){
 												$q->whereRaw('pl.is_flight = 1')
 												  ->orWhere(function($q2){
															//dd($q2);
 												  			$q2->whereRaw('pl.is_bus = 0')->whereRaw('pl.is_flight = 0');
 												  });
 				});
				//dd($packages->toSql());
 			}
 		}

 		if($priceFrom != 0){
 			$packages = $packages->whereRaw('pl.min_price >= '.$priceFrom);
 		}
 		if($priceTo != 0){
 			$packages = $packages->whereRaw('pl.min_price <= '.$priceTo);
 		}

		if($sortBy == "price"){
			$sortByText = "pl.min_price";
		} else if ($sortBy == "date"){
			$sortByText = "pl.id";
		} else if ($sortBy == "name"){
			$sortByText = "pl.hotel_name";
		}
		$packages = $packages->orderBy($sortByText,$sortOrder);
		//dd($packages->toSql());
		$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS temp_offers AS (".$packages->toSql().");";
		$packageResults = Cache::remember(md5($sql)."_".$page, 60*60, function() use ($sql,$page) {
	        DB::statement(DB::raw($sql));
	    	$packages = DB::table('temp_offers');
			$result = new stdClass();
			$result->minPrice = $packages->min('min_price');
 		    $result->maxPrice = $packages->max('min_price');
 		    $result->noPackages = $packages->count('*');
			$result->packages = $packages->skip(($page - 1)*10)->take(10)->get();
			DB::statement(DB::raw('DROP TABLE IF EXISTS temp_offers;'));
			return $result;
		});
		$minPrice = $packageResults->minPrice;
 		$maxPrice = $packageResults->maxPrice;
 		$noPackages = $packageResults->noPackages;
		$packages = $packageResults->packages;
		$returnData['packages'] = $packages;
		$returnData['noPackages'] = $noPackages;
		$returnData['minPrice'] = $minPrice;
		$returnData['maxPrice'] = $maxPrice;
		return $returnData;
	}

	public static function getTransportCode($isTour,$isBus,$isFlight){
		$code = $isTour == 0 ? "0" : "1";
		if($isFlight == 0 && $isBus == 0){
			$code .= "3";
		} else if ($isFlight == 0 && $isBus == 1) {
			$code .= "1";
		} else if ($isFlight == 1 && $isBus == 0){
			$code .= "2";
		}
		return $code;
	}

	public static function getTransportByCode($code){
		$return = array();
		$return['is_tour'] = intval($code{0});
		switch($code{1}){
			case '3':
				$return['is_bus'] = 0;
				$return['is_flight'] = 0;
			break;
			case '1':
				$return['is_bus'] = 1;
				$return['is_flight'] = 0;
			break;
			case '2':
				$return['is_bus'] = 0;
				$return['is_flight'] = 1;
			break;
			default:
		}
		return $return;
	}

	public static function getInfoForViewAllPackagesByHotel($idHotel,$soapClient,$isTour,$isBus,$isFlight){
		return DB::table(DB::raw('packages p'))->select(array(DB::raw('MIN(pdd.departure_date) as departure_date'),
															  'pdp.id_geography as departure_point',
															  'p.destination as destination',
															  'p.duration as duration'))
									  ->leftJoin(DB::raw('packages_departure_dates pdd'),function($join){
									  	$join->on('pdd.id_package','=','p.id');
									  	$join->on('pdd.soap_client','=','p.soap_client');
									  })
									  ->leftJoin(DB::raw('packages_departure_points pdp'),function($join){
									  	$join->on('pdp.id_package','=','p.id');
									  	$join->on('pdp.soap_client','=','p.soap_client');
									  })
									  ->where('p.id_hotel','=',$idHotel)
									  ->where('p.soap_client','=',$soapClient)
									  ->where('p.is_tour','=',$isTour)
									  ->where('p.is_bus','=',$isBus)
									  ->where('p.is_flight','=',$isFlight)
									  ->whereRaw('p.duration = (SELECT MIN(p2.duration) FROM packages p2 WHERE p2.id_hotel = '.$idHotel.' AND p2.soap_client = "'.$soapClient.'" AND p2.is_tour = '.$isTour.' AND p2.is_bus = '.$isBus.' AND p2.is_flight = '.$isFlight.')')
									  ->whereRaw('pdd.departure_date >= CURDATE()')
									  ->get()[0];
	}

	public static function getCachedPricesTable($id_package,$soap_client){
		$pricesCachedArray = [];
		$package = PackageInfo::where('id','=',$id_package)->where('soap_client','=',$soap_client)->first();
		//dd($package);
		if($package){
			$prices = Adminpackages::getPricesForPackage($package->id,$package->soap_client);
			//dd(date('d-m-Y',strtotime($prices[0]->departure_date)));
			foreach($prices as $price){
				$exists = false;

				if(isset($pricesCachedArray[$price->room_category_name." + ".$price->meal_plan_name])){
					foreach($pricesCachedArray[$price->room_category_name." + ".$price->meal_plan_name] as $dateA => $priceA){
						if($priceA == ($price->gross + $price->tax)){
							unset($pricesCachedArray[$price->room_category_name." + ".$price->meal_plan_name][$dateA]);
							$pricesCachedArray[$price->room_category_name." + ".$price->meal_plan_name][$dateA.", ".date('d-m-Y',strtotime($price->departure_date))] = $priceA;
							$exists = true;
							break;
						}
					}
			  	}
				if(!$exists){
					$pricesCachedArray[$price->room_category_name." + ".$price->meal_plan_name][date('d-m-Y',strtotime($price->departure_date))] = $price->gross + $price->tax;
				}
			}
			foreach($pricesCachedArray as $k => $v){
				$priceSort = array();
				foreach ($pricesCachedArray[$k] as $key => $row)
				{
				    $priceSort[$key] = $row;
				}
				array_multisort($priceSort, SORT_ASC, $pricesCachedArray[$k]);
			}
		}
		return $pricesCachedArray;
	}


}
