<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelBindable;
use ET_SoapClient\ET_SoapClient;
use App\Models\Travel\RoomCategory;


class RoomCategorySoapObject extends INF_SoapObjectModelBindable{

	public $Id;
	public $Name;
	public $Description;
	public $Images = array();

	/*
	 *	INF_SoapObjectModelBindable methods
	 */

	protected function setModelClass(){

		$this->model = "RoomCategory";

	}

	public function saveToDB($foreignKeys = null){

		$check = RoomCategory::where('id','=',$this->Id)->where('soap_client','=',$foreignKeys['soapClient'])->first();
		if($check == null){
			$room_category = new RoomCategory;
		} else {
			$room_category = $check;
		}
		$room_category->id = $this->Id;
		$room_category->id_hotel = isset($foreignKeys['hotelId']) ? $foreignKeys['hotelId'] : null;
		$room_category->soap_client = isset($foreignKeys['soapClient']) ? $foreignKeys['soapClient'] : null;
		$room_category->name = $this->Name;
		$room_category->description = $this->Description;
		$room_category->save();
		foreach($this->Images as $image){
			$image->saveToDB(array('roomCategoryId' => $this->Id, 'soapClient' => $room_category->soap_client));
		}
	
	}

}