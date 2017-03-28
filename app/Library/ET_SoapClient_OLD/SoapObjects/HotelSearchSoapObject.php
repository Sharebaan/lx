<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class HotelSearchSoapObject extends INF_SoapObjectModelUnbindable{

	public $Destination;
	public $Hotel;
	public $CheckIn;
	public $Stay;
	public $MinStars;
	public $Rooms;
	public $PricesAsOf;
	public $ForPackage;
	public $ShowBlackedOut;
	public $Currency;


	public function __construct($Destination,$Hotel,$CheckIn,$Stay,$MinStars,$Rooms,$PricesAsOf,$ForPackage,$ShowBlackedOut,$Currency){
		$this->Destination = $Destination;
		$this->Hotel = $Hotel;
		$this->CheckIn = $CheckIn;
		$this->Stay = $Stay;
		$this->MinStars = $MinStars;
		$this->Rooms = $Rooms;
		$this->PricesAsOf = $PricesAsOf;
		$this->ForPackage = $ForPackage;
		$this->ShowBlackedOut = $ShowBlackedOut;
		$this->Currency = $Currency;
	}



}