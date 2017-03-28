<?php
namespace App\Http\Controllers\Travel;

use App\Http\Controllers\controller;

use App\Models\Travel\Hotel;
use App\Models\Travel\HotelEloquent;
use App\Models\Travel\HotelSearchCached;
use App\Models\Travel\Geography;
use App\Models\Travel\MealPlanHotelSearch;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\PriceSet;
use App\Models\Travel\CachedPrice;
use App\Models\Travel\PackagesSearchCached;
use App\Models\Travel\HotelSearchCachedPrice;
use App\Models\Travel\PackageSearchCachedPrice;
use App\Models\Travel\AskForOffersItem;
use App\Models\Travel\BookingPackageSearch;
use App\Models\Travel\BookingHotelSearch;

use App\Models\Travel\FareType;

use App\Models\Travel\MealPlan;
use App\Models\Travel\MealPlanPackageSearch;


use App;
use DB;
use URL;
use View;
use Config;
use ET_SoapClient\SoapObjects\RoomSoapObject;
use ET_SoapClient\SoapObjects\PackageSearchSoapObject;
use ET_SoapClient\SoapObjects\RoomCategorySoapObject;
use ET_SoapClient\SoapObjects\HotelSearchSoapObject;
use App\Models\Travel\RoomCategory;
use stdClass;
use ET_SoapClient\ET_SoapClient;
use Illuminate\Http\Request;

class SearchAjaxController extends Controller {

	public function getTransportTypes(){
		$callback = isset($_GET['callback']) ? $_GET['callback'] : die;
		$holidayType = isset($_GET['holidayType']) ? $_GET['holidayType'] : die;
		$results = PackageInfo::getTransportTypes($holidayType);
		$transportTypes = array();
		foreach($results as $result){
			$transportTypes[] = array("id" => $result->id,"name" => $result->name);
		}
		echo $callback."(".json_encode($transportTypes).")";
	}

	private function findDeparturePoints2($locations){
		$departurePointsTmp = array();
		foreach($locations as $location){
			if(empty($location->childrens)){
				$departurePointsTmp[] = array("id" => $location->id, "name" => $location->name);
			} else {
				$newDeparturePoints = $this->findDeparturePoints($location->childrens);
				foreach($newDeparturePoints as $newDeparturePoint){
					$departurePointsTmp[] = $newDeparturePoint;
				}
			}
		}
		$departurePointsTmp = array_map("unserialize", array_unique(array_map("serialize", $departurePointsTmp)));
		return $departurePointsTmp;
	}

	private function findDeparturePoints($locations){
		$departurePointsTmp = array();
		foreach($locations as $location){
			if(empty($location->childrens)){
				$departurePointsTmp[] = array("id" => $location->id, "name" => $location->name);
			} else {
				$newDeparturePoints = $this->findDeparturePoints($location->childrens);
				foreach($newDeparturePoints as $newDeparturePoint){
					$departurePointsTmp[] = $newDeparturePoint;
				}
			}
		}
		return $departurePointsTmp;
	}

	public function getDeparturePoints(){
		$callback = isset($_GET['callback']) ? $_GET['callback'] : die;
		$holidayType = isset($_GET['holidayType']) ? $_GET['holidayType'] : die;
		$transportType = isset($_GET['transportType']) ? $_GET['transportType'] : die;
		//dd(Geography::getLocationsForPackageSearch(PackageInfo::getDeparturePoints($holidayType,$transportType)));
		if($transportType == 3){
			$locations = Geography::getCountryForPackageSearch(PackageInfo::getDestinationsFromTranportType($holidayType,$transportType));

					$departurePoints = $this->findDeparturePoints2($locations);
					usort($departurePoints,function($a,$b){
						return strcmp($a["name"],$b["name"]);
					});
					//dd($departurePoints);
					echo $callback."(".json_encode($departurePoints).")";
		}else{
			$locations = Geography::getLocationsForPackageSearch(PackageInfo::getDeparturePoints($holidayType,$transportType));
			//dd($locations);
					$departurePoints = $this->findDeparturePoints($locations);
					usort($departurePoints,function($a,$b){
						return strcmp($a["name"],$b["name"]);
					});

					echo $callback."(".json_encode($departurePoints).")";
		}

	}



	public function getCountryDestination(Request $req){

		$callback = isset($_GET['callback']) ? $_GET['callback'] : die;
		$holidayType = isset($_GET['holidayType']) ? $_GET['holidayType'] : die;
		if($holidayType != 3){
			$transportType = isset($_GET['transportType']) ? $_GET['transportType'] : die;
			$departurePoint = isset($_GET['departurePoint']) ? $_GET['departurePoint'] : die;

			$locations = Geography::getLocationsForPackageSearch(PackageInfo::getDestinations($holidayType,$transportType,$departurePoint));
		} else {
			$locations = Geography::getLocationsForPackageSearch(Hotel::getDestinations());
		}
		usort($locations,function($a,$b){
			return strcmp($a->name,$b->name);
		});
		$countries = array();
		foreach($locations as $location){
			$countries[] = array("id" => $location->id,"name" => $location->name);
		}
		//dd($countries);
		echo $callback."(".json_encode($countries).")";
	}

	public function getCityDestination(Request $req){
		//dd($req->all());
		$callback = isset($_GET['callback']) ? $_GET['callback'] : die;
		$holidayType = isset($_GET['holidayType']) ? $_GET['holidayType'] : die;
		$country = isset($_GET['country']) ? $_GET['country'] : die;
		if($holidayType != 3){
			$transportType = isset($_GET['transportType']) ? $_GET['transportType'] : die;
			$departurePoint = isset($_GET['departurePoint']) ? $_GET['departurePoint'] : die;
			//dd($transportType);
			if($transportType == 3){
				$locations = Geography::getLocationsForPackageSearch(PackageInfo::getDestinationsFromTranportType($holidayType,$transportType));
			}else{
				$locations = Geography::getLocationsForPackageSearch(PackageInfo::getDestinations($holidayType,$transportType,$departurePoint));
			}

		} else {
			$locations = Geography::getLocationsForPackageSearch(Hotel::getDestinations());
		}
		//dd($locations);
		foreach($locations as $location){
			if($location->id == $country){
				$locations = $location->childrens;
				break;
			}
		}

		usort($locations,function($a,$b){
			return strcmp($a->name,$b->name);
		});
		foreach($locations as $location){
			$cities[] = array("id" => $location->id,"name" => $location->name);
			usort($location->childrens,function($a,$b){
				return strcmp($a->name,$b->name);
			});
			foreach($location->childrens as $child){
				$cities[] = array("id" => $child->id, "name" => $child->name.", ".$location->name);
			}
		}
		echo $callback."(".json_encode($cities).")";
	}

