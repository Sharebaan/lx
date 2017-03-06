<?php
namespace INF_SoapClient;

interface INF_ISoapClient
{
	public function connectToSOAP();
	public function mapClasses();
}