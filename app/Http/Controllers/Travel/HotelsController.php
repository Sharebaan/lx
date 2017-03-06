<?php namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Travel\Hotel;
use App\Models\Travel\HotelEloquent;
use App\Models\Travel\Eloquent\PackageInfoEloquent;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\PackagesSearchCached;
use App\Models\Travel\Geography;
use App\Models\Travel\FareType;
use App\Models\Travel\MealPlanPackageSearch;

use Illuminate\Http\Request;
use URL;

class HotelsController extends Controller {

	public function viewHotel($hotelCountry,$hotelName,$hotelId,$soapClientId,$searchId){
		if($soapClientId == "HO"){
			$SOAPClient = App::make('INF_ISoapClient');
		} else {
			$soapClientName = Config::get('soapadditional.'.$soapClientId.'.soap_client');
			$SOAPClient = new $soapClientName;
		}
		$data['soapClientId'] = $soapClientId;
		$dbHotel = Hotel::where('id','=',$hotelId)->where('soap_client','=',$soapClientId)->first();
		$data['searchId'] = $searchId;
		$roomsOutput = "";
		$hotelSearchCached = HotelSearchCached::where('id','=',$searchId)->first();
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

		$hotelSearch = new HotelSearchSoapObject($hotelSearchCached->destination,$hotelId,$hotelSearchCached->check_in,$hotelSearchCached->stay,1,
												 $Rooms,null,false,false,true);
		$soapHotelResults = $SOAPClient->hotelSearch($hotelSearch);
		$prices = array();
		foreach($soapHotelResults as $hotelResult){
			$defaultMealPlan = true;
			$defaultMealPlanPrice = 0;
			foreach($hotelResult->MealPlans as $mealPlan){
				if($defaultMealPlan){
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"] = $hotelResult->Price->Gross + $hotelResult->Price->Tax;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["function"] =  "book(".$hotelResult->HotelId.",".$hotelResult->CategoryId.","."'".$mealPlan->Label."',".($hotelResult->Price->Gross + $hotelResult->Price->Tax).",event);";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $soapClientId == "HO" ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $hotelResult->IsAvailable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isBookable"] = $hotelResult->IsBookable ? true : false;
					$defaultMealPlanPrice = $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$defaultMealPlan = false;
				} else {
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["price"] = $hotelResult->Price->Gross + $hotelResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["function"] =  "book(".$hotelResult->HotelId.",".$hotelResult->CategoryId.","."'".$mealPlan->Label."',".($hotelResult->Price->Gross + $hotelResult->Price->Tax - $defaultMealPlanPrice + $mealPlan->Price->Gross + $mealPlan->Price->Tax).",event);";
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isMainSoapClient"] = $soapClientId == "HO" ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isAvailable"] = $hotelResult->IsAvailable ? true : false;
					$prices[$hotelResult->HotelId][RoomCategory::find($hotelResult->CategoryId)->name][$mealPlan->Label]["isBookable"] = $hotelResult->IsBookable ? true : false;

				}
			}
		}
		$data['hotel'] = $dbHotel;
		$data['prices'] = $prices;
		$data['pages'] = 'pages.'.CNF_THEME.'.hotels.viewHotel';//hotels.viewHotel
		$page = 'layouts.'.CNF_THEME.'.index';
		//'pages.'.CNF_THEME.
		//$page = 'layouts.'.CNF_THEME.'.index';
		return View::make($page,$data);

	}

	public function searchSelect(Request $req){
		$hotel = HotelEloquent::find($req->id);
		$geo = \DB::table('geographies')->where('id',$hotel->location)->first()->id_parent;
		$country = \DB::table('geographies')->where('id',$geo)->first()->name;
		$transport = \DB::table('packages')->where('id_hotel',$hotel->id)->first();
		if($transport == null){
			return redirect()->back();
		}
		

		$tType = PackageInfo::getTransportCode($transport->is_tour,$transport->is_bus,$transport->is_flight);

		return redirect('/oferte/Hoteluri/'.$country.'/'.implode('-',explode(' ',$hotel->name)).'_'.$tType.'_'.$hotel->id.'_'.$hotel->soap_client.'_sid0');
	}

	public function search(Request $req){

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
		$searchId = 1;
		$categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : 0;
		$stars = isset($_GET['stars']) ? $_GET['stars'] : 0;
		$data['page'] = $page;
			
		

		$hotels = Hotel::getListingForHotelSearch($req->search_input,$searchId,$mealPlans,$priceFrom,$priceTo,$sortBy,$sortOrder,$stars,$page);

	//	dd($hotels);
		/*$s = HotelEloquent::search($req->search_input)->get();

		$hotels = [];
		$dest = [];
		foreach($s as $n=>$a){
			$p = PackageInfo::where('id_hotel','=',$a->id)->first();
			$hcp = \DB::table('hotels_search_cached_prices')->where('id_hotel','=',$a->id)->get();
			if(empty($hcp)){continue;}
			if(empty($p) || $p->is_tour == 0 && $p->is_bus == 0 && $p->is_flight == 0){continue;}
			$prices = [];
			foreach($hcp as $k=>$v){
				$prices[$v->id_meal_plan] = $v->gross + $v->tax;
			}
			
			$mealPlanIds=[];
			foreach($prices as $k=>$v){
				if($v == min($prices)){$mealPlanIds[$k]=$k;}
			}
			$mealPlanIds = array_values($mealPlanIds);

			$p->hotel_description = $a->description;

			$p->hotel_name = $a->name;
			$p->stars = $a->class;
			$p->hotel_address = $a->address;
			$p->available = $a->available;
			$p->min_price = min($prices);
			$p->meal_plans_ids = $mealPlanIds;

			

			$hotels['hotels'][$n] = $p;
			$hotels['noPackage'][$n] = count($hcp);
			$hotels['minPrice'][$n] = min($prices);
			$hotels['maxPrice'][$n] = max($prices);
		}

		$criteria = [
			'stars'=>$stars,
			'mealPlans'=>$mealPlans,
			'sortBy'=>$sortBy,
			'sortOrder'=>$sortOrder,
			'priceFrom'=> $priceFrom,
			'priceTo'=>$priceTo
		];

		if(!empty($hotels['hotels'])){
			$hotelsFiltered = $this->filterItems($hotels['hotels'],$criteria);
		}else{
			$hotelsFiltered = [];
		}

		$hotels['noPackage'] = count($hotelsFiltered);
		
		$noHotels = count($hotelsFiltered);*/
		$noHotels = $hotels['noHotels'];
		$lastPage = $noHotels % 10 != 0 ? intval($noHotels / 10) + 1 : intval($noHotels / 10);
		$additionalGETsBefore = "?search=".$req->search;
		$pagesArray = array();

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
									  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET.$i.$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			if($lastPage >= 7){
				$pagesArray[] = array("text" => "&raquo;",
									  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET.$lastPage.$additionalGETsAfter,
									  "selected" => false
									 );
			}
		} elseif($page >= $lastPage - 2) {
			if($lastPage > 6){
				$pagesArray[] = array("text" => "&laquo;",
									  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET."1".$additionalGETsAfter,
									  "selected" => false
									 );
			}
			$t = $lastPage < 6 ? $lastPage - 1 : 5;
			for($i = $t; $i >= 0; $i--){
				$selected = ($page == $lastPage - $i) ? true : false;
				$pagesArray[] = array("text" => $lastPage - $i,
									  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET.($lastPage - $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}

		} else {
			$pagesArray[] = array("text" => "&laquo;",
								  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET."1".$additionalGETsAfter,
								  "selected" => false
								 );
			for($i = 2; $i > 0; $i--){
				$selected = false;
				$pagesArray[] = array("text" => $page - $i,
									  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET.($page - $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			$pagesArray[] = array("text" => $page,
								  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET.$page.$additionalGETsAfter,
								  "selected" => true
								 );
			for($i = 1; $i <= 2; $i++){
				$selected = false;
				$pagesArray[] = array("text" => $page + $i,
									  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET.($page + $i).$additionalGETsAfter,
									  "selected" => $selected
									 );
			}
			$pagesArray[] = array("text" => "&raquo;",
								  "url" => URL::route('search_hotel').$additionalGETsBefore.$pageGET.$lastPage.$additionalGETsAfter,
								  "selected" => false
								 );
		}
		$data['pagesArray'] = $pagesArray;


		$mealPlan = [];

		foreach(\DB::table('meal_plans')->get() as $k=>$v){
			$obj = new \stdClass();
			$obj->id = $v->id;
			$obj->name = $v->name;
			$obj->url = URL::route('search_hotel').$additionalGETsBefore.$pageGET.$page."&mealPlans=".$mealPlans.$additionalGETsAfter;
			$mealPlan[$k] = $obj;
		}

		$starsArray = explode(";",$stars);
		$starsOutputArray = array();
		$additionalGETsBefore = "?search=".$req->search
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
				$starsGET = $stars == "" ? $i : $i;;
				$starsOutputArray[$i]["selected"] = false;
			}
			$starsGET = $starsGET == "" ? "" : "&stars=".$starsGET;
			$starsOutputArray[$i]["url"] = URL::route('search_hotel').$additionalGETsBefore.$starsGET.$additionalGETsAfter;
		}

		$tmpSortBy = $sortBy == "" ? "price" : $sortBy;
		$tmpSortOrder = $sortOrder == "" ? "ASC" : $sortOrder;
		$sortArray["date"] = array("url" => URL::route('search_hotel').$additionalGETsBefore."&sortBy=date&sortOrder=ASC",
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray["name"] = array("url" => URL::route('search_hotel').$additionalGETsBefore."&sortBy=name&sortOrder=ASC",
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray["price"] = array("url" => URL::route('search_hotel').$additionalGETsBefore."&sortBy=price&sortOrder=ASC",
								   "additionalSelected" => "",
								   "additionalOrder" => "");
		$sortArray[$sortBy]["additionalSelected"] = " active";
		$sortArray[$sortBy]["additionalOrder"] = " ba-".$sortOrder;
		if($tmpSortOrder == "ASC"){
			$sortArray[$tmpSortBy]["url"] = URL::route('search_hotel').$additionalGETsBefore."&sortBy=".$tmpSortBy."&sortOrder=DESC";
		} else {
			$sortArray[$tmpSortBy]["url"] = URL::route('search_hotel').$additionalGETsBefore."&sortBy=".$tmpSortBy."&sortOrder=ASC";
		}

		$data['priceUrl'] = URL::route('search_hotel')."?page=1"
															 .($offerTypes != 0 ? "&offerTypes=".$offerTypes : "")
															 .($mealPlans != 0 ? "&mealPlans=".$mealPlans : "")
															 .($transportType != "" ? "&transportType=".$transportType : "")
															 .($locationFiltering != "base" ? "&locationFiltering=".$locationFiltering : "")
							 								 .($locationId != 0 ? "&locationId=".$locationId : "")
															 .($stars != 0 ? "&stars=".$stars : "")
							 								 .($categoryId != 0 ? "&categoryId=".$categoryId : "")
								  							 .($sortBy != "" ? "&sortBy=".$sortBy: "")
							 	  							 .($sortOrder != "" ? "&sortOrder=".$sortOrder: "");

			
		//$hotels['hotels'] = empty($hotelsFiltered) ? $hotelsFiltered : array_chunk(array_values($hotelsFiltered),10,true)[$page-1];

		$data['hotels'] = $hotels['hotels'];
		$data['noHotels'] = $hotels['noHotels'];
		$data['sort'] = $sortArray;
		//dd($hotels);
		$data['minPrice'] = isset($hotels['minPrice']) == false ? 0 : $hotels['minPrice'];//min($hotels['minPrice']);
		$data['maxPrice'] = isset($hotels['maxPrice']) == false ? 0 : $hotels['maxPrice'];//max($hotels['maxPrice']);
		$data['leftPrice'] = $priceFrom == 0 ? $data['minPrice'] : $priceFrom;
		$data['rightPrice'] = $priceTo == 0 ? $data['maxPrice'] : $priceTo;
		$data['mealPlans'] = $mealPlan;
		$data['stars'] = $starsOutputArray;
		$data['pageTitle'] = 'Hoteluri';
		$data['pageNote'] = 'Hoteluri';
		$data['pageMetakey'] = 'Hoteluri';
		$data['pageMetadesc'] = 'Hoteluri';

		$data['pages'] = 'pages.'.CNF_THEME.'.offers.hotel_search';
		$page = 'layouts.'.CNF_THEME.'.index';
		return view($page,$data);
		
	}

	public function filterItems($hotels,$criteria){
		//dd($criteria,$hotels);
		$so=[];
		$st=[];
		$pr=[];
		if($criteria['sortOrder'] == "ASC"){
			if($criteria["mealPlans"] != 0){
				foreach($hotels as $k=>$v){
					if(in_array($criteria["mealPlans"],$v->meal_plans_ids)){
						$so[$k]=$v;
					}
				}
			}else{
				$so = $hotels;	
			}

			if($criteria["stars"] != 0){
				foreach($so as $k=>$v){
					if($criteria["stars"] == $v->stars){
						$st[$k]=$v;
					}
				}
			}else{
				$st=$so;
			}

			if($criteria['priceFrom'] != 0 || $criteria['priceTo'] != 0){
				foreach($st as $k=>$v){
					if($v->min_price >= $criteria['priceFrom'] && $v->min_price <= $criteria['priceTo']){
						$pr[$k]=$v;
					}
				}
			}else{
				$pr=$st;
			}

			if($criteria['sortBy'] == "price"){
				usort($pr,function($a,$b){
					return $a->min_price < $b->min_price? -1 : 1;
				});
			}elseif($criteria['sortBy'] == "name"){
				usort($pr,function($a,$b){
					return $a->hotel_name < $b->hotel_name ? -1 : 1;
				});
			}else{
				usort($pr,function($a,$b){
					return $a->updateOn < $b->updateOn? -1 : 1;
				});
			}
					
		}else{
			if($criteria["mealPlans"] != 0){
				foreach($hotels as $k=>$v){
					if(in_array($criteria["mealPlans"],$v->meal_plans_ids)){
						$so[$k]=$v;
					}
				}
			}else{
				$so = $hotels;	
			}

			if($criteria["stars"] != 0){
				foreach($so as $k=>$v){
					if($criteria["stars"] == $v->stars){
						$st[$k]=$v;
					}
				}
			}else{
				$st=$so;
			}

			if($criteria['priceFrom'] != 0 || $criteria['priceTo'] != 0){
				foreach($st as $k=>$v){
					if($v->min_price >= $criteria['priceFrom'] && $v->min_price <= $criteria['priceTo']){
						$pr[$k]=$v;
					}
				}
			}else{
				$pr=$st;
			}
			
			if($criteria['sortBy'] == "price"){
				usort($pr,function($a,$b){
					return $a->min_price > $b->min_price? -1 : 1;
				});
			}elseif($criteria['sortBy'] == "name"){
				usort($pr,function($a,$b){
					return $a->hotel_name > $b->hotel_name ? -1 : 1;
				});
			}else{
				usort($pr,function($a,$b){
					return $a->updateOn > $b->updateOn? -1 : 1;
				});
			}
		}
		return array_values($pr);
	}

}