	public function getDepartureDates(Request $req){
		$callback = isset($_GET['callback']) ? $_GET['callback'] : die;
		$holidayType = isset($_GET['holidayType']) ? $_GET['holidayType'] : die;
		$transportType = isset($_GET['transportType']) ? $_GET['transportType'] : die;
		$city = isset($_GET['city']) ? $_GET['city'] : die;
		$departurePoint = isset($_GET['departurePoint']) ? $_GET['departurePoint'] : die;

			$results = PackageInfo::getDepartureDates($holidayType,$transportType,$city,$departurePoint);

		$departureDates = array();
		foreach($results as $result){
			$departureDates[] = $result->departure_date;
		}
		echo $callback."(".json_encode($departureDates).")";
	}

	public function getDurations(){
		$callback = isset($_GET['callback']) ? $_GET['callback'] : die;
		$holidayType = isset($_GET['holidayType']) ? $_GET['holidayType'] : die;
		$transportType = isset($_GET['transportType']) ? $_GET['transportType'] : die;
		$city = isset($_GET['city']) ? $_GET['city'] : die;
		$departureDate = isset($_GET['departureDate']) ? $_GET['departureDate'] : die;
		$departurePoint = isset($_GET['departurePoint']) ? $_GET['departurePoint'] : die;
		$results = PackageInfo::getDurations($holidayType,$transportType,$city,$departureDate,$departurePoint);
		
		$durations = array();
		foreach($results as $result){
			$durations['duration'][] = $result->duration;
			$durations['day_night'][] = $result->day_night;
		}
		echo $callback."(".json_encode($durations).")";
	}

