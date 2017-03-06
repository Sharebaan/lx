<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\DetailedDescription;
use App\Models\Travel\CachedPrice;
use App\Models\Travel\Hotel;
use App\Models\Travel\DepartureDate;
use App\Models\Travel\DeparturePoint;
use App\Models\Admingeographies;

class adminpackages extends Sximo  {

	protected $table = 'packages';
	protected $primaryKey = 'idx';

	public function __construct() {
		parent::__construct();

	}

	public static function querySelect(  ){

		return "  SELECT packages.* FROM packages  ";
	}

	public static function queryWhere(  ){

		return "  WHERE packages.idx IS NOT NULL ";
	}

	public static function queryGroup(){
		return "  ";
	}

	public static function getDetailedDescriptionsPackage( $packageID,$soap_client)
	{
		$result = array();

		$descriptions = \DB::table('detailed_descriptions')->where('id_package', $packageID)->where('soap_client', $soap_client)->get();

		if(empty($descriptions)) return $result;

		foreach ($descriptions as $key => $value) {
			$result[$key] = array();
			$result[$key]['id'] = $value->id;
			$result[$key]['label'] = $value->label;
			$result[$key]['text'] = $value->text;
		}
		return $result;
	}

	public static function getPricesForPackage($packageID, $soap_client){
		$prices = \DB::table('cached_prices')
					->selectRaw('meal_plans.name as meal_plan_name, room_categories.name as room_category_name, cached_prices.gross as gross, cached_prices.tax as tax, cached_prices.currency as currency, cached_prices.departure_date, price_sets.label as price_set_label, price_sets.description as price_set_description, cached_prices.id_room_category as id_room_category, cached_prices.id_meal_plan as id_meal_plan, cached_prices.id_price_set as id_price_set')
					->leftJoin('packages',function($join){
						$join->on('packages.id','=','cached_prices.id_package');
						$join->on('packages.soap_client','=','cached_prices.soap_client');
					})
					->leftJoin('meal_plans','cached_prices.id_meal_plan','=','meal_plans.id');

		$prices = $prices->leftJoin('room_categories',function($join){
						$join->on('room_categories.id','=','cached_prices.id_room_category');
						$join->on('room_categories.id_hotel','=','packages.id_hotel');
						$join->on('room_categories.soap_client','=','packages.soap_client');
				  });

		$prices = $prices->leftJoin('price_sets',function($join){
						$join->on('price_sets.id','=','cached_prices.id_price_set');
						$join->on('price_sets.soap_client','=','cached_prices.soap_client');
					})
					->where('cached_prices.id_package','=',$packageID)
					->where('cached_prices.soap_client','=',$soap_client)
					->whereRaw('price_sets.valid_from < CURDATE()')
					->whereRaw('price_sets.valid_to >= CURDATE()')
					->get();
		return $prices;
	}

	public static function getPricesForPackageArray($packageID, $soap_client){
		//dd($packageID, $soap_client);

		$results = array();
		$prices = \DB::table('cached_prices')
					->where('cached_prices.id_package','=',$packageID)
					->where('cached_prices.soap_client','=',$soap_client)
					->get();
		if(empty($prices)) return $results;
		foreach($prices as $key => $value){
			$results[$key] = array();
			$results[$key]['id_room_category'] = $value->id_room_category;
			$results[$key]['id_price_set'] = $value->id_price_set;
			$results[$key]['id_meal_plan'] = $value->id_meal_plan;
			$results[$key]['departure_date'] = $value->departure_date;
			$results[$key]['gross'] = $value->gross;
			$results[$key]['tax'] = $value->tax;
		}
		return $results;
	}

	public static function updatePackage(Request $request){
		//dd($request->all());
		if($request->input('idx') != ''){
			$package = PackageInfo::where('idx','=',$request->input('idx'))->first();
		} else {
			$package = new PackageInfo();
		}
		$package->id = !isset($package->id) ? 0 : $package->id;
		$package->soap_client = $package->soap_client == null ? "LOCAL" : $package->soap_client;
		$package->name = $request->input('name');
		$package->description = $request->input('description');
		$package->included_services = $request->input('included_services');
		$package->not_included_services = $request->input('not_included_services');
		$package->is_tour = $request->input('is_tour');
		$package->duration = $request->input('duration');
		$package->day_night = $request->input('day_night');
		$package->id_hotel = $request->input('id_hotel');
		$package->outbound_transport_duration = $request->input('outbound_transport_duration') == null ? 0 : $request->input('outbound_transport_duration');
		switch($request->input('transport_type')){
			case 0:
				$package->is_bus = 0;
				$package->is_flight = 0;
			break;
			case 1:
				$package->is_bus = 1;
				$package->is_flight = 0;
			break;
			case 2:
				$package->is_bus = 0;
				$package->is_flight = 1;
			break;
			default:
		}
		if($package->soap_client == "LOCAL"){
			$package->destination = Hotel::where('id','=',$package->id_hotel)->where('soap_client','=',$package->soap_client)->first()->location;
		}
		$package->save();
		if($package->soap_client == "LOCAL"){
			DeparturePoint::where('id_package','=',$package->id)->where('soap_client','=','LOCAL')->delete();
			$departurePoint = new DeparturePoint;
			$departurePoint->id_package = $package->id;
			$departurePoint->soap_client = "LOCAL";
			$departurePoint->id_geography = 5687;
			$departurePoint->save();
		}
		if($request->input('idx') == ''){
			\DB::table('packages')->where('idx','=',$package->id)->update(array('id' => $package->id));
		}
		if($request->input('detail') != 0){
			self::updateDetailedDescriptions($request->input('detail'),$package->id,$package->soap_client);
		} else {
			self::deleteDetailedDescriptions($package->id,$package->soap_client);
		}
		if($request->input('price_entry') != null){
			//dd('not null',$request->input('price_entry'));
			self::updatePrices($request->input('price_entry'),$package->id,$package->soap_client);
		} else {
			self::deletePrices($package->id,$package->soap_client);
		}
		if($request->input('fare_types') != null){
			self::updateFareTypes($request->input('fare_types'),$package->id,$package->soap_client);
		} else {
			self::deleteFareTypes($package->id,$package->soap_client);
		}
		if($request->input('categories') != null){
			self::updateCategories($request->input('categories'),$package->id,$package->soap_client);
		} else {
			self::deleteCategories($package->id,$package->soap_client);
		}
		return $package->idx;
	}

