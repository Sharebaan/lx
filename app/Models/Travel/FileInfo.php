<?php namespace App\Models\Travel;

class FileInfo extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'file_infos';
	protected $fillable = ['id_hotel'];
	/*
	 * Hotel entity attributes
	 */

	public $timestamps = false;

}
