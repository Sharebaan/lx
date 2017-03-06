<?php namespace App\Models\Travel;

class BookingPackageSearch extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'booking_package_searches';

	public $timestamps = true;

	public function package(){
		return $this->hasOne('App\Models\Travel\PackageInfo','id','id_package')->where('soap_client','=','HO');
	}


}