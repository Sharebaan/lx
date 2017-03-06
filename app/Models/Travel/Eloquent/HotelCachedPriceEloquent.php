<?php namespace App\Models\Travel\Eloquent;

use Illuminate\Database\Eloquent\Model;

class HotelCachedPriceEloquent extends Model{
  protected $table = 'hotels_search_cached_prices';
  protected $fillable = ['id_hotel'];
}