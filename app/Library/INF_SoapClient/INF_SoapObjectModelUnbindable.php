<?php

namespace INF_SoapClient;

class INF_SoapObjectModelUnbindable extends INF_SoapObject{

	public function __construct(){
		$this->isModelBindable = false;
	}

}