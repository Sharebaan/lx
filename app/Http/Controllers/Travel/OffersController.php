<?php namespace App\Http\Controllers\Travel;

use App\Http\Controllers\controller;

use App\Models\Travel\Hotel;
use App\Models\Travel\HotelSearchCached;
use App\Models\Travel\Geography;
use App\Models\Travel\MealPlanHotelSearch;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\PackagesSearchCached;

use App\Models\Travel\FareType;

use App\Models\Travel\MealPlan;
use App\Models\Travel\MealPlanPackageSearch;


use App;
use DB;
use URL;
use View;
use ET_SoapClient\SoapObjects\RoomSoapObject;
use ET_SoapClient\SoapObjects\PackageSearchSoapObject;
use ET_SoapClient\SoapObjects\RoomCategorySoapObject;
use ET_SoapClient\SoapObjects\HotelSearchSoapObject;
use ET_SoapClient\ET_SoapClient;
use App\Models\Travel\RoomCategory;
use App\Models\Adminpackages;
use Config;

class OffersController extends Controller {

	const T_HOTEL = "00";
	const T_PACKAGES_BUS = "01";
	const T_PACKAGES_FLIGHT = "02";
	const T_PACKAGES_INDIVIDUAL = "03";
	const T_CIRCUITS_BUS = "11";
	const T_CIRCUITS_FLIGHT = "12";
	const T_CIRCUITS_INDIVIDUAL = "13";

	const O_HOTEL = 0;
	const O_PACKAGES = 1;

