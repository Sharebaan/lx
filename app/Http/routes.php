<?php



Route::get('/', 'HomeController@index');
Route::controller('home', 'HomeController');

Route::get('/page/{slug}',"StaticPageController@index");

Route::controller('importdata', 'ImportdataController');


Route::controller('/user', 'UserController');
include('moduleroutes.php');


Route::get('/restric',function(){
	return view('errors.blocked');
});
Route::get('/error/404',['as' => 'error.404',function(){
	return view('pages.'.CNF_THEME.'.error',['errorMessage'=>'Not Available','errorNumber'=>404,'errorUrl'=>$_SERVER['SERVER_NAME']]);
}]);

Route::get('/clear-cache','HomeController@clearCache');


Route::resource('sximoapi', 'SximoapiController');
Route::group(['middleware' => 'auth'], function()
{

	Route::get('core/elfinder', 'Core\ElfinderController@getIndex');
	Route::post('core/elfinder', 'Core\ElfinderController@getIndex');
	Route::controller('/dashboard', 'DashboardController');
	Route::controllers([
		'core/users'		=> 'Core\UsersController',
		'notification'		=> 'NotificationController',
		'core/logs'			=> 'Core\LogsController',
		'core/pages' 		=> 'Core\PagesController',
		'core/groups' 		=> 'Core\GroupsController',
		'core/template' 	=> 'Core\TemplateController',
	]);

});

Route::group(['middleware' => 'auth' , 'middleware'=>'sximoauth'], function()
{

	Route::controllers([
		'sximo/menu'		=> 'Sximo\MenuController',
		'sximo/config' 		=> 'Sximo\ConfigController',
		'sximo/module' 		=> 'Sximo\ModuleController',
		'sximo/tables'		=> 'Sximo\TablesController'
	]);

	Route::get('/import/etrip',"Travel\ImportController@importEtrip");
	Route::get('/import/etrip/curl',"Travel\ImportController@importEtripCurl");
	Route::get('/import',"Travel\ImportController@importView");

	


});
/*
 |--------------------------------------------------------------------------
 | TwoPerformant
 |--------------------------------------------------------------------------
 */

Route::get('/2performant/{type}','Travel\TwoPerformantController@categorie');

/*
|--------------------------------------------------------------------------
| Offers Routes
|--------------------------------------------------------------------------
*/



Route::get('/oferte/sejururi',array('as' => 'package_listing', 'uses' => 'Travel\OffersController@listPackages'));
Route::get('/oferte/circuite',array('as' => 'circuits_listing', 'uses' => 'Travel\OffersController@listCircuits'));
Route::get('/oferte/hoteluri',array('as' => 'hotels_listing', 'uses' => 'Travel\OffersController@listHotels'));
Route::get('/oferte/{offertype}/{country}/{name}_{transport}_{id}_{soapClient}_sid{sid}',"Travel\OffersController@view");



/*
|--------------------------------------------------------------------------
| Orders Routes
|--------------------------------------------------------------------------
*/

Route::get('/rezerva/pachet/ref{id}',"Travel\OrdersController@order_package");
Route::post('/rezerva/pachet/ref{id}/valideaza',['as' => 'order.validate', 'uses' => 'Travel\OrdersController@validate_package']);
Route::get('/rezerva/hotel/ref{id}',"Travel\OrdersController@order_hotel");
Route::post('/rezerva/hotel/ref{id}/valideaza',['as' => 'order.validate_hotel', 'uses' => 'Travel\OrdersController@validate_hotel']);
Route::get('/rezerva/thankyou',['as' => 'order.thankyou', 'uses' => 'Travel\OrdersController@thankyou']);
Route::get('/online_failed/{type}',['as' => 'order.onlinefailed', 'uses' => 'Travel\OrdersController@online_error']);
Route::post('/mobilpay/confirm',['as' => 'mobilpay.confirm', 'uses' => 'Travel\OrdersController@confirmMobilpayOrder']);
Route::get('/cere_oferta/ref{id}',"Travel\OrdersController@ask_for_offer");
Route::post('/cere_oferta/ref{id}/valideaza',['as' => 'ask_for_offer.validate', 'uses' => 'Travel\OrdersController@ask_for_offer_validate']);
Route::post('/savetosession','Travel\OrdersController@savetosession');
Route::get('/savepaymenttosession/{id}','Travel\OrdersController@savepaymenttosession');
Route::get('/inregistrare_plata_client','Travel\OrdersController@inregistrare_plata_client');
Route::get('/paymentdone','Travel\OrdersController@paymentdone');
/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
*/

