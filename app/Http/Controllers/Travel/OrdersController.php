<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\controller;

use App\Models\Travel\Hotel;
use App\Models\Travel\HotelSearchCached;
use App\Models\Travel\Geography;
use App\Models\Travel\MealPlanHotelSearch;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\PackagesSearchCached;
use App\Models\Travel\AskForOffersItem;
use App\Models\Travel\BookingPackageSearch;
use App\Models\Travel\BookingHotelSearch;

use App\Models\Travel\Eloquent\RezervarePlataEloquent;
use App\Models\Travel\Eloquent\RezervarePlataClientiEloquent;
use App\Models\Travel\Eloquent\RezervarePlataPSignEloquent;
use App\Models\Travel\Eloquent\PlataOnlineRomcard;

use App\Models\Travel\FareType;

use App\Models\Travel\MealPlan;
use App\Models\Travel\MealPlanPackageSearch;

use Mail;
use App;
use DB;
use URL;
use View;
//use HO_SoapClient\SoapObjects\RoomSoapObject;
//use HO_SoapClient\SoapObjects\PackageSearchSoapObject;
//use HO_SoapClient\SoapObjects\RoomCategorySoapObject;

use ET_SoapClient\ET_SoapClient;
use ET_SoapClient\SoapObjects\PackageSearchSoapObject;
use ET_SoapClient\SoapObjects\HotelSearchSoapObject;
use ET_SoapClient\SoapObjects\BookRequestSoapObject;
use ET_SoapClient\SoapObjects\PaxInfoSoapObject;
use ET_SoapClient\SoapObjects\BookOptionHotelSoapObject;
use ET_SoapClient\SoapObjects\ClientSoapObject;

use App\Models\Travel\RoomCategory;
use Config;
use Validator;
use Input;
use Response;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Session;


class OrdersController extends Controller {

	const M_CASH = 0;
	const M_PAYPAL = 1;
	const M_PAYU = 2;

	public function order_package($bookingPackageSearchId){
		$bookingPackageSearch = BookingPackageSearch::find($bookingPackageSearchId);
		date_default_timezone_set("UTC");
		$elapsedTime = time() - $bookingPackageSearch->created_at->timestamp;
		if($elapsedTime > 15*60){
			return redirect('/error/order_expired')->with('message',URL::previous());
		}
		$data['bookingPackageSearch'] = $bookingPackageSearch;
		$rooms = json_decode($bookingPackageSearch->rooms);
		$noRooms = count($rooms);
		$noAdults = 0;
		$noKids = 0;
		foreach ($rooms as $room) {
			$noAdults += $room->Adults;
			$noKids += $room->ChildAges == 0 ? 0 : count($room->ChildAges);
		}
		$data["noRooms"] = $noRooms;
		$data["noAdults"] = $noAdults;
		$data["noKids"] = $noKids;
		$data["rooms"] = $rooms;
		$data["bookingPackageSearchId"] = $bookingPackageSearchId;
		$data["mobilpayFormUrl"] = Config::get('mobilpay.formUrl');
		$data['pageTitle'] = "PayOrder";
		$data['pageMetakey'] = "PayOrder";
		$data['pageMetadesc'] = "PayOrder";
		$data['pages'] = 'pages.'.CNF_THEME.'.order.test';//'pages.'.CNF_THEME.'.order.order_package';//order.order_package
		$page = 'layouts.'.CNF_THEME.'.index';
		//dd($data);
		return view($page,$data);
	}

	public function order_hotel($bookingHotelSearchId){
		$bookingHotelSearch = BookingHotelSearch::find($bookingHotelSearchId);
		date_default_timezone_set("UTC");
		$elapsedTime = time() - $bookingHotelSearch->created_at->timestamp;
		if($elapsedTime > 15*60){
			return redirect('/error/order_expired')->with('message',URL::previous());
		}
		$data['bookingHotelSearch'] = $bookingHotelSearch;
		$rooms = json_decode($bookingHotelSearch->rooms);
		$noRooms = count($rooms);
		$noAdults = 0;
		$noKids = 0;
		foreach ($rooms as $room) {
			$noAdults += $room->Adults;
			$noKids += $room->ChildAges == 0 ? 0 : count($room->ChildAges);
		}
		$data["noRooms"] = $noRooms;
		$data["noAdults"] = $noAdults;
		$data["noKids"] = $noKids;
		$data["rooms"] = $rooms;
		$data["bookingHotelSearchId"] = $bookingHotelSearchId;
		$data["mobilpayFormUrl"] = Config::get('mobilpay.formUrl');
		$data['pageTitle'] = "PayOrder";
		$data['pageMetakey'] = "PayOrder";
		$data['pageMetadesc'] = "PayOrder";
		$data['pages'] = 'pages.'.CNF_THEME.'.order.order_hotel';//order.order_hotel
		$page = 'layouts.'.CNF_THEME.'.index';
		return View::make($page,$data);
	}

	