	public function listPackages(){
		$page = isset($_GET["page"]) ? $_GET["page"] : 1;
		$offerTypes = isset($_GET["offerTypes"]) ? $_GET["offerTypes"] : 0;
		$mealPlans = isset($_GET["mealPlans"]) ? $_GET["mealPlans"] : 0;
		$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : "price";
		$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : "ASC";
		$priceFrom = isset($_GET['priceFrom']) ? $_GET['priceFrom'] : 0;
		$priceTo = isset($_GET['priceTo']) ? $_GET['priceTo'] : 0;
		$locationFiltering = isset($_GET['locationFiltering']) ? $_GET['locationFiltering'] : "base";
		$locationId = isset($_GET['locationId']) ? $_GET['locationId'] : 0;
		$transportType = isset($_GET['transportType']) ? $_GET['transportType'] : "";
		$searchId = isset($_GET['searchId']) ? $_GET['searchId'] : 0;
		$categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : 0;
		$stars = isset($_GET['stars']) ? $_GET['stars'] : 0;
		$data['page'] = $page;

		if($searchId == 0){
			$packagesData = PackageInfo::getListingResultsByFilteringOptions(0,$page,$locationFiltering,$locationId,$offerTypes,$transportType,$mealPlans,$stars,$priceFrom,$priceTo,$categoryId,$sortBy,$sortOrder);
			foreach($packagesData['packages'] as $v){
				if($v->soap_client == 'LOCAL'){
					$v->currency = \DB::table('cached_prices')->where('id_package','=',$v->id)->where('gross','=',$v->min_price)->first()->currency;
				}else{
					$v->currency = 0;
				}
			}
		} else {
			$packagesData = PackageInfo::getListingResultsByFilteringOptionsForPackageSearch($searchId,$page,$offerTypes,$mealPlans,$stars,$priceFrom,$priceTo,$sortBy,$sortOrder);
			foreach($packagesData['packages'] as $v){
				if($v->soap_client == 'LOCAL'){
					foreach(\DB::table('packages_search_cached_prices')->where('id_package','=',$v->id)->get() as $p){
						$v->currency = \DB::table('cached_prices')->where('id_package','=',$p->id_package)->where('gross','=',$v->min_price)->first()->currency;
					}
				}else{
					$v->currency = 0;
				}
			}
		}
		
		if($searchId != 0){
			$searchObj = new \stdClass();
			$packageSearchDB = PackagesSearchCached::find($searchId);
			if($packageSearchDB->is_flight == 1){
				$searchObj->transportType = 1;
			} else if ($packageSearchDB->is_bus == 1){
				$searchObj->transportType = 2;
			} else if ($packageSearchDB->is_flight == 0 && $packageSearchDB->is_bus == 0){
				$searchObj->transportType = 3;
			}
			if($packageSearchDB->is_tour == 0){
				$searchObj->holidayType = 1;
			} else if($packageSearchDB->is_tour == 1){
				$searchObj->holidayType = 2;
			}
			$searchObj->city = $packageSearchDB->destination;
			$city = Geography::find($packageSearchDB->destination);
			$searchObj->country = $city;
			$cityLevel = $city->tree_level - 2;
			for($i = 1;$i <= $cityLevel;$i++){
				$searchObj->country = $searchObj->country->parent;
			}
			$searchObj->country = $searchObj->country->id;
			$searchObj->departureDate = $packageSearchDB->departure_date;
			$searchObj->duration = $packageSearchDB->duration;
			$searchObj->rooms = $packageSearchDB->rooms;
			$searchObj->departure = $packageSearchDB->departure;
			$data['searchObj'] = $searchObj;
			$data['transportTypesSearch'] = PackageInfo::getTransportTypes($searchObj->holidayType);
			$countries = Geography::getLocationsForPackageSearch(PackageInfo::getDestinations($searchObj->holidayType,$searchObj->transportType,$searchObj->departure));
			usort($countries,function($a,$b){
				return strcmp($a->name,$b->name);
			});
			$data['countriesDestinations'] = $countries;
			$cities = array();
			$tmpLocations = array();
			foreach($countries as $location){
				if($location->id == $searchObj->country){
					$tmpLocations = $location->childrens;
				}
			}
			foreach($tmpLocations as $location){
				$cities[] = $location;
				usort($location->childrens,function($a,$b){
					return strcmp($a->name,$b->name);
				});
				foreach($location->childrens as $child){
					$child->name = $child->name.", ".$location->name;
					$cities[] = $child;
				}
			}
			$Rooms = json_decode($packageSearchDB->rooms);
			$jsonRooms = array();
			$jsonRooms['count'] = count($Rooms);
			$jsonRooms['guests'] = array();
			foreach($Rooms as $room){
				$jsonRoom = array();
				$jsonRoom['adults'] = $room->adults;
				if(isset($room->kids)){
					$jsonRoom['kids'] = array();
					foreach($room->kids as $kid){
						$jsonRoom['kids'][] = $kid;
					}
				}
				$jsonRooms['guests'][] = $jsonRoom;
			}
			$data['jsonRooms'] = json_encode($jsonRooms);
			$data['citiesDestinations'] = $cities;
			$data['departurePoints'] = PackageInfo::getDeparturePoints($searchObj->holidayType,$searchObj->transportType);
			$data['departureDates'] = PackageInfo::getDepartureDates($searchObj->holidayType,$searchObj->transportType,$searchObj->city,$searchObj->departure);
			$data['durations'] = PackageInfo::getDurations($searchObj->holidayType,$searchObj->transportType,$searchObj->city,$searchObj->departureDate,$searchObj->departure);

		}

		$packages = $packagesData['packages'];
		$noPackages = $packagesData['noPackages'];
		//dd($packagesData);
		//PackageInfo::getListingResultsByFilteringOptionsForPackageSearch
		$data['minPrice'] = intval($packagesData['minPrice']);
		$data['maxPrice'] = intval($packagesData['maxPrice']);
		$data['leftPrice'] = $priceFrom == 0 ? $data['minPrice'] : $priceFrom;
		$data['rightPrice'] = $priceTo == 0 ? $data['maxPrice'] : $priceTo;

		/*
		 * ===================== CREATING URLs FOR FILTERING ========================
		 * ================================ START ===================================
		 */

		// Pages URLs

		$lastPage = $noPackages % 10 != 0 ? intval($noPackages / 10) + 1 : intval($noPackages / 10);

		$pagesArray = array();
		if($searchId == 0){
			$additionalGETsBefore = "";
			$pageGET = "?page=";
			$additionalGETsAfter = ($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
							 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
							 .($transportType != "" ? "&transportType=".$transportType : "")
							 .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 .($locationId != 0 ? "&locationId=".$locationId : "")
							 .($categoryId != 0 ? "&categoryId=".$categoryId : "")
							 .($stars != 0 ? "&stars=".$stars : "")
							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId;
			$pageGET = "&page=";
			$additionalGETsAfter = ($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
							 .($stars != 0 ? "&stars=".$stars : "")
							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}
		if($page <= 3){
			$t = $lastPage < 6 ? $lastPage : 6;
			for($i = 1; $i <= $t; $i++){
				$selected = ($page == $i) ? true : false;
				$pagesArray[] = array("text" => $i,
									  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET.$i.$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			if($lastPage >= 7){
				$pagesArray[] = array("text" => "&raquo;",
									  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET.$lastPage.$additionalGETsAfter,
									  "selected" => false
									 );
			}
		} elseif($page >= $lastPage - 2) {
			if($lastPage > 6){
				$pagesArray[] = array("text" => "&laquo;",
									  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET."1".$additionalGETsAfter,
									  "selected" => false
									 );
			}
			$t = $lastPage < 6 ? $lastPage - 1 : 5;
			for($i = $t; $i >= 0; $i--){
				$selected = ($page == $lastPage - $i) ? true : false;
				$pagesArray[] = array("text" => $lastPage - $i,
									  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET.($lastPage - $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}

		} else {
			$pagesArray[] = array("text" => "&laquo;",
								  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET."1".$additionalGETsAfter,
								  "selected" => false
								 );
			for($i = 2; $i > 0; $i--){
				$selected = false;
				$pagesArray[] = array("text" => $page - $i,
									  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET.($page - $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			$pagesArray[] = array("text" => $page,
								  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET.$page.$additionalGETsAfter,
								  "selected" => true
								 );
			for($i = 1; $i <= 2; $i++){
				$selected = false;
				$pagesArray[] = array("text" => $page + $i,
									  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET.($page + $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			$pagesArray[] = array("text" => "&raquo;",
								  "url" => URL::route('package_listing').$additionalGETsBefore.$pageGET.$lastPage.$additionalGETsAfter,
								  "selected" => false
								 );
		}
		$data['pages'] = $pagesArray;

		// Location URLs

		if($searchId == 0){
			$additionalGETsBefore = "?page=1"
									.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
									.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
									.($transportType != "" ? "&transportType=".$transportType : "")
							 		.($categoryId != 0 ? "&categoryId=".$categoryId : "");
			$additionalGETsAfter = ($stars != 0 ? "&stars=".$stars : "")
									 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	   .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
								   .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	   .($priceTo != 0 ? "&priceTo=".$priceTo: "");

			if($locationFiltering == "base"){
				$data['locations']['tree_level'] =  2;
				$data['locations']['typename'] = "Filtrare dupa locatie";
				$locations = Geography::where('tree_level','=',$data['locations']['tree_level'])->where('available_in_stays','=',1)->orderBy('name','ASC')->get();
			} else if($locationFiltering == "child") {
				$location = Geography::where('id','=',$locationId)->where('available_in_stays','=',1)->first();
				$locations = Geography::where('id_parent','=',$locationId)->where('available_in_stays','=',1)->orderBy('name','ASC')->get();
				$noChildrens = count($locations);
				$locations = $noChildrens == 0 ? Geography::where('id_parent','=',$location->id_parent)->where('available_in_stays','=',1)->orderBy('name','ASC')->get() : $locations;
				$data['locations']['tree_level'] =  $location->tree_level;
				$data['locations']['typename'] = "" ;
				$locationTemp = $location;
				$data['locations']['country'] = $noChildrens == 0 ? $location->parent->name : $location->name;
				$data['locations']['countrySelected'] = $noChildrens == 0 ? false : true;
				$data['locations']['countryUrl'] = $noChildrens == 0 ? URL::route('package_listing').$additionalGETsBefore."&locationFiltering=child"."&locationId=".$location->parent->id.$additionalGETsAfter : URL::route('package_listing').$additionalGETsBefore."&locationFiltering=child"."&locationId=".$location->id.$additionalGETsAfter;
				$data['locations']['backToCountrySelectionUrl'] =  URL::route('package_listing').$additionalGETsBefore.$additionalGETsAfter;

			}
			foreach($locations as $location){
				if($locationFiltering == "base"){
					$locationFilteringGET = "&locationFiltering=child";
					$locationFilteringID = $location->id;
				} else if($locationFiltering == "child") {
					$locationFilteringGET = "&locationFiltering=child";
					$locationFilteringID = $location->id;
				}
				$location->url = URL::route('package_listing').$additionalGETsBefore.$locationFilteringGET."&locationId=".$locationFilteringID.$additionalGETsAfter;
				if($locationFiltering == "child"){
					if($location->id == $locationId){
						$location->selected = true;
					} else {
						$location->selected = false;
					}
				}
			}

			$data['locations']['data'] = $locations;
		}

		// Fare Types URLs

		$offerTypesDB = FareType::all();
		$offerTypesArray = explode(";",$offerTypes);
		if($searchId == 0){
			$additionalGETsBefore = "?page=1";
			$additionalGETsAfter = ($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
								  .($transportType != "" ? "&transportType=".$transportType : "")
								  .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 	  .($locationId != 0 ? "&locationId=".$locationId : "")
							 	  .($categoryId != 0 ? "&categoryId=".$categoryId : "")
									.($stars != 0 ? "&stars=".$stars : "")
								  .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId
								   ."&page=1";
			$additionalGETsAfter = ($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
									.($stars != 0 ? "&stars=".$stars : "")
								  .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}
		foreach($offerTypesDB as $offerTypeDB){
			$offerTypesArrayTemp = $offerTypesArray;
			$offerTypesGET = "";
			if(in_array($offerTypeDB->id,$offerTypesArray)){
				if(($key = array_search($offerTypeDB->id, $offerTypesArrayTemp)) !== false) {
				    unset($offerTypesArrayTemp[$key]);
				}
				foreach($offerTypesArrayTemp as $offerTypeA){
					$offerTypesGET .= $offerTypesGET == "" ? $offerTypeA : ";".$offerTypeA;
				}
				$offerTypeDB->selected = true;
			} else {
				$offerTypesGET = $offerTypes == 0 ? $offerTypeDB->id : $offerTypes.";".$offerTypeDB->id;
				$offerTypeDB->selected = false;
			}
			$offerTypesGET = $offerTypesGET == "" ? "" : "&offerTypes=".$offerTypesGET;
			$offerTypeDB->url = URL::route('package_listing').$additionalGETsBefore.$offerTypesGET.$additionalGETsAfter;
		}

		$data['fareTypes'] = $offerTypesDB;

		// Transport Type URLs

		$transportTypeArray = explode(";",$transportType);
		$transportTypes = array();
		$additionalGETsBefore = "?page=1"
								.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
								.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "");
		$additionalGETsAfter = ($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
						     .($locationId != 0 ? "&locationId=".$locationId : "")
								 .($stars != 0 ? "&stars=".$stars : "")
							   .($categoryId != 0 ? "&categoryId=".$categoryId : "")
						 	   .($sortBy != "" ? "&sortBy=".$sortBy: "")
						 	   .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							   .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
						 	   .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		for($i = 1 ; $i <=3 ; $i++){
			$transportTypeArrayTemp = $transportTypeArray;
			$transportTypeGET = "";
			if(in_array($i,$transportTypeArrayTemp)){
				if(($key = array_search($i, $transportTypeArrayTemp)) !== false) {
				    unset($transportTypeArrayTemp[$key]);
				}
				foreach($transportTypeArrayTemp as $transportTypeA){
					$transportTypeGET .= $transportTypeGET == "" ? $transportTypeA : ";".$transportTypeA;
				}
				$transportTypes[$i]["selected"] = true;
			} else {
				$transportTypeGET = $transportType == "" ? $i : $transportType.";".$i;
				$transportTypes[$i]["selected"] = false;
			}
			$transportTypeGET = $transportTypeGET == "" ? "" : "&transportType=".$transportTypeGET;
			$transportTypes[$i]["url"] = URL::route('package_listing').$additionalGETsBefore.$transportTypeGET.$additionalGETsAfter;
		}

		$data['transportTypes'] = $transportTypes;


		// Stars URLs

		$starsArray = explode(";",$stars);
		$starsOutputArray = array();
		if($searchId == 0){
			$additionalGETsBefore = "?page=1"
								.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
								.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
								.($transportType != "" ? "&transportType=".$transportType : "")
								.($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
						        .($locationId != 0 ? "&locationId=".$locationId : "");
			$additionalGETsAfter =  ($sortBy != "" ? "&sortBy=".$sortBy: "")
						 	   	   .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							       .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
						 	       .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId
								   ."&page=1"
								   .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "");
			$additionalGETsAfter = ($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}

		$starsGET = "";
		for($i = 1;$i <= 5;$i++){
			$starsArrayTemp = $starsArray;
			$starsGET = "";
			if(in_array($i, $starsArray)){
				if(($key = array_search($i, $starsArrayTemp)) !== false) {
				    unset($starsArrayTemp[$key]);
				}
				foreach($starsArrayTemp as $starA){

					$starsGET .= $starsGET == "" ? $starA : ";".$starA;
				}
				$starsOutputArray[$i]["selected"] = true;

			} else {
				//$stars.";".
				$starsGET = $stars == "" ? $i : $i;
				$starsOutputArray[$i]["selected"] = false;
			}
			$starsGET = $starsGET == "" ? "" : "&stars=".$starsGET;
			$starsOutputArray[$i]["url"] = URL::route('package_listing').$additionalGETsBefore.$starsGET.$additionalGETsAfter;
			//var_dump($starsGET);
		}
		//dd($starsOutputArray);
		$data['stars'] = $starsOutputArray;



		// Meal Plans URLs
		if($searchId == 0){
			$mealPlansDB = MealPlan::all();
		} else {
			$mealPlansDB = MealPlanPackageSearch::where('id_package_search','=',$searchId)->get();
		}
		$mealPlansArray = explode(";",$mealPlans);
		if($searchId == 0){
			$additionalGETsBefore = "?page=1"
								   .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "");
			$additionalGETsAfter = ($transportType != "" ? "&transportType=".$transportType : "")
								  .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 	  .($locationId != 0 ? "&locationId=".$locationId : "")
							 	  .($categoryId != 0 ? "&categoryId=".$categoryId : "")
									.($stars != 0 ? "&stars=".$stars : "")
							      .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId
								   ."&page=1"
							       .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "");
			$additionalGETsAfter = ($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}
		foreach($mealPlansDB as $mealPlanDB){
			$mealPlansArrayTemp = $mealPlansArray;
			$mealPlansGET = "";
			if(in_array($mealPlanDB->id,$mealPlansArray)){
				if(($key = array_search($mealPlanDB->id, $mealPlansArrayTemp)) !== false) {
				    unset($mealPlansArrayTemp[$key]);
				}
				foreach($mealPlansArrayTemp as $mealPlanA){
					$mealPlansGET .= $mealPlansGET == "" ? $mealPlanA : ";".$mealPlanA;
				}
				$mealPlanDB->selected = true;
			} else {
				$mealPlansGET = $mealPlans == 0 ? $mealPlanDB->id : $mealPlans.";".$mealPlanDB->id;
				$mealPlanDB->selected = false;
			}
			$mealPlansGET = $mealPlansGET == "" ? "" : "&mealPlans=".$mealPlansGET;
			$mealPlanDB->url = URL::route('package_listing').$additionalGETsBefore.$mealPlansGET.$additionalGETsAfter;
		}

		$data['mealPlans'] = $mealPlansDB;

		// Prices URLs

		if($searchId == 0){
			$data['priceUrl'] = URL::route('package_listing')."?page=1"
															 .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
															 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
															 .($transportType != "" ? "&transportType=".$transportType : "")
															 .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 								 .($locationId != 0 ? "&locationId=".$locationId : "")
															 .($stars != 0 ? "&stars=".$stars : "")
							 								 .($categoryId != 0 ? "&categoryId=".$categoryId : "")
								  							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "");
		} else {
			$data['priceUrl'] = URL::route('package_listing')."?searchId=".$searchId
								   							 ."&page=1"
															 .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
															 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
															 .($stars != 0 ? "&stars=".$stars : "")
								  							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "");
		}

		// Sort By and Sort Order URLs

		if($searchId == 0){
			$additionalGETsBefore = "?page=1"
									.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
									.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
									.($transportType != "" ? "&transportType=".$transportType : "")
									.($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 		.($locationId != 0 ? "&locationId=".$locationId : "")
									.($stars != 0 ? "&stars=".$stars : "")
							 		.($categoryId != 0 ? "&categoryId=".$categoryId : "");
			$additionalGETsAfter = ($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId
									."&page=1"
									.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
									.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
									.($stars != 0 ? "&stars=".$stars : "");
			$additionalGETsAfter = ($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}

		$tmpSortBy = $sortBy == "" ? "price" : $sortBy;
		$tmpSortOrder = $sortOrder == "" ? "ASC" : $sortOrder;
		$sortArray["date"] = array("url" => URL::route('package_listing').$additionalGETsBefore."&sortBy=date&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray["name"] = array("url" => URL::route('package_listing').$additionalGETsBefore."&sortBy=name&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray["price"] = array("url" => URL::route('package_listing').$additionalGETsBefore."&sortBy=price&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray[$sortBy]["additionalSelected"] = " active";
		$sortArray[$sortBy]["additionalOrder"] = " ba-".$sortOrder;
		if($tmpSortOrder == "ASC"){
			$sortArray[$tmpSortBy]["url"] = URL::route('package_listing').$additionalGETsBefore."&sortBy=".$tmpSortBy."&sortOrder=DESC".$additionalGETsAfter;
		} else {
			$sortArray[$tmpSortBy]["url"] = URL::route('package_listing').$additionalGETsBefore."&sortBy=".$tmpSortBy."&sortOrder=ASC".$additionalGETsAfter;
		}
		$data['sort'] = $sortArray;

		/*
		 * ================================= END ====================================
		 */

		$data['searchId'] = $searchId;
		$data['noPackages'] = $noPackages;
		$data['packages'] = $packages;

		$data['sorting'] = $data['pages'];
		unset($data['pages']);

		$data['pageTitle'] = 'Sejururi';
		$data['pageNote'] = 'Sejururi';
		$data['pageMetakey'] = 'Helloholiday';
		$data['pageMetadesc'] = 'Sejururi';


		//dd($data);

		$data['pages'] = 'pages.'.CNF_THEME.'.offers.packages';
		$page = 'layouts.'.CNF_THEME.'.index';

		return view($page,$data);
	}

	public function listCircuits(){
		$page = isset($_GET["page"]) ? $_GET["page"] : 1;
		$offerTypes = isset($_GET["offerTypes"]) ? $_GET["offerTypes"] : 0;
		$mealPlans = isset($_GET["mealPlans"]) ? $_GET["mealPlans"] : 0;
		$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : "price";
		$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : "ASC";
		$priceFrom = isset($_GET['priceFrom']) ? $_GET['priceFrom'] : 0;
		$priceTo = isset($_GET['priceTo']) ? $_GET['priceTo'] : 0;
		$locationFiltering = isset($_GET['locationFiltering']) ? $_GET['locationFiltering'] : "base";
		$locationId = isset($_GET['locationId']) ? $_GET['locationId'] : 0;
		$transportType = isset($_GET['transportType']) ? $_GET['transportType'] : "";
		$searchId = isset($_GET['searchId']) ? $_GET['searchId'] : 0;
		$categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : 0;
		$stars = isset($_GET['stars']) ? $_GET['stars'] : 0;
		$data['page'] = $page;
		if($searchId == 0){
			$packagesData = PackageInfo::getListingResultsByFilteringOptions(1,$page,$locationFiltering,$locationId,$offerTypes,$transportType,$mealPlans,$stars,$priceFrom,$priceTo,$categoryId,$sortBy,$sortOrder);
				

			
			foreach($packagesData['packages'] as $k=>$v){
				if($v->soap_client == 'LOCAL'){

					if(\DB::table('cached_prices')->where('id_package','=',$v->idx)->where('gross','=',($v->min_price-$v->tax))->first() == null){
						$v->currency = 0;
					}else{
						$v->currency = \DB::table('cached_prices')->where('id_package','=',$v->idx)->where('gross','=',($v->min_price-$v->tax))->first()->currency;
					}
						
				}else{
					$v->currency = 0;
				}
			}
		} else {
			$packagesData = PackageInfo::getListingResultsByFilteringOptionsForPackageSearch($searchId,$page,$offerTypes,$mealPlans,$stars,$priceFrom,$priceTo,$sortBy,$sortOrder);

			foreach($packagesData['packages'] as $v){
				if($v->soap_client == 'LOCAL'){
					foreach(\DB::table('packages_search_cached_prices')->where('id_package','=',$v->idx)->get() as $p){
						$v->currency = \DB::table('cached_prices')->where('id_package','=',$p->id_package)->where('gross','=',$v->min_price)->first()->currency;
					}
				}else{
					$v->currency = 0;
				}
			}
		}

		if($searchId != 0){
			$searchObj = new \stdClass();
			$packageSearchDB = PackagesSearchCached::find($searchId);
			if($packageSearchDB->is_flight == 1){
				$searchObj->transportType = 1;
			} else if ($packageSearchDB->is_bus == 1){
				$searchObj->transportType = 2;
			} else if ($packageSearchDB->is_flight == 0 && $packageSearchDB->is_bus == 0){
				$searchObj->transportType = 3;
			}
			if($packageSearchDB->is_tour == 0){
				$searchObj->holidayType = 1;
			} else if($packageSearchDB->is_tour == 1){
				$searchObj->holidayType = 2;
			}
			$searchObj->city = $packageSearchDB->destination;
			$city = Geography::find($packageSearchDB->destination);
			$searchObj->country = $city;
			$cityLevel = $city->tree_level - 2;
			for($i = 1;$i <= $cityLevel;$i++){
				$searchObj->country = $searchObj->country->parent;
			}
			$searchObj->country = $searchObj->country->id;
			$searchObj->departureDate = $packageSearchDB->departure_date;
			$searchObj->duration = $packageSearchDB->duration;
			$searchObj->rooms = $packageSearchDB->rooms;
			$searchObj->departure = $packageSearchDB->departure;
			$data['searchObj'] = $searchObj;
			$data['transportTypesSearch'] = PackageInfo::getTransportTypes($searchObj->holidayType);
			$countries = Geography::getLocationsForPackageSearch(PackageInfo::getDestinations($searchObj->holidayType,$searchObj->transportType,$searchObj->departure));
			usort($countries,function($a,$b){
				return strcmp($a->name,$b->name);
			});
			$data['countriesDestinations'] = $countries;
			$cities = array();
			$tmpLocations = array();
			foreach($countries as $location){
				if($location->id == $searchObj->country){
					$tmpLocations = $location->childrens;
				}
			}
			foreach($tmpLocations as $location){
				$cities[] = $location;
				usort($location->childrens,function($a,$b){
					return strcmp($a->name,$b->name);
				});
				foreach($location->childrens as $child){
					$child->name = $child->name.", ".$location->name;
					$cities[] = $child;
				}
			}
			$Rooms = json_decode($packageSearchDB->rooms);
			$jsonRooms = array();
			$jsonRooms['count'] = count($Rooms);
			$jsonRooms['guests'] = array();
			foreach($Rooms as $room){
				$jsonRoom = array();
				$jsonRoom['adults'] = $room->adults;
				if(isset($room->kids)){
					$jsonRoom['kids'] = array();
					foreach($room->kids as $kid){
						$jsonRoom['kids'][] = $kid;
					}
				}
				$jsonRooms['guests'][] = $jsonRoom;
			}
			$data['jsonRooms'] = json_encode($jsonRooms);
			$data['citiesDestinations'] = $cities;
			$data['departurePoints'] = PackageInfo::getDeparturePoints($searchObj->holidayType,$searchObj->transportType);
			$data['departureDates'] = PackageInfo::getDepartureDates($searchObj->holidayType,$searchObj->transportType,$searchObj->city,$searchObj->departure);
			$data['durations'] = PackageInfo::getDurations($searchObj->holidayType,$searchObj->transportType,$searchObj->city,$searchObj->departureDate,$searchObj->departure);
		}
		//dd($packagesData['packages']);
		$packages = $packagesData['packages'];
		$noPackages = $packagesData['noPackages'];

		$data['minPrice'] = intval($packagesData['minPrice']);
		$data['maxPrice'] = intval($packagesData['maxPrice']);
		$data['leftPrice'] = $priceFrom == 0 ? $data['minPrice'] : $priceFrom;
		$data['rightPrice'] = $priceTo == 0 ? $data['maxPrice'] : $priceTo;

		/*
		 * ===================== CREATING URLs FOR FILTERING ========================
		 * ================================ START ===================================
		 */

		// Pages URLs

		$lastPage = $noPackages % 10 != 0 ? intval($noPackages / 10) + 1 : intval($noPackages / 10);

		$pagesArray = array();
		if($searchId == 0){
			$additionalGETsBefore = "";
			$pageGET = "?page=";
			$additionalGETsAfter = ($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
							 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
							 .($transportType != "" ? "&transportType=".$transportType : "")
							 .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 .($locationId != 0 ? "&locationId=".$locationId : "")
							 .($categoryId != 0 ? "&categoryId=".$categoryId : "")
							 .($stars != 0 ? "&stars=".$stars : "")
							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId;
			$pageGET = "&page=";
			$additionalGETsAfter = ($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
								.($stars != 0 ? "&stars=".$stars : "")
							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}
		if($page <= 3){
			$t = $lastPage < 6 ? $lastPage : 6;
			for($i = 1; $i <= $t; $i++){
				$selected = ($page == $i) ? true : false;
				$pagesArray[] = array("text" => $i,
									  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET.$i.$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			if($lastPage >= 7){
				$pagesArray[] = array("text" => "&raquo;",
									  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET.$lastPage.$additionalGETsAfter,
									  "selected" => false
									 );
			}
		} elseif($page >= $lastPage - 2) {
			if($lastPage > 6){
				$pagesArray[] = array("text" => "&laquo;",
									  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET."1".$additionalGETsAfter,
									  "selected" => false
									 );
			}
			$t = $lastPage < 6 ? $lastPage - 1 : 5;
			for($i = $t; $i >= 0; $i--){
				$selected = ($page == $lastPage - $i) ? true : false;
				$pagesArray[] = array("text" => $lastPage - $i,
									  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET.($lastPage - $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}

		} else {
			$pagesArray[] = array("text" => "&laquo;",
								  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET."1".$additionalGETsAfter,
								  "selected" => false
								 );
			for($i = 2; $i > 0; $i--){
				$selected = false;
				$pagesArray[] = array("text" => $page - $i,
									  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET.($page - $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			$pagesArray[] = array("text" => $page,
								  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET.$page.$additionalGETsAfter,
								  "selected" => true
								 );
			for($i = 1; $i <= 2; $i++){
				$selected = false;
				$pagesArray[] = array("text" => $page + $i,
									  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET.($page + $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			$pagesArray[] = array("text" => "&raquo;",
								  "url" => URL::route('circuits_listing').$additionalGETsBefore.$pageGET.$lastPage.$additionalGETsAfter,
								  "selected" => false
								 );
		}
		$data['pages'] = $pagesArray;

		// Location URLs

		if($searchId == 0){
			$additionalGETsBefore = "?page=1"
									.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
									.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
									.($transportType != "" ? "&transportType=".$transportType : "");
			$additionalGETsAfter = ($stars != 0 ? "&stars=".$stars : "")
										.($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	   .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
								   .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	   .($priceTo != 0 ? "&priceTo=".$priceTo: "")
							 	   .($categoryId != 0 ? "&categoryId=".$categoryId : "");

			if($locationFiltering == "base"){
				$data['locations']['tree_level'] =  2;
				$data['locations']['typename'] = "Filtrare dupa locatie";
				$locations = Geography::where('tree_level','=',$data['locations']['tree_level'])->where('available_in_circuits','=',1)->orderBy('name','ASC')->get();
			} else if($locationFiltering == "child") {
				$location = Geography::where('id','=',$locationId)->where('available_in_circuits','=',1)->first();
				$locations = Geography::where('id_parent','=',$locationId)->where('available_in_circuits','=',1)->orderBy('name','ASC')->get();
				$noChildrens = count($locations);
				$locations = $noChildrens == 0 ? Geography::where('id_parent','=',$location->id_parent)->where('available_in_circuits','=',1)->orderBy('name','ASC')->get() : $locations;
				$data['locations']['tree_level'] =  $location->tree_level;
				$data['locations']['typename'] = "" ;
				$locationTemp = $location;
				$data['locations']['country'] = $noChildrens == 0 ? $location->parent->name : $location->name;
				$data['locations']['countrySelected'] = $noChildrens == 0 ? false : true;
				$data['locations']['countryUrl'] = $noChildrens == 0 ? URL::route('circuits_listing').$additionalGETsBefore."&locationFiltering=child"."&locationId=".$location->parent->id.$additionalGETsAfter : URL::route('circuits_listing').$additionalGETsBefore."&locationFiltering=child"."&locationId=".$location->id.$additionalGETsAfter;
				$data['locations']['backToCountrySelectionUrl'] =  URL::route('circuits_listing').$additionalGETsBefore.$additionalGETsAfter;

			}
			foreach($locations as $location){
				if($locationFiltering == "base"){
					$locationFilteringGET = "&locationFiltering=child";
					$locationFilteringID = $location->id;
				} else if($locationFiltering == "child") {
					$locationFilteringGET = "&locationFiltering=child";
					$locationFilteringID = $location->id;
				}
				$location->url = URL::route('circuits_listing').$additionalGETsBefore.$locationFilteringGET."&locationId=".$locationFilteringID.$additionalGETsAfter;
				if($locationFiltering == "child"){
					if($location->id == $locationId){
						$location->selected = true;
					} else {
						$location->selected = false;
					}
				}
			}

			$data['locations']['data'] = $locations;
		}

		// Fare Types URLs

		$offerTypesDB = FareType::all();
		$offerTypesArray = explode(";",$offerTypes);
		if($searchId == 0){
			$additionalGETsBefore = "?page=1";
			$additionalGETsAfter = ($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
								  .($transportType != "" ? "&transportType=".$transportType : "")
								  .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 	  .($locationId != 0 ? "&locationId=".$locationId : "")
									.($stars != 0 ? "&stars=".$stars : "")
							 	  .($categoryId != 0 ? "&categoryId=".$categoryId : "")
								  .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId
								   ."&page=1";
			$additionalGETsAfter = ($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
									.($stars != 0 ? "&stars=".$stars : "")
								  .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}
		foreach($offerTypesDB as $offerTypeDB){
			$offerTypesArrayTemp = $offerTypesArray;
			$offerTypesGET = "";
			if(in_array($offerTypeDB->id,$offerTypesArray)){
				if(($key = array_search($offerTypeDB->id, $offerTypesArrayTemp)) !== false) {
				    unset($offerTypesArrayTemp[$key]);
				}
				foreach($offerTypesArrayTemp as $offerTypeA){
					$offerTypesGET .= $offerTypesGET == "" ? $offerTypeA : ";".$offerTypeA;
				}
				$offerTypeDB->selected = true;
			} else {
				$offerTypesGET = $offerTypes == 0 ? $offerTypeDB->id : $offerTypes.";".$offerTypeDB->id;
				$offerTypeDB->selected = false;
			}
			$offerTypesGET = $offerTypesGET == "" ? "" : "&offerTypes=".$offerTypesGET;
			$offerTypeDB->url = URL::route('circuits_listing').$additionalGETsBefore.$offerTypesGET.$additionalGETsAfter;
		}

		$data['fareTypes'] = $offerTypesDB;

		// Transport Type URLs

		$transportTypeArray = explode(";",$transportType);
		$transportTypes = array();
		$additionalGETsBefore = "?page=1"
								.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
								.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "");
		$additionalGETsAfter = ($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
						       .($locationId != 0 ? "&locationId=".$locationId : "")
									 .($stars != 0 ? "&stars=".$stars : "")
							   .($categoryId != 0 ? "&categoryId=".$categoryId : "")
						 	   .($sortBy != "" ? "&sortBy=".$sortBy: "")
						 	   .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							   .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
						 	   .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		for($i = 1 ; $i <=3 ; $i++){
			$transportTypeArrayTemp = $transportTypeArray;
			$transportTypeGET = "";
			if(in_array($i,$transportTypeArrayTemp)){
				if(($key = array_search($i, $transportTypeArrayTemp)) !== false) {
				    unset($transportTypeArrayTemp[$key]);
				}
				foreach($transportTypeArrayTemp as $transportTypeA){
					$transportTypeGET .= $transportTypeGET == "" ? $transportTypeA : ";".$transportTypeA;
				}
				$transportTypes[$i]["selected"] = true;
			} else {
				$transportTypeGET = $transportType == "" ? $i : $transportType.";".$i;
				$transportTypes[$i]["selected"] = false;
			}
			$transportTypeGET = $transportTypeGET == "" ? "" : "&transportType=".$transportTypeGET;
			$transportTypes[$i]["url"] = URL::route('circuits_listing').$additionalGETsBefore.$transportTypeGET.$additionalGETsAfter;
		}

		$data['transportTypes'] = $transportTypes;

		// Stars URLs

		$starsArray = explode(";",$stars);
		$starsOutputArray = array();
		if($searchId == 0){
			$additionalGETsBefore = "?page=1"
								.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
								.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
								.($transportType != "" ? "&transportType=".$transportType : "")
								.($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
						        .($locationId != 0 ? "&locationId=".$locationId : "")
								.($stars != 0 ? "&stars=".$stars : "");
			$additionalGETsAfter =  ($sortBy != "" ? "&sortBy=".$sortBy: "")
						 	   	   .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							       .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
						 	       .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId
								   ."&page=1"
								   .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
								   .($stars != 0 ? "&stars=".$stars : "");
			$additionalGETsAfter = ($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}

		$starsGET = "";
		for($i = 1;$i <= 5;$i++){
			$starsArrayTemp = $starsArray;
			$starsGET = "";
			if(in_array($i, $starsArray)){
				if(($key = array_search($i, $starsArrayTemp)) !== false) {
				    unset($starsArrayTemp[$key]);
				}
				foreach($starsArrayTemp as $starA){
					$starsGET .= $starsGET == "" ? $starA : ";".$starA;
				}
				$starsOutputArray[$i]["selected"] = true;

			} else {
				$starsGET = $stars == "" ? $i : $stars.";".$i;
				$starsOutputArray[$i]["selected"] = false;
			}
			$starsGET = $starsGET == "" ? "" : "&stars=".$starsGET;
			$starsOutputArray[$i]["url"] = URL::route('circuits_listing').$additionalGETsBefore.$starsGET.$additionalGETsAfter;
		}
		$data['stars'] = $starsOutputArray;




		// Meal Plans URLs
		if($searchId == 0){
			$mealPlansDB = MealPlan::all();
		} else {
			$mealPlansDB = MealPlanPackageSearch::where('id_package_search','=',$searchId)->get();
		}
		$mealPlansArray = explode(";",$mealPlans);
		if($searchId == 0){
			$additionalGETsBefore = "?page=1"
								   .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "");
			$additionalGETsAfter = ($transportType != "" ? "&transportType=".$transportType : "")
								  .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 	  .($locationId != 0 ? "&locationId=".$locationId : "")
							 	  .($categoryId != 0 ? "&categoryId=".$categoryId : "")
									.($stars != 0 ? "&stars=".$stars : "")
							      .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId
								   ."&page=1"
							       .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "");
			$additionalGETsAfter = ($stars != 0 ? "&stars=".$stars : "")
									.($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
							 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}
		foreach($mealPlansDB as $mealPlanDB){
			$mealPlansArrayTemp = $mealPlansArray;
			$mealPlansGET = "";
			if(in_array($mealPlanDB->id,$mealPlansArray)){
				if(($key = array_search($mealPlanDB->id, $mealPlansArrayTemp)) !== false) {
				    unset($mealPlansArrayTemp[$key]);
				}
				foreach($mealPlansArrayTemp as $mealPlanA){
					$mealPlansGET .= $mealPlansGET == "" ? $mealPlanA : ";".$mealPlanA;
				}
				$mealPlanDB->selected = true;
			} else {
				$mealPlansGET = $mealPlans == 0 ? $mealPlanDB->id : $mealPlans.";".$mealPlanDB->id;
				$mealPlanDB->selected = false;
			}
			$mealPlansGET = $mealPlansGET == "" ? "" : "&mealPlans=".$mealPlansGET;
			$mealPlanDB->url = URL::route('circuits_listing').$additionalGETsBefore.$mealPlansGET.$additionalGETsAfter;
		}

		$data['mealPlans'] = $mealPlansDB;

		// Prices URLs

		if($searchId == 0){
			$data['priceUrl'] = URL::route('circuits_listing')."?page=1"
															 .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
															 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
															 .($transportType != "" ? "&transportType=".$transportType : "")
															 .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 								 .($locationId != 0 ? "&locationId=".$locationId : "")
															 .($stars != 0 ? "&stars=".$stars : "")
							 								 .($categoryId != 0 ? "&categoryId=".$categoryId : "")
								  							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "");
		} else {
			$data['priceUrl'] = URL::route('circuits_listing')."?searchId=".$searchId
								   							 ."&page=1"
															 .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
															 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
															 .($stars != 0 ? "&stars=".$stars : "")
								  							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "");
		}

		// Sort By and Sort Order URLs

		if($searchId == 0){
			$additionalGETsBefore = "?page=1"
									.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
									.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
									.($transportType != "" ? "&transportType=".$transportType : "")
									.($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 		.($locationId != 0 ? "&locationId=".$locationId : "")
							 		.($categoryId != 0 ? "&categoryId=".$categoryId : "")
									.($stars != 0 ? "&stars=".$stars : "");
			$additionalGETsAfter = ($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		} else {
			$additionalGETsBefore = "?searchId=".$searchId
									."&page=1"
									.($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
									.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
									.($stars != 0 ? "&stars=".$stars : "");
			$additionalGETsAfter = ($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
							 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");
		}

		$tmpSortBy = $sortBy == "" ? "price" : $sortBy;
		$tmpSortOrder = $sortOrder == "" ? "ASC" : $sortOrder;
		$sortArray["date"] = array("url" => URL::route('circuits_listing').$additionalGETsBefore."&sortBy=date&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray["name"] = array("url" => URL::route('circuits_listing').$additionalGETsBefore."&sortBy=name&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray["price"] = array("url" => URL::route('circuits_listing').$additionalGETsBefore."&sortBy=price&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray[$sortBy]["additionalSelected"] = " active";
		$sortArray[$sortBy]["additionalOrder"] = " ba-".$sortOrder;
		if($tmpSortOrder == "ASC"){
			$sortArray[$tmpSortBy]["url"] = URL::route('circuits_listing').$additionalGETsBefore."&sortBy=".$tmpSortBy."&sortOrder=DESC".$additionalGETsAfter;
		} else {
			$sortArray[$tmpSortBy]["url"] = URL::route('circuits_listing').$additionalGETsBefore."&sortBy=".$tmpSortBy."&sortOrder=ASC".$additionalGETsAfter;
		}
		$data['sort'] = $sortArray;

		/*
		 * ================================= END ====================================
		 */

		$data['searchId'] = $searchId;
		$data['noPackages'] = $noPackages;
		$data['packages'] = $packages;

		$data['sorting'] = $data['pages'];
		unset($data['pages']);

		$data['pageTitle'] = 'Circuite';
		$data['pageNote'] = 'Circuite';
		$data['pageMetakey'] = 'Circuite';
		$data['pageMetadesc'] = 'Circuite';

		$data['pages'] = 'pages.'.CNF_THEME.'.offers.circuits';
		$page = 'layouts.'.CNF_THEME.'.index';

		return view($page,$data);
	}

	public function listHotels(){
		$page = isset($_GET["page"]) ? $_GET["page"] : 1;
		$offerTypes = isset($_GET["offerTypes"]) ? $_GET["offerTypes"] : 0;
		$mealPlans = isset($_GET["mealPlans"]) ? $_GET["mealPlans"] : 0;
		$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : "price";
		$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : "ASC";
		$priceFrom = isset($_GET['priceFrom']) ? $_GET['priceFrom'] : 0;
		$priceTo = isset($_GET['priceTo']) ? $_GET['priceTo'] : 0;
		$searchId = isset($_GET['searchId']) ? $_GET['searchId'] : 0;
		$stars = isset($_GET['stars']) ? $_GET['stars'] : 0;
		$data['page'] = $page;
		$hotelsData = Hotel::getListingResultsByFilteringOptionsForHotelSearch($searchId,$page,$mealPlans,$priceFrom,$priceTo,$sortBy,$sortOrder,$stars);

		$searchObj = new \stdClass();
		//$searchObj = app('stdClass');
		$hotelSearchDB = HotelSearchCached::find($searchId);
		//dd(Geography::getLocationsForPackageSearch(Hotel::getDestinationsForHotelSearch()));

		$searchObj->city = $hotelSearchDB->destination;
		$city = Geography::find($hotelSearchDB->destination);
		$searchObj->country = $city;
		$cityLevel = $city->tree_level - 2;
		for($i = 1;$i <= $cityLevel;$i++){
			$searchObj->country = $searchObj->country->parent;
		}
		$searchObj->country = $searchObj->country->id;
		$checkInArray = explode('-',$hotelSearchDB->check_in);
		$searchObj->checkIn = $checkInArray[2]."/".$checkInArray[1]."/".$checkInArray[0];
		$time = strtotime($checkInArray[1]."/".$checkInArray[2]."/".$checkInArray[0]." +".$hotelSearchDB->stay."days");
		$searchObj->checkOut = date('d/m/Y',$time);
		$searchObj->stay = $hotelSearchDB->stay;
		$searchObj->rooms = $hotelSearchDB->rooms;
		$data['searchObj'] = $searchObj;
		$countries = Geography::getLocationsForPackageSearch(Hotel::getDestinationsForHotelSearch());
		usort($countries,function($a,$b){
			return strcmp($a->name,$b->name);
		});
		$data['countriesDestinations'] = $countries;
		$cities = array();
		$tmpLocations = array();
		foreach($countries as $location){
			if($location->id == $searchObj->country){
				$tmpLocations = $location->childrens;
			}
		}
		foreach($tmpLocations as $location){
			$cities[] = $location;
			usort($location->childrens,function($a,$b){
				return strcmp($a->name,$b->name);
			});
			foreach($location->childrens as $child){
				$child->name = $child->name.", ".$location->name;
				$cities[] = $child;
			}
		}
		$data['citiesDestinations'] = $cities;

		$hotels = $hotelsData['hotels'];
		$noHotels = $hotelsData['noHotels'];

		$data['minPrice'] = intval($hotelsData['minPrice']);
		$data['maxPrice'] = intval($hotelsData['maxPrice']);
		$data['leftPrice'] = $priceFrom == 0 ? $data['minPrice'] : $priceFrom;
		$data['rightPrice'] = $priceTo == 0 ? $data['maxPrice'] : $priceTo;

		/*
		 * ===================== CREATING URLs FOR FILTERING ========================
		 * ================================ START ===================================
		 */

		// Pages URLs

		$lastPage = $noHotels % 10 != 0 ? intval($noHotels / 10) + 1 : intval($noHotels / 10);

		$pagesArray = array();

		$additionalGETsBefore = "?searchId=".$searchId;
		$pageGET = "&page=";
		$additionalGETsAfter = ($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
							.($stars != 0 ? "&stars=".$stars : "")
						 .($sortBy != "" ? "&sortBy=".$sortBy: "")
						 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
						 .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
						 .($priceTo != 0 ? "&priceTo=".$priceTo: "");

		if($page <= 3){
			$t = $lastPage < 6 ? $lastPage : 6;
			for($i = 1; $i <= $t; $i++){
				$selected = ($page == $i) ? true : false;
				$pagesArray[] = array("text" => $i,
									  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET.$i.$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			if($lastPage >= 7){
				$pagesArray[] = array("text" => "&raquo;",
									  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET.$lastPage.$additionalGETsAfter,
									  "selected" => false
									 );
			}
		} elseif($page >= $lastPage - 2) {
			if($lastPage > 6){
				$pagesArray[] = array("text" => "&laquo;",
									  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET."1".$additionalGETsAfter,
									  "selected" => false
									 );
			}
			$t = $lastPage < 6 ? $lastPage - 1 : 5;
			for($i = $t; $i >= 0; $i--){
				$selected = ($page == $lastPage - $i) ? true : false;
				$pagesArray[] = array("text" => $lastPage - $i,
									  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET.($lastPage - $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}

		} else {
			$pagesArray[] = array("text" => "&laquo;",
								  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET."1".$additionalGETsAfter,
								  "selected" => false
								 );
			for($i = 2; $i > 0; $i--){
				$selected = false;
				$pagesArray[] = array("text" => $page - $i,
									  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET.($page - $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			$pagesArray[] = array("text" => $page,
								  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET.$page.$additionalGETsAfter,
								  "selected" => true
								 );
			for($i = 1; $i <= 2; $i++){
				$selected = false;
				$pagesArray[] = array("text" => $page + $i,
									  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET.($page + $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			$pagesArray[] = array("text" => "&raquo;",
								  "url" => URL::route('hotels_listing').$additionalGETsBefore.$pageGET.$lastPage.$additionalGETsAfter,
								  "selected" => false
								 );
		}
		$data['pagesArray'] = $pagesArray;
		// Meal Plans URLs
		$mealPlansDB = MealPlanHotelSearch::where('id_hotel_search','=',$searchId)->get();
		$mealPlansArray = explode(";",$mealPlans);

		$additionalGETsBefore = "?searchId=".$searchId
							   ."&page=1";
		$additionalGETsAfter = ($stars != 0 ? "&stars=".$stars : "")
								.($sortBy != "" ? "&sortBy=".$sortBy: "")
						 	  .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
						 	  .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
						 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");

		foreach($mealPlansDB as $mealPlanDB){
			$mealPlansArrayTemp = $mealPlansArray;
			$mealPlansGET = "";
			if(in_array($mealPlanDB->id,$mealPlansArray)){
				if(($key = array_search($mealPlanDB->id, $mealPlansArrayTemp)) !== false) {
				    unset($mealPlansArrayTemp[$key]);
				}
				foreach($mealPlansArrayTemp as $mealPlanA){
					$mealPlansGET .= $mealPlansGET == "" ? $mealPlanA : ";".$mealPlanA;
				}
				$mealPlanDB->selected = true;
			} else {
				$mealPlansGET = $mealPlans == 0 ? $mealPlanDB->id : $mealPlans.";".$mealPlanDB->id;
				$mealPlanDB->selected = false;
			}
			$mealPlansGET = $mealPlansGET == "" ? "" : "&mealPlans=".$mealPlansGET;
			$mealPlanDB->url = URL::route('hotels_listing').$additionalGETsBefore.$mealPlansGET.$additionalGETsAfter;
		}

		$data['mealPlans'] = $mealPlansDB;

		// Stars URLs

			$starsArray = explode(";",$stars);
			$starsOutputArray = array();
			$additionalGETsBefore = "?searchId=".$searchId
										 ."&page=1"
									 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "");
			$additionalGETsAfter =  ($sortBy != "" ? "&sortBy=".$sortBy: "")
										 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "")
										 .($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
										 .($priceTo != 0 ? "&priceTo=".$priceTo: "");
			$starsGET = "";
			for($i = 1;$i <= 5;$i++){
				$starsArrayTemp = $starsArray;
				$starsGET = "";
				if(in_array($i, $starsArray)){
					if(($key = array_search($i, $starsArrayTemp)) !== false) {
							unset($starsArrayTemp[$key]);
					}
					foreach($starsArrayTemp as $starA){
						$starsGET .= $starsGET == "" ? $starA : ";".$starA;
					}
					$starsOutputArray[$i]["selected"] = true;

				} else {
					$starsGET = $stars == "" ? $i : $stars.";".$i;
					$starsOutputArray[$i]["selected"] = false;
				}
				$starsGET = $starsGET == "" ? "" : "&stars=".$starsGET;
				$starsOutputArray[$i]["url"] = URL::route('hotels_listing').$additionalGETsBefore.$starsGET.$additionalGETsAfter;
			}
			$data['stars'] = $starsOutputArray;


		// Prices URLs
		$data['priceUrl'] = URL::route('hotels_listing')."?searchId=".$searchId
							   							 ."&page=1"
														 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
														 .($stars !=0?"&stars=".$stars:'')
							  							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
						 	  							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "");


		// Sort By and Sort Order URLs

		$additionalGETsBefore = "?searchId=".$searchId
								."&page=1"
								.($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
								.($stars !=0?"&stars=".$stars:'');
		$additionalGETsAfter = ($priceFrom != 0 ? "&priceFrom=".$priceFrom: "")
						 	  .($priceTo != 0 ? "&priceTo=".$priceTo: "");

		$tmpSortBy = $sortBy == "" ? "price" : $sortBy;
		$tmpSortOrder = $sortOrder == "" ? "ASC" : $sortOrder;
		$sortArray["date"] = array("url" => URL::route('hotels_listing').$additionalGETsBefore."&sortBy=date&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray["name"] = array("url" => URL::route('hotels_listing').$additionalGETsBefore."&sortBy=name&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray["price"] = array("url" => URL::route('hotels_listing').$additionalGETsBefore."&sortBy=price&sortOrder=ASC".$additionalGETsAfter,
								   "selected" => false,
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray[$sortBy]["additionalSelected"] = " active";
		$sortArray[$sortBy]["additionalOrder"] = " ba-".$sortOrder;
		if($tmpSortOrder == "ASC"){
			$sortArray[$tmpSortBy]["url"] = URL::route('hotels_listing').$additionalGETsBefore."&sortBy=".$tmpSortBy."&sortOrder=DESC".$additionalGETsAfter;
		} else {
			$sortArray[$tmpSortBy]["url"] = URL::route('hotels_listing').$additionalGETsBefore."&sortBy=".$tmpSortBy."&sortOrder=ASC".$additionalGETsAfter;
		}
		$data['sort'] = $sortArray;

		/*
		 * ================================= END ====================================
		 */

		//dd($data);
		$data['searchId'] = $searchId;
		$data['noHotels'] = $noHotels;
		$data['hotels'] = $hotels;

		$data['sorting'] = $data['pagesArray'];
		unset($data['pages']);

		$data['pageTitle'] = 'Hoteluri';
		$data['pageNote'] = 'Hoteluri';
		$data['pageMetakey'] = 'Hoteluri';
		$data['pageMetadesc'] = 'Hoteluri';

		$data['pages'] = 'pages.'.CNF_THEME.'.offers.hotels';
		$page = 'layouts.'.CNF_THEME.'.index';

		return view($page,$data);



		return View::make('offers.hotels',$data);
	}

	public function view($offertype,$hotelCountry,$hotelName,$transportCode,$hotelId,$soapClientId,$searchId){

		if($soapClientId == "LOCAL"){
			$SOAPClient = null;
		} else {
			$SOAPClient = new ET_SoapClient($soapClientId);
		}
		$data['soapClientId'] = $soapClientId;
		$dbHotel = Hotel::where('id','=',$hotelId)->where('soap_client','=',$soapClientId)->first();
		$data['searchId'] = $searchId;
		$data['transportCode'] = $transportCode;

		switch($transportCode){
			case self::T_HOTEL:
				if($soapClientId == "LOCAL"){
					$data = $this->renderLocalHotel($data,$dbHotel,$soapClientId,$searchId);
				} else {
					$data = $this->renderHotel($data,$dbHotel,$soapClientId,$searchId,$SOAPClient);
				}
				$data['offerType'] = self::O_HOTEL;
				$IsTour = 0;
			break;
			case self::T_PACKAGES_BUS:
			case self::T_PACKAGES_FLIGHT:
			case self::T_PACKAGES_INDIVIDUAL:
				if($soapClientId == "LOCAL"){
					$data = $this->renderLocalPackages($data,$dbHotel,$soapClientId,$searchId);
				} else {
					//dd($data,$dbHotel,$soapClientId,$searchId,$SOAPClient);
					$data = $this->renderPackages($data,$dbHotel,$soapClientId,$searchId,$SOAPClient);
				//	dd($data);
				}
				$data['offerType'] = self::O_PACKAGES;
				$IsTour = 0;
			break;
			case self::T_CIRCUITS_BUS:
			case self::T_CIRCUITS_FLIGHT:
			case self::T_CIRCUITS_INDIVIDUAL:
				if($soapClientId == "LOCAL"){
					$data = $this->renderLocalPackages($data,$dbHotel,$soapClientId,$searchId);
				} else {

					$data = $this->renderPackages($data,$dbHotel,$soapClientId,$searchId,$SOAPClient);
				}
				$data['offerType'] = self::O_PACKAGES;
				$IsTour = 1;
			break;
			default:
				die('Transport code not vaild');
			break;
		}
		//dd($data);
		$data['IsTour'] = $IsTour;
		$data['packages'] = PackageInfo::where('id_hotel','=',$hotelId)->where('soap_client','=',$soapClientId)->get();

		if($soapClientId != "LOCAL"){
			$data['departureDatesIndividual'] = $dbHotel->getDepartureDatesForPackages($IsTour,0,0);
			$data['departureDatesBus'] = $dbHotel->getDepartureDatesForPackages($IsTour,1,0);
			$data['departureDatesPlane'] = $dbHotel->getDepartureDatesForPackages($IsTour,0,1);
			$data['durationsIndividual'] = $dbHotel->getDurationsForPackages($IsTour,0,0);
			$data['durationsBus'] = $dbHotel->getDurationsForPackages($IsTour,1,0);
			$data['durationsPlane'] = $dbHotel->getDurationsForPackages($IsTour,0,1);
			$data['departurePointsIndividual'] = $dbHotel->getDeparturePointsForPackages($IsTour,0,0);
			$data['departurePointsBus'] = $dbHotel->getDeparturePointsForPackages($IsTour,1,0);
			$data['departurePointsPlane'] = $dbHotel->getDeparturePointsForPackages($IsTour,0,1);
		}

		$featuredPackages = PackageInfo::leftJoin('package_categories',function($join){
			$join->on('package_categories.id_package','=','packages.id');
			$join->on('package_categories.soap_client','=','packages.soap_client');
		})->where('package_categories.id_category','=',6)->take(6)->get();

		$data['featuredPackages'] = $featuredPackages;
		//dd($data);
		$data['pageTitle'] = $data['hotel']->name;
		$data['pageNote'] = 'View';
		$data['pageMetakey'] = $data['hotel']->name.' , '.$data['hotel']->address;
		$data['pageMetadesc'] = $data['hotel']->name.' , '.$data['hotel']->description;
		$data['offerType']=$offertype;
		if($soapClientId != "LOCAL"){
			$data['pages'] = 'pages.'.CNF_THEME.'.offers.view';
		} else {
			$data['pages'] = 'pages.'.CNF_THEME.'.offers.view_local';
		}
		$page = 'layouts.'.CNF_THEME.'.index';
		
		return view($page,$data);
	}

	private function renderPackages($data,$dbHotel,$soapClientId,$searchId,$SOAPClient){
		$transport = PackageInfo::getTransportByCode($data['transportCode']);
		$IsTour = $transport['is_tour'];
		$IsFlight = $transport['is_flight'];
		$IsBus = $transport['is_bus'];
		$data['estimatedPrice'] = $dbHotel->estPriceForPackages($IsTour,$IsFlight,$IsBus);
		$pSearchParams = PackageInfo::getInfoForViewAllPackagesByHotel($dbHotel->id,$soapClientId,$IsTour,$IsBus,$IsFlight);
		$Departure = Geography::getLocationIdForSoapClient($soapClientId,$pSearchParams->departure_point);
		$Destination = Geography::getLocationIdForSoapClient($soapClientId,$pSearchParams->destination);
		$Hotel = $dbHotel->id;
		$DepartureDate = $pSearchParams->departure_date;
		$Duration = $pSearchParams->duration;
		$data['duration'] = $Duration;
		$MinStars = null;
		//dinamic nu 2,0
		$Rooms = array(new RoomSoapObject(2,0));
		$ShowBlackedOut = true;
		$Currency = true;
		$roomsOutput = "1 (2 Adulti)";
		$tmpRoom = new \stdClass();
		$tmpRoom->adults = "2";
		$data['rooms'] = json_encode(array($tmpRoom));
		if($searchId != 0){
			$roomsOutput = "";
			$packageSearchCached = PackagesSearchCached::where('id','=',$searchId)->first();
			$IsTour = $packageSearchCached->is_tour;
			$IsFlight = $packageSearchCached->is_flight;
			$IsBus = $packageSearchCached->is_bus;
			$Departure = Geography::getLocationIdForSoapClient($soapClientId,$packageSearchCached->departure);
			$Destination = Geography::getLocationIdForSoapClient($soapClientId,$packageSearchCached->destination);
			$DepartureDate = $packageSearchCached->departure_date;
			$Duration = $packageSearchCached->duration;
			$data['rooms'] = $packageSearchCached->rooms;
			$jsonRooms = array();
			$jsonRooms['count'] = count($Rooms);
			$jsonRooms['guests'] = array();
			foreach($Rooms as $room){
				$jsonRoom = array();
				$jsonRoom['adults'] = $room->Adults;
				if($room->ChildAges != 0){
					$jsonRoom['kids'] = $room->ChildAges;
				}
				$jsonRooms['guests'][] = $jsonRoom;
			}
			$data['jsonRooms'] = json_encode($jsonRooms);
			$data['departure_date'] = $packageSearchCached->departure_date;
			$data['durationSid'] = $Duration;
			$data['duration'] = $Duration;
			$data['departure_point'] = $Departure;
			$tmpRooms = json_decode($packageSearchCached->rooms);
			$Rooms = array();
			$noRooms = count($tmpRooms);
			$i = 1;
			$roomsOutput .= count($tmpRooms)." ( ";
			foreach($tmpRooms as $tmpRoom){
				$roomsOutput .= $tmpRoom->adults." adult".($tmpRoom->adults != 1 ? "i" : "");
				if(isset($tmpRoom->kids)){
					$roomsOutput .= " si ".count($tmpRoom->kids)." copi".(count($tmpRoom->kids) != 1 ? "i" : "l");
					$Rooms[] = new RoomSoapObject($tmpRoom->adults,$tmpRoom->kids);
				} else {
					$Rooms[] = new RoomSoapObject($tmpRoom->adults,0);
				}
				if($i != $noRooms){
					$roomsOutput .= " / ";
				}
				$i++;
			}
			$roomsOutput .= " )";
		}
		$data['departureDate'] = $DepartureDate;
		$data['roomsOutput'] = $roomsOutput;

		$soapPackageResults = $SOAPClient->packageSearch(new PackageSearchSoapObject($IsTour,$IsFlight,$IsBus,$Departure,$Destination,$Hotel,
																			  $DepartureDate,$Duration,$MinStars,$Rooms,false,
																			  $Currency));
		//dd($soapPackageResults[0]->ExtraComponents);
		$prices = array();
		$packagesFound = array();
		$extraComponents=[];

		foreach($soapPackageResults as $packageResult){

			$defaultMealPlan = true;
			$defaultMealPlanPrice = 0;

			if($packageResult->ExtraComponents != null){
				foreach($packageResult->ExtraComponents as $k=>$v){
					$extraComponents[$k]=$v;
				}
			}
			if(!in_array($packageResult->PackageId,$packagesFound)) $packagesFound[] = $packageResult->PackageId;
			foreach($packageResult->HotelInfo->MealPlans as $mealPlan){
				$discounts = array();
				if(!is_null($packageResult->DiscountInfo)){
					$discounts[] = $packageResult->DiscountInfo;
				}
				foreach($packageResult->HotelInfo->Discounts as $disc){
					$discounts[] = $disc;
				}
				if($defaultMealPlan){
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"] = $packageResult->Price->Gross + $packageResult->Price->Tax;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["priceWithoutDiscount"] = $packageResult->Price->Gross + $packageResult->Price->Tax + $packageResult->TotalDiscount;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["discounts"] = $discounts;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["function"] =  "bookPackage(".$packageResult->PackageId.",".$packageResult->HotelInfo->CategoryId.","
																																								  ."'".$mealPlan->Label."',"
																																								  .($packageResult->Price->Gross + $packageResult->Price->Tax).",event);";
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $packageResult->IsAvailable ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isBookable"] = $packageResult->IsBookable ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = true;//$soapClientId == "HO" ? true : false;old version just for helloholliday operator
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["earlyBooking"] = in_array('early_booking',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["specialOffer"] = in_array('special_offer',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["lastMinute"] = in_array('last_minute',$packageResult->FareType);
					$defaultMealPlanPrice = $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$defaultMealPlan = false;
				} else {
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"] = $packageResult->Price->Gross + $packageResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["priceWithoutDiscount"] = $packageResult->Price->Gross + $packageResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax + $packageResult->TotalDiscount;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["discounts"] = $discounts;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["function"] =  "bookPackage(".$packageResult->PackageId.",".$packageResult->HotelInfo->CategoryId.","
																																								  ."'".$mealPlan->Label."',"
																																								  .($packageResult->Price->Gross + $packageResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax).",event);";
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $packageResult->IsAvailable ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isBookable"] = $packageResult->IsBookable ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = true;//$soapClientId == "HO" ? true : false;old version just for helloholliday operator
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["earlyBooking"] = in_array('early_booking',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["specialOffer"] = in_array('special_offer',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["lastMinute"] = in_array('last_minute',$packageResult->FareType);

				}
			}
		}

		session(['extracomponents'=>$extraComponents]);
		$data['hotel'] = $dbHotel;
		$data['packagesFound'] = $packagesFound;
		$data['prices'] = $prices;
		$data['pSearch'] = array( 'IsTour' => $IsTour,
								  'IsFlight' => $IsFlight,
								  'IsBus' => $IsBus,
								  'Hotel' => $Hotel,
								  'Destination' => $Destination,
								  'DeparturePoint' => $Departure,
								  'SoapClient' => $soapClientId);
		if($IsBus == 1 && $IsFlight == 0){
			$data['selectedSearchType'] = 0;
		} else if($IsBus == 0 && $IsFlight == 1){
			$data['selectedSearchType'] = 1;
		} else {
			$data['selectedSearchType'] = null;
		}
		return $data;
	}

	private function renderLocalPackages($data,$dbHotel,$soapClientId,$searchId){

		$transport = PackageInfo::getTransportByCode($data['transportCode']);
		
		$IsTour = $transport['is_tour'];
		$IsFlight = $transport['is_flight'];
		$IsBus = $transport['is_bus'];
		$data['estimatedPrice'] = $dbHotel->estPriceForPackages($IsTour,$IsFlight,$IsBus);
		$data['hotel'] = $dbHotel;
		return $data;
	}

	private function renderLocalHotel($data,$dbHotel,$soapClientId,$searchId){
		$transport = PackageInfo::getTransportByCode($data['transportCode']);
		//dd($transport,$data['transportCode']);
		$IsTour = $transport['is_tour'];
		$IsFlight = $transport['is_flight'];
		$IsBus = $transport['is_bus'];
		$data['estimatedPrice'] = $dbHotel->estPriceForPackages($IsTour,$IsFlight,$IsBus);
		$data['hotel'] = $dbHotel;
		return $data;
	}


	private function renderHotel($data,$dbHotel,$soapClientId,$searchId,$SOAPClient){
		//dd($data,$dbHotel);
		$roomsOutput = "";
		if($searchId == 0){
			$hotelSearchCached = HotelSearchCached::where('destination','=',$dbHotel->location)->first();
		}else{
			$hotelSearchCached = HotelSearchCached::where('id','=',$searchId)->first();
		}
		if($hotelSearchCached!=null){
		$checkInArray = explode('-',$hotelSearchCached->check_in);
		$data['check_in'] = $checkInArray[2]."/".$checkInArray[1]."/".$checkInArray[0];
		$time = strtotime($checkInArray[1]."/".$checkInArray[2]."/".$checkInArray[0]." +".$hotelSearchCached->stay."days");
		$data['check_out'] = date('d/m/Y',$time);
		$data['rooms'] = $hotelSearchCached->rooms;
		$tmpRooms = json_decode($hotelSearchCached->rooms);
		$Rooms = array();
		$noRooms = count($tmpRooms);
		$i = 1;
		$roomsOutput .= count($tmpRooms)." ( ";
		foreach($tmpRooms as $tmpRoom){
			$roomsOutput .= $tmpRoom->adults." adult".($tmpRoom->adults != 1 ? "i" : "");
			if(isset($tmpRoom->kids)){
				$roomsOutput .= " si ".count($tmpRoom->kids)." copi".(count($tmpRoom->kids) != 1 ? "i" : "l");
				$Rooms[] = new RoomSoapObject($tmpRoom->adults,$tmpRoom->kids);
			} else {
				$Rooms[] = new RoomSoapObject($tmpRoom->adults,0);
			}
			if($i != $noRooms){
				$roomsOutput .= " / ";
			}
			$i++;
		}
		$roomsOutput .= " )";
		$data['roomsOutput'] = $roomsOutput;
		$hotelSearchCached->destination = Geography::getLocationIdForSoapClient($soapClientId,$hotelSearchCached->destination);
		$data['hotelSearchCached'] = $hotelSearchCached;
		$jsonRooms = array();
		$jsonRooms['count'] = count($Rooms);
		$jsonRooms['guests'] = array();
		foreach($Rooms as $room){
			$jsonRoom = array();
			$jsonRoom['adults'] = $room->Adults;
			if($room->ChildAges != 0){
				$jsonRoom['kids'] = $room->ChildAges;
			}
			$jsonRooms['guests'][] = $jsonRoom;
		}
		$data['jsonRooms'] = json_encode($jsonRooms,JSON_PRETTY_PRINT);

		$hotelSearch = new HotelSearchSoapObject($hotelSearchCached->destination,$dbHotel->id,$hotelSearchCached->check_in,$hotelSearchCached->stay,1,
												 $Rooms,null,false,false,true);
		}else{
			//dd($dbHotel);
			$hotelSearch = new HotelSearchSoapObject($dbHotel->location,$dbHotel->id,null,null,1,
												 null,null,false,false,true);	
		}
		$soapHotelResults = $SOAPClient->hotelSearch($hotelSearch);
	//	dd($soapHotelResults);
		$prices = array();
		foreach($soapHotelResults as $hotelResult){
			$defaultMealPlan = true;
			$defaultMealPlanPrice = 0;
			foreach($hotelResult->MealPlans as $mealPlan){
				if($defaultMealPlan){
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"] = $hotelResult->Price->Gross + $hotelResult->Price->Tax;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["function"] =  "bookHotel(".$hotelResult->HotelId.",".$hotelResult->CategoryId.","."'".$mealPlan->Label."',".($hotelResult->Price->Gross + $hotelResult->Price->Tax).",event);";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = true;//$soapClientId == "HO" ? true : false;old version just for helloholliday operator
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $hotelResult->IsAvailable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isBookable"] = $hotelResult->IsBookable ? true : false;
					$defaultMealPlanPrice = $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$defaultMealPlan = false;
				} else {
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"] = $hotelResult->Price->Gross + $hotelResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["function"] =  "bookHotel(".$hotelResult->HotelId.",".$hotelResult->CategoryId.","."'".$mealPlan->Label."',".($hotelResult->Price->Gross + $hotelResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax).",event);";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = true;//$soapClientId == "HO" ? true : false;old version just for helloholliday operator
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $hotelResult->IsAvailable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isBookable"] = $hotelResult->IsBookable ? true : false;

				}
			}
		}
		$data['hotel'] = $dbHotel;
		$data['prices'] = $prices;
		$data['estimatedPrice'] = 0;
		return $data;
	}

}
