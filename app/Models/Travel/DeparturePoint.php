<?php namespace App\Models\Travel;

class DeparturePoint extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'packages_departure_points';

	/*
	 * Hotel entity attributes
	 */

	public $timestamps = false;

	public function location(){
		return $this->hasOne('App\Models\Travel\Geography','id','id_geography');
	}

}
