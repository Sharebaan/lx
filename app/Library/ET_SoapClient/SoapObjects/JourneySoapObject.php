<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class JourneySoapObject extends INF_SoapObjectModelUnbindable{

	public $Airline;
	public $Class;
	public $Cabin;
	public $CabinName;
	public $Legs;

}