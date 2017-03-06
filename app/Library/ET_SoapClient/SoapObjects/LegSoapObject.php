<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class LegSoapObject extends INF_SoapObjectModelUnbindable{

	public $From;
	public $To;
	public $Departure;
	public $Arrival;
	public $Airline;
	public $FlightNo;
	public $Class;
	public $DepTerminal;
	public $ArrTerminal;
	public $DepGate;
	public $ArrGate;
	public $FlightTime;


}