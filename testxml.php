<?php
function crs_post($id,$key,$fn,$xml='')

{

   $ch = curl_init();

   $link = "b2b.allinclusivebg.com";

   curl_setopt($ch, CURLOPT_URL, $link);

   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   curl_setopt($ch, CURLOPT_POST, true);

   curl_setopt($ch, CURLOPT_POSTFIELDS,
"fn=".$fn."&key=".$key."&id=".$id."&xml=".$xml);

   curl_setopt($ch, CURLOPT_REFERER, "Referer: http://travel.denku.ro");

   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

   $data = curl_exec($ch); 

   curl_close($ch);

   return $data;

}

 

   

   $key = 'TEST-TEST-TEST-TEST-TEST';

   $id = 713;

 

   header("Content-Type: text/html; charset=utf8");

 

   // List with hotel names

   

   $fn = 'hotel_list';

   $data =

     '<?xml version="1.0" encoding="windows-1251"?>

      <hotel_list>

       <hotelinfo>

        <Hotel>%</Hotel>

        <City>%</City>

      <Country>BULGARIA</Country>

        <HotelType>%</HotelType>

       </hotelinfo>

      </hotel_list>';

   $xml = crs_post($id,$key,$fn,$data);

   echo $xml;
?>
