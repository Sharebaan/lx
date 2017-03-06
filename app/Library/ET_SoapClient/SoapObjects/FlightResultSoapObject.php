<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\SoapObjects\ResultSoapObject;
use ET_SoapClient\ET_SoapClient;


class FlightResultSoapObject extends ResultSoapObject{

	public $Outbound;
	public $Inbound;

}