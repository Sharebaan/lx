<?php namespace App\Models\Travel;

class GeographyTemp extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'geographies_temp';

	/*
	 * Hotel entity attributes
	 */

	public $timestamps = false;

	public static function displayTreeFor($file,$soapClient){
		$root = GeographyTemp::where('id','=',1)->where('soap_client','=',$soapClient)->first();
		$root->displayTree($file,$soapClient,0);
	}

	public function displayTree($file,$soapClient,$level){
		$display = "";
		for($i = 0; $i < $level; $i++){
			$display .= "   ";
		}
		$display .= $this->id."-".$this->soap_client."-".$this->name."\n";
		fwrite($file,$display);
		$childrens = GeographyTemp::where('soap_client','=',$soapClient)->where('id_parent','=',$this->id)->get();
		foreach($childrens as $children){
			$children->displayTree($file,$soapClient,$level+1);
		}
	}

}