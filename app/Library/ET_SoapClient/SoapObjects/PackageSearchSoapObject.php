<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class PackageSearchSoapObject extends INF_SoapObjectModelUnbindable{

	public $IsTour;
	public $IsFlight;
	public $IsBus;
	public $Departure;
	public $Destination;
	public $Hotel;
	public $DepartureDate;
	public $Duration;
	public $MinStars;
	public $Rooms;
	public $ShowBlackedOut;
	public $Currency;


	public function __construct($IsTour,$IsFlight,$IsBus,$Departure,$Destination,$Hotel,$DepartureDate,$Duration,$MinStars,$Rooms,$ShowBlackedOut,$Currency){
		$this->IsTour = $IsTour;
		$this->IsFlight = $IsFlight;
		$this->IsBus = $IsBus;
		$this->Departure = $Departure;
		$this->Destination = $Destination;
		$this->Hotel = $Hotel;
		$this->DepartureDate = $DepartureDate;
		$this->Duration = $Duration;
		$this->MinStars = $MinStars;
		$this->Rooms = $Rooms;
		$this->ShowBlackedOut = $ShowBlackedOut;
		$this->Currency = $Currency;
	}



}
