<?php

namespace INF_SoapClient;

abstract class INF_SoapObject {

	protected $SOAPClient;
	protected $isModelBindable;

	public function __construct(INF_ISoapClient $SOAPClient){

		$this->SOAPClient = $SOAPClient;

	}

	public function isModelBindable(){
		return $this->isModelBindable;
	}

}