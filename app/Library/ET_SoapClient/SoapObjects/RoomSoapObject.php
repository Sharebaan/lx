<?php
namespace ET_SoapClient\SoapObjects;

use INF_SoapClient\INF_SoapObjectModelUnbindable;
use ET_SoapClient\ET_SoapClient;


class RoomSoapObject extends INF_SoapObjectModelUnbindable{

	public $Adults;
	public $ChildAges;


	public function __construct($Adults,$ChildAges){
		$this->Adults = $Adults;
		$this->ChildAges = $ChildAges;
	}



}