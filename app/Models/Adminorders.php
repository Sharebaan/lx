<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class adminorders extends Sximo
{
  protected $table = 'plata_rezervare';
  protected $primaryKey = 'id';

  public function __construct() {
    parent::__construct();
  }

  public static function querySelect(  ){
		return "  SELECT plata_rezervare.* FROM plata_rezervare ";
	}

  public static function queryWhere(  ){
		return "  WHERE plata_rezervare.id IS NOT NULL ";
	}

	public static function queryGroup(){
		return "  ";
	}

}