	public function packageSearch(){
		$callback = isset($_GET['callback']) ? $_GET['callback'] : die;
		$packageSearch = isset($_GET['packageSearch']) ? $_GET['packageSearch'] : die;
		$Rooms = array();
		foreach($packageSearch["rooms"] as $room){
			$room = new RoomSoapObject($room["adults"],isset($room["kids"]) ? $room["kids"] : 0);
			$Rooms[] = $room;
		}
		$locations = Geography::getLocationIdsBySoapClient($packageSearch['destination']);
		
		$resultsBySoapClient = array();
		
		
		
		foreach($locations as $soapClient => $locationId){
				$eSOAPClient = new ET_SoapClient($soapClient);
				
				$departure_point = Geography::getLocationIdForSoapClient($soapClient,$packageSearch["departure_point"]);
				$resultsBySoapClient[$eSOAPClient->id_operator] = $eSOAPClient->packageSearch(new PackageSearchSoapObject($packageSearch["is_tour"],$packageSearch["is_flight"],$packageSearch["is_bus"],$departure_point,$locations[$eSOAPClient->id_operator],null,
														 																  $packageSearch["departure_date"],$packageSearch["duration"],null,$Rooms,
														 																  true,true));
				
		}

	
		
		$packageSearchDB = new PackagesSearchCached();
		$packageSearchDB->is_flight = $packageSearch["is_flight"];
		$packageSearchDB->is_bus = $packageSearch["is_bus"];
		$packageSearchDB->is_tour = $packageSearch["is_tour"];
		$packageSearchDB->departure = $packageSearch["departure_point"];
		$packageSearchDB->destination = $packageSearch["destination"];
		$packageSearchDB->departure_date = $packageSearch["departure_date"];
		$packageSearchDB->duration = $packageSearch["duration"];
		$packageSearchDB->rooms = json_encode($packageSearch["rooms"]);
		$packageSearchDB->save();
		
		/*
		$d = explode('-',$packageSearch["departure_date"]);
		$date = \Carbon\Carbon::create($d[0],$d[1],$d[2],0);
		
		$packageCache = new PriceSet;
		$packageCache->id = "2000";
		$packageCache->valid_from = $date;
		$packageCache->valid_to = $date;
		$packageCache->soap_client = 'CH';
		$packageCache->label = 'Search';
		$packageCache->description = 'Search';
		$packageCache->is_local = 0;
		$packageCache->save();
		*/
		
		foreach($resultsBySoapClient as $soapClientId => $soapPackageResults){
			foreach($soapPackageResults as $packageResult){
				$defaultMealPlan = true;
				$defaultMealPlanPrice = 0;
				foreach($packageResult->HotelInfo->MealPlans as $mealPlan){
					$packageSearchCachedPriceDB = new PackageSearchCachedPrice();
					$packageSearchCachedPriceDB->id_package_search = $packageSearchDB->id;
					$packageSearchCachedPriceDB->id_package = $packageResult->PackageId;
					$packageSearchCachedPriceDB->soap_client = $soapClientId;

					//TODO:
					//Check if room category exists in database
					//If not, add it
					//or add room category based on search

					$packageSearchCachedPriceDB->id_room_category = $packageResult->HotelInfo->CategoryId;
					$mealPlanDB = MealPlanPackageSearch::where('code','=',$mealPlan->Code)->where('id_package_search','=',$packageSearchDB->id)->first();
					if($mealPlanDB == null){
						$mealPlanDB = new MealPlanPackageSearch();
						$mealPlanDB->id_package_search = $packageSearchDB->id;
						$mealPlanDB->code = $mealPlan->Code;
						$mealPlanDB->name = $mealPlan->Label;
						$mealPlanDB->save();
					}
					$packageSearchCachedPriceDB->id_meal_plan = $mealPlanDB->id;
					if($defaultMealPlan){
						$packageSearchCachedPriceDB->gross = $packageResult->Price->Gross;
						$packageSearchCachedPriceDB->vat = $packageResult->Price->VAT;
						$packageSearchCachedPriceDB->tax = $packageResult->Price->Tax;
						$defaultMealPlanPrice = $mealPlan->Price->Gross;
						$defaultMealPlan = false;
					} else {
						$packageSearchCachedPriceDB->gross = $packageResult->Price->Gross - $defaultMealPlanPrice + $mealPlan->Price->Gross;
						$packageSearchCachedPriceDB->vat = $packageResult->Price->VAT;
						$packageSearchCachedPriceDB->tax = $packageResult->Price->Tax;
					}
					$packageSearchCachedPriceDB->save();
					
					
					/*
					$cachedPrice = new CachedPrice;
					$cachedPrice->id_package = $packageResult->PackageId;
					$cachedPrice->id_room_category = $packageResult->HotelInfo->CategoryId;
					$cachedPrice->id_price_set = $packageCache->id;
					$cachedPrice->id_meal_plan = $mealPlanDB->id;
					$cachedPrice->departure_date = $packageSearch["departure_point"];
					
					if($defaultMealPlan){
						$cachedPrice->gross = $packageResult->Price->Gross;
						$cachedPrice->tax = $packageResult->Price->Tax;
					}else{
						$cachedPrice->gross = $packageResult->Price->Gross - $defaultMealPlanPrice + $mealPlan->Price->Gross;
						$cachedPrice->tax = $packageResult->Price->Tax;		
					}
					
					$cachedPrice->currency = 0;
					$cachedPrice->soap_client = $soapClientId;
					$cachedPrice->save();
					*/
				}
			}
		}
		
		$locationIds = array();
		Geography::getLocationsFromBaseLocation(Geography::find($packageSearch['destination']),$locationIds);
		$localResults = DB::table('packages')->join('cached_prices','cached_prices.id_package','=','packages.id')->where('packages.soap_client','=','LOCAL')->where('is_tour','=',$packageSearch['is_tour'])
											 ->where('is_flight','=',$packageSearch['is_flight'])->where('is_bus','=',$packageSearch['is_bus'])->where('duration','=',$packageSearch['duration'])
											 ->whereIn('destination',$locationIds)->where('cached_prices.soap_client','=','LOCAL')->where('cached_prices.departure_date','=',$packageSearch['departure_date'])->get();
		$eOps = \DB::table('etrip_operators')->value('id_operator');
		$localSoapResults = DB::table('packages')->join('cached_prices','cached_prices.id_package','=','packages.id')->where('packages.soap_client','=',$eOps)->where('is_tour','=',$packageSearch['is_tour'])
											 ->where('is_flight','=',$packageSearch['is_flight'])->where('is_bus','=',$packageSearch['is_bus'])->where('duration','=',$packageSearch['duration'])
											 ->whereIn('destination',$locationIds)->where('cached_prices.soap_client','=',$eOps)->where('cached_prices.departure_date','=',$packageSearch['departure_date'])->get();

										 
		
		
		foreach($localResults as $result){
			$packageSearchCachedPriceDB = new PackageSearchCachedPrice();
			$packageSearchCachedPriceDB->id_package_search = $packageSearchDB->id;
			$packageSearchCachedPriceDB->id_package = $result->id;
			$packageSearchCachedPriceDB->soap_client = "LOCAL";
			$packageSearchCachedPriceDB->id_room_category = $result->id_room_category;
			$packageSearchCachedPriceDB->id_meal_plan = $result->id_meal_plan;
			$packageSearchCachedPriceDB->gross = $result->gross * count($Rooms);
			$packageSearchCachedPriceDB->vat = 0;
			$packageSearchCachedPriceDB->tax = $result->tax * count($Rooms);
			$packageSearchCachedPriceDB->save();
			
			/*
			$cachedPrice = new CachedPrice;
			$cachedPrice->id_package = $result->id;
			$cachedPrice->id_room_category = $result->id_room_category;
			$cachedPrice->id_price_set = $packageSearchDB->id;
			$cachedPrice->id_meal_plan = $result->id_meal_plan;
			$cachedPrice->departure_date = $date;
			$cachedPrice->gross = $result->gross * count($Rooms);
			$cachedPrice->tax = $result->tax * count($Rooms);
			$cachedPrice->currency = 0;
			$cachedPrice->soap_client = "LOCAL";
			$cachedPrice->save();
			*/
		}
		foreach($localSoapResults as $result){
			$packageSearchCachedPriceDB = new PackageSearchCachedPrice();
			$packageSearchCachedPriceDB->id_package_search = $packageSearchDB->id;
			$packageSearchCachedPriceDB->id_package = $result->id;
			$packageSearchCachedPriceDB->soap_client = $result->soap_client;
			$packageSearchCachedPriceDB->id_room_category = $result->id_room_category;
			$packageSearchCachedPriceDB->id_meal_plan = $result->id_meal_plan;
			$packageSearchCachedPriceDB->gross = $result->gross * count($Rooms);
			$packageSearchCachedPriceDB->vat = 0;
			$packageSearchCachedPriceDB->tax = $result->tax * count($Rooms);
			$packageSearchCachedPriceDB->save();
			
			/*
			$cachedPrice = new CachedPrice;
			$cachedPrice->id_package = $result->id;
			$cachedPrice->id_room_category = $result->id_room_category;
			$cachedPrice->id_price_set = $packageSearchDB->id;
			$cachedPrice->id_meal_plan = $result->id_meal_plan;
			$cachedPrice->departure_date = $date;
			$cachedPrice->gross = $result->gross * count($Rooms);
			$cachedPrice->tax = $result->tax * count($Rooms);
			$cachedPrice->currency = 0;
			$cachedPrice->soap_client = $result->soap_client;
			$cachedPrice->save();
			*/
		}
		//dd($packageSearchDB);
		echo $callback."(".json_encode($packageSearchDB->id).")";
	}

