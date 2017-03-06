<?php namespace App\Models\Travel;

class AskForOffersItem extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ask_for_offers';

	public $timestamps = true;

	public function hotel(){
		return $this->hasOne('App\Models\Travel\Hotel','id','id_hotel')->where('hotels.soap_client','=',$this->soap_client);
	}

	public function package(){
		return $this->hasOne('App\Models\Travel\PackageInfo','id','id_package')->where('packages.soap_client','=',$this->soap_client);
	}

}