<?php

namespace ET_SoapClient\Cache;

use INF_SoapClient\INF_Cached;
use App\Models\Travel\CachedPrice;
use App\Models\Travel\MealPlan;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\EtripOperator;
use Config;

class PricesCached extends INF_Cached{

	protected $soapClient;

	public function __construct($soapClient){
		$this->soapClient = $soapClient;
		parent::__construct();
	}

	public function setAuthorization(){
		$etripOperator = EtripOperator::where('id_operator','=',$this->soapClient)->first();
		$this->url = $etripOperator->cached_prices_url;
		$this->username = $etripOperator->username;
		$this->password = $etripOperator->password;
	}

	public function saveToDB(){
		foreach($this->data as $entry){
			$package = PackageInfo::where('id','=',intval($entry[0]))->where('soap_client','=',$this->soapClient)->first();
			if($package != null){
				$price = new CachedPrice;
				$price->id_package = intval($entry[0]);
				$price->id_room_category = intval($entry[1]);
				$price->id_price_set = intval($entry[2]);
				$price->departure_date = $entry[3];
				$price->gross = floatval($entry[4]);
				$price->tax = floatval($entry[5]);
				$price->soap_client = $this->soapClient;
				$mealPlan = MealPlan::where('name','=',$entry[6])->first();
				if($mealPlan == null){
					$mealPlan = new MealPlan;
					$mealPlan->name = $entry[6];
					$mealPlan->save();
				}
				$price->id_meal_plan = $mealPlan->id;
				CachedPrice::where('id_package','=',$price->id_package)
						   ->where('id_room_category','=',$price->id_room_category)
						   ->where('id_price_set','=',$price->id_price_set)
						   ->where('id_meal_plan','=',$price->id_meal_plan)
						   ->where('departure_date','=',$price->departure_date)
						   ->where('soap_client','=',$price->soap_client)
						   ->delete();
				$price->save();
			}

		}

	}

}
