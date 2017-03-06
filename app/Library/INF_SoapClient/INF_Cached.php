<?php

namespace INF_SoapClient;

abstract class INF_Cached{

	protected $url;
	protected $name;
	protected $data;
	protected $username;
	protected $password;

	public function __construct(){
		//dd($this->username.':'.$this->password);
		$this->setAuthorization();
		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header' => "Authorization: Basic " . base64_encode($this->username.':'.$this->password)
		  )
		);
		//false,
		$lines = file($this->url,true,stream_context_create($opts));
		foreach ($lines as $line) {
		    $this->data[] = str_getcsv($line);
		}
	}

	public abstract function setAuthorization();

	public abstract function saveToDB();

}
