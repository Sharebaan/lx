<?php namespace App\Models\Travel;

class CachedPrice extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cached_prices';

	/*
	 * Hotel entity attributes
	 */
	
	public $timestamps = false;

	public function roomCategory(){
		return $this->hasOne('App\Models\Travel\RoomCategory','id','id_room_category');
	}
	public function roomCategories(){
			return $this->hasMany('App\Models\Travel\RoomCategory','id','id_room_category')
					->where('id_hotel','=',$this->package->id_hotel);
	}
	public function priceSet(){
		return $this->hasOne('App\Models\Travel\PriceSet','id','id_price_set');
	}

	public function package(){
		return $this->hasOne('App\Models\Travel\PackageInfo','id','id_package');
	}

	public function mealPlan(){
		return $this->hasOne('App\Models\Travel\MealPlan','id','id_meal_plan');
	}

}
