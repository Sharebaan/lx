<?php

namespace App\Http\Controllers\Travel;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InfoController extends Controller
{
  public function contact(){
    $data['pageTitle'] = 'Contact';
  	$data['pageNote'] = 'Contact';
  	$data['pageMetakey'] = 'Contact';
  	$data['pageMetadesc'] = 'Contact';
  	$data['pages'] = 'pages.'.CNF_THEME.'.contact';
  	return view('layouts.'.CNF_THEME.'.index',$data);
  }
  public function desprenoi(){
    $data['pageTitle'] = 'Despre Noi';
  	$data['pageNote'] = 'Despre Noi';
  	$data['pageMetakey'] = 'Despre Noi';
  	$data['pageMetadesc'] = 'Despre Noi';
  	$data['pages'] = 'pages.'.CNF_THEME.'.despre-noi';
  	return view('layouts.'.CNF_THEME.'.index',$data);
  }
  public function modplata(){
    $data['pageTitle'] = 'Mod Plata';
  	$data['pageNote'] = 'Mod Plata';
  	$data['pageMetakey'] = 'Mod Plata';
  	$data['pageMetadesc'] = 'Mod Plata';
  	$data['pages'] = 'pages.'.CNF_THEME.'.mod-plata';
  	return view('layouts.'.CNF_THEME.'.index',$data);
  }
  public function informatii(){
    $data['pageTitle'] = 'Informatii';
  	$data['pageNote'] = 'Informatii';
  	$data['pageMetakey'] = 'Informatii';
  	$data['pageMetadesc'] = 'Informatii';
  	$data['pages'] = 'pages.'.CNF_THEME.'.informatii';
  	return view('layouts.'.CNF_THEME.'.index',$data);
  }
}
