
<link rel="stylesheet" href="{{ asset('sximo/themes/luxuria/js/jquery-loading/waitMe.css') }}" />
<script type="text/javascript" src="{{ asset('sximo/themes/luxuria/js/jquery-loading/waitMe.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('sximo/themes/luxuria/js/search_ajax_jsonp.js') }}"></script> 
<script src="{{ asset('js/vex.combined.min.js') }}"></script>
<script>vex.defaultOptions.className = 'vex-theme-default';</script>
<link rel="stylesheet" href="{{ asset('css/vex.css') }}" />
<link rel="stylesheet" href="{{ asset('css/vex-theme-default.css') }}" />
<script type="text/javascript">
	$(document).ready(function(){
      	$('#guests').roomify(null);
      	//$('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
      	$('.ba-item-view-search-type-option').click(function(e){
      		$('.ba-item-view-search-type-option').removeClass('search_active');
      		$(e.target).addClass('search_active');
      		$('.ba-input-search-type').prop("checked",false).trigger('change');
      		var radioInput = $(e.target).find(".ba-input-search-type");
      		radioInput.prop("checked",true).trigger('change');
      		if(radioInput.val() == 3){
      			$('.input-others').hide();
      			$('.input-hotel').show();
      		} else {
      			$('.input-hotel').hide();
      			$('.input-others').show();
      		}
      	});
	})
</script>
<!-- slider -->
<script class="rs-file" src="/js/slider/jquery.royalslider.min.js"></script>
<link class="rs-file" href="/js/slider/royalslider.css" rel="stylesheet">
<link class="rs-file" href="/js/slider/rs-minimal-white.css" rel="stylesheet"> 
<script>
	jQuery(document).ready(function($) {
	  $('#full-width-slider').royalSlider({
	    arrowsNav: true,
	    loop: true,
	    keyboardNavEnabled: true,
	    controlsInside: false,
	    imageScaleMode: 'fill',
	    arrowsNavAutoHide: false,
	    autoScaleSlider: true, 
	    autoScaleSliderWidth: 870,     
	    autoScaleSliderHeight: 420,
	    controlNavigation: 'bullets',
	    thumbsFitInViewport: false,
	    navigateByClick: true,
	    startSlideId: 0,
		slidesSpacing: 0,
	    transitionType:'move',
	    globalCaption: false,
	    deeplinking: {
	      enabled: true,
	      change: false,
	    },
		autoPlay: {
	            enabled: true,
	            pauseOnHover: true,
				delay: 6000,
				transitionSpeed: 600,
	        },
	    imgWidth: 1200,
	    imgHeight: 394,
	  });
	});
	</script>		
