<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelBindable;
use ET_SoapClient\ET_SoapClient;
use App\Models\Travel\Hotel;
use DB;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\DepartureDate;
use App\Models\Travel\DeparturePoint;
use App\Models\Travel\FareType;
use App\Models\Travel\Geography;


class PackageInfoSoapObject extends INF_SoapObjectModelBindable{
	
	public $Id;
	public $Name;
	public $IsTour;
	public $IsBus;
	public $IsFlight;
	public $Duration;
	public $Hotel;
	public $DepartureDates;
	public $DeparturePoints;
	public $PriceSets;
	public $DetailedDescriptions;
	public $FareType;
	public $OutboundTransportDuration;
	public $Description;
	public $Destination;
	public $IncludedServices;
	public $NotIncludedServices;

	public static function getAll($SOAPClient){

		return $SOAPClient->getPackages();
	}

	/*
	 *	INF_SoapObjectModelBindable methods
	 */

	protected function setModelClass(){

		$this->model = "PackageInfo";

	}

	public function saveToDB($foreignKeys = null){
		$check = PackageInfo::where('id','=',$this->Id)->where('soap_client','=','HO')->first();
		if($check == null){
			$package = new PackageInfo;
		} else {
			$package = $check;
		}
		$package->id = $this->Id;
		$package->name = $this->Name;
		$package->is_tour = $this->IsTour;
		$package->is_bus = $this->IsBus;
		$package->is_flight = $this->IsFlight;
		$package->duration = $this->Duration;
		$package->id_hotel = $this->Hotel;
		$package->outbound_transport_duration = $this->OutboundTransportDuration;
		$package->description = $this->Description;
		$package->destination = $this->Destination;
		$package->included_services = $this->IncludedServices;
		$package->not_included_services = $this->NotIncludedServices;
		$package->soap_client = 'HO';
		$package->save();
		DepartureDate::where('id_package',$this->Id)->where('soap_client','=','HO')->delete();
		foreach($this->DepartureDates as $departureDate){
			$tmp = new DepartureDate;
			$tmp->id_package = $this->Id;
			$tmp->departure_date = $departureDate;
			$tmp->soap_client = 'HO';
			$tmp->save();
		}
		DeparturePoint::where('id_package',$this->Id)->where('soap_client','=','HO')->delete();
		foreach($this->DeparturePoints as $departurePoint){
			$tmp = new DeparturePoint;
			$tmp->id_package = $this->Id;
			$tmp->id_geography = $departurePoint;
			$tmp->soap_client = 'HO';
			$tmp->save();
		}
		DB::statement("DELETE FROM packages_price_sets WHERE id_package = {$this->Id} AND soap_client = 'HO'");
		foreach($this->PriceSets as $priceSet){
			$priceSet->saveToDB(array('soapClient' => 'HO'));
			DB::statement("INSERT INTO packages_price_sets VALUES ($this->Id,$priceSet->Id,'HO')");
		}
		foreach($this->DetailedDescriptions as $detailedDescription){
			$detailedDescription->saveToDB(array('packageId' => $this->Id, 'soapClient' => 'HO'));
		}
		DB::statement("DELETE FROM packages_fare_types WHERE id_package = {$this->Id} AND soap_client = 'HO'");
		foreach($this->FareType as $fareType){
			$checkFareType = FareType::where("name" , "LIKE", "%{$fareType}%")->first();
			if($checkFareType == null){
				$tmp = new FareType;
				$tmp->name = $fareType;
				$tmp->save();
			} else {
				$tmp = $checkFareType;
			}
			DB::statement("INSERT INTO packages_fare_types VALUES ($this->Id,$tmp->id,'HO')");
		}
		return $this->Id;
	}

	public static function saveExtraPackages($soapClient){
		$packages = PackageInfoSoapObject::getAll($soapClient);
		$packageIds = array();
		foreach($packages as $package){
			$idPackage = $package->saveExtraToDB($soapClient);
			if($idPackage != 0) $packageIds[] = $idPackage;
		}
		return $packageIds;
	}

