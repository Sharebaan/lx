<?php namespace ET_SoapClient;

use Illuminate\Support\ServiceProvider;
use INF_SoapClient\INF_ISoapClient;
use ET_SoapClient;

class ET_SoapClientServiceProvider extends ServiceProvider {

    public function register()
    {

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('ET_SoapClient', 'ET_SoapClient\ET_SoapClient');
            $loader->alias('ET_SoapObject', 'ET_SoapClient\ET_SoapObject');
            $loader->alias('HotelSoapObject', 'ET_SoapClient\SoapObjects\HotelSoapObject');
            $loader->alias('FileInfoSoapObject', 'ET_SoapClient\SoapObjects\FileInfoSoapObject');
            $loader->alias('RoomCategorySoapObject', 'ET_SoapClient\SoapObjects\RoomCategorySoapObject');
            $loader->alias('DetailedDescriptionSoapObject', 'ET_SoapClient\SoapObjects\DetailedDescriptionSoapObject');
            $loader->alias('PackageInfoSoapObject', 'ET_SoapClient\SoapObjects\PackageInfoSoapObject');
            $loader->alias('GeographySoapObject', 'ET_SoapClient\SoapObjects\GeographySoapObject');
            $loader->alias('PriceSetSoapObject', 'ET_SoapClient\SoapObjects\PriceSetSoapObject');
            $loader->alias('PackageSearchSoapObject', 'ET_SoapClient\SoapObjects\PackageSearchSoapObject');
            $loader->alias('RoomSoapObject', 'ET_SoapClient\SoapObjects\RoomSoapObject');
            $loader->alias('ResultSoapObject', 'ET_SoapClient\SoapObjects\ResultSoapObject');
            $loader->alias('PriceInfoSoapObject', 'ET_SoapClient\SoapObjects\PriceInfoSoapObject');
            $loader->alias('PackageResultSoapObject', 'ET_SoapClient\SoapObjects\PackageResultSoapObject');
            $loader->alias('HotelResultSoapObject', 'ET_SoapClient\SoapObjects\HotelResultSoapObject');
            $loader->alias('BusResultSoapObject', 'ET_SoapClient\SoapObjects\BusResultSoapObject');
            $loader->alias('FlightResultSoapObject', 'ET_SoapClient\SoapObjects\FlightResultSoapObject');
            $loader->alias('JourneySoapObject', 'ET_SoapClient\SoapObjects\JourneySoapObject');
            $loader->alias('LegSoapObject', 'ET_SoapClient\SoapObjects\LegSoapObject');
            $loader->alias('TransferResultSoapObject', 'ET_SoapClient\SoapObjects\TransferResultSoapObject');
            $loader->alias('DiscountInfoSoapObject', 'ET_SoapClient\SoapObjects\DiscountInfoSoapObject');
            $loader->alias('SpecialOfferSoapObject', 'ET_SoapClient\SoapObjects\SpecialOfferSoapObject');
            $loader->alias('PaxInfoSoapObject', 'ET_SoapClient\SoapObjects\PaxInfoSoapObject');
            $loader->alias('BookRequestSoapObject', 'ET_SoapClient\SoapObjects\BookRequestSoapObject');
            $loader->alias('BookingSoapObject', 'ET_SoapClient\SoapObjects\BookingSoapObject');
            $loader->alias('BookingItemSoapObject', 'ET_SoapClient\SoapObjects\BookingItemSoapObject');
            $loader->alias('BookOptionHotelSoapObject', 'ET_SoapClient\SoapObjects\BookOptionHotelSoapObject');
            $loader->alias('BookOptionFlightSoapObject', 'ET_SoapClient\SoapObjects\BookOptionFlightSoapObject');
            $loader->alias('PricesCached', 'ET_SoapClient\Cache\PricesCached');
			$loader->alias('ClientSoapObject', 'ET_SoapClient\SoapObjects\ClientSoapObject');
            $loader->alias('HotelSearchSoapObject', 'ET_SoapClient\SoapObjects\HotelSearchSoapObject');

        });

    }

}