Route::group(array('prefix' => '/ajax_search'), function()
{
	Route::get('/getTransportTypes', "Travel\SearchAjaxController@getTransportTypes");
	Route::get('/getDeparturePoints', "Travel\SearchAjaxController@getDeparturePoints");
    Route::get('/getCountryDestination', "Travel\SearchAjaxController@getCountryDestination");
    Route::get('/getCityDestination', "Travel\SearchAjaxController@getCityDestination");
    Route::get('/getDepartureDates', "Travel\SearchAjaxController@getDepartureDates");
    Route::get('/getDurations', "Travel\SearchAjaxController@getDurations");
    Route::get('/packageSearch', "Travel\SearchAjaxController@packageSearch");
    Route::get('/hotelSearch', "Travel\SearchAjaxController@hotelSearch");
    Route::get('/singlePackageSearch', "Travel\SearchAjaxController@singlePackageSearch");
    Route::get('/singleHotelSearch', "Travel\SearchAjaxController@singleHotelSearch");
    Route::get('/singlePackageSearchBeforeBooking', "Travel\SearchAjaxController@singlePackageSearchBeforeBooking");
    Route::get('/singleHotelSearchBeforeBooking', "Travel\SearchAjaxController@singleHotelSearchBeforeBooking");
    Route::get('/askForOffer',"Travel\SearchAjaxController@askForOffer");

		Route::get('/suggestions','Travel\SearchAjaxController@searchSuggestion');//

		Route::get('/saveExtraToSession','Travel\SearchAjaxController@saveExtraToSession');
});
Route::get('/hotel/{id}','Travel\HotelsController@searchSelect');
Route::get('/search',['as'=>'search_hotel','uses'=>'Travel\HotelsController@search']);

Route::get('/contact','Travel\InfoController@contact');

Route::get('/confirmareplata','Travel\OrdersController@confirmareplata');
Route::get('/finalizare','Travel\OrdersController@finalizareplata');


//======================================== UTILS ===================================
Route::get('/geo',function(){
	$temp = \DB::table('geographies_temp')->get();
	
	foreach($temp as $v){
		$g = new App\Models\Travel\Geography;
		$g->id = $v->id;
		$g->id_parent = $v->id_parent;
		$g->min_val = $v->min_val;
		$g->max_val = $v->max_val;
		$g->availability = $v->soap_client.':'.$v->id;
		$g->int_name = $v->name;
		$g->name = $v->name;
		$g->tree_level = $v->tree_level;
		$g->available_in_stays = 1;
		$g->available_in_circuits = 1;
		$g->save();
	}
	
});
/*Route::get('/da',function(){

	$eco = \DB::table('hoteluri_eco')->get();
	$finfo = \DB::table('file_infos')->get();
	//$items = [];
	//dd(empty(\DB::table('file_infos')->where('id',83)->get()));
	foreach($eco as $k=>$v){
		$h = \DB::table('file_infos')->where('id_hotel',$v->id_hotel);
		$hotel = \DB::table('hotels')->where('code',$v->id_hotel);
		if(!empty($h->get())){
				$poze = [$v->poza1,$v->poza2,$v->poza3,$v->poza4,$v->poza5,$v->poza6,$v->poza7,$v->poza8];
				//$rezpe=[];
				foreach($poze as $n=>$p){
					//$rezpe[$n+1]= empty($p)?'':true;
					$last = \DB::table('file_infos')->orderBy('id','DESC')->first();
					//dd($last);
					$nme = 'poza'.($n+1);
					if(!empty($p)){
						//var_dump($v->$nme,$h->id_hotel);
						\DB::table('file_infos')->insert([
							'id'=>$last->id+1,
							'id_hotel'=>$hotel->first()->id,
							'soap_client'=>'LOCAL',
							'name'=>$v->$nme,
							'mime_type'=>'image/jpeg'
						]);
					}

				}
				//die;
				//dd(array_filter($rezpe));


				//$items[$k] = \DB::table('file_infos')->where('id',$v->id_hotel)->get();
		}
	}
	//$items = array_values($items);
	dd('done');



});*/