	public static function updateCategories($categories,$packageId,$soapClient){
		self::deleteCategories($packageId, $soapClient);
		foreach($categories as $category){
			\DB::table('package_categories')->insert(array(
													 	"id_package" => $packageId,
													 	"id_category" => $category,
													 	"soap_client" => $soapClient
													 ));
		}
	}

	public static function deleteCategories($packageId,$soapClient){
		\DB::table('package_categories')->where('id_package','=',$packageId)->where('soap_client','=',$soapClient)->delete();
	}

	public static function updateFareTypes($fareTypes,$packageId,$soapClient){
		self::deleteFareTypes($packageId, $soapClient);
		foreach($fareTypes as $fareType){
			\DB::table('packages_fare_types')->insert(array(
													 	"id_package" => $packageId,
													 	"id_fare_type" => $fareType,
													 	"soap_client" => $soapClient
													 ));
		}
	}

	public static function deleteFareTypes($packageId,$soapClient){
		\DB::table('packages_fare_types')->where('id_package','=',$packageId)->where('soap_client','=',$soapClient)->delete();
	}

	public static function updatePrices($prices,$packageId,$soapClient){
		self::deletePrices($packageId,$soapClient);
		if($soapClient == "LOCAL"){
			self::deleteDepartureDates($packageId,$soapClient);
		}
		//dd($prices,$packageId,$soapClient);
		foreach($prices as $price){
			//var_dump($packageId,$price['room_category'],$price['price_set'],$price['meal_plan'],$price['departure_date'],$price['gross'],$price['tax'],$soapClient);
			//echo '<br>';
			\DB::table('cached_prices')->insert(array(
														"id_package" => $packageId,
													"id_room_category" => $price['room_category'],
													"id_price_set" => $price['price_set'],
													"id_meal_plan" => $price['meal_plan'],
													"departure_date" => $price['departure_date'],
													"gross" => $price['gross'],
													"tax" => $price['tax'],
													"currency"=> $price['currency'],
													"soap_client" => $soapClient
											  ));
			//OLD CHUNK
			/*


			"id_package" => $packageId,
			"id_room_category" => $price['room_category'],
			"id_price_set" => $price['price_set'],
			"id_meal_plan" => $price['meal_plan'],
			"departure_date" => $price['departure_date'],
			"gross" => array_key_exists('gross',$price)?$price['gross']:0.00,
			"tax" => array_key_exists('tax',$price)?$price['gross']:0.00,
			"soap_client" => $soapClient


			*/
			if($soapClient == "LOCAL"){

				if(DepartureDate::where('id_package','=',$packageId)->where('soap_client','=','LOCAL')->where('departure_date','=',$price['departure_date'])->count() == 0){
					$departureDate = new DepartureDate;
					$departureDate->id_package = $packageId;
					$departureDate->soap_client = "LOCAL";
					$departureDate->departure_date = $price['departure_date'];
					$departureDate->save();
				}
			}
		}
		//die;
	}

	public static function deleteDepartureDates($packageId,$soapClient){
		DepartureDate::where('id_package','=',$packageId)->where('soap_client','=',$soapClient)->delete();
	}

	public static function deletePrices($packageId,$soapClient){
		CachedPrice::where('id_package','=',$packageId)->where('soap_client','=',$soapClient)->delete();
	}

	public static function updateDetailedDescriptions($detailedDescriptions,$packageId,$soapClient){
		foreach($detailedDescriptions as $detail){
			if(isset($detail['id'])){
				$detailedDescription = DetailedDescription::find($detail['id']);
				if($detail['delete'] == 0){
					if($detail['text'] != $detailedDescription->text || $detail['label'] != $detailedDescription->label){
						$detailedDescription->update = 1;
					}
					$detailedDescription->label = $detail['label'];
					$detailedDescription->text = $detail['text'];
					$detailedDescription->save();
				} else {
					$detailedDescription->delete();
				}
			} else {
				$detailedDescription = new DetailedDescription();
				$detailedDescription->id_package = $packageId;
				$detailedDescription->soap_client = $soapClient;
				$detailedDescription->label = $detail['label'];
				$detailedDescription->text = $detail['text'];
				$detailedDescription->index = 0;
				$detailedDescription->update = 1;
				$detailedDescription->save();
			}
		}
	}

	public static function deleteDetailedDescriptions($packageId,$soapClient){
		DetailedDescription::where('id_package','=',$packageId)->where('soap_client','=',$soapClient)->delete();
	}


}
