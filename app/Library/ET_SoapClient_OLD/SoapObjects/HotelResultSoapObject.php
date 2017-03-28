<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\SoapObjects\ResultSoapObject;
use ET_SoapClient\ET_SoapClient;


class HotelResultSoapObject extends ResultSoapObject{

	public $HotelId;
	public $CategoryId;
	public $Allocation;
	public $Rooms;
	public $MealPlans;
	public $IsAvailable;
	public $Discounts;
	public $FareType;

}