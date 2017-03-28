<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelBindable;
use ET_SoapClient\ET_SoapClient;
use App\Models\Travel\PriceSet;
use DB;

class PriceSetSoapObject extends INF_SoapObjectModelBindable{

	public $Id;
	public $Label;
	public $Description;

	/*
	 *	INF_SoapObjectModelBindable methods
	 */

	protected function setModelClass(){

		$this->model = "PriceSet";

	}

	public function saveToDB($foreignKeys = null){

		$check = PriceSet::find($this->Id);
		if($check == null){
			$priceSet = new PriceSet;
		} else {
			$priceSet = $check;
		}
		$priceSet->id = $this->Id;
		$priceSet->label = $this->Label;
		$priceSet->description = $this->Description;
		$priceSet->valid_from = $this->ValidFrom;
		$priceSet->valid_to = $this->ValidTo;
		$priceSet->soap_client = isset($foreignKeys['soapClient']) ? $foreignKeys['soapClient'] : null;
		$priceSet->save();
		
	}

	public static function deleteOrphans(){
		
	}

}