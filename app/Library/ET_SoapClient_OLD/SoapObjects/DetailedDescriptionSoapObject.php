<?php

namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelBindable;
use ET_SoapClient\ET_SoapClient;
use App\Models\Travel\DetailedDescription;

class DetailedDescriptionSoapObject extends INF_SoapObjectModelBindable{

	public $Label;
	public $Text;
	public $Index;

	/*
	 *	INF_SoapObjectModelBindable methods
	 */

	protected function setModelClass(){

		$this->model = "DetailedDescription";

	}

	public function saveToDB($foreignKeys = null){

		if(isset($foreignKeys['hotelId'])){
			$check = DetailedDescription::where('id_hotel',$foreignKeys['hotelId'])->where('label',$this->Label)->where('soap_client','=',$foreignKeys['soapClient'])->first();
		} else if(isset($foreignKeys['packageId'])) {
			$check = DetailedDescription::where('id_package',$foreignKeys['packageId'])->where('label',$this->Label)->where('soap_client','=',$foreignKeys['soapClient'])->first();
		}
		if($check == null){
			$detailed_description = new DetailedDescription;
		} else {
			$detailed_description = $check;
		}
		$detailed_description->id_hotel = isset($foreignKeys['hotelId']) ? $foreignKeys['hotelId'] : null;
		$detailed_description->id_package = isset($foreignKeys['packageId']) ? $foreignKeys['packageId'] : null;
		$detailed_description->soap_client = isset($foreignKeys['soapClient']) ? $foreignKeys['soapClient'] : null;
		$detailed_description->label = $this->Label;
		$detailed_description->text = $this->Text;
		$detailed_description->index = $this->Index;
		$detailed_description->save();
	}

}