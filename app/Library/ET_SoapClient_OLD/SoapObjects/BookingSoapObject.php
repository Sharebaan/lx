<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class BookingSoapObject extends INF_SoapObjectModelUnbindable{

	public $Reference;
	public $Created;
	public $StartDate;
	public $EndDate;
	public $BalanceDueDate;
	public $InvoiceCurrency;
	public $Price;
	public $Items;


}