<?php namespace App\Models\Travel;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use DB;

class HotelEloquent extends Model
{
    use SearchableTrait;
    protected $table = 'hotels';

    protected $searchable = [
        'columns' => [
            'hotels.name' => 20
		]
	];

    public function packages(){
      return $this->hasMany('App\Models\Travel\Eloquent\PackageInfoEloquent','id_hotel');
    }

    public function cached_prices(){
      return $this->hasManyThrough(
        'App\Models\Travel\Eloquent\CachedPriceEloquent',
        'App\Models\Travel\Eloquent\PackageInfoEloquent','id_hotel','id_package'
      );
    }

    public static function getListingResultsByFilteringOptionsForPackageSearch($searchId,$page,$offerTypes,$mealPlans,$stars,$priceFrom,$priceTo,$sortBy,$sortOrder,$search=null){

  		$packagesTable = HotelEloquent::getPackageListingTableByFilteringOptionsForPackageSearch($searchId,$offerTypes,$mealPlans,$stars,$search);
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
  		return DB::table(DB::raw('('.HotelEloquent::listingResultsByFilteringOptionsForPackageSearch($searchId,$offerTypesArray,$mealPlansArray,$stars,$search)->toSql().') as pl'));
  	}

    private static function listingResultsByFilteringOptionsForPackageSearch($searchId,$offerTypesArray,$mealPlansArray,$stars,$search=null){

      $results = DB::table('packages')->whereRaw("packages.id_hotel = '{$search}'");/*->select(['packages.*',
  									 		DB::raw('MIN(packages_search_cached_prices.gross + packages_search_cached_prices.tax) as min_price'),
  									 	   'hotels.name as hotel_name',
  									 	   'hotels.description as hotel_description',
  									 	   'hotels.location as location',
  										   'hotels.class as stars',
  										   'hotels.address as hotel_address']);

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
                        //$results =$results->whereRaw("hotels.name LIKE '{$search}'");
                  			$results = $results->whereRaw("packages_search_cached_prices.id_package_search = ".$searchId.$offerTypesArray[0].$mealPlansArray[0])
                  						 		   	->groupBy('id_hotel')
                  						 		   	->havingRaw('MIN(packages_search_cached_prices.gross + packages_search_cached_prices.tax) = (SELECT MIN(pscp.gross + pscp.tax) '.
                  														 						  									  			'FROM packages p '.
                  														 						  									  			'LEFT JOIN packages_search_cached_prices pscp ON p.id = pscp.id_package AND pscp.soap_client = p.soap_client '.
                  														 						  									  			'LEFT JOIN packages_fare_types pft ON pft.id_package = p.id AND pft.soap_client = p.soap_client '.
                  														 						  									  			'WHERE p.id_hotel = packages.id_hotel AND pscp.id_package_search = '.$searchId.$offerTypesArray[1].$mealPlansArray[1].')');
*/
                        dd($results->get());
                        return $results;
  	}


}