	public function hotelSearch(){
		$callback = isset($_GET['callback']) ? $_GET['callback'] : die;
		$hotelSearchJSON = isset($_GET['hotelSearch']) ? $_GET['hotelSearch'] : die;
		$Rooms = array();
		foreach($hotelSearchJSON["rooms"] as $room){
			$room = new RoomSoapObject($room["adults"],isset($room["kids"]) ? $room["kids"] : 0);
			$Rooms[] = $room;
		}
		$roomsDB = $hotelSearchJSON["rooms"];
		$checkInArray = explode('/', $hotelSearchJSON['checkIn']);
		$checkIn = $checkInArray[2].'-'.$checkInArray[1].'-'.$checkInArray[0];

		$locations = Geography::getLocationIdsBySoapClient($hotelSearchJSON['destination']);
		$resultsBySoapClient = array();
		foreach($locations as $soapClient => $locationId){

				$eSOAPClient = new ET_SoapClient($soapClient);
				$hotelSearch = new HotelSearchSoapObject($locations[$soapClient][0],null,$checkIn,$hotelSearchJSON['stay'],
																		  					   1,$Rooms,null,false,false,true);
				$resultsBySoapClient[$eSOAPClient->id_operator] = $eSOAPClient->hotelSearch($hotelSearch);
		}
		
		
		$hotelSearchDB = new HotelSearchCached();
		$hotelSearchDB->destination = $hotelSearchJSON['destination'];
		$hotelSearchDB->check_in = $hotelSearch->CheckIn;
		$hotelSearchDB->stay = $hotelSearch->Stay;
		$hotelSearchDB->min_stars = $hotelSearch->MinStars;
		$hotelSearchDB->hotel = $hotelSearch->Hotel;
		$hotelSearchDB->rooms = json_encode($roomsDB);
		$hotelSearchDB->save();

		foreach($resultsBySoapClient as $soapClientId => $soapHotelResults){
			foreach($soapHotelResults as $hotelResult){
				$defaultMealPlan = true;
				$defaultMealPlanPrice = 0;
				foreach($hotelResult->MealPlans as $mealPlan){
					$hotelSearchCachedPriceDB = new HotelSearchCachedPrice();
					$hotelSearchCachedPriceDB->id_hotel_search = $hotelSearchDB->id;
					$hotelSearchCachedPriceDB->id_hotel = $hotelResult->HotelId;
					$hotelSearchCachedPriceDB->soap_client = $soapClientId;

					//TODO:
					//Check if room category exists in database
					//If not, add it
					//or add room category based on search

					$hotelSearchCachedPriceDB->id_room_category = $hotelResult->CategoryId;
					$mealPlanDB = MealPlanHotelSearch::where('code','=',$mealPlan->Code)->where('id_hotel_search','=',$hotelSearchDB->id)->first();
					if($mealPlanDB == null){
						$mealPlanDB = new MealPlanHotelSearch();
						$mealPlanDB->id_hotel_search = $hotelSearchDB->id;
						$mealPlanDB->code = $mealPlan->Code;
						$mealPlanDB->name = $mealPlan->Label;
						$mealPlanDB->save();
					}
					$hotelSearchCachedPriceDB->id_meal_plan = $mealPlanDB->id;
					if($defaultMealPlan){
						$hotelSearchCachedPriceDB->gross = $hotelResult->Price->Gross;
						$hotelSearchCachedPriceDB->vat = $hotelResult->Price->VAT;
						$hotelSearchCachedPriceDB->tax = $hotelResult->Price->Tax;
						$defaultMealPlanPrice = $mealPlan->Price->Gross;
						$defaultMealPlan = false;
					} else {
						$hotelSearchCachedPriceDB->gross = $hotelResult->Price->Gross - $defaultMealPlanPrice + $mealPlan->Price->Gross;
						$hotelSearchCachedPriceDB->vat = $hotelResult->Price->VAT;
						$hotelSearchCachedPriceDB->tax = $hotelResult->Price->Tax;
					}
					$hotelSearchCachedPriceDB->save();
				}
			}
		}
		
		
		echo $callback."(".json_encode($hotelSearchDB->id).")";
	}

