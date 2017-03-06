<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\SoapObjects\ResultSoapObject;
use ET_SoapClient\ET_SoapClient;


class PackageResultSoapObject extends ResultSoapObject{

	public $Departure;
	public $Destination;
	public $Duration;
	public $Date;
	public $UsesAllocation;
	public $PackageId;
	public $IsHotDeal;
	public $HotelInfo;
	public $FlightInfo;
	public $BusInfo;
	public $TransferInfo;
	public $DiscountInfo;
	public $Specials;
	public $FareType;
	public $IsAvailable;
	public $ExtraComponents;

}