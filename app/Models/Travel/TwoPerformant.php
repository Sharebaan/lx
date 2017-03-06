<?php

namespace App\Models\Travel;

use Illuminate\Database\Eloquent\Model;

use DB;
use Cache;
use \stdClass;
use App\Models\Adminpackages;

class TwoPerformant extends Model
{
   
	protected $table = 'packages';

	public $timestamps = false;



	public static function getListingResultsByFilteringOptions($categoryId,$category,$location = null,$mealPlan = null){
		
		$transportTypesArray = [];
		$locationIds=[];
		
		if($location != null){
			Geography::getLocationsFromBaseLocation(Geography::where('id','=',$location)->first(),$locationIds);
		}
		
		$packagesTable = self::getPackageListingTableByFilteringOptions($categoryId,$mealPlan);

		$packages = $packagesTable;
		
		if($location != null){
			$packages = $packages->whereRaw('pl.destination IN ('.implode(',',$locationIds).')');
		}
		
		$packages = $packages->whereRaw('pl.available = 1');

 		$transportTypesArray = [];
		
 		if(count($transportTypesArray) == 1){
			
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

 			}
 		}

 		

		
		$sortByText = "pl.min_price";
		
		$packages = $packages->orderBy($sortByText,'ASC');
		
		$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS temp_offers AS (".$packages->toSql().");";
		$packageResults = Cache::remember(md5($sql)."_", 60*60, function() use ($sql) {
	        DB::statement(DB::raw($sql));
	    	$packages = DB::table('temp_offers');
			$result = new stdClass();
			$result->minPrice = $packages->min('min_price');
 		    $result->maxPrice = $packages->max('min_price');
 		    $result->noPackages = $packages->count('*');
			$result->packages = $packages->get();
			DB::statement(DB::raw('DROP TABLE IF EXISTS temp_offers;'));
			return $result;
		});
		
		$packages = $packageResults->packages;
		//dd(self::check($packages,$category));
		return self::forExcel(self::check($packages,$category),$category);
	}
	
	private static function check($packages,$category){
		if($category === 'paste2017'){
			$result=[];
			foreach($packages as $v){
				if($v->id_hotel == 1236 || $v->id_hotel == 1237 || $v->id_hotel == 1238 || $v->id_hotel == 1336 || $v->id_hotel == 1337){
					array_push($result,$v);
				}
			}
			return $result;
		}else{
			return $packages;
		}
	}

	private static function forExcel($packages,$category){
		$array=[];
		foreach($packages as $k=>$v){
			$location = Geography::getCountryForHotel($v->id_hotel,$v->soap_client);
			$transport = PackageInfo::getTransportCode($v->is_tour,$v->is_bus,$v->is_flight);
			$picture = \DB::table('file_infos')->where('id_hotel','=',$v->id_hotel)->first();
			$array[$k]=[
				'title'=>$v->name,
				'description'=>strip_tags(preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $v->description)),
				'short_message'=>'',
				'price'=>($v->min_price + $v->tax),
				'category'=>$category,
				'subcategory'=>'',
				'url'=>'http://hello.infora.ro/oferte/Circuite/'.$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$v->hotel_name))).'_'.$transport."_".$v->id_hotel."_".$v->soap_client."_sid0",
				'image_url'=>(!empty($picture) ? 'http://hello.infora.ro/public/images/offers/'.$v->soap_client.'/'.$picture->name : ''),
				'product_id'=>$v->idx,
				'generate_link_text'=>0,
				'brand'=>'',
				'active'=>$v->available,
				'other_data'=>''
			];
		}
		return $array;
	}


	private static function getPackageListingTableByFilteringOptions($categoryId,$mealPlan = null){
	//$is_tour,$offerTypes,$mealPlans,$categoryId,$stars
		return DB::table(DB::raw('('.self::listingResultsByFilteringOptions($categoryId,$mealPlan)->toSql().') as pl'));
	}

	private static function listingResultsByFilteringOptions($categoryId,$mealPlan = null){
		//dd($is_tour,$offerTypes,$mealPlans,$categoryId);
		//dd($offerTypes);
		
		$offerTypesArray[0] = "";
		$offerTypesArray[1] = "";
	
		if($mealPlan != null){
			$mealPlansArray[0] = " AND cached_prices.id_meal_plan IN (".str_replace(";",",",$mealPlan).")";
			$mealPlansArray[1] = " AND cp.id_meal_plan IN (".str_replace(";",",",$mealPlan).")";
		} else {
			$mealPlansArray[0] = "";
			$mealPlansArray[1] = "";
		}
		
		//dd($mealPlansArray);
		//[10,01,00]
		return self::transportTypePackagesWithFilteringOptions(1,0,1,$offerTypesArray,$mealPlansArray,$categoryId,0)
		                  ->union(self::transportTypePackagesWithFilteringOptions(0,1,1,$offerTypesArray,$mealPlansArray,$categoryId,0))
		                  ->union(self::transportTypePackagesWithFilteringOptions(0,0,1,$offerTypesArray,$mealPlansArray,$categoryId,0));
	}

	private static function transportTypePackagesWithFilteringOptions($is_flight,$is_bus,$is_tour,$offerTypesArray,$mealPlansArray,$categoryId,$stars){
		//dd($mealPlansArray);
		//$is_flight,$is_bus,$is_tour,$offerTypesArray,$mealPlansArray,$categoryId,$stars
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
		}
		

		if($categoryId != 0){
			$results = $results->whereRaw('package_categories.id_category = '.$categoryId);
		}

		$results = $results->groupBy(array('id_hotel','soap_client'))
						 		   	->havingRaw('MIN(cached_prices.gross + cached_prices.tax) = (SELECT MIN(cp.gross + cp.tax) '.
						 						  									  			'FROM packages p '.
						 						  									  			'LEFT JOIN cached_prices cp ON p.id = cp.id_package AND p.soap_client = cp.soap_client '.
						 						  									  			'LEFT JOIN packages_fare_types pft ON pft.id_package = p.id AND pft.soap_client = p.soap_client '.
						 						  									  			'WHERE cp.departure_date > CURDATE() AND p.id_hotel = packages.id_hotel AND p.is_flight = '.$is_flight.' AND p.is_tour = '.$is_tour.' AND p.is_bus = '.$is_bus.$offerTypesArray[1].$mealPlansArray[1].')');
		//dd($results->toSql());
		return $results;
	}
}