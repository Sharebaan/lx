
<script type="text/javascript" src="/js/roomify.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="/css/roomify.css" />
<link rel="stylesheet" href="/sximo/themes/default/js/jquery-loading/waitMe.css" />
<script type="text/javascript" src="/sximo/themes/default/js/jquery-loading/waitMe.min.js"></script>
<script type="text/javascript" src="/sximo/themes/default/js/search_ajax_jsonp.js"></script>
<script src="/js/vex.combined.min.js"></script>
<script>vex.defaultOptions.className = 'vex-theme-default';</script>
<link rel="stylesheet" href="/css/vex.css" />
<link rel="stylesheet" href="/css/vex-theme-default.css" />
<script type="text/javascript">
	$(document).ready(function(){
      	$('#guests').roomify(null);
      	$('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
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
<section class="slider">
	<div class="nicdark_container nicdark_vc nicdark_clearfix">
		<div class="vc_col-sm-6 wpb_column vc_column_container ">
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
		          <div class="clear sep-20"></div>
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
		          <div class="clear sep-20"></div>
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
		          <div class="clear sep-20"></div>
		          <button id="searchButton">Cauta</button>
		    	</div>
		    	<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</section>
<section class="top-promotions">
	<div class="nicdark_container nicdark_vc nicdark_clearfix">
		<div class="vc_col-sm-12 wpb_column vc_column_container ">
			<div class="vc_empty_space" style="height: 20px">
				<span class="vc_empty_space_inner"></span>
			</div>
			<h2>OFERTE SPECIALE</h2>
			<h3 class="subtitle center">Cele mai bune pachete disponibile</h3>
			<div class="nicdark_divider center big ">
				<span style="background-color:#f1f1f1;"></span>
			</div>
			<div class="vc_empty_space" style="height: 20px">
				<span class="vc_empty_space_inner"></span>
			</div>
			<div class="nicdark_masonry_container">

				@foreach($featuredPackages as $package)
				<?php
			        $location = App\Models\Travel\Geography::getCountryForHotel($package->id_hotel,$package->soap_client);
			        $transport = App\Models\Travel\PackageInfo::getTransportCode($package->is_tour,$package->is_bus,$package->is_flight);
			        $link = "/oferte/".$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->hotel->name)))."_".$transport."_".$package->id_hotel."_".$package->soap_client."_sid0";
			    ?>
				<!-- START TRIP ITEM -->
				<div id="257" class="grid grid_4 percentage nicdark_masonry_item nicdark_padding10 nicdark_sizing">
					<div class="nicdark_archive1 nicdark_bg_white nicdark_border_grey nicdark_sizing ">
						<!--start image-->
						<div class="nicdark_focus nicdark_relative nicdark_fadeinout nicdark_overflow">
							<img alt="" class="nicdark_focus nicdark_zoom_image" style="height: 248px;" src="{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}">
							<!--price-->
							<!--
							<div class="nicdark_fadeout nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
								<a href="#" class="nicdark_btn nicdark_bg_white left grey medium">de la {{$package->estPrice()}} EUR</a>
							</div>
							-->
							<!--end price-->
							<!--start content-->
							<div class="nicdark_fadein nicdark_filter greydark nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
								<div class="nicdark_absolute nicdark_display_table nicdark_height100percentage nicdark_width_percentage100">
									<div class="nicdark_cell nicdark_vertical_middle">
										<a href="{{$link}}" class="nicdark_btn nicdark_border_white white medium">VEZI DETALII</a>
									</div>
								</div>
							</div>
							<!--end content-->
						</div>
						<!--end image-->
						<div class="nicdark_textevidence nicdark_bg_greydark">
							<h4 class="white nicdark_margin20">{{$package->hotel->name}}</h4>
						</div>
						<div class="nicdark_focus nicdark_bg_green">
							<div class="nicdark_bg_greendark nicdark_focus nicdark_padding1020 nicdark_sizing nicdark_width_percentage100">
								<p class="white">
									<i class="icon-direction"></i> {{$package->hotel->getFormatedLocation()}}
								</p>
							</div>
						</div>
						<div class="nicdark_margin20">
							<p>
								@if($package->hotel->description != '')
								{{mb_strimwidth($package->hotel->description, 0, 120, "...")}}
								@else
								<br/><br/><br/>
								@endif
							</p>
							<div class="nicdark_space20"></div><a href="{{$link}}" class="nicdark_border_grey grey nicdark_btn nicdark_outline medium ">DETALII</a>
							<!--start pop up window-->
							<div id="nicdark_window_popup_257" class="nicdark_bg_greydark nicdark_window_popup zoom-anim-dialog mfp-hide">
								<div class="nicdark_textevidence nicdark_bg_green">
									<div class="nicdark_margin20">
										<h4 class="white">JOIN ALL TOUR</h4>
									</div>
								</div>

							</div>
							<!--end pop up window-->
						</div>
					</div>
				</div>
				<!-- END TRIP ITEM -->
				@endforeach
				<div class="clear"></div>
			</div>
			<div class="vc_empty_space" style="height: 20px">
				<span class="vc_empty_space_inner"></span>
			</div>
			<div class="vc_col-sm-6 wpb_column vc_column_container ">
				<div class="wpb_wrapper">
					<a title="" target="" href="#" class="nicdark_disable_floatright_iphonepotr nicdark_margintop20 nicdark_btn nicdark_bg_greydark medium right   nicdark_square white">ALL PACKAGES</a>
				</div>
			</div>
			<div class="vc_col-sm-6 wpb_column vc_column_container ">
				<div class="wpb_wrapper">
					<a title="" target="" href="#" class="nicdark_disable_floatleft_iphonepotr nicdark_margintop20 nicdark_btn nicdark_bg_greydark medium    nicdark_square white">PROMOTIONS</a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</section>
