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
use Cache;
use ET_SoapClient\ET_SoapClient;
use ET_SoapClient\SoapObjects\RoomSoapObject;
use ET_SoapClient\SoapObjects\PackageSearchSoapObject;
use ET_SoapClient\SoapObjects\RoomCategorySoapObject;
use ET_SoapClient\SoapObjects\GeographySoapObject;
use ET_SoapClient\SoapObjects\HotelSoapObject;
use ET_SoapClient\SoapObjects\PackageInfoSoapObject;
use ET_SoapClient\Cache\PricesCached;
use App\Models\Travel\RoomCategory;
use App\Models\Travel\GeographyTemp;
use App\Models\Travel\EtripOperator;
use Config;

class ImportController extends Controller {

	private function send_message($id, $message, $progress){
	    $d = array('message' => $message , 'progress' => $progress);
	    echo "id: $id" . PHP_EOL;
	    echo "data: " . json_encode($d) . PHP_EOL;
	    echo PHP_EOL;
	    ob_flush();
	    flush();
	}

	public function importEtripCurl(){
		$extraSoapClientsConfig = EtripOperator::all();
		$extraSoapClients = array();
		foreach($extraSoapClientsConfig as $extraSoapClientConfig){
			$extraSoapClients[] = new ET_SoapClient($extraSoapClientConfig->id_operator);
		}
		$hotelIds = array();
		foreach($extraSoapClients as $eSOAPClient){
			$hotelIds[$eSOAPClient->id_operator] = HotelSoapObject::saveExtraHotels($eSOAPClient);
			HotelSoapObject::deleteFromDB($hotelIds[$eSOAPClient->id_operator],$eSOAPClient->id_operator);
		}
		$packagesIds = array();
		foreach($extraSoapClients as $eSOAPClient){
			$packagesIds[$eSOAPClient->id_operator] = PackageInfoSoapObject::saveExtraPackages($eSOAPClient);
			PackageInfoSoapObject::deleteFromDB($packagesIds[$eSOAPClient->id_operator],$eSOAPClient->id_operator);
		}
		
		
	}

	public function importEtrip(){

		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		$this->send_message(0, "LOG: Import started\n",0);
		if (!file_exists(public_path()."/images/offers")) {
		    mkdir(public_path()."/images/offers");
		}
		set_time_limit(60*60);
		ini_set('memory_limit','-1');
		$extraSoapClientsConfig = EtripOperator::all();
		$extraSoapClients = array();
		foreach($extraSoapClientsConfig as $extraSoapClientConfig){
			$extraSoapClients[] = new ET_SoapClient($extraSoapClientConfig->id_operator);
		}
		$this->send_message(3, "LOG: Importing hotels\n",20);
		$hotelIds = array();
		foreach($extraSoapClients as $eSOAPClient){
			$hotelIds[$eSOAPClient->id_operator] = HotelSoapObject::saveExtraHotels($eSOAPClient);
			HotelSoapObject::deleteFromDB($hotelIds[$eSOAPClient->id_operator],$eSOAPClient->id_operator);
		}
		$this->send_message(4, "LOG: Hotels import finished successfully\n",50);

		$this->send_message(5, "LOG: Importing packages\n",50);
		$packagesIds = array();
		foreach($extraSoapClients as $eSOAPClient){
			$packagesIds[$eSOAPClient->id_operator] = PackageInfoSoapObject::saveExtraPackages($eSOAPClient);
			PackageInfoSoapObject::deleteFromDB($packagesIds[$eSOAPClient->id_operator],$eSOAPClient->id_operator);
		}
		$this->send_message(6, "LOG: Packages import finished successfully\n",75);

		$this->send_message(7, "LOG: Importing cached prices\n",75);
		foreach($extraSoapClients as $eSOAPClient){
			$priceCaches = new PricesCached($eSOAPClient->id_operator);
			$priceCaches->saveToDB();
		}
		$this->send_message(8, "LOG: Cached prices import finished successfully\n",90);
		Cache::flush();
		$this->send_message(9, "LOG: Import finished successfully\n",100);
		$this->send_message(10, 'TERMINATE',100);
	}

