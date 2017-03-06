<?php namespace App\Models\Travel;

class BookingHotelSearch extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'booking_hotel_searches';

	public $timestamps = true;

	public function hotel(){
		return $this->hasOne('App\Models\Travel\Hotel','id','id_hotel');
	}


}