	public function singlePackageSearch(){
		$packageSearch = isset($_GET['packageSearch']) ? $_GET['packageSearch'] : die;
		$roomsForFunction = json_encode($packageSearch["rooms"]);
		$Rooms = array();
		foreach($packageSearch["rooms"] as $room){
			$room = new RoomSoapObject($room["adults"],isset($room["kids"]) ? $room["kids"] : 0);
			$Rooms[] = $room;
		}
		$pSearchSoapObject = new PackageSearchSoapObject($packageSearch["is_tour"],$packageSearch["is_flight"],$packageSearch["is_bus"],$packageSearch['departure_point'],$packageSearch["destination"],$packageSearch["hotel"],
														 $packageSearch["departure_date"],$packageSearch["duration"],null,$Rooms,
														 true,true);

		$SOAPClient = new ET_SoapClient($packageSearch['soap_client']);
		$soapPackageResults = $SOAPClient->packageSearch($pSearchSoapObject);

		$prices = array();
		$jsonPrices = array();
		$packagesFound = array();
		$packagesFoundIds = array();
		foreach($soapPackageResults as $packageResult){
			$defaultMealPlan = true;
			$defaultMealPlanPrice = 0;
			if(!in_array($packageResult->PackageId, $packagesFoundIds)) $packagesFoundIds[] = $packageResult->PackageId;
			if(!isset($packagesFound[$packageResult->PackageId])){
 				$packagesFound[$packageResult->PackageId] = PackageInfo::find($packageResult->PackageId);
 			}
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
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $packageSearch['soap_client'] == "HO" ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"] = $packageResult->Price->Gross + $packageResult->Price->Tax;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["askForOfferFunction"] = "askForOfferPackage(".$packageResult->PackageId.",\"".$packageSearch['soap_client']."\","
																																								  ."\"".RoomCategory::find($packageResult->HotelInfo->CategoryId)->name."\",\"".$mealPlan->Label."\","
																																								  .json_encode($packageSearch['rooms'],JSON_UNESCAPED_SLASHES).",".$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"].","
																																								  ."\"".$packageSearch['departure_date']."\",".$packageSearch['duration'].",event)";
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
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $packageSearch['soap_client'] == "HO" ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["askForOfferFunction"] = "askForOfferPackage(".$packageResult->PackageId.",\"".$packageSearch['soap_client']."\","
																																								  ."\"".RoomCategory::find($packageResult->HotelInfo->CategoryId)->name."\",\"".$mealPlan->Label."\","
																																								  .json_encode($packageSearch['rooms'],JSON_UNESCAPED_SLASHES).",".$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"].","
																																								  ."\"".$packageSearch['departure_date']."\",".$packageSearch['duration'].",event)";
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["earlyBooking"] = in_array('early_booking',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["specialOffer"] = in_array('special_offer',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["lastMinute"] = in_array('last_minute',$packageResult->FareType);

				}
			}
		}
		if(count($prices) > 0){
			foreach($prices as $packageId => $package )
				foreach($package as $roomCategory => $roomCategoryPrices){
		            foreach($roomCategoryPrices as $mealPlan => $mealPlanPrice){
		            	$object = new stdClass();
		            	$object->packageId = $packageId;
		            	$object->roomCategory = $roomCategory;
		            	$object->mealPlan = $mealPlan;
		            	$object->price = floatval($mealPlanPrice['price']);
		            	$object->onClickFunction = $mealPlanPrice['function'];
		            	$object->isAvailable = $mealPlanPrice['isAvailable'];
		            	$object->isBookable = $mealPlanPrice['isBookable'];
						$object->earlyBooking = $mealPlanPrice['earlyBooking'];
						$object->specialOffer = $mealPlanPrice['specialOffer'];
						$object->lastMinute = $mealPlanPrice['lastMinute'];
						$object->askForOfferFunction = $mealPlanPrice['askForOfferFunction'];
						$object->isMainSoapClient = $mealPlanPrice['isMainSoapClient'];
						$object->priceWithoutDiscount = $mealPlanPrice['priceWithoutDiscount'];
						$object->discounts = $mealPlanPrice['discounts'];
		            	$jsonPrices[$packageId][] = $object;
		            }
		        }
    	}
    	$json = new stdClass();
    	$json->packages = $packagesFound;
			$json->packagesFound = $packagesFoundIds;
    	$json->prices = $jsonPrices;
			echo json_encode($json);
	}

	public function singleHotelSearch(){
		$hotelSearch = isset($_GET['hotelSearch']) ? $_GET['hotelSearch'] : die;
		$SOAPClient = new ET_SoapClient($hotelSearch['soap_client']);

		$Rooms = array();
		foreach($hotelSearch['rooms'] as $room){
			$room = new RoomSoapObject($room["adults"],isset($room["kids"]) ? $room["kids"] : 0);
			$Rooms[] = $room;
		}
		$checkInArray = explode('/', $hotelSearch['check_in']);
		$checkIn = $checkInArray[2].'-'.$checkInArray[1].'-'.$checkInArray[0];
		$hotelSearchSOAP = new HotelSearchSoapObject($hotelSearch['destination'],$hotelSearch['hotel'],$checkIn,$hotelSearch['stay'],
													1,$Rooms,null,false,false,true);
		$soapHotelResults = $SOAPClient->hotelSearch($hotelSearchSOAP);
		$prices = array();
		$jsonPrices = array();
		foreach($soapHotelResults as $hotelResult){
			$defaultMealPlan = true;
			$defaultMealPlanPrice = 0;
			foreach($hotelResult->MealPlans as $mealPlan){
				if($defaultMealPlan){
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"] = $hotelResult->Price->Gross + $hotelResult->Price->Tax;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["function"] =  "bookHotel(".$hotelResult->HotelId.",".$hotelResult->CategoryId.","."'".$mealPlan->Label."',".($hotelResult->Price->Gross + $hotelResult->Price->Tax).",event);";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $hotelSearch['soap_client'] == "HO" ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["askForOfferFunction"] = "askForOfferHotel(".$hotelResult->HotelId.",\"".$hotelSearch['soap_client']."\","
																																								  ."\"".RoomCategory::find($hotelResult->CategoryId)->name."\",\"".$mealPlan->Label."\","
																																								  .json_encode($hotelSearch['rooms'],JSON_UNESCAPED_SLASHES).",".$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"].","
																																								  ."\"".$hotelSearch['check_in']."\",\"".$hotelSearch['check_out']."\",event)";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $hotelResult->IsAvailable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isBookable"] = $hotelResult->IsBookable ? true : false;
					$defaultMealPlanPrice = $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$defaultMealPlan = false;
				} else {
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"] = $hotelResult->Price->Gross + $hotelResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["function"] =  "bookHotel(".$hotelResult->HotelId.",".$hotelResult->CategoryId.","."'".$mealPlan->Label."',".($hotelResult->Price->Gross + $hotelResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax).",event);";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $hotelSearch['soap_client'] == "HO" ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["askForOfferFunction"] = "askForOfferHotel(".$hotelResult->HotelId.",\"".$hotelSearch['soap_client']."\","
																																								  ."\"".RoomCategory::find($hotelResult->CategoryId)->name."\",\"".$mealPlan->Label."\","
																																								  .json_encode($hotelSearch['rooms'],JSON_UNESCAPED_SLASHES).",".$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"].","
																																								  ."\"".$hotelSearch['check_in']."\",\"".$hotelSearch['check_out']."\",event)";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $hotelResult->IsAvailable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isBookable"] = $hotelResult->IsBookable ? true : false;

				}
			}
		}
		if(count($prices) > 0){
			foreach($prices as $packageId => $package )
				foreach($package as $roomCategory => $roomCategoryPrices){
		            foreach($roomCategoryPrices as $mealPlan => $mealPlanPrice){
		            	$object = new stdClass();
		            	$object->hotelId = $packageId;
		            	$object->roomCategory = $roomCategory;
		            	$object->mealPlan = $mealPlan;
		            	$object->price = floatval($mealPlanPrice['price']);
		            	$object->onClickFunction = $mealPlanPrice['function'];
		            	$object->isAvailable = $mealPlanPrice['isAvailable'];
		            	$object->isBookable = $mealPlanPrice['isBookable'];
		            	$object->isMainSoapClient = $mealPlanPrice['isMainSoapClient'];
		            	$object->askForOfferFunction = $mealPlanPrice['askForOfferFunction'];
		            	$jsonPrices[$packageId][] = $object;
		            }
		        }
    	}
    	$json = new stdClass();
    	$json->prices = $jsonPrices;
		echo json_encode($json);
	}

	public function singlePackageSearchBeforeBooking(Request $req){
		//dd($req->all());
		$packageSearch = isset($_GET['packageSearch']) ? $_GET['packageSearch'] : die;
		$packageId = isset($_GET['packageId']) ? $_GET['packageId'] : die;
		$oldPriceInfo = isset($_GET['oldPriceInfo']) ? $_GET['oldPriceInfo'] : die;
		$SOAPClient = new ET_SoapClient($packageSearch['soap_client']);
		//dd($packageSearch);
		$goToBooking = false;
		$Rooms = array();
		foreach($packageSearch["rooms"] as $k=>$room){
			$room = new RoomSoapObject($room["adults"],isset($room["kids"]) ? $room["kids"] : 0);
			$Rooms[$k] = $room;
		}
		//dd($packageSearch);
		$soapPackageResults = $SOAPClient->packageSearch(new PackageSearchSoapObject($packageSearch["is_tour"],$packageSearch["is_flight"],$packageSearch["is_bus"],(empty($packageSearch["departure_point"])?0:$packageSearch["departure_point"]),$packageSearch["destination"],$packageSearch["hotel"],
																			  (isset($packageSearch["departure_date"])?$packageSearch["departure_date"]:null),(isset($packageSearch["duration"])?$packageSearch["duration"]:null),null,$Rooms,
																			  true,true));
		$prices = array();
		$jsonPrices = array();
		$packagesFound = array();
		$i = 0;
		$resultIndex = 0;
		$mealPlanIndex = 0;
		//dd($soapPackageResults,$packageSearch,$Rooms);
		foreach($soapPackageResults as $packageResult){
			$defaultMealPlan = true;
			$defaultMealPlanPrice = 0;
			$j = 0;
			if(!isset($packagesFound[$packageResult->PackageId])){
				$packagesFound[$packageResult->PackageId] = PackageInfo::find($packageResult->PackageId);
			}
			foreach($packageResult->HotelInfo->MealPlans as $mealPlan){
				$roomCategoryName = RoomCategory::find($packageResult->HotelInfo->CategoryId)->name;
				$tPrice = 0;
				if($defaultMealPlan){
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"] = $packageResult->Price->Gross + $packageResult->Price->Tax;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["function"] =  "bookPackage(".$packageResult->PackageId.",".$packageResult->HotelInfo->CategoryId.","
																																								  ."'".$mealPlan->Label."',"
																																								  .($packageResult->Price->Gross + $packageResult->Price->Tax).",event);";
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $packageResult->IsAvailable ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isBookable"] = $packageResult->IsBookable ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $packageSearch['soap_client'] == "HO" ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["askForOfferFunction"] = "askForOfferPackage(".$packageResult->PackageId.",\"HO\","
																																								  ."\"".RoomCategory::find($packageResult->HotelInfo->CategoryId)->name."\",\"".$mealPlan->Label."\","
																																								  .json_encode($packageSearch['rooms'],JSON_UNESCAPED_SLASHES).",".$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"].","
																																								  ."\"".$packageSearch['departure_date']."\",".$packageSearch['duration'].",event)";
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["earlyBooking"] = in_array('early_booking',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["specialOffer"] = in_array('special_offer',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["lastMinute"] = in_array('last_minute',$packageResult->FareType);
					$defaultMealPlanPrice = $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$defaultMealPlan = false;
				} else {
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"] = $packageResult->Price->Gross + $packageResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["function"] =  "bookPackage(".$packageResult->PackageId.",".$packageResult->HotelInfo->CategoryId.","
																																								  ."'".$mealPlan->Label."',"
																																								  .($packageResult->Price->Gross + $packageResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax).",event);";
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $packageResult->IsAvailable ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isBookable"] = $packageResult->IsBookable ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $packageSearch['soap_client'] == "HO" ? true : false;
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["askForOfferFunction"] = "askForOfferPackage(".$packageResult->PackageId.",\"HO\","
																																								  ."\"".RoomCategory::find($packageResult->HotelInfo->CategoryId)->name."\",\"".$mealPlan->Label."\","
																																								  .json_encode($packageSearch['rooms'],JSON_UNESCAPED_SLASHES).",".$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"].","
																																								  ."\"".$packageSearch['departure_date']."\",".$packageSearch['duration'].",event)";
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["earlyBooking"] = in_array('early_booking',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["specialOffer"] = in_array('special_offer',$packageResult->FareType);
					$prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["lastMinute"] = in_array('last_minute',$packageResult->FareType);
				}
				$tPrice = $prices[$packageResult->PackageId][RoomCategory::find($packageResult->HotelInfo->CategoryId)->name][$mealPlan->Label]["price"];
				if($packageResult->PackageId == $oldPriceInfo['packageId'] && $packageResult->HotelInfo->CategoryId == $oldPriceInfo['categoryId'] && $mealPlan->Label == $oldPriceInfo['mealPlanLabel'] && $tPrice == $oldPriceInfo['price']){
					$goToBooking = true;
					$resultIndex = $i;
					$mealPlanIndex = $j;
				}
				$j++;
			}
			$i++;
		}
		if(count($prices) > 0){
			foreach($prices as $packageId => $package )
				foreach($package as $roomCategory => $roomCategoryPrices){
		            foreach($roomCategoryPrices as $mealPlan => $mealPlanPrice){
		            	$object = new stdClass();
		            	$object->packageId = $packageId;
		            	$object->roomCategory = $roomCategory;
		            	$object->mealPlan = $mealPlan;
		            	$object->price = floatval($mealPlanPrice['price']);
		            	$object->onClickFunction = $mealPlanPrice['function'];
		            	$object->isAvailable = $mealPlanPrice['isAvailable'];
		            	$object->isBookable = $mealPlanPrice['isBookable'];
									$object->earlyBooking = $mealPlanPrice['earlyBooking'];
									$object->specialOffer = $mealPlanPrice['specialOffer'];
									$object->lastMinute = $mealPlanPrice['lastMinute'];
									$object->askForOfferFunction = $mealPlanPrice['askForOfferFunction'];
									$object->isMainSoapClient = $mealPlanPrice['isMainSoapClient'];
		            	$jsonPrices[$packageId][] = $object;
		            }
		        }
    	}
    	$response = new stdClass();
    	$response->status = $goToBooking;
    	if($goToBooking){
    		$response->prices = null;
    		$bookingPackageSearch = new BookingPackageSearch();
    		$bookingPackageSearch->id_package = $packageId;
				$bookingPackageSearch->id_hotel = $packageSearch["hotel"];
				$bookingPackageSearch->is_tour = $packageSearch["is_tour"];
				$bookingPackageSearch->is_flight = $packageSearch["is_flight"];
				$bookingPackageSearch->is_bus = $packageSearch["is_bus"];
				$bookingPackageSearch->destination = $packageSearch["destination"];
    		$bookingPackageSearch->room_category = RoomCategory::find($oldPriceInfo['categoryId'])->name;
    		$bookingPackageSearch->meal_plan = $oldPriceInfo['mealPlanLabel'];
    		$bookingPackageSearch->price = floatval($oldPriceInfo['price']);
    		$bookingPackageSearch->duration = $packageSearch["duration"];
    		$bookingPackageSearch->check_in = $packageSearch["departure_date"];
    		$bookingPackageSearch->result_index = $resultIndex;
    		$bookingPackageSearch->meal_plan_index = $mealPlanIndex;
			$checkOut = date('Y-m-d', strtotime($packageSearch["departure_date"]." +".$packageSearch["duration"]." days"));
    		$bookingPackageSearch->check_out = $checkOut;
    		$bookingPackageSearch->rooms = json_encode($Rooms);
				$bookingPackageSearch->departure_point = $packageSearch["departure_point"];
				$bookingPackageSearch->soap_client=$packageSearch['soap_client'];
    		$bookingPackageSearch->save();
    		$response->id = $bookingPackageSearch->id;
    	} else {
    		$response->prices = $jsonPrices;
    		$response->packages = $packagesFound;
    	}
		echo json_encode($response);
	}

	public function singleHotelSearchBeforeBooking(Request $req){
		$hotelId = isset($_GET['hotelId']) ? $_GET['hotelId'] : die;
		$oldPriceInfo = isset($_GET['oldPriceInfo']) ? $_GET['oldPriceInfo'] : die;
		$hotelSearch = isset($_GET['hotelSearch']) ? $_GET['hotelSearch'] : die;
		$SOAPClient = new ET_SoapClient($req->hotelSearch['soap_client']);
		$Rooms = array();
		foreach($hotelSearch['rooms'] as $room){
			$room = new RoomSoapObject($room["adults"],isset($room["kids"]) ? $room["kids"] : 0);
			$Rooms[] = $room;
		}
		$checkInArray = explode('/', $hotelSearch['check_in']);
		$checkIn = $checkInArray[2].'-'.$checkInArray[1].'-'.$checkInArray[0];
		$hotelSearchSOAP = new HotelSearchSoapObject($hotelSearch['destination'],$hotelSearch['hotel'],$checkIn,$hotelSearch['stay'],
													1,$Rooms,null,false,false,true);
		$soapHotelResults = $SOAPClient->hotelSearch($hotelSearchSOAP);
		$goToBooking = false;
		$prices = array();
		$jsonPrices = array();
		$i = 0;
		$resultIndex = 0;
		$mealPlanIndex = 0;
		foreach($soapHotelResults as $hotelResult){
			$defaultMealPlan = true;
			$defaultMealPlanPrice = 0;
			$j = 0;
			foreach($hotelResult->MealPlans as $mealPlan){
				$roomCategoryName = RoomCategory::find($hotelResult->CategoryId)->name;
				$tPrice = 0;
				if($defaultMealPlan){
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"] = $hotelResult->Price->Gross + $hotelResult->Price->Tax;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["function"] =  "bookHotel(".$hotelResult->HotelId.",".$hotelResult->CategoryId.","."'".$mealPlan->Label."',".($hotelResult->Price->Gross + $hotelResult->Price->Tax).",event);";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $hotelSearch['soap_client'] == "HO" ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $hotelResult->IsAvailable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isBookable"] = $hotelResult->IsBookable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["askForOfferFunction"] = "askForOfferHotel(".$hotelResult->HotelId.",\"HO\","
																																								  ."\"".RoomCategory::find($hotelResult->CategoryId)->name."\",\"".$mealPlan->Label."\","
																																								  .json_encode($hotelSearch['rooms'],JSON_UNESCAPED_SLASHES).",".$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"].","
																																								  ."\"".$hotelSearch['check_in']."\",\"".$hotelSearch['check_out']."\",event)";
					$defaultMealPlanPrice = $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$defaultMealPlan = false;
				} else {
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"] = $hotelResult->Price->Gross + $hotelResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["function"] =  "bookHotel(".$hotelResult->HotelId.",".$hotelResult->CategoryId.","."'".$mealPlan->Label."',".($hotelResult->Price->Gross + $hotelResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax).",event);";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $hotelSearch['soap_client'] == "HO" ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $hotelResult->IsAvailable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isBookable"] = $hotelResult->IsBookable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["askForOfferFunction"] = "askForOfferHotel(".$hotelResult->HotelId.",\"HO\","
																																								  ."\"".RoomCategory::find($hotelResult->CategoryId)->name."\",\"".$mealPlan->Label."\","
																																								  .json_encode($hotelSearch['rooms'],JSON_UNESCAPED_SLASHES).",".$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"].","
																																								  ."\"".$hotelSearch['check_in']."\",\"".$hotelSearch['check_out']."\",event)";
				}
				$tPrice = $prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"];
				if($hotelResult->HotelId == $oldPriceInfo['hotelId'] && $hotelResult->CategoryId == $oldPriceInfo['categoryId'] && $mealPlan->Label == $oldPriceInfo['mealPlanLabel'] && $tPrice == $oldPriceInfo['price']){
					$goToBooking = true;
					$resultIndex = $i;
					$mealPlanIndex = $j;
				}
				$j++;
			}
			$i++;
		}
		if(count($prices) > 0){
			foreach($prices as $packageId => $package )
				foreach($package as $roomCategory => $roomCategoryPrices){
		            foreach($roomCategoryPrices as $mealPlan => $mealPlanPrice){
		            	$object = new stdClass();
		            	$object->hotelId = $packageId;
		            	$object->roomCategory = $roomCategory;
		            	$object->mealPlan = $mealPlan;
		            	$object->price = floatval($mealPlanPrice['price']);
		            	$object->onClickFunction = $mealPlanPrice['function'];
		            	$object->isAvailable = $mealPlanPrice['isAvailable'];
		            	$object->isBookable = $mealPlanPrice['isBookable'];
		            	$object->isMainSoapClient = $mealPlanPrice['isMainSoapClient'];
		            	$object->askForOfferFunction = $mealPlanPrice['askForOfferFunction'];
		            	$jsonPrices[$packageId][] = $object;
		            }
		        }
    	}
    	$response = new stdClass();
    	$response->status = $goToBooking;
    	if($goToBooking){
    		$response->prices = null;
    		$bookingPackageSearch = new BookingHotelSearch();
    		$bookingPackageSearch->id_hotel = $packageId;
    		$bookingPackageSearch->room_category = RoomCategory::find($oldPriceInfo['categoryId'])->name;
    		$bookingPackageSearch->meal_plan = $oldPriceInfo['mealPlanLabel'];
    		$bookingPackageSearch->price = floatval($oldPriceInfo['price']);
    		$bookingPackageSearch->duration = $hotelSearch["stay"];
    		$checkInArray = explode('/',$hotelSearch["check_in"]);
    		$bookingPackageSearch->check_in = $checkInArray[2].'-'.$checkInArray[1].'-'.$checkInArray[0];
    		$bookingPackageSearch->result_index = $resultIndex;
    		$bookingPackageSearch->meal_plan_index = $mealPlanIndex;
			$checkOut = date('Y-m-d', strtotime($bookingPackageSearch->check_in ." +".$hotelSearch["stay"]." days"));
    		$bookingPackageSearch->check_out = $checkOut;
    		$bookingPackageSearch->rooms = json_encode($Rooms);
    		$bookingPackageSearch->save();
    		$response->id = $bookingPackageSearch->id;
    	} else {
    		$response->prices = $jsonPrices;
    	}
		echo json_encode($response);
	}

	public function askForOffer(Request $req){
		
		$offer = isset($_GET['offer']) ? $_GET['offer'] : die;
		$askForOfferItem = new AskForOffersItem;
		if($offer['offerType'] == "PACKAGE"){
			$askForOfferItem->id_package = $offer['packageId'];
			$askForOfferItem->soap_client = $offer['soapClient'];
			$askForOfferItem->room_category = $offer['roomCategory'];
			$askForOfferItem->meal_plan = $offer['mealPlan'];
			$askForOfferItem->rooms = json_encode($offer['rooms']);
			$askForOfferItem->price = $offer['price'];
			$askForOfferItem->departure_date = $offer['departureDate'];
			$askForOfferItem->duration = $offer['duration'];
			$askForOfferItem->return_date =  date('Y-m-d', strtotime($offer["departureDate"]." +".$offer["duration"]." days"));
			$askForOfferItem->save();
		} else if($offer['offerType'] == "HOTEL"){
			$askForOfferItem->id_hotel = $offer['hotelId'];
			$askForOfferItem->soap_client = $offer['soapClient'];
			$askForOfferItem->room_category = $offer['roomCategory'];
			$askForOfferItem->meal_plan = $offer['mealPlan'];
			$askForOfferItem->rooms = json_encode($offer['rooms']);
			$askForOfferItem->price = $offer['price'];
			$askForOfferItem->departure_date = $offer['departureDate'];
			$askForOfferItem->return_date =  $offer['returnDate'];
			$askForOfferItem->duration = floor((strtotime($offer['returnDate']) - strtotime($offer['departureDate']))/(60*60*24));
			$askForOfferItem->save();
		}
		return response()->json(['id'=>$askForOfferItem->id]);
	}

	public function searchSuggestion(Request $req){
			if($req->ajax()){
				//->take(5)
				$h = HotelEloquent::where('available',1)->search($req->search)->get();
				$s=[];
				for ($i=0; $i < count($h); $i++) { 
					if(\DB::table('packages')->where('id_hotel',$h[$i]->id)->first() != null){
						$s[$i] = $h[$i];
					}
					if(count($s) == 5){break;}
				}
				
				return response()->json(['r'=>array_values($s)]);
			}
	}

	public function saveExtraToSession(Request $req){
		dd($req->all());
	}

}
