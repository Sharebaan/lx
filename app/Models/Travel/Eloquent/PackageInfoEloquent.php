<?php namespace App\Models\Travel\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class PackageInfoEloquent extends Model{
  use SearchableTrait;
  protected $table = 'packages';

  protected $searchable = [
      'columns' => [
          'packages.name' => 50
      ],
      'joins' => [
        'hotels' => ['packages.id_hotel','hotels.id'],
        'cached_prices'=>['packages.id','cached_prices.id_package'],
      ]
  ];

  public function hotels(){
    return $this->belongsTo('App\Models\Travel\HotelEloquent','id_hotel','id');
  }

  public function cached_prices(){
    return $this->hasMany('App\Models\Travel\Eloquent\CachedPriceEloquent','id_package','id');
  }

  

}