<!-- slider -->
<div class="container">
	<div class="row">
		<div class="left_search col-md-3">
			<div class="ba-item-view-search clearfix home_search_box">
			  <div class="ba-item-view-search-type clearfix">
		        <span class="ba-item-view-search-type-option search_active"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="1" checked> Sejururi</span>
		        <span class="ba-item-view-search-type-option"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="2"> Circuite</span>
		        <span class="ba-item-view-search-type-option"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="3"> Hoteluri</span>
		        <div class="clear"></div>
		      </div>
		      <div class="ba-item-view-search-in clearfix">
		          <div class="ba-search-transport-type input-others">
		            <div class="ba-search-selector-div">
		              <select id="searchTransportType" class="ba-search-selector">
		              	<option value="0">* Tip transport</option>
			            <option value="1">Avion</option>
			            <option value="2">Autocar</option>
			            <option value="3">Transport Individual</option>
		              </select>
		            </div>
		          </div>
		          <div class="ba-search-departure-point input-others">
		            <div class="ba-search-selector-div">
		              <select id="searchDeparturePoint" class="ba-search-selector">
		              	<option value="0">* Plecare din</option>
		              </select>
		            </div>
		          </div>
		          <div class="ba-search-country-destination">
		            <div class="ba-search-selector-div">
		              <select id="searchCountryDestination" class="ba-search-selector">
		              	<option value="0">* Destinatie</option>
		              </select>
		            </div>
		          </div>
		          <div class="ba-search-city-destination">
		            <div class="ba-search-selector-div">
		              <select id="searchCityDestination" class="ba-search-selector">
		              	<option value="0">* Oras</option>
		              </select>
		            </div>
		          </div>
		          <div class="ba-search-departure-date input-others">
		            <div class="ba-search-selector-div">
		              <select id="searchDepartureDate" class="ba-search-selector">
		              	<option value="0">* Data plecare</option>
		              </select>
		            </div>
		          </div>
		          <div class="ba-search-duration input-others">
		            <div class="ba-search-selector-div">
		              <select id="searchDuration" class="ba-search-selector">
		              	<option value="0">* Durata</option>
		              </select>
		            </div>
		          </div>
		          <div class="ba-search-departure-date input-hotel">
		            <div class="ba-search-selector-div">
		              <input type="text" id="searchDepartureDateHotel" value="* Data plecare" class="datepicker ba-search-selector" readonly/>
		            </div>
		          </div>
		          <div class="ba-search-return-date input-hotel">
		            <div class="ba-search-selector-div">
		              <input type="text" id="searchArrivalDateHotel" value="* Data intoarcere" class="datepicker ba-search-selector" readonly/>
		            </div>
		          </div>
			      	<div class="ba-search-guests">
		          	<div id="guests"></div>
		          </div>
		          <button id="searchButton">Cauta</button>
		    	</div>
		    	<div class="clear"></div>
			</div>
		</div>
		<!-- <div class="col-md-9 slider"> -->
		<div class="col-md-9">
			<!-- <img src="/images/slider/split.jpg" alt="" /> -->
			<div id="full-width-slider" class="royalSlider rsMinW">
				
				<div class="rsContent">
        <a href="/page/bulgaria"><img src="/sximo/themes/luxuria/images/slider/split3.jpg" alt="" /></a>
        </div>
        
        <div class="rsContent">
        <a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=145&sortBy=price&sortOrder=ASC"><img src="/sximo/themes/luxuria/images/slider/split1.jpg" alt="" /></a>
        </div>
        
        <div class="rsContent">
        <a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=168&sortBy=price&sortOrder=ASC"><img src="/sximo/themes/luxuria/images/slider/split2.jpg" alt="" /></a>
        </div>
                
      </div>
		</div>
	</div>
</div>

<section class="top-promotions">
	<div class="container">
		<div class="row">
			<div class="sep-30"></div>
			<h2 class="section_title">OFERTE SPECIALE</h2>
			<h3 class="section_subtitle">Cele mai bune pachete disponibile</h3>
			<div class="sep-line-3-outer">
				<div class="sep-line-3"></div>
			</div>
			<div class="container_oferte">
				@foreach($featuredPackages as $package)
				<?php
			        $location = App\Models\Travel\Geography::getCountryForHotel($package->id_hotel,$package->soap_client);
			        $transport = App\Models\Travel\PackageInfo::getTransportCode($package->is_tour,$package->is_bus,$package->is_flight);
			        $link = "/oferte/".$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->hotel->name)))."_".$transport."_".$package->id_hotel."_".$package->soap_client."_sid0";
			    ?>
				<!-- START TRIP ITEM -->
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 trip_item">
						<div class="trip_img" style="background-image: url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}');">
							<!-- <img src="{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}"> -->
						</div>
						<div class="trip_location">
							<i class="icon-location"></i>&nbsp;<span>{{$package->hotel->getFormatedLocation()}}</span>
							<a href="{{$link}}" class="trip_link">DETALII</a>
						</div>
						<div class="trip_name">
							<h4>{{$package->hotel->name}}</h4>
						</div>
						<div class="trip_desc">
							<p>
								@if($package->hotel->description != '')
								{{mb_strimwidth($package->hotel->description, 0, 120, "...")}}
								@else
								<br/><br/><br/>
								@endif
							</p>
						</div>
				</div>
				<!-- END TRIP ITEM -->
				@endforeach
				<div class="clear"></div>
			</div>
			<div class="home_ul">
				<ul>
					<li><a href="#" class="blue_yellow">TOATE PACHETELE</a></li>
					<li><a href="#" class="blue_yellow">PROMOTII</a></li>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</section>
