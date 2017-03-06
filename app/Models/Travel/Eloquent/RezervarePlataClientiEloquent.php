<?php namespace App\Models\Travel\Eloquent;

use Illuminate\Database\Eloquent\Model;

class RezervarePlataClientiEloquent extends Model
{
    public $table = 'plata_rezervare_clienti';

    public function rezervare(){
      return $this->belongsTo('App\Models\Travel\Eloquent\RezervarePlata');
    }
}