<section style="background:url(http://www.nicdarkthemes.com/themes/love-travel/wp/demo-travel/wp-content/uploads/2015/05/love-travel-27-1920.jpg) 50% 0 fixed; background-size:cover;" id="destination-parallax" class="nicdark_section  nicdark_imgparallax  vc_row wpb_row vc_row-fluid">
	<div class="nicdark_filter greydark2">
		<div class="nicdark_container nicdark_vc nicdark_clearfix">
			<div class="vc_col-sm-12 nicdark_center wpb_column vc_column_container ">
				<div class="wpb_wrapper">
					<div class="vc_empty_space" style="height: 100px">
						<span class="vc_empty_space_inner"></span>
					</div>

					<h3 style="color:#ffffff;" class=" title center">Promotii Vara</h3>
					<div class="vc_empty_space" style="height: 20px">
						<span class="vc_empty_space_inner"></span>
					</div>

					<div class="nicdark_divider  center small ">
						<span style="background-color:#ffffff;"></span>
					</div>
					<div class="vc_empty_space" style="height: 20px">
						<span class="vc_empty_space_inner"></span>
					</div>

					<h1 style="color:#ffffff;" class=" subtitle center">SANTORINI - GREEK ISLAND TOUR</h1>
					<div class="vc_empty_space" style="height: 20px">
						<span class="vc_empty_space_inner"></span>
					</div>

					<h3 style="color:#ffffff;" class=" title center">1000 $ for person - 6 nights</h3>
					<div class="vc_empty_space" style="height: 30px">
						<span class="vc_empty_space_inner"></span>
					</div>

					<a title="" target="#" href="" class=" nicdark_btn nicdark_bg_blue medium    nicdark_square white"> <i class=" nicdark_marginright10  icon-anchor"></i>MORE DETAILS</a>
					<div class="vc_empty_space" style="height: 100px">
						<span class="vc_empty_space_inner"></span>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</section>
