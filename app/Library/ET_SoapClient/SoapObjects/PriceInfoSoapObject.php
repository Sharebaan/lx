<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class PriceInfoSoapObject extends INF_SoapObjectModelUnbindable{

	public $Gross;
	public $Commission;
	public $VAT;
	public $Tax;
	public $IsAvailable;


}