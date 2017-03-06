<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Travel\PriceSet;

class adminpricesets extends Sximo  {
	
	protected $table = 'price_sets';
	protected $primaryKey = 'soap_client';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT price_sets.* FROM price_sets  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE price_sets.soap_client = 'LOCAL' ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
	public static function insertPriceSet(Request $request){
		if($request->input('idx') == 0){
			$priceSet = \DB::table('price_sets')->insertGetId(array(
				'id' => 0,
				'valid_from' => $request->input('valid_from'),
				'valid_to' => $request->input('valid_to'),
				'label' => $request->input('label'),
				'description' => $request->input('description'),
				'soap_client' => "LOCAL",
				'is_local' => 1
			));
			\DB::table('price_sets')->where('idx','=',$priceSet)->update(array('id' => $priceSet));
		} else {
			$priceSet = $request->input('idx');
			\DB::table('price_sets')->where('idx','=',$request->input('idx'))->update(array(
				'valid_from' => $request->input('valid_from'),
				'valid_to' => $request->input('valid_to'),
				'label' => $request->input('label'),
				'description' => $request->input('description'),
			));
		}
		return $priceSet;
	}
	

}