<div class="clear"></div>
<section class="our-destinations">
	<div class="nicdark_container nicdark_vc nicdark_clearfix">
		<div class="vc_col-sm-12 wpb_column vc_column_container ">
			<h2>DESTINATII NOASTRE</h2>
			<h3 class="subtitle center">ALEGE URMATOAREA TA DESTINATIE</h3>
			<div class="nicdark_divider center big ">
				<span style="background-color:#f1f1f1;"></span>
			</div>
			<div class="vc_empty_space" style="height: 20px">
				<span class="vc_empty_space_inner"></span>
			</div>
		</div>
	</div>
	<div class="vc_empty_space" style="height: 20px">
		<span class="vc_empty_space_inner"></span>
	</div>
	<div class="nicdark_container nicdark_vc nicdark_clearfix">
		<!-- START ITEM -->
		<div class="vc_col-sm-3 wpb_column vc_column_container ">
			<div class="wpb_wrapper">
				<!--start image-->
				<div class="nicdark_focus nicdark_border_grey nicdark_sizing nicdark_relative nicdark_fadeinout nicdark_overflow">
					<img alt="" class="nicdark_focus nicdark_zoom_image" src="http://www.nicdarkthemes.com/themes/love-travel/wp/demo-travel/wp-content/uploads/2015/04/love-travel-32-556.jpg">
					<!--start content-->
					<div class="nicdark_fadeout nicdark_filter nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
						<div class="nicdark_absolute nicdark_display_table nicdark_width_percentage100">
							<div class="nicdark_cell nicdark_vertical_middle">
								<a class="nicdark_btn nicdark_bg_white grey medium">EUROPA</a>
							</div>
						</div>
					</div>
					<!--end content-->
					<!--start content-->
					<div class="nicdark_fadein nicdark_filter greydark nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
						<div class="nicdark_absolute nicdark_display_table nicdark_height100percentage nicdark_width_percentage100">
							<div class="nicdark_cell nicdark_vertical_middle">
								<p class="white">
									Descopera toate destinatiile
								</p>
								<div class="nicdark_space20"></div>
								<a target="" href="#" class="white nicdark_btn nicdark_border_white medium">VIEW ALL</a>
							</div>
						</div>
					</div>
					<!--end content-->
				</div>
				<!--end image-->
			</div>
		</div>
		<!-- END ITEM -->

		<!-- START ITEM -->
		<div class="vc_col-sm-3 wpb_column vc_column_container ">
			<div class="wpb_wrapper">
				<!--start image-->
				<div class="nicdark_focus nicdark_border_grey nicdark_sizing nicdark_relative nicdark_fadeinout nicdark_overflow">
					<img alt="" class="nicdark_focus nicdark_zoom_image" src="http://www.nicdarkthemes.com/themes/love-travel/wp/demo-travel/wp-content/uploads/2015/05/love-travel-3-556.jpg">
					<!--start content-->
					<div class="nicdark_fadeout nicdark_filter nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
						<div class="nicdark_absolute nicdark_display_table nicdark_width_percentage100">
							<div class="nicdark_cell nicdark_vertical_middle">
								<a class="nicdark_btn nicdark_bg_white grey medium">ASIA</a>
							</div>
						</div>
					</div>
					<!--end content-->
					<!--start content-->
					<div class="nicdark_fadein nicdark_filter greydark nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
						<div class="nicdark_absolute nicdark_display_table nicdark_height100percentage nicdark_width_percentage100">
							<div class="nicdark_cell nicdark_vertical_middle">
								<p class="white">
									Descopera toate destinatiile
								</p>
								<div class="nicdark_space20"></div>
								<a target="" href="#" class="white nicdark_btn nicdark_border_white medium">VIEW ALL</a>
							</div>
						</div>
					</div>
					<!--end content-->
				</div>
				<!--end image-->
			</div>
		</div>
		<!-- END ITEM -->
		<!-- START ITEM -->
		<div class="vc_col-sm-3 wpb_column vc_column_container ">
			<div class="wpb_wrapper">
				<!--start image-->
				<div class="nicdark_focus nicdark_border_grey nicdark_sizing nicdark_relative nicdark_fadeinout nicdark_overflow">
					<img alt="" class="nicdark_focus nicdark_zoom_image" src="http://www.nicdarkthemes.com/themes/love-travel/wp/demo-travel/wp-content/uploads/2015/05/love-travel-34-556.jpg">
					<!--start content-->
					<div class="nicdark_fadeout nicdark_filter nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
						<div class="nicdark_absolute nicdark_display_table nicdark_width_percentage100">
							<div class="nicdark_cell nicdark_vertical_middle">
								<a class="nicdark_btn nicdark_bg_white grey medium">AMERICA</a>
							</div>
						</div>
					</div>
					<!--end content-->
					<!--start content-->
					<div class="nicdark_fadein nicdark_filter greydark nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
						<div class="nicdark_absolute nicdark_display_table nicdark_height100percentage nicdark_width_percentage100">
							<div class="nicdark_cell nicdark_vertical_middle">
								<p class="white">
									Descopera toate destinatiile
								</p>
								<div class="nicdark_space20"></div>
								<a target="" href="#" class="white nicdark_btn nicdark_border_white medium">VIEW ALL</a>
							</div>
						</div>
					</div>
					<!--end content-->
				</div>
				<!--end image-->
			</div>
		</div>
		<!-- END ITEM -->

		<!-- START ITEM -->
		<div class="vc_col-sm-3 wpb_column vc_column_container ">
			<div class="wpb_wrapper">
				<!--start image-->
				<div class="nicdark_focus nicdark_border_grey nicdark_sizing nicdark_relative nicdark_fadeinout nicdark_overflow">
					<img alt="" class="nicdark_focus nicdark_zoom_image" src="http://www.nicdarkthemes.com/themes/love-travel/wp/demo-travel/wp-content/uploads/2015/05/love-travel-15-556.jpg">
					<!--start content-->
					<div class="nicdark_fadeout nicdark_filter nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
						<div class="nicdark_absolute nicdark_display_table nicdark_width_percentage100">
							<div class="nicdark_cell nicdark_vertical_middle">
								<a class="nicdark_btn nicdark_bg_white grey medium">AFRICA</a>
							</div>
						</div>
					</div>
					<!--end content-->
					<!--start content-->
					<div class="nicdark_fadein nicdark_filter greydark nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
						<div class="nicdark_absolute nicdark_display_table nicdark_height100percentage nicdark_width_percentage100">
							<div class="nicdark_cell nicdark_vertical_middle">
								<p class="white">
									Descopera toate destinatiile
								</p>
								<div class="nicdark_space20"></div>
								<a target="" href="#" class="white nicdark_btn nicdark_border_white medium">VIEW ALL</a>
							</div>
						</div>
					</div>
					<!--end content-->
				</div>
				<!--end image-->
			</div>
		</div>
		<!-- END ITEM -->

	</div>
