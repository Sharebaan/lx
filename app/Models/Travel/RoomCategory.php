<?php namespace App\Models\Travel;

class RoomCategory extends SximoTravel{


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'room_categories';

	/*
	 * Hotel entity attributes
	 */


	public $timestamps = false;

	public function images(){
		$this->hasMany('App\Models\Travel\FileInfo','id_room_category');
	}

}
