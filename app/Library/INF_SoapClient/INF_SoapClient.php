<?php

namespace INF_SoapClient;

use Config;
use SoapClient;
use INF_ISoapClient;

abstract class INF_SoapClient implements INF_ISoapClient{

	public $username;
	public $password;
	public $wsdl;
	protected $SOAPClient;
	protected $SOAPClasses;
	protected $classMap;
	public $id_operator;
	public $name_operator;
	public $file_url;
	public $cached_prices_url;
	public $url;

	public function connectToSOAP(){
		try{
			$client = new SoapClient($this->wsdl, array('trace' => 1, 'login' => $this->username, 'password' => $this->password, 'classmap' => $this->classMap ));
		} catch (SoapFault $exception) {
			throw $exception;
		}

		return $client;

	}

	public function __getLastRequest(){
		return $this->SOAPClient->__getLastRequest();
	}

	public function __getLastResponse(){
		return $this->SOAPClient->__getLastResponse();
	}

	public function mapClasses(){

		$classmap = array();

		foreach( $this->SOAPClasses as $SOAPClass ){
			$classmap[$SOAPClass] = $SOAPClass."SoapObject";
		}

		return $classmap;

	}

}
