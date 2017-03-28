<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class BookingItemSoapObject extends INF_SoapObjectModelUnbindable{

	public $Type;
	public $Status;
	public $Label;
	public $Description;
	public $StartDate;
	public $EndDate;


}