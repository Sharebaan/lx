<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class BusResultSoapObject extends INF_SoapObjectModelUnbindable{

	public $Label;
	public $From;
	public $To;
	public $OutboundDate;
	public $InboundDate;
	public $BusType;

}