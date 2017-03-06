<?php

namespace INF_SoapClient;

abstract class INF_SoapObjectModelBindable extends INF_SoapObject{

	protected $model;
	

	public function __construct(){
		$this->isModelBindable = true;
		$this->setModelClass();
	}

	/*
	 * TODO: Implement a default way of mapping a SOAP Object to a model 
	 */

	abstract protected function setModelClass();

	public function getModelClass(){
		return $this->model;
	}

	abstract protected function saveToDB($foreignKeys = null);

}