<?php namespace App\Models\Travel\Eloquent;

use Illuminate\Database\Eloquent\Model;

class RezervarePlataEloquent extends Model
{
    public $table = 'plata_rezervare';
    protected $fillable = [
      'achitat',
      'refuzat'
    ];

    public function clienti(){
      return $this->hasMany('App\Models\Travel\Eloquent\RezervarePlataClienti');
    }

    public function romcard(){
      return $this->hasMany('App\Models\Travel\Eloquent\RezervarePlataClienti');
    }
}