<section style="background:url('/sximo/themes/luxuria/images/love-travel-27-1920.jpg') 50% 0 fixed; background-size:cover;" id="destination-parallax">
	<div class="transparent_background">
		<div class="container">
			<div class="row">
				<div class="all_center text_white">
					<h3>Promotii Vara</h3>
					<h1>SANTORINI - GREEK ISLAND TOUR</h1>
					<h3>1000 $ for person - 6 nights</h3>
					<a href="#" class="blue_yellow">MORE DETAILS</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="our-destinations">
	<div class="container">
		<div class="row">
			<h2 class="section_title">DESTINATIILE NOASTRE</h2>
			<h3 class="section_subtitle">Alege urmatoarea ta destinatie</h3>
			<div class="sep-line-3-outer">
				<div class="sep-line-3"></div>
			</div>
			<div class="container_oferte">
				<!-- START ITEM -->
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 destination_item">
					<div class="destination_inside">
						<img alt="" src="/sximo/themes/luxuria/images/love-travel-32-556.jpg">
						<div class="destination_title">EUROPA</div>
						<div class="destination_under text_white">
							<div class="display_table">
								<div class="vertical_middle">
									<p>Descopera toate destinatiile</p>
									<div class="sep-20"></div>
									<a href="#" class="blue_yellow">VIEW ALL</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END ITEM -->
				
				<!-- START ITEM -->
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 destination_item">
					<div class="destination_inside">
						<img alt="" src="/sximo/themes/luxuria/images/love-travel-3-556.jpg">
						<div class="destination_title">ASIA</div>
						<div class="destination_under text_white">
							<div class="display_table">
								<div class="vertical_middle">
									<p>Descopera toate destinatiile</p>
									<div class="sep-20"></div>
									<a href="#" class="blue_yellow">VIEW ALL</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END ITEM -->
				
				<!-- START ITEM -->
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 destination_item">
					<div class="destination_inside">
						<img alt="" src="/sximo/themes/luxuria/images/love-travel-34-556.jpg">
						<div class="destination_title">AMERICA</div>
						<div class="destination_under text_white">
							<div class="display_table">
								<div class="vertical_middle">
									<p>Descopera toate destinatiile</p>
									<div class="sep-20"></div>
									<a href="#" class="blue_yellow">VIEW ALL</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END ITEM -->
				
				<!-- START ITEM -->
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 destination_item">
					<div class="destination_inside">
						<img alt="" src="/sximo/themes/luxuria/images/love-travel-15-556.jpg">
						<div class="destination_title">AFRICA</div>
						<div class="destination_under text_white">
							<div class="display_table">
								<div class="vertical_middle">
									<p>Descopera toate destinatiile</p>
									<div class="sep-20"></div>
									<a href="#" class="blue_yellow">VIEW ALL</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END ITEM -->
		
				<div class="clear"></div>
			</div>
		</div>
	</div>
</section>

<section style="background:url('/sximo/themes/luxuria/images/love-travel-35-1920.jpg') 50% 0 fixed; background-size:cover;" id="counter-parallax">
	<div class="transparent_background">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="conter_circle">
						<div class="display_table">
							<div class="vertical_middle">
								<p class="circle_big">75</p>
								<p>DESTINATIONS</p>
							</div>
						</div>
					</div>
				</div>
	
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="conter_circle">
						<div class="display_table">
							<div class="vertical_middle">
								<p class="circle_big">149</p>
								<p>TOURS PACK</p>
							</div>
						</div>
					</div>
				</div>
	
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="conter_circle">
						<div class="display_table">
							<div class="vertical_middle">
								<p class="circle_big">32</p>
								<p>CRUISES</p>
							</div>
						</div>
					</div>
				</div>
	
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="conter_circle">
						<div class="display_table">
							<div class="vertical_middle">
								<p class="circle_big">24</p>
								<p>HOTELS</p>
							</div>
						</div>
					</div>
 				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</section>













