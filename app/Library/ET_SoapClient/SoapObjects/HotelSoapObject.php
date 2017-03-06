<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelBindable;
use ET_SoapClient\ET_SoapClient;
use CT_SoapClient\CT_SoapClient;
use ET_SoapClient\SoapObjects\FileInfoSoapObject;
use App\Models\Travel\Hotel;
use DB;
use App\Models\Travel\RoomAmenity;
use App\Models\Travel\HotelTheme;
use App\Models\Travel\HotelAmenity;
use Exception;
use App\Models\Travel\Geography;


class HotelSoapObject extends INF_SoapObjectModelBindable{

	public $Id;
	public $Code;
	public $Name;
	public $Class;
	public $Description;
	public $Address;
	public $ZIP;
	public $Phone;
	public $Fax;
	public $Location;
	public $URL;
	public $Latitude;
	public $Longitude;
	public $RoomCategories = array();
	public $Images = array();
	public $DetailedDescriptions = array();
	public $HotelTheme = array();
	public $HotelAmenities = array();
	public $RoomAmenities = array();
	public $ExtraClass;
	public $UseIndividually;
	public $UseOnPackages;
	public $PopertyType = 0;

	public static function getAll($SOAPClient){

		return $SOAPClient->getHotels();
	}

	/*
	 *	INF_SoapObjectModelBindable methods
	 */

	protected function setModelClass(){

		$this->model = "Hotel";

	}

	public function saveToDB($foreignKeys = null){
	}

	public static function saveExtraHotels($soapClient){
		$hotels = HotelSoapObject::getAll($soapClient);
		$hotelIds = array();
		foreach($hotels as $hotel){
			$idHotel = $hotel->saveExtraToDB($soapClient);
			if($idHotel != 0) $hotelIds[] = $idHotel;
		}
		return $hotelIds;
	}

	public function saveExtraToDB($soapClient){
		$newGeography = Geography::whereRaw('availability LIKE "%'.$soapClient->id_operator.':'.$this->Location.'%"')->first();
		if($newGeography == null) return 0;
		$check = Hotel::where('id','=',$this->Id)->where('soap_client','=',$soapClient->id_operator)->first();
		if($check == null){
			$hotel = new Hotel;
		} else {
			$hotel = $check;
		}
		$hotel->id = $this->Id;
		$hotel->code = $this->Code;
		$hotel->name = $this->Name;
		$hotel->class = $this->Class;
		$hotel->description = $this->Description;
		$hotel->address = $this->Address;
		$hotel->zip = $this->ZIP;
		$hotel->phone = $this->Phone;
		$hotel->fax = $this->Fax;
		$hotel->location =  $newGeography->id;
		$hotel->url = $this->URL;
		$hotel->latitude = $this->Latitude;
		$hotel->longitude = $this->Longitude;
		$hotel->extra_class = $this->ExtraClass;
		$hotel->use_individually = $this->UseIndividually;
		$hotel->use_on_packages = $this->UseOnPackages;
		$hotel->soap_client = $soapClient->id_operator;
		$hotel->save();
		//Possible need to delete room categories
		foreach($this->RoomCategories as $roomCategory){
			$roomCategory->saveToDB(array('hotelId' => $this->Id,'soapClient' => $soapClient->id_operator));
		}
		$fileInfosArray = array();
		foreach($this->Images as $image){
			$id = $image->saveToDB(array('hotelId' => $this->Id,'soapClient' => $soapClient->id_operator));
			array_push($fileInfosArray,$id);
		}
		FileInfoSoapObject::deleteFromDB($fileInfosArray,array('hotelId' => $this->Id,'soapClient' => $soapClient->id_operator));
		foreach($this->DetailedDescriptions as $detailedDescription){
			$detailedDescription->saveToDB(array('hotelId' => $this->Id,'soapClient' => $soapClient->id_operator));
		}
		HotelTheme::where('id_hotel',$this->Id)->delete();
		foreach($this->HotelTheme as $hotelTheme){
			$tmp = new HotelTheme;
			$tmp->id_hotel = $this->Id;
			$tmp->soap_client =  $soapClient->id_operator;
			$tmp->text = $hotelTheme;
			$tmp->save();
		}
		HotelAmenity::where('id_hotel',$this->Id)->delete();
		foreach($this->HotelAmenities as $hotelAmenity){
			$tmp = new HotelAmenity;
			$tmp->id_hotel = $this->Id;
			$tmp->soap_client = $soapClient->id_operator;
			$tmp->text = $hotelAmenity;
			$tmp->save();
		}
		RoomAmenity::where('id_hotel',$this->Id)->delete();
		foreach($this->RoomAmenities as $roomAmenity){
			$tmp = new RoomAmenity;
			$tmp->id_hotel = $this->Id;
			$tmp->soap_client = $soapClient->id_operator;
			$tmp->text = $roomAmenity;
			$tmp->save();
		}
		return $this->Id;		
	}

	public static function deleteFromDB($hotelsIds,$soapClient){
		DB::table('room_amenities')->whereNotIn('id_hotel',$hotelsIds)->where('soap_client','=',$soapClient)->delete();
		DB::table('hotel_amenities')->whereNotIn('id_hotel',$hotelsIds)->where('soap_client','=',$soapClient)->delete();
		DB::table('hotel_themes')->whereNotIn('id_hotel',$hotelsIds)->where('soap_client','=',$soapClient)->delete();
		DB::table('detailed_descriptions')->where('id_hotel','!=','NULL')->whereNotIn('id_hotel',$hotelsIds)->where('soap_client','=',$soapClient)->delete();
		$fileInfos = DB::table('file_infos')->where('id_hotel','!=','NULL')->whereNotIn('id_hotel',$hotelsIds)->where('soap_client','=',$soapClient)->get();
		foreach($fileInfos as $fileInfo){
			$mimeArray = explode('/', $fileInfo->mime_type);
			$type = $mimeArray[count($mimeArray)-1];
			$img = $_SERVER["DOCUMENT_ROOT"].'/images/offers/'.$soapClient.'/'.$fileInfo->id.'.'.$type;
			if(file_exists($img)) unlink($img);
			DB::table('file_infos')->where('id','=',$fileInfo->id)->where('soap_client','=',$soapClient)->delete();
		}
		$roomCategories = DB::table('room_categories')->whereNotIn('id_hotel',$hotelsIds)->where('soap_client','=',$soapClient)->get();
		foreach($roomCategories as $roomCategory){
			$fileInfos = DB::table('file_infos')->where('id_room_category','!=','NULL')->where('id_room_category','=',$roomCategory->id)->where('soap_client','=',$soapClient)->get();
			foreach($fileInfos as $fileInfo){
				$mimeArray = explode('/', $fileInfo->mime_type);
				$type = $mimeArray[count($mimeArray)-1];
				$img = $_SERVER["DOCUMENT_ROOT"].'/images/offers/'.$soapClient.'/'.$fileInfo->id.'.'.$type;
				if(file_exists($img)) unlink($img);
				DB::table('file_infos')->where('id','=',$fileInfo->id)->where('soap_client','=',$soapClient)->delete();
			}
			DB::table('room_categories')->where('id','=',$roomCategory->id)->where('soap_client','=',$soapClient)->delete();
		}
		DB::table('hotels')->whereNotIn('id',$hotelsIds)->where('soap_client','=',$soapClient)->delete();
	}

}