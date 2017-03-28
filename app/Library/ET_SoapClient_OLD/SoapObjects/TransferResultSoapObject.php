<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class TransferResultSoapObject extends INF_SoapObjectModelUnbindable{

	public $Label;
	public $From;
	public $To;
	public $Pickup;
	public $Dropoff;
	public $Date;
	public $Price;
	public $TotalDiscount;
	public $IsBookable;
	public $IsHotDeal;


}