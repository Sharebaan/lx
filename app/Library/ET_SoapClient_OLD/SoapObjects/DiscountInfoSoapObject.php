<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class DiscountInfoSoapObject extends INF_SoapObjectModelUnbindable{

	public $Label;
	public $Text;
	public $BookingFrom;
	public $BookingTo;
	public $TravelFrom;
	public $TravelTo;
	public $Percent;
	public $Value;


}