<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelBindable;
use ET_SoapClient\ET_SoapClient;
use App\Models\Travel\Geography;
use App\Models\Travel\GeographyTemp;


class GeographySoapObject extends INF_SoapObjectModelBindable{

	public $Id;
	public $Name;
	public $IntName;
	public $ChildLabel;
	public $Description;
	public $Image;
	public $Children;
	public $TreeLevel;
	public $MinVal;
	public $MaxVal;

	public static function get($SOAPClient){

		return $SOAPClient->getGeography();
	}

	/*
	 *	INF_SoapObjectModelBindable methods
	 */

	protected function setModelClass(){

		$this->model = "Geography";

	}

	public function saveToTempDB($soapClient,$foreignKeys = null){
		$check = GeographyTemp::where('id','=',$this->Id)->where('soap_client','=',$soapClient)->first();
		if($check == null){
			$geography = new GeographyTemp;
		} else {
			$geography = $check;
		}
		$geography->id = $this->Id;
		$geography->id_parent = isset($foreignKeys['parentId']) ? $foreignKeys['parentId'] : null;
		$geography->name = $this->Name;
		$geography->int_name = $this->IntName;
		$geography->child_label = $this->ChildLabel;
		$geography->description = $this->Description;
		$geography->tree_level = $this->TreeLevel;
		$geography->min_val = $this->MinVal;
		$geography->max_val = $this->MaxVal;
		$geography->soap_client = $soapClient;
		$geography->save();
		foreach($this->Children as $child){
			$child->saveToTempDB($soapClient,array('parentId' => $this->Id));
		}
	}

	public static function saveClientToDB($soapClient){
		$geographiesToClear = Geography::whereRaw("availability LIKE '%{$soapClient->id_operator}:%'")->get();
		foreach($geographiesToClear as $geography){
			$availabilities = explode("|",$geography->availability);
			$newAvailabilities = array();
			foreach($availabilities as $availability){
				if(!(strpos($availability,$soapClient->id_operator) !== FALSE)){
					if($availability != ""){
						$newAvailabilities[] = $availability;
					}
				}
			}
			$geography->availability = implode("|",$newAvailabilities);
			$geography->save();
		}
		$mappings = $soapClient->import_options['geographies']['mappings'];
		foreach($mappings as $idTemp => $id){
			$geography = Geography::where('id','=',$id)->first();
			if($geography != null){
				if($geography->availability != ""){
					$geography->availability = $geography->availability."|".$soapClient->id_operator.":".$idTemp;
				} else {
					$geography->availability = $soapClient->id_operator.":".$idTemp;
				}
				$geography->save();
			}
		}
	}
	
	public function saveToDB($foreignKeys = null){
		
	}

}