	public function saveExtraToDB($soapClient){
		$checkHotel = Hotel::where('id','=',$this->Hotel)->where('soap_client','=',$soapClient->id_operator)->first();
		if($checkHotel == null) return 0;
		$newGeography = Geography::whereRaw('availability LIKE "%'.$soapClient->id_operator.':'.$this->Destination.'%"')->first();
		if($newGeography == null) return 0;
		$check = PackageInfo::where('id','=',$this->Id)->where('soap_client','=',$soapClient->id_operator)->first();
		if($check == null){
			$package = new PackageInfo;
		} else {
			$package = $check;
		}
		$package->id = $this->Id;
		$package->name = $this->Name;
		$package->is_tour = $this->IsTour;
		$package->is_bus = $this->IsBus;
		$package->is_flight = $this->IsFlight;
		$package->duration = $this->Duration;
		$package->id_hotel = $this->Hotel;
		$package->outbound_transport_duration = $this->OutboundTransportDuration;
		$package->description = $this->Description;
		$package->destination = $newGeography->id;
		$package->included_services = $this->IncludedServices;
		$package->not_included_services = $this->NotIncludedServices;
		$package->soap_client = $soapClient->id_operator;
		$package->save();
		DepartureDate::where('id_package',$this->Id)->where('soap_client','=',$soapClient->id_operator)->delete();
		foreach($this->DepartureDates as $departureDate){
			$tmp = new DepartureDate;
			$tmp->id_package = $this->Id;
			$tmp->departure_date = $departureDate;
			$tmp->soap_client = $soapClient->id_operator;
			$tmp->save();
		}
		DeparturePoint::where('id_package',$this->Id)->where('soap_client','=',$soapClient->id_operator)->delete();
		foreach($this->DeparturePoints as $departurePoint){
			$tmp = new DeparturePoint;
			$tmp->id_package = $this->Id;
			$newGeography = Geography::whereRaw('availability LIKE "%'.$soapClient->id_operator.':'.$departurePoint.'%"')->first();
			$tmp->id_geography = ($newGeography != null) ? $newGeography->id : 0;
			$tmp->soap_client = $soapClient->id_operator;
			if($newGeography != null) $tmp->save();
		}
		DB::statement("DELETE FROM packages_price_sets WHERE id_package = {$this->Id} AND soap_client = '".$soapClient->id_operator."'");
		foreach($this->PriceSets as $priceSet){
			$priceSet->saveToDB(array('soapClient' => $soapClient->id_operator));
			DB::statement("INSERT INTO packages_price_sets VALUES ($this->Id,$priceSet->Id,'".$soapClient->id_operator."')");
		}
		foreach($this->DetailedDescriptions as $detailedDescription){
			$detailedDescription->saveToDB(array('packageId' => $this->Id, 'soapClient' => $soapClient->id_operator));
		}
		DB::statement("DELETE FROM packages_fare_types WHERE id_package = {$this->Id} AND soap_client = '".$soapClient->id_operator."'");
		foreach($this->FareType as $fareType){
			$checkFareType = FareType::where("name" , "LIKE", "%{$fareType}%")->first();
			if($checkFareType == null){
				$tmp = new FareType;
				$tmp->name = $fareType;
				$tmp->save();
			} else {
				$tmp = $checkFareType;
			}
			DB::statement("INSERT INTO packages_fare_types VALUES ($this->Id,$tmp->id,'".$soapClient->id_operator."')");
		}
		return $this->Id;
	}

	public static function deleteFromDB($packagesIds,$soapClient){
		DB::table('packages_departure_dates')->whereNotIn('id_package',$packagesIds)->where('soap_client','=',$soapClient)->delete();
		DB::table('packages_departure_points')->whereNotIn('id_package',$packagesIds)->where('soap_client','=',$soapClient)->delete();
		DB::table('packages_fare_types')->whereNotIn('id_package',$packagesIds)->where('soap_client','=',$soapClient)->delete();
		$priceSets = DB::table('packages_price_sets')->whereNotIn('id_package',$packagesIds)->where('soap_client','=',$soapClient)->get();
		$priceSetsIds = array();
		foreach($priceSets as $priceSet){
			if(!in_array($priceSet->id_price_set,$priceSetsIds)){
				$priceSetsIds[] = $priceSet->id_price_set;
			}
			DB::table('packages_price_sets')->where('id_package','=',$priceSet->id_package)->where('soap_client','=',$soapClient)->where('id_price_set','=',$priceSet->id_price_set)->delete();
		}
		DB::table('price_sets')->whereIn('id',$priceSetsIds)->where('soap_client','=',$soapClient)->delete();
		DB::table('detailed_descriptions')->where('id_package','!=','NULL')->whereNotIn('id_package',$packagesIds)->where('soap_client','=',$soapClient)->delete();
		DB::table('packages')->whereNotIn('id',$packagesIds)->where('soap_client','=',$soapClient)->delete();
		DB::table('cached_prices')->whereNotIn('id_package',$packagesIds)->where('soap_client','=',$soapClient)->delete();
		//Additional deletes
		//DB::table('booking_package_searches')->whereNotIn('id_package',$packagesIds)->delete();
		//DB::table('packages_search_cached_prices')->whereNotIn('id_package',$packagesIds)->delete();
	}

}
