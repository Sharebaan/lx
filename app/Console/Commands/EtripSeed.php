<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Travel\Hotel;
use App\Models\Travel\HotelSearchCached;
use App\Models\Travel\Geography;
use App\Models\Travel\MealPlanHotelSearch;
use App\Models\Travel\PackageInfo;
use App\Models\Travel\PackagesSearchCached;

use App\Models\Travel\FareType;

use App\Models\Travel\MealPlan;
use App\Models\Travel\MealPlanPackageSearch;


use App;
use DB;
use URL;
use View;
use Cache;
use ET_SoapClient\ET_SoapClient;
use ET_SoapClient\SoapObjects\RoomSoapObject;
use ET_SoapClient\SoapObjects\PackageSearchSoapObject;
use ET_SoapClient\SoapObjects\RoomCategorySoapObject;
use ET_SoapClient\SoapObjects\GeographySoapObject;
use ET_SoapClient\SoapObjects\HotelSoapObject;
use ET_SoapClient\SoapObjects\PackageInfoSoapObject;
use ET_SoapClient\Cache\PricesCached;
use App\Models\Travel\RoomCategory;
use App\Models\Travel\GeographyTemp;
use App\Models\Travel\EtripOperator;
use Config;
use Mail;

class EtripSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:etrip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed from etrip';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function send_message($id, $message, $progress){
  	    echo $message;
  	}

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $this->send_message(0, "LOG: Import started\n",0);
      if (!file_exists(public_path()."/images/offers")) {
          mkdir(public_path()."/images/offers");
      }
      ini_set('memory_limit','-1');
      $extraSoapClientsConfig = EtripOperator::all();
      $extraSoapClients = array();
      foreach($extraSoapClientsConfig as $extraSoapClientConfig){
        $extraSoapClients[] = new ET_SoapClient($extraSoapClientConfig->id_operator);
      }
      $this->send_message(3, "LOG: Importing hotels\n",20);
      $hotelIds = array();
      foreach($extraSoapClients as $eSOAPClient){
        $hotelIds[$eSOAPClient->id_operator] = HotelSoapObject::saveExtraHotels($eSOAPClient);
        HotelSoapObject::deleteFromDB($hotelIds[$eSOAPClient->id_operator],$eSOAPClient->id_operator);
      }
      $this->send_message(4, "LOG: Hotels import finished successfully\n",50);

      $this->send_message(5, "LOG: Importing packages\n",50);
      $packagesIds = array();
      foreach($extraSoapClients as $eSOAPClient){
        $packagesIds[$eSOAPClient->id_operator] = PackageInfoSoapObject::saveExtraPackages($eSOAPClient);
        PackageInfoSoapObject::deleteFromDB($packagesIds[$eSOAPClient->id_operator],$eSOAPClient->id_operator);
      }
      $this->send_message(6, "LOG: Packages import finished successfully\n",75);

      $this->send_message(7, "LOG: Importing cached prices\n",75);
      foreach($extraSoapClients as $eSOAPClient){
        $priceCaches = new PricesCached($eSOAPClient->id_operator);
        $priceCaches->saveToDB();
      }
      $this->send_message(8, "LOG: Cached prices import finished successfully\n",90);
      Cache::flush();
      $this->send_message(9, "LOG: Import finished successfully\n",100);
      $this->send_message(10, 'TERMINATE',100);

      Mail::raw('Import done!', function ($message) {
        $message->to('office@infora.ro')->subject('Import');
      });

      Mail::raw('Import done!', function ($message) {
        $message->to('sefulataste@gmail.com')->subject('Import');
      });
    }
}
