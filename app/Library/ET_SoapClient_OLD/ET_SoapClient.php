<?php
namespace ET_SoapClient;

use INF_SoapClient\INF_SoapClient;
use \Config;
use App\Models\Travel\EtripOperator;

class ET_SoapClient extends INF_SoapClient {

	public function __construct($operatorID){
		$etripOperator = EtripOperator::where('id_operator','=',$operatorID)->first();
		//dd($etripOperator);
		$this->username = $etripOperator->username;
		$this->password = $etripOperator->password;
		$this->wsdl = $etripOperator->wsdl;
		$this->SOAPClasses = array( 'Hotel','RoomCategory','FileInfo','DetailedDescription','PackageInfo','Geography','PriceSet','PackageSearch','Room','Result','PriceInfo','PackageResult',
									'HotelResult', 'BusResult', 'FlightResult', 'Journey', 'Leg', 'TransferResult', 'DiscountInfo', 'SpecialOffer','PaxInfo','BookRequest','Booking','BookingItem',
									'BookOptionHotel', 'BookOptionFlight', 'Client', 'HotelSearch' );
		$this->id_operator = $etripOperator->id_operator;
		$this->name_operator = $etripOperator->name_operator;
		$this->file_url = $etripOperator->file_url;
		$this->cached_prices_url = $etripOperator->cached_prices_url;
		$this->classMap = $this->mapClasses();
		$this->SOAPClient = $this->connectToSOAP();
		$this->url = $etripOperator->url;

	}

	public function getHotels(){
		return $this->SOAPClient->GetHotels();
	}

	public function getPackages(){
		return $this->SOAPClient->GetPackages();
	}

	public function getGeography(){
		return $this->SOAPClient->GetGeography();
	}

	public function packageSearch($packageSearch){
		return $this->SOAPClient->PackageSearch($packageSearch);
	}

	public function hotelSearch($hotelSearch){
		return $this->SOAPClient->HotelSearch($hotelSearch);
	}

	public function book($bookingRequest){
		return $this->SOAPClient->Book($bookingRequest);
	}


}