	public function importEtripClient($operatorId){
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		$this->send_message(0, "LOG: Import started\n",0);
		set_time_limit(60*60);
		ini_set('memory_limit','-1');
		$soapClients = Config::get('soapadditional');
		$SOAPClient = new ET_SoapClient($operatorId);

		$this->send_message(1, "LOG: Importing geography\n",0);
		$geography = GeographySoapObject::get($SOAPClient);
		$geography->saveToTempDB("HO");
		$geography->saveToDB();

		foreach($extraSoapClients as $extraSoapClient){
			$eSOAPClient = new $extraSoapClient['soap_client'];
			$geography = GeographySoapObject::get($eSOAPClient);
			$geography->saveToTempDB($eSOAPClient->id_operator);
		}

		foreach($extraSoapClients as $extraSoapClient){
			$eSOAPClient = new $extraSoapClient['soap_client'];
			GeographySoapObject::saveExtraLocations($eSOAPClient);
		}
		$this->send_message(2, "LOG: Geography import finished successfully\n",20);

		$this->send_message(3, "LOG: Importing hotels\n",20);
		$hotelIds = array();
		$hotels = HotelSoapObject::getAll($SOAPClient);
		foreach($hotels as $hotel){
			$idHotel = $hotel->saveToDB();
			$hotelIds['HO'][] = $idHotel;
		}
		foreach($extraSoapClients as $extraSoapClient){
			$eSOAPClient = new $extraSoapClient['soap_client'];
			$hotelIds[$eSOAPClient->id_operator] = HotelSoapObject::saveExtraHotels($eSOAPClient);
		}
		$this->send_message(4, "LOG: Hotels import finished successfully\n",50);

		$this->send_message(5, "LOG: Importing packages\n",50);
		$packagesIds = array();
		$packages = PackageInfoSoapObject::getAll($SOAPClient);
		foreach($packages as $package){
			$idPackage = $package->saveToDB();
			$packagesIds['HO'][] = $idPackage;
		}
		foreach($extraSoapClients as $extraSoapClient){
			$eSOAPClient = new $extraSoapClient['soap_client'];
			$packagesIds[$eSOAPClient->id_operator] = PackageInfoSoapObject::saveExtraPackages($eSOAPClient);
		}
		$this->send_message(6, "LOG: Packages import finished successfully\n",75);

		$this->send_message(7, "LOG: Importing cached prices\n",75);
		$priceCaches = new PricesCached("HO");
		$priceCaches->saveToDB();
		foreach($extraSoapClients as $extraSoapClient){
			$eSOAPClient = new $extraSoapClient['soap_client'];
			$priceCaches = new PricesCached($eSOAPClient->id_operator);
			$priceCaches->saveToDB();
		}
		$this->send_message(8, "LOG: Cached prices import finished successfully\n",90);

		$this->send_message(9, "LOG: Deleting old hotels/packages\n",90);
		HotelSoapObject::deleteFromDB($hotelIds['HO'],"HO");
		foreach($extraSoapClients as $extraSoapClient){
			$eSOAPClient = new $extraSoapClient['soap_client'];
			HotelSoapObject::deleteFromDB($hotelIds[$eSOAPClient->id_operator],$eSOAPClient->id_operator);
		}

		PackageInfoSoapObject::deleteFromDB($packagesIds['HO'],"HO");
		foreach($extraSoapClients as $extraSoapClient){
			$eSOAPClient = new $extraSoapClient['soap_client'];
			PackageInfoSoapObject::deleteFromDB($packagesIds[$eSOAPClient->id_operator],$eSOAPClient->id_operator);
		}
		$this->send_message(10, "LOG: Delete finished successfully\n",99);
		Cache::flush();
		$this->send_message(11, "LOG: Import finished successfully\n",100);
		$this->send_message(12, 'TERMINATE',100);
	}

	public function importView(){
		$data['pages'] = 'pages.'.CNF_THEME.'.travel.import_view';
		$page = 'layouts.'.CNF_THEME.'.index';
		return View::make($page,$data);
	}

}
