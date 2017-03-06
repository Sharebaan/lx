<?php

return array(
	
	'wsdl'  => 'http://etrip.holidayoffice.ro/ws.php?op=etrip_webservice&wsdl',
	'file_url' => 'http://etrip.holidayoffice.ro/file.php?file=',
	'username' => 'vera.travel',
	'password' => 'qfxMDnnEvK6N9J8E',
	'classes' => array( 'Hotel','RoomCategory','FileInfo','DetailedDescription','PackageInfo','Geography','PriceSet','PackageSearch','Room','Result','PriceInfo','PackageResult',
						'HotelResult', 'BusResult', 'FlightResult', 'Journey', 'Leg', 'TransferResult', 'DiscountInfo', 'SpecialOffer','PaxInfo','BookRequest','Booking','BookingItem',
						'BookOptionHotel', 'BookOptionFlight', 'Client','HotelSearch' ),
	'cache' => array( 'prices' => 'http://etrip.holidayoffice.ro/wscsv.php')

);