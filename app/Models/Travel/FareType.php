<?php namespace App\Models\Travel;

class FareType extends SximoTravel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'fare_types';

	/*
	 * Hotel entity attributes
	 */

	public $timestamps = false;

	public static function getFareTypesFor($hotelId,$soapClient,$is_tour,$is_bus,$is_flight){
		$fareTypes = self::distinct('fare_types.id')
							 ->select('fare_types.name as fare_type_name')
							 ->leftJoin('packages_fare_types','packages_fare_types.id_fare_type','=','fare_types.id')
							 ->leftJoin('packages',function($join){
							 	$join->on('packages_fare_types.id_package','=','packages.id');
							 	$join->on('packages_fare_types.soap_client','=','packages.soap_client');
							 })
							 ->where('packages.id_hotel','=',$hotelId)
							 ->where('packages.is_tour','=',$is_tour)
							 ->where('packages.is_bus','=',$is_bus)
							 ->where('packages.is_flight','=',$is_flight)
							 ->where('packages.soap_client','=',$soapClient)
							 ->get();
		return $fareTypes;
	}

	public static function getFareTypesForHotelAndPackageSearch($hotelId,$soapClient,$searchId){
		$fareTypes = self::distinct('fare_types.id')
							 ->select('fare_types.name as fare_type_name')
							 ->leftJoin('packages_fare_types','packages_fare_types.id_fare_type','=','fare_types.id')
							 ->leftJoin('packages',function($join){
							 	$join->on('packages_fare_types.id_package','=','packages.id');
							 	$join->on('packages_fare_types.soap_client','=','packages.soap_client');
							 })
							 ->leftJoin('packages_search_cached_prices',function($join){
							 	$join->on('packages.id','=','packages_search_cached_prices.id_package');
							 	$join->on('packages.soap_client','=','packages_search_cached_prices.soap_client');
							 })
							 ->where('packages.id_hotel','=',$hotelId)
							 ->where('packages.soap_client','=',$soapClient)
							 ->whereRaw("packages_search_cached_prices.id_package_search = ".$searchId)
							 ->get();
		return $fareTypes;
	}

}
