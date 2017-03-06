<script type="text/javascript" src="/js/roomify.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="/css/roomify.css" />
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
<script type="text/javascript">
$(document).ready(function(){
	$(".ba-checkbox").click(function(e){
		window.location.href = $(e.target).parent().parent()[0].href;
	});
});
</script>
<section class="packages">

	<div class="vc_empty_space" style="height: 100px">
		<span class="vc_empty_space_inner"></span>
	</div>

	<div class="nicdark_container nicdark_vc nicdark_clearfix">

		<!-- START COL FORM -->
		<div class="vc_col-sm-3 nicdark_displaynone_responsive wpb_column vc_column_container " id="column_filters">
			<div class="wpb_wrapper">

				<div class="ba-filters-left">
					@if($searchId != 0)
					<!-- SEARCH -->
					<div class="ba-item-view-search clearfix home_search_box">
					  <div class="ba-item-view-search-type clearfix">
				        <span class="ba-item-view-search-type-option"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="1"> Sejururi</span>
				        <span class="ba-item-view-search-type-option search_active"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="2" checked> Circuite</span>
				        <span class="ba-item-view-search-type-option"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="3"> Hoteluri</span>
				        <div class="clear"></div>
				      </div>
				      <div class="ba-item-view-search-in clearfix">
				          <div class="ba-search-transport-type input-others">
				            <div class="ba-search-selector-div">
				              <select id="searchTransportType" class="ba-search-selector">
				              		<option value="0">* Tip transport</option>
						            <?php foreach($transportTypesSearch as $transportType){ ?>
                                        <?php if($searchObj->transportType == $transportType->id ){ ?>
                                            <option value="{{{$transportType->id}}}" selected>{{{$transportType->name}}}</option>
                                        <?php } else { ?>
                                            <option value="{{{$transportType->id}}}">{{{$transportType->name}}}</option>
                                        <?php } ?>
                                    <?php } ?>
				              </select>
				            </div>
				          </div>
				          <div class="ba-search-departure-point input-others">
				            <div class="ba-search-selector-div">
				              <select id="searchDeparturePoint" class="ba-search-selector">
				              	<option value="0">* Plecare din</option>
				              	<?php foreach($departurePoints as $departurePoint){ ?>
                                    <?php if($searchObj->departure == $departurePoint->id ){ ?>
                                        <option value="{{{$departurePoint->id}}}" selected>{{{$departurePoint->name}}}</option>
                                    <?php } else { ?>
                                        <option value="{{{$departurePoint->id}}}">{{{$departurePoint->name}}}</option>
                                    <?php } ?>
                                <?php } ?>
				              </select>
				            </div>
				          </div>
				          <div class="clear sep-20 input-others"></div>
				          <div class="ba-search-country-destination">
				            <div class="ba-search-selector-div">
				              <select id="searchCountryDestination" class="ba-search-selector">
				              	<option value="0">* Destinatie</option>
				              	<?php foreach($countriesDestinations as $countriesDestination){ ?>
                                    <?php if($searchObj->country == $countriesDestination->id ){ ?>
                                        <option value="{{{$countriesDestination->id}}}" selected>{{{$countriesDestination->name}}}</option>
                                    <?php } else { ?>
                                        <option value="{{{$countriesDestination->id}}}">{{{$countriesDestination->name}}}</option>
                                    <?php } ?>
                                <?php } ?>
				              </select>
				            </div>
				          </div>
				          <div class="ba-search-city-destination">
				            <div class="ba-search-selector-div">
				              <select id="searchCityDestination" class="ba-search-selector">
				              	<option value="0">* Oras</option>
				              	<?php foreach($citiesDestinations as $citiesDestination){ ?>
                                    <?php if($searchObj->city == $citiesDestination->id ){ ?>
                                        <option value="{{{$citiesDestination->id}}}" selected>{{{$citiesDestination->name}}}</option>
                                    <?php } else { ?>
                                        <option value="{{{$citiesDestination->id}}}">{{{$citiesDestination->name}}}</option>
                                    <?php } ?>
                                <?php } ?>
				              </select>
				            </div>
				          </div>
				          <div class="clear sep-20"></div>
				          <div class="ba-search-departure-date input-others">
				            <div class="ba-search-selector-div">
				              <select id="searchDepartureDate" class="ba-search-selector">
				              	<option value="0">* Data plecare</option>
				              	<?php foreach($departureDates as $departureDate){ ?>
                                    <?php if($searchObj->departureDate == $departureDate->departure_date ){ ?>
                                        <option value="{{{$departureDate->departure_date}}}" selected>{{{$departureDate->departure_date}}}</option>
                                    <?php } else { ?>
                                        <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
                                    <?php } ?>
                                <?php } ?>
				              </select>
				            </div>
				          </div>
				          <div class="ba-search-duration input-others">
				            <div class="ba-search-selector-div">
				              <select id="searchDuration" class="ba-search-selector">
				              	<option value="0">* Durata</option>
				              	<?php foreach($durations as $duration){ ?>
                                    <?php if($searchObj->duration == $duration->duration ){ ?>
                                        <option value="{{{$duration->duration}}}" selected>{{{$duration->duration}}} zile</option>
                                    <?php } else { ?>
                                        <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
                                    <?php } ?>
                                <?php } ?>
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
				          <div class="clear sep-20"></div>
					      	<div class="ba-search-guests">
				          	<div id="guests"></div>
				          </div>
				          <div class="clear sep-20"></div>
				          <button id="searchButton">Cauta</button>
				    	</div>
				    	<div class="clear"></div>
					</div>
					<div class="clear"></div>
					<!-- END SEARCH -->
					@endif
					<div class="box_filter">
						<div class="box_filter_title">
							<span class="">Filtreaza rezultatele</span>
							<span class=""><span class="ba-color-blue">{{($page-1) * 10 + 1}} -  {{( (int) ($noPackages/10 + 1) == $page) ? $noPackages : ($page * 10)}}</span> din <span class="ba-color-blue">{{$noPackages}}</span> oferte</span>
						</div>
						<div class="ba-filter-box clearfix">
							<div class="ba-filter-box-title ba-collapse" data-collapse="#price-div" data-collapsed="true">
								Pret <!-- <img class="ba-collapse-img" style="float:right" src="{{ URL::to('/') }}//images/collapse-up.png"/> -->
							</div>
							<div class="ba-filter-box-content" id="price-div">
								<p>
									<label for="amount">Pret</label>
									<input type="text" id="amount" readonly style="border:0; font-weight:bold;">
								</p>
								<div id="slider-range"></div>
								<script>
									jQuery( "#slider-range" ).slider({
									range: true,
									min: {{$minPrice}}, max: { { { $maxPrice
											}
										}
									}, values: [ {{$leftPrice}}, { { { $rightPrice
											}
										}
									} ], slide: function( event, ui ) {
										jQuery("#amount").val("€" + ui.values[0] + " - €" + ui.values[1]);
										var div = document.createElement('div');
										div.innerHTML = "{{$priceUrl}}";
										var decodedURL = div.firstChild.nodeValue;
										jQuery("#price-filter-button").attr("href", decodedURL + "&priceFrom=" + ui.values[0] + "&priceTo=" + ui.values[1]);
									}
									});
									jQuery("#amount").val("€" + jQuery("#slider-range").slider("values", 0) + " - €" + jQuery("#slider-range").slider("values", 1));
									jQuery(document).ready(function() {
										var div = document.createElement('div');
										div.innerHTML = "{{$priceUrl}}";
										var decodedURL = div.firstChild.nodeValue;
										jQuery("#price-filter-button").attr("href", decodedURL + "&priceFrom=" + jQuery("#slider-range").slider("values", 0) + "&priceTo=" + jQuery("#slider-range").slider("values", 1));
									});
								</script>
								<br/>
								<a id="price-filter-button" class="button btn-small text-center" style="float:right;" title="" href="rwar">Filtreaza</a>
								<div class="clear"></div>
							</div>

						</div>
						<div class="clear"></div>
					</div>
					<div class="box_filter">
						<div class="ba-filter-box-title ba-collapse box_filter_title" data-collapse="#offer-type-div" data-collapsed="true">
							Tip oferta <!-- <img class="ba-collapse-img" style="float:right" src="/images/collapse-up.png"/> -->
						</div>
						<div class="box_filter_inside">
							<div class="ba-filter-box-content" id="offer-type-div">
								@foreach ($fareTypes as $fareType)
								<a class="ba-filter-option-a" href="{{$fareType->url}}">
								<div class="ba-filter-option<?php
								if ($fareType -> selected)
									echo ' ba-filter-option-active';
								?>">
									<input class="ba-checkbox" type="checkbox"<?php
								if ($fareType -> selected)
									echo ' checked';
								?>>
									<span class="tickbox<?php
									if ($fareType -> selected)
										echo '-active';
									?>"> </span>
									<label>{{strtoupper(str_replace("_"," ",$fareType->name))}}</label>
								</div></a>
								@endforeach
							</div>
						</div>
					</div>
					@if($searchId == 0)
					<div class="box_filter">
						<div class="ba-filter-box-title ba-collapse box_filter_title" data-collapse="#transport-type-div" data-collapsed="true">
							Tip transport <!-- <img class="ba-collapse-img" style="float:right" src="/images/collapse-up.png"/> -->
						</div>
						<div class="ba-filter-box-content box_filter_inside" id="transport-type-div">
							<a class="ba-filter-option-a" href="{{$transportTypes[1]['url']}}">
							<div class="ba-filter-option<?php
							if ($transportTypes[1]['selected'])
								echo ' ba-filter-option-active';
							?>">
								<input class="ba-checkbox" type="checkbox"<?php
							if ($transportTypes[1]['selected'])
								echo ' checked';
							?>>
								<span class="tickbox<?php
								if ($transportTypes[1]['selected'])
									echo '-active';
								?>"> </span>
								<label>AVION</label>
							</div></a>
							<a class="ba-filter-option-a" href="{{$transportTypes[2]['url']}}">
							<div class="ba-filter-option<?php
							if ($transportTypes[2]['selected'])
								echo ' ba-filter-option-active';
							?>">
								<input class="ba-checkbox" type="checkbox"<?php
							if ($transportTypes[2]['selected'])
								echo ' checked';
							?>>
								<span class="tickbox<?php
								if ($transportTypes[2]['selected'])
									echo '-active';
								?>"> </span>
								<label>AUTOCAR</label>
							</div></a>
							<a class="ba-filter-option-a" href="{{$transportTypes[3]['url']}}">
							<div class="ba-filter-option<?php
							if ($transportTypes[3]['selected'])
								echo ' ba-filter-option-active';
							?>">
								<input class="ba-checkbox" type="checkbox"<?php
							if ($transportTypes[3]['selected'])
								echo ' checked';
							?>>
								<span class="tickbox<?php
								if ($transportTypes[3]['selected'])
									echo '-active';
								?>"> </span>
								<label>TRANSPORT INDIVIDUAL</label>
							</div></a>
						</div>
					</div>
					@endif
					<div class="box_filter">
						<div class="ba-filter-box-title ba-collapse box_filter_title" data-collapse="#meal-plans-div" data-collapsed="false">
							Tip masa <!-- <img class="ba-collapse-img" style="float:right" src="/images/collapse-up.png"/> -->
						</div>
						<div class="ba-filter-box-content box_filter_inside" id="meal-plans-div">
							@foreach ($mealPlans as $mealPlan)
							<a class="ba-filter-option-a" href="{{$mealPlan->url}}">
							<div class="ba-filter-option<?php
							if ($mealPlan -> selected)
								echo ' ba-filter-option-active';
							?>">
								<input class="ba-checkbox" type="checkbox"<?php
								if ($mealPlan -> selected)
									echo ' checked';
								?>>
								<span class="tickbox<?php
								if ($mealPlan -> selected)
									echo '-active';
								?>"> </span>
								<label>{{strtoupper(str_replace("_"," ",$mealPlan->name))}}</label>
							</div></a>
							@endforeach
						</div>
					</div>


					<div class="ba-filter-box">
					<div class="ba-filter-box-title ba-collapse" data-collapse="#stars-div" data-collapsed="true">Stele <img class="ba-collapse-img" style="float:right" src="/images/collapse-up.png"/></div>
					<div class="ba-filter-box-content" id="stars-div">
					@for($i = 1; $i <= 5; $i++)
					<a class="ba-filter-option-a" href="{{{$stars[$i]['url']}}}">
					<div class="ba-filter-option<?php if($stars[$i]['selected']) echo ' ba-filter-option-active'; ?>">
					<input type="checkbox">
					<span class="tickbox<?php if($stars[$i]['selected']) echo '-active'; ?>"> </span>
					<label>
					@for($j = 1; $j <= $i; $j++)
					<img src="/images/star.png" />
					@endfor
					</label>
					</div>
					</a>
					@endfor
					</div>
					</div>


				</div>

			</div>
		</div>

		<!-- START PACKAGES LIST -->
		<div class="vc_col-sm-9 nicdark_sidebar_fixed_container nicdark_width100_responsive wpb_column vc_column_container ">
			<div class="wpb_wrapper">

				<!-- START LOCATION  TOP -->
				<div class="clearfix nicdark_bg_grey nicdark_border_grey" id="country_box_filter">
					@if($searchId == 0)
					<div class="ba-filter-container">
						<div class="ba-filter-box clearfix">
							<?php $locationTypeName = isset($locations['country']) ? $locations['typename'] . "<a " . ($locations['countrySelected'] ? 'font-weight: bold;"' : '') . " href='" . $locations["countryUrl"] . "'>" . $locations['country'] . "</a>" : $locations['typename']; ?>
							<div class="ba-filter-box-title ba-collapse" data-collapse="#locations-div" data-collapsed="true"><?php echo $locationTypeName; ?>
								<!-- <img class="ba-collapse-img" style="float:right" src="/images/collapse-up.png"/> -->
							</div>
							<div class="ba-filter-box-content" id="locations-div">
								<ul class="ba-filter-options-2 clearfix" id="country_filter">
									<?php foreach($locations["data"] as $location){?>
										<li class="ba-four-columns <?php
										if (isset($location -> selected) && $location -> selected) echo 'active'; ?>">
											<a href="{{$location->url}}">{{$location->name}}</a>
										</li>
									<?php } ?>
								</ul>
								@if(isset($locations['backToCountrySelectionUrl']))
								<div class="clearfix" style="background-color:#FFF;">
									<a style="font-weight:bold;" href="{{$locations['backToCountrySelectionUrl']}}">&laquo; Inapoi la selectarea tarii</a>
								</div>
								@endif
							</div>
						</div>
					</div>
					@endif
				</div>
				<!-- END LOCATION  TOP -->
				@if($noPackages == 0)
				<div style="position: relative;" class="nicdark_masonry_container">
					<div class="grid grid_12 percentage nicdark_masonry_item nicdark_sizing trip_item">
						<center><font color="red"><b>Nu exista oferte pentru filtrele selectate!</b></font></center>
					</div>
				</div>
				@endif

				@if($noPackages != 0)
				<!-- START SORT -->
				<div class="clearfix nicdark_bg_grey nicdark_border_grey" id="box_sort_filter">
					<div class="ba-filter-box clearfix">
						<div class="ba-filter-box-content">
							<div class="seven columns alpha clearfix">
								<div class="ba-sort-title">Sorteaza dupa </div>
								<ul class="ba-filter-options-2" style="margin-top:3px;" id="sort_filter">
			                        <li class="sort-by-date{{$sort['date']['additionalSelected']}}"><a href="{{$sort['date']['url']}}"><span>
			                        	@if($sort['date']['additionalSelected'] == " active")
			                        		@if($sort['date']['additionalOrder'] == " ba-ASC")
			                        			<i class="fa fa-sort-desc"></i>
			                        		@else
			                        			<i class="fa fa-sort-asc"></i>
			                        		@endif
			                        	@else
			                        		<i class="fa fa-sort"></i>
			                        	@endif
			                        	data
			                        </span></a></li>
			                        <li class="sort-by-name{{$sort['name']['additionalSelected']}}"><a href="{{$sort['name']['url']}}"><span>
			                        	@if($sort['name']['additionalSelected'] == " active")
			                        		@if($sort['name']['additionalOrder'] == " ba-ASC")
			                        			<i class="fa fa-sort-desc"></i>
			                        		@else
			                        			<i class="fa fa-sort-asc"></i>
			                        		@endif
			                        	@else
			                        		<i class="fa fa-sort"></i>
			                        	@endif
			                        	nume
			                        </span></a></li>
			                        <li class="sort-by-price{{$sort['price']['additionalSelected']}}"><a href="{{$sort['price']['url']}}"><span>
			                        	@if($sort['price']['additionalSelected'] == " active")
			                        		@if($sort['price']['additionalOrder'] == " ba-ASC")
			                        			<i class="fa fa-sort-desc"></i>
			                        		@else
			                        			<i class="fa fa-sort-asc"></i>
			                        		@endif
			                        	@else
			                        		<i class="fa fa-sort"></i>
			                        	@endif
			                        	pret
			                        </span></a></li>
			                    </ul>
							</div>
							<div classs="five columns alpha">
								<ul class="ba-filter-options-2 clearfix pagination_style">
									@foreach ($sorting as $sort)
									<li <?php if($sort['selected']) echo 'class="active"'; ?>><a href="{{$sort['url']}}">{{$sort['text']}}</a></li>
									@endforeach
								</ul>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<!-- END SORT -->

				@foreach($packages as $package)
					<?php
			        $location = App\Models\Travel\Geography::getCountryForHotel($package->id_hotel,$package->soap_client);
			        $transport = App\Models\Travel\PackageInfo::getTransportCode($package->is_tour,$package->is_bus,$package->is_flight);
			        $link = "/oferte/".$pageTitle.'/'.$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->hotel_name)))."_".$transport."_".$package->id_hotel."_".$package->soap_client."_sid".$searchId;
			    ?>
				<!-- START ITEM -->
				<div style="position: relative;" class="nicdark_masonry_container">
					<div class="grid grid_12 percentage nicdark_masonry_item nicdark_sizing trip_item">

						<div class="nicdark_focus nicdark_bg_red nicdark_relative">

							<div class="nicdark_focus nicdark_displaynone_desktop nicdark_displayblock_iphonepotr nicdark_displayblock_iphoneland nicdark_displayblock_ipadpotr nicdark_displayblock_ipadland">
								<!--start image-->
								<div class="nicdark_focus nicdark_relative nicdark_fadeinout nicdark_overflow">

									<img alt="" class="nicdark_focus nicdark_zoom_image" src="{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}">

									<!--price-->
									<div class="nicdark_fadeout nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
										<a href="" class="nicdark_btn nicdark_bg_red left white medium">€{{(int)$package->min_price}}</a>
									</div>
									<!--end price-->

									<!--start content-->
									<div class="nicdark_fadein nicdark_filter greydark nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
										<div class="nicdark_absolute nicdark_display_table nicdark_height100percentage nicdark_width_percentage100">
											<div class="nicdark_cell nicdark_vertical_middle">
												<a href="{{$link}}" class="nicdark_btn nicdark_border_white white medium">Detalii</a>
											</div>
										</div>
									</div>
									<!--end content-->

								</div>
								<!--end image-->
							</div>

							<div class="nicdark_displaynone_responsive nicdark_width_percentage30 nicdark_focus">
								<div class="nicdark_space1"></div>
							</div>

							<a href="{{$link}}"><div style="background-image:url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}'); background-size:cover; background-position:center center;" class="nicdark_displaynone_responsive nicdark_overflow nicdark_bg_greydark nicdark_width_percentage30 nicdark_absolute_floatnone nicdark_height100percentage nicdark_focus"></div></a>

							<div class="nicdark_width100_responsive nicdark_width_percentage50 nicdark_focus nicdark_bg_white nicdark_border_grey nicdark_sizing list_text">
								<div class="nicdark_textevidence nicdark_bg_grey nicdark_borderbottom_grey">
								
									<h4 class="grey nicdark_margin20 list_title"><a style="color: #1671ff !important;font-size: 20px !important;" href="{{$link}}">{{$package->name}}</a><br /><p> @for($i = 0;  $i < $package->stars; $i++)<img src="/images/star.png" />@endfor</p></h4>
									<p class="simple_trip_row">{{$package->hotel_address}}</p>
									<p class="simple_trip_row"><i class="fa fa-map-marker"></i> {{App\Models\Travel\Geography::getFormatedLocation($package->location)}}</p>
								</div>
								<div class="nicdark_margin20">
									<p>{{$package->hotel_description}}</p>
									<div class="nicdark_space20"></div>
									@foreach(App\Models\Travel\FareType::getFareTypesFor($package->id_hotel,$package->soap_client,$package->is_tour,$package->is_bus,$package->is_flight) as $fareType)
									<div class="ba-list-article-fare-type"><img src="/images/icons/{{$fareType->fare_type_name}}.gif" class="et-tooltip" title="{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}" alt="{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}"/> <div class="ba-list-article-fare-type-text">{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}</div></div>
									@endforeach
								</div>
							</div>

							<div class="nicdark_displaynone_responsive nicdark_width_percentage20 nicdark_height100percentage nicdark_absolute_floatnone right">
								<div class="nicdark_filter nicdark_display_table nicdark_height100percentage center">

									<div class="nicdark_cell nicdark_vertical_middle">
										<div class="ba-list-article-transport-type">
										@if ($package->is_flight)
					                        <img src="/images/transport/plane.png" class="et-tooltip" alt="Avion" title="Avion" />
					                    @elseif ($package->is_bus)
					                        <img src="/images/transport/bus.png" class="et-tooltip" alt="Autocar" title="Autocar" />
					                    @else
					                        <img src="/images/transport/individual.png" class="et-tooltip" alt="Individual" title="Individual" />
					                    @endif
						    			</div>
						    			<div class="nicdark_space20"></div>
						    			<h1 class="white">{{(int)$package->min_price}}€</h1>
										<div class="nicdark_space20"></div><a href="{{$link}}" class="nicdark_border_white white nicdark_btn nicdark_outline medium ">Detalii</a>
									</div>

								</div>
							</div>

						</div>
					</div>
				</div>
				<!-- END ITEM -->

				@endforeach

				@endif
				<div class="clear"></div>
				<div class="pag_bottom">
					<div classs="five columns alpha">
						<ul class="ba-filter-options-2 clearfix pagination_style">
							@foreach ($sorting as $sort)
							<li <?php if($sort['selected']) echo 'class="active"'; ?>><a href="{{$sort['url']}}">{{$sort['text']}}</a></li>
							@endforeach
						</ul>
					</div>
					<div class="clear"></div>
				</div>

			</div>
		</div>
		<!-- END PACKAGES LIST -->

	<div class="clear"></div>
	</div>
</section>