</section>
<section style="background:url(http://www.nicdarkthemes.com/themes/love-travel/wp/demo-travel/wp-content/uploads/2015/04/love-travel-35-1920.jpg) 50% 0 fixed; background-size:cover;" id="counter-parallax" class="nicdark_section  nicdark_imgparallax  vc_row wpb_row vc_row-fluid">
	<div class="nicdark_filter greydark">
		<div class="nicdark_container nicdark_vc nicdark_clearfix">
			<div class="vc_col-sm-12 wpb_column vc_column_container ">
				<div class="wpb_wrapper">
					<section id="vc_custom_1430753455786" class="nicdark_section  vc_row wpb_row vc_inner vc_row-fluid vc_custom_1430753455786">
						<div class="vc_col-sm-3 nicdark_center wpb_column vc_column_container ">
							<div class="wpb_wrapper">
								<div class="nicdark_archive1 center">
									<a href="#" class=" nicdark_width50 white nicdark_btn nicdark_bg_none nicdark_transition extrasize subtitle nicdark_counter" data-to="75" data-speed="1000">75</a><div class="nicdark_space5"></div>
								</div><a title="" target="" href="" class=" nicdark_btn nicdark_bg_yellow small    nicdark_square white">DESTINATIONS</a>

							</div>
						</div>

						<div class="vc_col-sm-3 nicdark_center wpb_column vc_column_container ">
							<div class="wpb_wrapper">
								<div class="nicdark_archive1 center">
									<a href="#" class="nicdark_width100 nicdark_width50 white nicdark_btn nicdark_bg_none nicdark_transition extrasize subtitle nicdark_counter" data-to="149" data-speed="1000">149</a><div class="nicdark_space5"></div>
								</div><a title="" target="" href="" class=" nicdark_btn nicdark_bg_green small    nicdark_square white">TOURS PACK</a>

							</div>
						</div>

						<div class="vc_col-sm-3 nicdark_center wpb_column vc_column_container ">
							<div class="wpb_wrapper">
								<div class="nicdark_archive1 center">
									<a href="#" class=" nicdark_width50 white nicdark_btn nicdark_bg_none nicdark_transition extrasize subtitle nicdark_counter" data-to="32" data-speed="1000">32</a><div class="nicdark_space5"></div>
								</div><a title="" target="" href="" class=" nicdark_btn nicdark_bg_blue small    nicdark_square white">CRUISES</a>

							</div>
						</div>

						<div class="vc_col-sm-3 nicdark_center wpb_column vc_column_container ">
							<div class="wpb_wrapper">
								<div class="nicdark_archive1 center">
									<a href="#" class=" nicdark_width50 white nicdark_btn nicdark_bg_none nicdark_transition extrasize subtitle nicdark_counter" data-to="24" data-speed="1000">24</a><div class="nicdark_space5"></div>
								</div><a title="" target="" href="" class=" nicdark_btn nicdark_bg_red small    nicdark_square white">HOUR SUPPORT</a>

							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="clear"></div>
