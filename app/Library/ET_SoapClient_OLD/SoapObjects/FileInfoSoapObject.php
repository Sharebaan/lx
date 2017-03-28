<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelBindable;
use ET_SoapClient\ET_SoapClient;
use App\Models\Travel\FileInfo;
use App\Models\Travel\EtripOperator;
use URL;
use DB;
use Config;


class FileInfoSoapObject extends INF_SoapObjectModelBindable{

	public $Id;
	public $MimeType;
	public $Name;

	/*
	 *	INF_SoapObjectModelBindable methods
	 */

	protected function setModelClass(){

		$this->model = "FileInfo";

	}

	public function saveToDB($foreignKeys = null){

		$check = FileInfo::where('id','=',$this->Id)->where('soap_client','=',$foreignKeys['soapClient'])->first();
		if($check == null){
			$file_info = new FileInfo;
		} else {
			$file_info = $check;
		}
		$file_info->id = $this->Id;
		$file_info->id_hotel = isset($foreignKeys['hotelId']) ? $foreignKeys['hotelId'] : null;
		$file_info->id_room_category = isset($foreignKeys['roomCategoryId']) ? $foreignKeys['roomCategoryId'] : null;
		$file_info->id_geography = isset($foreignKeys['geographyId']) ? $foreignKeys['geographyId'] : null;
		$file_info->soap_client = isset($foreignKeys['soapClient']) ? $foreignKeys['soapClient'] : null;
		$file_info->mime_type = $this->MimeType;
		$file_info->name = $this->Name;
		$file_info->save();

		/*
		 *   Save files locally
		 */
		
		$offersDirectory = public_path()."/images/offers/".$file_info->soap_client;
		if (!file_exists($offersDirectory)) {
		    mkdir($offersDirectory);
		}
		$url = EtripOperator::where('id_operator','=',$file_info->soap_client)->first()->file_url.$this->Id;
		$mimeArray = explode('/', $this->MimeType);
		$type = $mimeArray[count($mimeArray)-1];
		$img = public_path().'/images/offers/'.$file_info->soap_client.'/'.$this->Id.'.'.$type;
		if(!file_exists($img)){
			$content = file_get_contents($url);
			$fp = fopen($img, "w");
			fwrite($fp, $content);
			fclose($fp);
		} 
		
		return $this->Id;
	}
	
	public static function deleteFromDB($fileInfosIds,$foreignKeys){
		$fileInfos = DB::table('file_infos');

		if(isset($foreignKeys['hotelId'])){
			$fileInfos = $fileInfos->where('id_hotel','=',$foreignKeys['hotelId']);
		} else if(isset($foreignKeys['roomCategoryId'])){
			$fileInfos = $fileInfos->where('id_room_category','=',$foreignKeys['roomCategoryId']);
		} else if(isset($foreignKeys['geographyId'])){
			$fileInfos = $fileInfos->where('id_room_category','=',$foreignKeys['geographyId']);
		}
		$fileInfos = $fileInfos->whereNotIn('id',$fileInfosIds)->where('soap_client','=',$foreignKeys['soapClient'])->get();
		foreach($fileInfos as $fileInfo){
			$mimeArray = explode('/', $fileInfo->mime_type);
			$type = $mimeArray[count($mimeArray)-1];
			$img = $_SERVER["DOCUMENT_ROOT"].'/public/images/offers/'.$foreignKeys['soapClient'].'/'.$fileInfo->id.'.'.$type;
			if(file_exists($img)){
				unlink($img);
			}
			DB::table('file_infos')->where('id','=',$fileInfo->id)->where('soap_client','=',$foreignKeys['soapClient'])->delete();
		}
	}

}