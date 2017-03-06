<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class BookRequestSoapObject extends INF_SoapObjectModelUnbindable{

	public $PaxInfo;
	public $ResultIndex;
	public $ClientReference;
	public $HotelOptions;
	public $FlightOptions;
	public $Status;
	public $Client;


}