	public function validate_package($bookingPackageSearchId){
		$bookingPackageSearch = BookingPackageSearch::find($bookingPackageSearchId);
		date_default_timezone_set("UTC");
		$elapsedTime = time() - $bookingPackageSearch->created_at->timestamp;
		if($elapsedTime > 15*60){
			session()->flash('message','/');
			return response()->json(array('status' => 'ERROR', 'type' => 'E_EXPIRED','URL' => route('error.page','order_expired')));
		}
		$rules = array(
					"contact-person" => "required",
					"payment-method" => "required",
					"payment-lname" => "required",
					"payment-fname" => "required",
					"payment-address" => "required",
					"payment-city" => "required",
					"payment-zone" => "required",
					"payment-country" => "required",
					"payment-email" => "required|email",
					"payment-phone" => "required",
					"options-tac" => "required",
					"options-confirmare" => "required"
				);
		$messages = array(
						"contact-person.required" => "Selecteaza o persoana de contact.",
						"payment-method.required" => "Selecteaza o metoda de plata.",
						"payment-lname.required" => "Numele este obligatoriu.",
						"payment-fname.required" => "Prenumele este obligatoriu.",
						"payment-address.required" => "Adresa este obligatorie.",
						"payment-city.required" => "Orasul este obligatoriu.",
						"payment-zone.required" => "Judetul/Sectorul este obligatoriu.",
						"payment-country.required" => "Tara este obligatorie.",
						"payment-email.required" => "Emailul este obligatoriu.",
						"payment-email.email" => "Emailul trebuie sa fie valid.",
						"payment-phone.required" => "Telefonul este obligatoriu.",
						"options-tac.required" => "Cititi conditiile si termenii de comercializare a produselor.",
						"options-confirmare" => "Confirmare este obligatorie.",
						"company-name.required" => "Numele firmei este obligatoriu.",
						"company-address.required" => "Adresa firmei este obligatorie.",
						"company-city.required" => "Orasul firmei este obligatoriu.",
						"company-zone.required" => "Judetul/Sectorul firmei este obligatoriu.",
						"company-country.required" => "Tara firmei este obligatorie.",
						"company-nrc.required" => "Numarul din registrul comertului este obligatoriu.",
						"company-cui.required" => "CUI-ul firmei este obligatoriu.",
						"company-bank-account.required" => "Contul bancar al firmei este obligatoriu.",
						"company-bank.required" => "Numele banci este obligatoriu."
					);
		$rooms = json_decode($bookingPackageSearch->rooms);
		foreach($rooms as $i => $room){
			for($j = 1; $j <= $room->Adults; $j++){
				$adult = "c".($i+1)."-a".$j;
				$rules[$adult."-lname"] = "required";
				$rules[$adult."-fname"] = "required";
				$rules[$adult."-gender"] = "in:1,2";
				$rules[$adult."-birthdate"] = "required";
				$messages[$adult."-lname.required"] = "Numele este obligatoriu.";
				$messages[$adult."-fname.required"] = "Prenumele este obligatoriu.";
				$messages[$adult."-gender.in"] = "Campul sex este obligatoriu.";
				$messages[$adult."-birthdate.required"] = "Data nasterii este obligatorie.";
			}
			if($room->ChildAges != 0){
				for($j = 1; $j <= count($room->ChildAges); $j++){
					$children = "c".($i+1)."-c".$j;
					$rules[$children."-lname"] = "required";
					$rules[$children."-fname"] = "required";
					$rules[$children."-gender"] = "in:1,2";
					$rules[$children."-birthdate"] = "required";
					$messages[$children."-lname.required"] = "Numele este obligatoriu.";
					$messages[$children."-fname.required"] = "Prenumele este obligatoriu.";
					$messages[$children."-gender.in"] = "Campul sex este obligatoriu.";
					$messages[$children."-birthdate.required"] = "Data nasterii este obligatorie.";
				}
			}
		}

		$validator = Validator::make(Input::all(),$rules,$messages);
		$validator->sometimes('company-name', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		$validator->sometimes('company-address', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		$validator->sometimes('company-city', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		$validator->sometimes('company-zone', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		$validator->sometimes('company-country', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		$validator->sometimes('company-nrc', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		$validator->sometimes('company-cui', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		$validator->sometimes('company-bank-account', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		$validator->sometimes('company-bank', 'required', function($input)
		{
		    return Input::get('options-ff');
		});

		$validator->sometimes('company-bank-account', 'required', function($input)
		{
		    return Input::get('options-ff');
		});
		if ($validator->fails()){
			return response()->json(array('status' => 'ERROR', 'type' => 'E_VALIDATION' , 'messages' => $validator->messages()));
		} else {
			
				
				if(Input::get('paytype') != 'Romcard'){
					$persons=[];
					$formdata=[];

					foreach(Input::all() as $k=>$v){
						switch ($k) {
							case substr($k,0,1) == 'c' && substr($k,3,-8) == 'a':
									$persons[$k]=$v;
								break;

							case substr($k,0,1) == 'c' && substr($k,3,-7) == 'a':
									$persons[$k]=$v;
								break;

							case substr($k,0,1) == 'c' && substr($k,3,-11) == 'a':
									$persons[$k]=$v;
								break;

							default:
									$formdata[$k]=$v;
								break;
						}
					}

					$rezType='';
					if($formdata['paytype'] == 'Offer'){$rezType = 0;}elseif($formdata['paytype'] == 'OP'){$rezType = 2;}

					$rezervare = new RezervarePlataEloquent();
					$rezervare->id_bookedpackage = $formdata["packageid"];
					$rezervare->suma = $formdata['suma'];
					$rezervare->payment_type = (!isset($formdata['payment-type'])?'EUR':$formdata['payment-type']);
					$rezervare->lname = $formdata["payment-lname"];
					$rezervare->fname = $formdata["payment-fname"];
					$rezervare->address = $formdata["payment-address"];
					$rezervare->city = $formdata["payment-city"];
					$rezervare->zone = $formdata["payment-zone"];
					$rezervare->country = $formdata["payment-country"];
					$rezervare->email = $formdata["payment-email"];
					$rezervare->phone = $formdata["payment-phone"];
					$rezervare->company_name = $formdata["company-name"];
					$rezervare->company_address = $formdata["company-address"];
					$rezervare->company_city = $formdata["company-city"];
					$rezervare->company_zone = $formdata["company-zone"];
					$rezervare->company_country = $formdata["company-country"];
					$rezervare->company_nrc = $formdata["company-nrc"];
					$rezervare->company_cui = $formdata["company-cui"];
					$rezervare->company_bank_account = $formdata["company-bank-account"];
					$rezervare->company_bank = $formdata["company-bank"];
					$rezervare->bookpackage_search = $formdata["bookingpack"];
					$rezervare->options_newsletter = (isset($formdata["options-newsletter"])?$formdata["options-newsletter"]:0);
					$rezervare->soap_client = $formdata["soap_client"];
					$rezervare->rezervare = $rezType;
					$rezervare->hotel = $formdata['hotel'];
					$rezervare->paytype = $formdata['paytype'];
					$rezervare->achitat = 0;
					$rezervare->refuzat = 0;
					$rezervare->save();


					foreach(array_chunk($persons,4) as $v){
						$RClient = new RezervarePlataClientiEloquent();
						$RClient->rezervare_id = $rezervare->id;
						$RClient->gender = $v[0];
						$RClient->lname =$v[1];
						$RClient->fname =$v[2];
						$RClient->birthdate =$v[3];
						$RClient->save();
					}

					if(Input::get('payment-method') == self::M_CASH){
						try{
							$response = $this->book(Input::all(),$bookingPackageSearch);
						} catch (SoapFault $ex) {
							//dd('break');
							session()->flash('message','/');
							return response()->json(array('status' => 'ERROR', 'type' => 'E_SOAP' , 'URL' => route('error.page','order_expired') , 'message' => $ex->getMessage()));
						}
						$rooms = json_decode($bookingPackageSearch->rooms);
						$noRooms = count($rooms);
						$noAdults = 0;
						$noKids = 0;
						foreach ($rooms as $room) {
							$noAdults += $room->Adults;
							$noKids += $room->ChildAges == 0 ? 0 : count($room->ChildAges);
						}
						$hotel = $bookingPackageSearch->id_hotel;
						$transport = "00";
						$guests = $noRooms.' x '.$bookingPackageSearch->room_category.' pentru '.($noAdults == 1 ? "1 Adult" : $noAdults. " Adulti").($noKids == 0 ? "" : ($noKids == 1 ? " si 1 Copil" : " si ".$noKids." Copii"));
						$transport = "Individual";
						$this->sendMail($response,Input::all(),null);
					}else {
					
						return Response::json(array('status' => 'NOT_IMPLEMENTED'));
					}
					session(['2perfomant_data'=>['r'=>$response,'type'=>1]]);
					return response()->json(array('status' => 'SUCCESS', 'type' => self::M_CASH, 'URL' => route('order.thankyou')));
				}else{
					session(["bookingSearch"=>$bookingPackageSearch]);
					
					return response()->json(array('status' => 'SUCCESS', 'type' => self::M_CASH, 'URL' => route('order.thankyou')));
				}

				session(['2perfomant_data'=>['r'=>$response,'type'=>1]]);
			
		}
	}

	public function validate_hotel($bookingHotelSearchId){

		$bookingHotelSearch = BookingHotelSearch::find($bookingHotelSearchId);
		date_default_timezone_set("UTC");
		$elapsedTime = time() - $bookingHotelSearch->created_at->timestamp;
		if($elapsedTime > 15*60){
			session()->flash('message','/');
			return Response::json(array('status' => 'ERROR', 'type' => 'E_EXPIRED','URL' => route('error.404','order_expired')));
		}
		$rules = array(
					"contact-person" => "required",
					"payment-method" => "required",
					"payment-lname" => "required",
					"payment-fname" => "required",
					"payment-address" => "required",
					"payment-city" => "required",
					"payment-zone" => "required",
					"payment-country" => "required",
					"payment-email" => "required|email",
					"payment-phone" => "required",
					"options-tac" => "required"
				);
		$messages = array(
						"contact-person.required" => "Selecteaza o persoana de contact.",
						"payment-method.required" => "Selecteaza o metoda de plata.",
						"payment-lname.required" => "Numele este obligatoriu.",
						"payment-fname.required" => "Prenumele este obligatoriu.",
						"payment-address.required" => "Adresa este obligatorie.",
						"payment-city.required" => "Orasul este obligatoriu.",
						"payment-zone.required" => "Judetul/Sectorul este obligatoriu.",
						"payment-country.required" => "Tara este obligatorie.",
						"payment-email.required" => "Emailul este obligatoriu.",
						"payment-email.email" => "Emailul trebuie sa fie valid.",
						"payment-phone.required" => "Telefonul este obligatoriu.",
						"options-tac.required" => "Cititi conditiile si termenii de comercializare a produselor.",
						"company-name.required" => "Numele firmei este obligatoriu.",
						"company-address.required" => "Adresa firmei este obligatorie.",
						"company-city.required" => "Orasul firmei este obligatoriu.",
						"company-zone.required" => "Judetul/Sectorul firmei este obligatoriu.",
						"company-country.required" => "Tara firmei este obligatorie.",
						"company-nrc.required" => "Numarul din registrul comertului este obligatoriu.",
						"company-cui.required" => "CUI-ul firmei este obligatoriu.",
						"company-bank-account.required" => "Contul bancar al firmei este obligatoriu.",
						"company-bank.required" => "Numele banci este obligatoriu."
					);
		$rooms = json_decode($bookingHotelSearch->rooms);
		foreach($rooms as $i => $room){
			for($j = 1; $j <= $room->Adults; $j++){
				$adult = "c".($i+1)."-a".$j;
				$rules[$adult."-lname"] = "required";
				$rules[$adult."-fname"] = "required";
				$rules[$adult."-gender"] = "in:1,2";
				$rules[$adult."-birthdate"] = "required";
				$messages[$adult."-lname.required"] = "Numele este obligatoriu.";
				$messages[$adult."-fname.required"] = "Prenumele este obligatoriu.";
				$messages[$adult."-gender.in"] = "Campul sex este obligatoriu.";
				$messages[$adult."-birthdate.required"] = "Data nasterii este obligatorie.";
			}
			if($room->ChildAges != 0){
				for($j = 1; $j <= count($room->ChildAges); $j++){
					$children = "c".($i+1)."-c".$j;
					$rules[$children."-lname"] = "required";
					$rules[$children."-fname"] = "required";
					$rules[$children."-gender"] = "in:1,2";
					$rules[$children."-birthdate"] = "required";
					$messages[$children."-lname.required"] = "Numele este obligatoriu.";
					$messages[$children."-fname.required"] = "Prenumele este obligatoriu.";
					$messages[$children."-gender.in"] = "Campul sex este obligatoriu.";
					$messages[$children."-birthdate.required"] = "Data nasterii este obligatorie.";
				}
			}
		}

		$validator = Validator::make(Input::all(),$rules,$messages);
		$validator->sometimes('company-name', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		$validator->sometimes('company-address', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		$validator->sometimes('company-city', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		$validator->sometimes('company-zone', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		$validator->sometimes('company-country', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		$validator->sometimes('company-nrc', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		$validator->sometimes('company-cui', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		$validator->sometimes('company-bank-account', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		$validator->sometimes('company-bank', 'required', function($input)
		{
				return Input::get('options-ff');
		});
		if ($validator->fails()){
			return Response::json(array('status' => 'ERROR', 'type' => 'E_VALIDATION' , 'messages' => $validator->messages()));
		} else {
			
				


				if(Input::get('paytype') != 'Romcard'){
					$persons=[];
					$formdata=[];

					foreach(Input::all() as $k=>$v){
						switch ($k) {
							case substr($k,0,1) == 'c' && substr($k,3,-8) == 'a':
									$persons[$k]=$v;
								break;

							case substr($k,0,1) == 'c' && substr($k,3,-7) == 'a':
									$persons[$k]=$v;
								break;

							case substr($k,0,1) == 'c' && substr($k,3,-11) == 'a':
									$persons[$k]=$v;
								break;

							default:
									$formdata[$k]=$v;
								break;
						}
					}

					$rezType='';
					if($formdata['paytype'] == 'Offer'){$rezType = 0;}elseif($formdata['paytype'] == 'OP'){$rezType = 2;}

					$rezervare = new RezervarePlataEloquent();
					$rezervare->id_bookedpackage = $formdata["packageid"];
					$rezervare->suma = $formdata['suma'];
					$rezervare->payment_type = (!isset($formdata['payment-type'])?'EUR':$formdata['payment-type']);
					$rezervare->lname = $formdata["payment-lname"];
					$rezervare->fname = $formdata["payment-fname"];
					$rezervare->address = $formdata["payment-address"];
					$rezervare->city = $formdata["payment-city"];
					$rezervare->zone = $formdata["payment-zone"];
					$rezervare->country = $formdata["payment-country"];
					$rezervare->email = $formdata["payment-email"];
					$rezervare->phone = $formdata["payment-phone"];
					$rezervare->company_name = $formdata["company-name"];
					$rezervare->company_address = $formdata["company-address"];
					$rezervare->company_city = $formdata["company-city"];
					$rezervare->company_zone = $formdata["company-zone"];
					$rezervare->company_country = $formdata["company-country"];
					$rezervare->company_nrc = $formdata["company-nrc"];
					$rezervare->company_cui = $formdata["company-cui"];
					$rezervare->company_bank_account = $formdata["company-bank-account"];
					$rezervare->company_bank = $formdata["company-bank"];
					$rezervare->bookpackage_search = $formdata["bookingpack"];
					$rezervare->options_newsletter = (isset($formdata["options-newsletter"])?$formdata["options-newsletter"]:0);
					$rezervare->soap_client = $formdata["soap_client"];
					$rezervare->rezervare = $rezType;
					$rezervare->hotel = $formdata['hotel'];
					$rezervare->paytype = $formdata['paytype'];
					$rezervare->achitat = 0;
					$rezervare->refuzat = 0;
					$rezervare->save();


					foreach(array_chunk($persons,4) as $v){
						$RClient = new RezervarePlataClientiEloquent();
						$RClient->rezervare_id = $rezervare->id;
						$RClient->gender = $v[0];
						$RClient->lname =$v[1];
						$RClient->fname =$v[2];
						$RClient->birthdate =$v[3];
						$RClient->save();
					}

					if(Input::get('payment-method') == self::M_CASH){
						try{
							$response = $this->book_hotel(Input::all(),$bookingHotelSearch);
						} catch (SoapFault $ex) {
							Session::flash('message','/');
							return Response::json(array('status' => 'ERROR', 'type' => 'E_SOAP' , 'URL' => route('error.page','order_expired') , 'message' => $ex->getMessage()));
						}
						$rooms = json_decode($bookingHotelSearch->rooms);
						$noRooms = count($rooms);
						$noAdults = 0;
						$noKids = 0;
						foreach ($rooms as $room) {
							$noAdults += $room->Adults;
							$noKids += $room->ChildAges == 0 ? 0 : count($room->ChildAges);
						}
						$hotel = $bookingHotelSearch->hotel;
						$transport = "00";
						$guests = $noRooms.' x '.$bookingHotelSearch->room_category.' pentru '.($noAdults == 1 ? "1 Adult" : $noAdults. " Adulti").($noKids == 0 ? "" : ($noKids == 1 ? " si 1 Copil" : " si ".$noKids." Copii"));
						$transport = "Individual";
						$this->sendMail($response,Input::all(),null);
					}else {
						return Response::json(array('status' => 'NOT_IMPLEMENTED'));
					}
					session(['2perfomant_data'=>['r'=>$response,'type'=>1]]);
					
					return response()->json(array('status' => 'SUCCESS', 'type' => self::M_CASH, 'URL' => route('order.thankyou')));
				}else{
					session(["bookingSearch"=>$bookingHotelSearch]);
					// de pus cu alt obiect de valori session(['2perfomant_data'=>['r'=>$response,'type'=>1]]);
					return Response::json(array('status' => 'SUCCESS', 'type' => self::M_CASH, 'URL' => route('order.thankyou')));
				}


				
			
		}
	}

	private function sendToCRM($fname,$lname,$email,$phone,$url,$transport,$roomCat,$mealPlan,$guests,$price,$bookingId,$categ){
	/*	$token = "72Qo143u0Zu1T2yWI310ns5MAR33nFXb";
		$action = "add_ticket";
		$ch= curl_init();
		$data = array( 'token' => $token,
					   'action' => $action,
					   'fname' => $fname,
					   'lname' => $lname,
					   'email' => $email,
					   'phone' => $phone,
					   'url' => $url,
					   'transport' => $transport,
					   'roomCat' => $roomCat,
					   'mealPlan' => $mealPlan,
					   'guests' => $guests,
					   'price' => $price,
					   'bookingId' => $bookingId,
					   'categ' => $categ
					);
		curl_setopt($ch, CURLOPT_URL, 'http://europa.europatravel.ro/api_etrip.php');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$resp = curl_exec($ch);
		curl_close($ch);
		return $resp;*/
	}

	public function thankyou(Request $req){
		/*if(session()->get('booking') || session()->get('booking_input')){
			$data['booking'] = session()->get('booking');
			$data['bookingInput'] = session()->get('booking_input');
		} else if (isset($_GET['orderId'])) {
			$orderId = $_GET['orderId'];
			$mobilpayOrder = MobilpayOrder::find($orderId);
			$data['booking'] = json_decode($mobilpayOrder->booking_response);
			$bookingInput = new ArrayObject(json_decode($mobilpayOrder->input));
			$data['bookingInput'] = $bookingInput->getArrayCopy();
			if(!$mobilpayOrder->status){
				$status = "Pending";
			} else {
				$status = $mobilpayOrder->status;
			}
			$data['status'] = $status;
		} else {
			return redirect('/');
		}*/

		$data['orderT']=(isset($req->order)?true:false);

		$data['formdata'] = session('formdata');
		$data['pageTitle'] = 'Thank you';
		$data['pageNote'] = 'Thank you';
		$data['pageMetakey'] = 'Thank you';
		$data['pageMetadesc'] = 'Thank you';
		$data['pages'] = 'pages.'.CNF_THEME.'.order.thankyou';//order.thankyou
		$page = 'layouts.'.CNF_THEME.'.index';
		session()->forget('formdata');
		return View::make($page,$data);
	}

	private function book($bookingInput,$bookingPackageSearch){
		//dd(json_decode(json_decode($bookingPackageSearch)->rooms));
		$bookingPackageSearch = json_decode($bookingPackageSearch);
		$rooms = json_decode($bookingPackageSearch->rooms);
		$bookingRequest = new BookRequestSoapObject();
		$paxesInfo = array();
		foreach($rooms as $i => $room){
			for($j = 1; $j <= $room->Adults; $j++){
				$adult = "c".($i+1)."-a".$j;
				$paxInfo = new PaxInfoSoapObject();
				$paxInfo->FirstName = $bookingInput[$adult."-fname"];
				$paxInfo->LastName = $bookingInput[$adult."-lname"];
				$paxInfo->Type = "ADT";
				$paxInfo->BirthDate = $bookingInput[$adult."-birthdate"];
				if($bookingInput[$adult."-gender"] == 1){
					$paxInfo->Title = "Dl";
					$paxInfo->Gender  = "Male";
				} else if($bookingInput[$adult."-gender"] == 2){
					$paxInfo->Title = "Dna";
					$paxInfo->Gender  = "Female";
				}
				$paxesInfo[] = $paxInfo;
			}
			if($room->ChildAges != 0){
				for($j = 1; $j <= count($room->ChildAges); $j++){
					$children = "c".($i+1)."-c".$j;
					$paxInfo = new PaxInfoSoapObject();
					$paxInfo->FirstName = $bookingInput[$children."-fname"];
					$paxInfo->LastName = $bookingInput[$children."-lname"];
					$paxInfo->Type = "CHD";
					$paxInfo->BirthDate = $bookingInput[$children."-birthdate"];
					if($bookingInput[$adult."-gender"] == 1){
						$paxInfo->Title = "";
						$paxInfo->Gender  = "Male";
					} else if($bookingInput[$adult."-gender"] == 2){
						$paxInfo->Title = "";
						$paxInfo->Gender  = "Female";
					}
					$paxesInfo[] = $paxInfo;
				}
			}
		}
		//dd(json_decode($bookingPackageSearch));
		$bookingRequest->ResultIndex = $bookingPackageSearch->result_index;
		$bookOptionHotel = new BookOptionHotelSoapObject();
		$bookOptionHotel->MealPlanIndex = $bookingPackageSearch->meal_plan_index;
		$bookingRequest->HotelOptions = $bookOptionHotel;
		//dd($bookingInput);
		if($bookingInput['paytype'] == 'Offer' || $bookingInput['paytype'] == 'OP'){
			$bookingRequest->Status = "quote";
		}else{
			$bookingRequest->Status = "book";
		}
		$bookingRequest->Notes = "";
		$bookingRequest->PaxInfo = $paxesInfo;
		$client = new ClientSoapObject();

		$contactPerson = $bookingInput['contact-person'];
		if($bookingInput[$contactPerson."-gender"] == 1){
			$client->Title = "Dl";
		} else {
			$client->Title = "Dna";
		}
		//$client->BirthDate = $bookingInput[$contactPerson."-birthdate"];
		$client->FirstName = $bookingInput["payment-fname"];
		$client->LastName = $bookingInput["payment-lname"];
		$client->Email = $bookingInput["payment-email"];
		$client->Address1 = $bookingInput["payment-address"];
		$client->AddressCity = $bookingInput["payment-city"];
		$client->Country = $bookingInput["payment-country"];
		$bookingRequest->Client = $client;
		//$SOAPClient = App::make('INF_ISoapClient');
		$SOAPClient = new ET_SoapClient($bookingInput["soap_client"]);
		$soapPackageResults = $SOAPClient->packageSearch(new PackageSearchSoapObject($bookingPackageSearch->is_tour,$bookingPackageSearch->is_flight,$bookingPackageSearch->is_bus,$bookingPackageSearch->departure_point,$bookingPackageSearch->destination,$bookingPackageSearch->id_hotel,
																	  $bookingPackageSearch->check_in,$bookingPackageSearch->duration,null,$rooms,
																	  true,true));
		//$bookingRequest->ClientReference = $bookingInput["payment-lname"] .' '. $bookingInput["payment-fname"];
		//dd($bookingRequest);
		try{
			$response = $SOAPClient->book($bookingRequest);
		} catch(SoapFault $ex){
			throw $ex;
		}

		return $response;
	}

	private function book_hotel($bookingInput,$bookingHotelSearch){
		$rooms = json_decode($bookingHotelSearch->rooms);
		$bookingRequest = new BookRequestSoapObject();
		$paxesInfo = array();
		foreach($rooms as $i => $room){
			for($j = 1; $j <= $room->Adults; $j++){
				$adult = "c".($i+1)."-a".$j;
				$paxInfo = new PaxInfoSoapObject();
				$paxInfo->FirstName = $bookingInput[$adult."-fname"];
				$paxInfo->LastName = $bookingInput[$adult."-lname"];
				$paxInfo->Type = "ADT";
				$paxInfo->BirthDate = $bookingInput[$adult."-birthdate"];
				if($bookingInput[$adult."-gender"] == 1){
					$paxInfo->Title = "Dl";
					$paxInfo->Gender  = "Male";
				} else if($bookingInput[$adult."-gender"] == 2){
					$paxInfo->Title = "Dna";
					$paxInfo->Gender  = "Female";
				}
				$paxesInfo[] = $paxInfo;
			}
			if($room->ChildAges != 0){
				for($j = 1; $j <= count($room->ChildAges); $j++){
					$children = "c".($i+1)."-c".$j;
					$paxInfo = new PaxInfoSoapObject();
					$paxInfo->FirstName = $bookingInput[$children."-fname"];
					$paxInfo->LastName = $bookingInput[$children."-lname"];
					$paxInfo->Type = "CHD";
					$paxInfo->BirthDate = $bookingInput[$children."-birthdate"];
					if($bookingInput[$adult."-gender"] == 1){
						$paxInfo->Title = "";
						$paxInfo->Gender  = "Male";
					} else if($bookingInput[$adult."-gender"] == 2){
						$paxInfo->Title = "";
						$paxInfo->Gender  = "Female";
					}
					$paxesInfo[] = $paxInfo;
				}
			}
		}
		$bookingRequest->ResultIndex = $bookingHotelSearch->result_index;
		$bookOptionHotel = new BookOptionHotelSoapObject();
		$bookOptionHotel->MealPlanIndex = $bookingHotelSearch->meal_plan_index;
		$bookingRequest->HotelOptions = $bookOptionHotel;
		if($bookingInput['paytype'] == 'Offer' || $bookingInput['paytype'] == 'OP'){
			$bookingRequest->Status = "quote";
		}else{
			$bookingRequest->Status = "book";
		}
		$bookingRequest->PaxInfo = $paxesInfo;
		$bookingRequest->Notes = '';
		$client = new ClientSoapObject();
		$contactPerson = $bookingInput['contact-person'];
		if($bookingInput[$contactPerson."-gender"] == 1){
			$client->Title = "Dl";
		} else {
			$client->Title = "Dna";
		}
		//$client->BirthDate = $bookingInput[$contactPerson."-birthdate"];
		$client->FirstName = $bookingInput["payment-fname"];
		$client->LastName = $bookingInput["payment-lname"];
		$client->Email = $bookingInput["payment-email"];
		$client->Address1 = $bookingInput["payment-address"];
		$client->AddressCity = $bookingInput["payment-city"];
		$client->Country = $bookingInput["payment-country"];
		//Specification of new client not allowed
		$bookingRequest->Client = $client;

		$SOAPClient = new ET_SoapClient($bookingInput["soap_client"]);
		$hotelSearchBooking = new HotelSearchSoapObject($bookingHotelSearch->hotel->location,$bookingHotelSearch->id_hotel,$bookingHotelSearch->check_in,$bookingHotelSearch->duration,null,
												        $rooms,null,false,false,true);
		$soapHotelResults = $SOAPClient->hotelSearch($hotelSearchBooking);

		//$bookingRequest->ClientReference = $bookingInput["payment-lname"] .' '. $bookingInput["payment-fname"];
		//dd($bookingRequest);
		try{
			$response = $SOAPClient->book($bookingRequest);
		} catch(SoapFault $ex){
			throw $ex;
		}
		return $response;
	}

	private function sendMail($booking,$bookingInput,$mobilpayOrderId = null){
		$dataEmail['booking'] = $booking;
		$dataEmail['bookingInput'] = $bookingInput;


		$dataEmail['mobilpayOrderId'] = $mobilpayOrderId;
		
		$bcc = ['office@infora.ro','vacante@helloholidays.ro'];
		
		Mail::send('pages.'.CNF_THEME.'.emails.thankyou_new', $dataEmail, function($message) use ($bookingInput,$booking,$bcc) { 
			$message->from('no-reply@helloholidays.ro', 'HelloHolidays');
			$message->to($bookingInput['payment-email']);
			$message->bcc($bcc);
			$message->subject('Detalii rezervare HelloHolidays #'.(empty($booking) == false?$booking->Reference:''));
		});
		Mail::send('pages.'.CNF_THEME.'.emails.neworder', $dataEmail, function($message) use ($bookingInput,$booking) {
			$message->from('no-reply@helloholidays.ro', 'HelloHolidays');
			$message->to('vacante@helloholidays.ro');
			$message->bcc('office@infora.ro');
			$message->subject('Informatii aditionale rezervarea #'.(empty($booking) == false?$booking->Reference:''));
		});
		
		return true;
	}


	private function createCardOrder($bookingInput,$bookingResponse){
		srand((double) microtime() * 1000000);
		$objPmReqCard 						= new Mobilpay_Payment_Request_Card();
		$objPmReqCard->signature 			= Config::get('mobilpay.signature');
		$objPmReqCard->orderId 				= md5(uniqid(rand()));
		$objPmReqCard->confirmUrl 			= Config::get('mobilpay.confirmUrl');
		$objPmReqCard->returnUrl 			= Config::get('mobilpay.returnUrl');

		$objPmReqCard->invoice = new Mobilpay_Payment_Invoice();
		$objPmReqCard->invoice->currency	= 'EUR';
		$objPmReqCard->invoice->amount		= $bookingResponse->Price->Gross + $bookingResponse->Price->Tax;
		$objPmReqCard->invoice->details		= 'Plata cu card-ul prin mobilPay';

		$billingAddress 				= new Mobilpay_Payment_Address();
		$billingAddress->type			= "person";
		$billingAddress->firstName		= $bookingInput['payment-fname'];
		$billingAddress->lastName		= $bookingInput['payment-lname'];
		$billingAddress->address		= $bookingInput['payment-address'];
		$billingAddress->email			= $bookingInput['payment-email'];
		$billingAddress->mobilePhone	= $bookingInput['payment-phone'];
		$objPmReqCard->invoice->setBillingAddress($billingAddress);

		$shippingAddress 				= new Mobilpay_Payment_Address();
		$shippingAddress->type			= "person";
		$shippingAddress->firstName		= $bookingInput['payment-fname'];
		$shippingAddress->lastName		= $bookingInput['payment-lname'];
		$shippingAddress->address		= $bookingInput['payment-address'];
		$shippingAddress->email			= $bookingInput['payment-email'];
		$shippingAddress->mobilePhone	= $bookingInput['payment-phone'];
		$objPmReqCard->invoice->setShippingAddress($shippingAddress);

		$objPmReqCard->encrypt(Config::get('mobilpay.certificateLocation'));

		$fields = array(
								'env_key' => $objPmReqCard->getEnvKey(),
								'data' => $objPmReqCard->getEncData(),
								'id' => $objPmReqCard->orderId
						);

		return $fields;
	}

	private function sendOrderToDatabase($id,$bookingResponse,$input){
		$mobilpayOrder = new MobilpayOrder();
		$mobilpayOrder->id = $id;
		$mobilpayOrder->id_booking = $bookingResponse->Reference;
		$mobilpayOrder->input = json_encode($input);
		$mobilpayOrder->booking_response = json_encode($bookingResponse);
		$mobilpayOrder->save();
	}

	private function sendPaymentMail($clientMail,$type,$orderId){
		$dataEmail['type'] = $type;
		$dataEmail['orderId'] = $orderId;
		Mail::send('emails.payment_client', $dataEmail, function($message) use ($clientMail, $type, $orderId) {
			$message->from('noreply@holidayoffice.ro', 'Holiday Office');
			$message->to($clientMail);
			$message->cc('office@infora.ro');
			switch($type){
				case "Confirmed":
					$message->subject('Rezervarea #'.$orderId.' a fost confirmata');
				break;
				case "Canceled":
					$message->subject('Rezervarea #'.$orderId.' a fost anulata');
				break;
				case "Rejected":
					$message->subject('Rezervarea #'.$orderId.' a fost respinsa');
				break;
				case "Refunded":
					$message->subject('Rezervarea #'.$orderId.' a fost anulata si banii au fost retituiti');
				break;
				default:
					$message->subject('Test');
			}

		});
		Mail::send('emails.payment_company', $dataEmail, function($message) use ($clientMail, $type, $orderId) {
			$message->from('noreply@holidayoffice.ro', 'Holiday Office');
			$message->to('office@holidayoffice.ro');
			$message->cc('office@infora.ro');
			$message->cc('anca.vladu2014@gmail.com');
			switch($type){
				case "Confirmed":
					$message->subject('Rezervarea #'.$orderId.' a fost confirmata');
				break;
				case "Canceled":
					$message->subject('Rezervarea #'.$orderId.' a fost anulata');
				break;
				case "Rejected":
					$message->subject('Rezervarea #'.$orderId.' a fost respinsa');
				break;
				case "Refunded":
					$message->subject('Rezervarea #'.$orderId.' a fost anulata si banii au fost retituiti');
				break;
				default:
			}
		});
	}

	public function confirmMobilpayOrder(){
		$errorCode 		= 0;
		$errorType		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_NONE;
		$errorMessage	= '';

		if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') == 0)
		{
			if(isset($_POST['env_key']) && isset($_POST['data']))
			{
				$privateKeyFilePath = Config::get('mobilpay.keyLocation');
				try{
				$objPmReq = Mobilpay_Payment_Request_Abstract::factoryFromEncrypted($_POST['env_key'], $_POST['data'], $privateKeyFilePath);
				$errorCode = $objPmReq->objPmNotify->errorCode;
				$mobilpayOrder = MobilpayOrder::find($objPmReq->orderId);
				$clientMail = new ArrayObject(json_decode($mobilpayOrder->input));
				$clientMail = $clientMail['payment-email'];
				$orderId = $mobilpayOrder->id_booking;
					if ($errorCode == "0") {
				    	switch($objPmReq->objPmNotify->action){
				    		case 'confirmed':
								$errorMessage = $objPmReq->objPmNotify->errorMessage;
								$mobilpayOrder->status = "Confirmed";
								$mobilpayOrder->save();
								$this->sendPaymentMail($clientMail,"Confirmed", $orderId);
						    break;
							case 'confirmed_pending':
								$errorMessage = $objPmReq->objPmNotify->errorMessage;
								$mobilpayOrder->status = "Pending";
								$mobilpayOrder->save();
						    break;
							case 'paid_pending':
								$errorMessage = $objPmReq->objPmNotify->errorMessage;
								$mobilpayOrder->status = "Pending";
								$mobilpayOrder->save();
						    break;
							case 'paid':
								$errorMessage = $objPmReq->objPmNotify->errorMessage;
								$mobilpayOrder->status = "Open";
								$mobilpayOrder->save();
						    break;
							case 'canceled':
								$errorMessage = $objPmReq->objPmNotify->errorMessage;
								$mobilpayOrder->status = "Canceled";
								$mobilpayOrder->save();
								$this->sendPaymentMail($clientMail,"Canceled", $orderId);
						    break;
							case 'credit':
								$errorMessage = $objPmReq->objPmNotify->errorMessage;
								$mobilpayOrder->status = "Refunded";
								$mobilpayOrder->save();
								$this->sendPaymentMail($clientMail,"Refunded", $orderId);
						    break;
							default:
								$errorType		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
							    $errorCode 		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_ACTION;
							    $errorMessage 	= 'mobilpay_refference_action paramaters is invalid';
						    break;
				    	}
					} else {
						$errorMessage = $objPmReq->objPmNotify->errorMessage;
						$mobilpayOrder->status = "Rejected";
						$mobilpayOrder->save();
						$this->sendPaymentMail($clientMail,"Rejected", $orderId);
					}
				} catch(Exception $e){
					$errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_TEMPORARY;
					$errorCode		= $e->getCode();
					$errorMessage 	= $e->getMessage();
				}
			}
			else
			{
				$errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
				$errorCode		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_PARAMETERS;
				$errorMessage 	= 'mobilpay.ro posted invalid parameters';
			}
		}
		else
		{
			$errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
			$errorCode		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_METHOD;
			$errorMessage 	= 'invalid request metod for payment confirmation';
		}

		header('Content-type: application/xml');
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		if($errorCode == 0)
		{
			echo "<crc>{$errorMessage}</crc>";
		}
		else
		{
			echo "<crc error_type=\"{$errorType}\" error_code=\"{$errorCode}\">{$errorMessage}</crc>";
		}
	}

	public function ask_for_offer($id){
	
		$askForOffer = AskForOffersItem::where('id','=',$id)->first();
		if($askForOffer == null) return redirect('/error/404');// /error/404
		date_default_timezone_set("UTC");
		$elapsedTime = time() - $askForOffer->created_at->timestamp;
		if($elapsedTime > 60*60){
			return \Redirect::to('/error/ask_for_offer_expired')->with('message',URL::previous());
		}
		$data['askForOffer'] = $askForOffer;
		$rooms = json_decode($askForOffer->rooms);
		$noRooms = count($rooms);
		$noAdults = 0;
		$noKids = 0;
		if($askForOffer->soap_client != "LOCAL"){
			foreach ($rooms as $room) {
				$noAdults += $room->adults;
				if(isset($room->kids)){
					$noKids += count($room->kids);
				}
			}
		}
		$data["noRooms"] = $noRooms;
		$data["noAdults"] = $noAdults;
		$data["noKids"] = $noKids;
		$data["rooms"] = $rooms;
		$data['pageTitle'] = 'Cerere Oferta';
		$data['pageNote'] = 'Cerere Oferta';
		$data['pageMetakey'] = 'Cerere Oferta';
		$data['pageMetadesc'] = 'Cerere Oferta';

		if($askForOffer->id_package != null){
			$data['pages'] = 'pages.'.CNF_THEME.'.ask_for_offer.package';
		} else if($askForOffer->id_hotel != null){
			$data['pages'] = 'pages.'.CNF_THEME.'.ask_for_offer.hotel';
		}

		$page = 'layouts.'.CNF_THEME.'.index';
		//$data['assets'] = TemplateData::setAssets(CNF_THEME);
		return view($page,$data);
	}

	public function ask_for_offer_validate($id){
		$rules = array(
					"fname" => "required",
					"lname" => "required",
					"phone" => "required",
					"email" => "required|email",
				);
		$messages = array(
						"fname.required" => "Prenumele este obligatoriu",
						"lname.required" => "Numele este obligatoriu",
						"phone.required" => "Telefonul este obligatoriu",
						"email.required" => "Email-ul este obligatoriu",
						"email.email" => "Email-ul trebuie sa fie valid."
					);

		$validator = Validator::make(Input::all(),$rules,$messages);
		if ($validator->fails()){
			return Response::json(array('status' => 'ERROR', 'type' => 'E_VALIDATION' , 'messages' => $validator->messages()));
		} else {
			$askForOffer = AskForOffersItem::where('id','=',$id)->first();
			if(empty($askForOffer)) return redirect('/error/404');  // trebuie pusa o alta erorare
			$rooms = json_decode($askForOffer->rooms);
			$noRooms = count($rooms);

			$noAdults = 0;
			$noKids = 0;

			if(is_array($rooms)){
				foreach ($rooms as $room) {
					$noAdults += $room->adults;
					$noKids += !property_exists($room,'kids') ? 0 : count($room->kids);
				}
			}

			if($askForOffer->id_package != null){
				$package = $askForOffer->package;
				if($package->is_tour == 0 && $package->is_bus == 1){
					$transport = "01";
				} else if($package->is_tour == 0 && $package->is_flight == 1){
					$transport = "02";
				} else if($package->is_tour == 0 && $package->is_bus == 0 && $package->is_flight == 0){
					$transport = "03";
				} else if($package->is_tour == 1 && $package->is_bus == 1){
					$transport = "11";
				} else if($package->is_tour == 1 && $package->is_flight == 1){
					$transport = "12";
				} else if($package->is_tour == 1 && $package->is_bus == 0 && $package->is_flight == 0){
					$transport = "13";
				}
				//$url = "http://oferte.europatravel.ro/oferte/tara/".str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->hotel->name)))."_".$transport."_".$package->hotel->id."_".$package->soap_client."_sid0";

				$guests = $noRooms.' x '.$askForOffer->room_category.' pentru '.($noAdults == 1 ? "1 Adult" : $noAdults. " Adulti").($noKids == 0 ? "" : ($noKids == 1 ? " si 1 Copil" : " si ".$noKids." Copii"));
				if($package->is_flight == 1){
					$transport = "Avion";
				} else if($package->is_bus == 1){
					$transport = "Autocar";
				} else {
					$transport = "Individual";
				}
			} else {
				$hotel = $askForOffer->hotel;
				$transport = "00";
				//$url = "http://oferte.europatravel.ro/oferte/tara/".str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$hotel->name)))."_".$transport."_".$hotel->id."_".$hotel->soap_client."_sid0";
				$guests = $noRooms.' x '.$askForOffer->room_category.' pentru '.($noAdults == 1 ? "1 Adult" : $noAdults. " Adulti").($noKids == 0 ? "" : ($noKids == 1 ? " si 1 Copil" : " si ".$noKids." Copii"));
				$transport = "Individual";
			}
			//$this->sendToCRM(Input::get('fname'),Input::get('lname'),Input::get('email'),Input::get('phone'),$url,$transport,$askForOffer->room_category,$askForOffer->meal_plan,$guests,$askForOffer->price,"Nespecificat",14);
			session(['2perfomant_data'=>['r'=>$askForOffer,'type'=>0]]);

			$this->sendMailAskForOffer(Input::all(),$id);

			return response()->json([
					'status' => 'SUCCESS',
					'URL'=>route('order.thankyou')
				]);
		}
	}

	public function sendMailAskForOffer($contactInput,$askForOfferId){
		$askForOffer = AskForOffersItem::find($askForOfferId);
		$curr = \DB::table('cached_prices')->where('id_package','=',$askForOffer->id_package)->where('departure_date','=',$askForOffer->departure_date)->first()->currency;
		$askForOffer->currency = $curr;
		$dataEmail['askForOffer'] = $askForOffer;
		$dataEmail['contactInput'] = $contactInput;
		$rooms = json_decode($askForOffer->rooms);
		$noRooms = count($rooms);
		$noAdults = 0;
		$noKids = 0;
		if(is_array($rooms)){
			foreach ($rooms as $room) {
				$noAdults += $room->adults;
				if(isset($room->kids)){
					$noKids += count($room->kids);
				}
			}
		}
		$dataEmail["noRooms"] = $noRooms;
		$dataEmail["noAdults"] = $noAdults;
		$dataEmail["noKids"] = $noKids;
		
		$dataEmail["soapClientName"] = Config::get('soapadditional.'.$askForOffer->name_operator.'.soap_client');
		
		if($askForOffer->id_package != null){

			Mail::send('pages.'.CNF_THEME.'.emails.askForOfferPackage', $dataEmail, function($message) use ($askForOffer) {
				$message->from('no-reply@luxuriatrans.ro', 'Luxuria Trans');
				$message->to('vacante@luxuriatrans.ro');
				$message->bcc(['office@infora.ro']);
				$message->subject('Cererea de oferta #'.$askForOffer->id);
			});
			Mail::send('pages.'.CNF_THEME.'.emails.askForOfferPackage', $dataEmail, function($message) use ($askForOffer,$contactInput) {
				$message->from('no-reply@luxuriatrans.ro', 'Luxuria Trans');
				$message->to($contactInput['email']);
				$message->subject('Cererea dumneavoastra de oferta.');
			
			});

		} else if($askForOffer->id_hotel != null){
			Mail::send('pages.'.CNF_THEME.'.emails.askForOfferHotel', $dataEmail, function($message) use ($askForOffer) {
				$message->from('no-reply@luxuriatrans.ro', 'Luxuria Trans');
				$message->to('vacante@luxuriatrans.ro');
				$message->bcc(['office@infora.ro']);
				$message->subject('Cererea de oferta #'.$askForOffer->id);
			});
			Mail::send('pages.'.CNF_THEME.'.emails.askForOfferHotel', $dataEmail, function($message) use ($askForOffer,$contactInput) {
				$message->from('no-reply@luxuriatrans.ro', 'Luxuria Trans');
				$message->to($contactInput['email']);
				$message->subject('Cererea dumneavoastra de oferta.');
				
			});
		}

		return true;
	}

	public function savetosession(Request $req){
		Session::put(['formdata'=>$req->all()]);
		if(session()->has('formdata') && session()->get('formdata') != null){
			return response()->json(['savetosession'=>'done']);
		}else{
			return response()->json(['savetosession'=>'err']);
		}
	}

	public function savepaymenttosession(Request $req){
		//session(['payment'=>RezervarePlataEloquent::find($req->id)->toArray()]);
		Session::put(['payment'=>RezervarePlataEloquent::find($req->id)->toArray()]);
		return response()->json(['save'=>true]);
	}

	public function paymentdone(Request $req){
		//dd(RezervarePlataEloquent::find(session()->get('payment')['id']));

		if($req->TRTYPE == 21){
			RezervarePlataEloquent::find(session()->get('payment')['id'])->update([
				'achitat'=>1
			]);
		}else{
			RezervarePlataEloquent::find(session()->get('payment')['id'])->update([
				'refuzat'=>1
			]);
		}

		session()->forget('payment');
		return redirect('/adminorders');
	}

	public function inregistrare_plata_client(Request $req){

		$persons=[];
		$formdata=[];
		$xtrc=[];
		//dd(Session::get('formdata'));
		foreach($req->session()->get('formdata') as $k=>$v){
			switch ($k) {
				case substr($k,0,1) == 'c' && substr($k,3,-8) == 'a':
						$persons[$k]=$v;
					break;

				case substr($k,0,1) == 'c' && substr($k,3,-7) == 'a':
						$persons[$k]=$v;
					break;

				case substr($k,0,1) == 'c' && substr($k,3,-11) == 'a':
						$persons[$k]=$v;
					break;

				case substr($k,0,4) == 'xtrc' && count(explode('_',$k)) == 3:
						array_push($xtrc,$v);
					break;		

				default:
						$formdata[$k]=$v;
					break;
			}
		}
		
		
		//dd($xtrc);
		$rezervare = new RezervarePlataEloquent();
		$rezervare->id_bookedpackage = $formdata["packageid"];
		$rezervare->suma = $formdata['suma'];
		$rezervare->payment_type = $formdata['payment-type'];
		$rezervare->lname = $formdata["payment-lname"];
		$rezervare->fname = $formdata["payment-fname"];
		$rezervare->address = $formdata["payment-address"];
		$rezervare->city = $formdata["payment-city"];
		$rezervare->zone = $formdata["payment-zone"];
		$rezervare->country = $formdata["payment-country"];
		$rezervare->email = $formdata["payment-email"];
		$rezervare->phone = $formdata["payment-phone"];
		$rezervare->company_name = $formdata["company-name"];
		$rezervare->company_address = $formdata["company-address"];
		$rezervare->company_city = $formdata["company-city"];
		$rezervare->company_zone = $formdata["company-zone"];
		$rezervare->company_country = $formdata["company-country"];
		$rezervare->company_nrc = $formdata["company-nrc"];
		$rezervare->company_cui = $formdata["company-cui"];
		$rezervare->company_bank_account = $formdata["company-bank-account"];
		$rezervare->company_bank = $formdata["company-bank"];
		$rezervare->bookpackage_search = $formdata["bookingpack"];
		$rezervare->options_newsletter = $formdata["options-newsletter"];
		$rezervare->soap_client = $formdata["soap_client"];
		$rezervare->rezervare = 1;
		$rezervare->hotel = $formdata['hotel'];
		$rezervare->paytype = $formdata['paytype'];
		$rezervare->achitat = 0;
		$rezervare->refuzat = 0;
		$rezervare->error = ($req->all['MESSAGE'] !== 'Transaction successful' ? 1: 0);
		$rezervare->xtrc = json_encode($xtrc);
		$rezervare->save();

		if($formdata['paytype'] == 'Romcard'){
			$romcard = new PlataOnlineRomcard();
			$romcard->rezervare_id = $rezervare->id;
			$romcard->ORDER = $req->all()["ORDER"];
			$romcard->AMOUNT = $req->all()["AMOUNT"];
			$romcard->message = $req->all()['MESSAGE'];
			$romcard->CURRENCY = $req->all()["CURRENCY"];
			$romcard->RRN = $req->all()["RRN"];
			$romcard->INT_REF = $req->all()["INT_REF"];
			$romcard->TERMINAL = $req->all()["TERMINAL"];
			$romcard->save();
		if($req->all['MESSAGE'] !== 'Transaction successful'){

			return redirect('online_failed/'.$req->all()['MESSAGE']);	
		}
			foreach(array_chunk($persons,4) as $v){
				$RClient = new RezervarePlataClientiEloquent();
				$RClient->rezervare_id = $rezervare->id;
				$RClient->gender = $v[0];
				$RClient->lname =$v[1];
				$RClient->fname =$v[2];
				$RClient->birthdate =$v[3];
				$RClient->save();
			}
		}

		if($formdata['hotel'] == 0){

			$bookingPackageSearch = $req->session()->get('formdata')['bookingpack'];//session()->get("bookingSearch");
			try{
				$response = $this->book(session()->get('formdata'),$bookingPackageSearch);
			} catch (SoapFault $ex) {
				session()->flash('message','/');
				return response()->json(array('status' => 'ERROR', 'type' => 'E_SOAP' , 'URL' => route('error.page','order_expired') , 'message' => $ex->getMessage()));
			}

			$bookingPackageSearch = json_decode($bookingPackageSearch);

			$rooms = json_decode($bookingPackageSearch->rooms);
			$noRooms = count($rooms);
			$noAdults = 0;
			$noKids = 0;
			foreach ($rooms as $room) {
				$noAdults += $room->Adults;
				$noKids += $room->ChildAges == 0 ? 0 : count($room->ChildAges);
			}
			$hotel = $bookingPackageSearch->id_hotel;
			$transport = "00";
			$guests = $noRooms.' x '.$bookingPackageSearch->room_category.' pentru '.($noAdults == 1 ? "1 Adult" : $noAdults. " Adulti").($noKids == 0 ? "" : ($noKids == 1 ? " si 1 Copil" : " si ".$noKids." Copii"));
			$transport = "Individual";
		}else{
			$bookingHotelSearch = $req->session()->get('formdata')['bookingpack'];//session()->get("bookingSearch");
			try{
				$response = $this->book_hotel(session()->get('formdata'),$bookingHotelSearch);
			} catch (SoapFault $ex) {
				Session::flash('message','/');
				return Response::json(array('status' => 'ERROR', 'type' => 'E_SOAP' , 'URL' => route('error.page','order_expired') , 'message' => $ex->getMessage()));
			}
			$rooms = json_decode($bookingHotelSearch->rooms);
			$noRooms = count($rooms);
			$noAdults = 0;
			$noKids = 0;
			foreach ($rooms as $room) {
				$noAdults += $room->Adults;
				$noKids += $room->ChildAges == 0 ? 0 : count($room->ChildAges);
			}
			$hotel = $bookingHotelSearch->hotel;
			$transport = "00";
			$guests = $noRooms.' x '.$bookingHotelSearch->room_category.' pentru '.($noAdults == 1 ? "1 Adult" : $noAdults. " Adulti").($noKids == 0 ? "" : ($noKids == 1 ? " si 1 Copil" : " si ".$noKids." Copii"));
			$transport = "Individual";
		}
	//	dd($response,session()->get('formdata'));
		$this->sendMail($response,session()->get('formdata'),null);
		session()->forget("bookingSearch");
		session()->forget("formdata");
		session(['2perfomant_data'=>['r'=>$response,'type'=>1]]);
		return redirect('/rezerva/thankyou?order=1');

	}

	public function confirmareplata(Request $req){
		//dd($req->all());
		if($req->ACTION == 0){
			$data['pageTitle'] = 'ConfirmarePlata';
			$data['pageNote'] = 'ConfirmarePlata';
			$data['pageMetakey'] = 'ConfirmarePlata';
			$data['pageMetadesc'] = 'ConfirmarePlata';

			$data['pages'] = 'pages.'.CNF_THEME.'.order.confirmpay';
			$data['details'] = $req->all();
			$page = 'layouts.'.CNF_THEME.'.index';
			return view($page,$data);
		}else{
			dd('err');
		}


	}

	public function finalizareplata(Request $req){
		$data['pageTitle'] = 'Step3';
		$data['pageNote'] = 'Step3';
		$data['pageMetakey'] = 'Step3';
		$data['pageMetadesc'] = 'Step3';

		$data['pages'] = 'pages.'.CNF_THEME.'.order.step3';
		$data['details'] = $req->all();
		$page = 'layouts.'.CNF_THEME.'.index';
		//dd($req->all());
		return view($page,$data);
	}

	public function online_error(Request $req){
		$data['pageTitle'] = 'Error';
		$data['pageNote'] = 'Error';
		$data['pageMetakey'] = 'Error';
		$data['pageMetadesc'] = 'Error';
		$data['pages'] = 'pages.'.CNF_THEME.'.order.online_error_2';
		$page = 'layouts.'.CNF_THEME.'.index';
		return view($page,$data)->with('message',$req->type);
		 		/*if($req->type == 1){
			$data['pages'] = 'pages.'.CNF_THEME.'.order.online_error';
		}else{
			$data['pages'] = 'pages.'.CNF_THEME.'.order.online_error_refused';
		}
		$page = 'layouts.'.CNF_THEME.'.index';
		return view($page,$data);*/
	}


}
