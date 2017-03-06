<script type="text/javascript" src="{{ asset('js/roomify.js') }}"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="{{ asset('sximo/themes/luxuria/css/roomify.css') }}" />
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
<script type="text/javascript">
$(document).ready(function(){
	$(".ba-checkbox").click(function(e){
		window.location.href = $(e.target).parent().parent()[0].href;
	});
});
</script>

<section class="packages packagesblade">
	<div class="container">
		<div class="row">

			<!-- START LEFT FILTERS -->
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" id="column_filters">
					<!-- SEARCH -->
					@if($searchId != 0)
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
					      	<div class="ba-search-guests">
				          	<div id="guests"></div>
				          </div>
				          <button id="searchButton">Cauta</button>
				    	</div>
				    	<div class="clear"></div>
					</div>
					<div class="clear"></div>
					@endif
					<!-- END SEARCH -->

					<div class="box_filter">
						<div class="box_filter_title" data-toggle="collapse" data-target="#price-div">Filtreaza rezultatele {{($page-1) * 10 + 1}} -  {{( (int) ($noPackages/10 + 1) == $page) ? $noPackages : ($page * 10)}} din {{$noPackages}} oferte</div>
						<div class="ba-filter-box clearfix">
							<div class="ba-filter-box-content collapse in" id="price-div">
								<p>
									<label for="amount">Pret</label>
									<input type="text" id="amount" readonly style="border:0; font-weight:bold;">
								</p>
								<div id="slider-range"></div>
								<script>
									jQuery( "#slider-range" ).slider({
									range: true,
									min: {{$minPrice}},
									max: {{$maxPrice}},
									values: [ {{$leftPrice}}, {{$rightPrice}} ],
									slide: function( event, ui ) {
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
								<a id="price-filter-button" class="yellow_big_btn" href="#">Filtreaza</a>
							</div>
						</div>
						<div class="clear"></div>
					</div>

					<div class="box_filter">
						<div class="box_filter_title" data-toggle="collapse" data-target="#offer-type-div">Tip oferta</div>
						<div class="box_filter_inside collapse in">
							<div class="ba-filter-box-content" id="offer-type-div">
								@foreach ($fareTypes as $fareType)
								<a class="ba-filter-option-a<?php
								if ($fareType -> selected)
									echo ' ba-filter-option-active';
								?>" href="{{$fareType->url}}">{{strtoupper(str_replace("_"," ",$fareType->name))}}</a>
								@endforeach
							</div>
						</div>
					</div>
					@if($searchId == 0)
					<div class="box_filter">
						<div class="box_filter_title" data-toggle="collapse" data-target="#transport-type-div">Tip transport</div>
						<div class="ba-filter-box-content box_filter_inside collapse in" id="transport-type-div">
							<a class="ba-filter-option-a<?php
							if ($transportTypes[1]['selected'])
								echo ' ba-filter-option-active';
							?>" href="{{$transportTypes[1]['url']}}">AVION</a>
							<a class="ba-filter-option-a<?php
							if ($transportTypes[2]['selected'])
								echo ' ba-filter-option-active';
							?>" href="{{$transportTypes[2]['url']}}">AUTOCAR</a>
							<a class="ba-filter-option-a<?php
							if ($transportTypes[3]['selected'])
								echo ' ba-filter-option-active';
							?>" href="{{$transportTypes[3]['url']}}">TRANSPORT INDIVIDUAL</a>
						</div>
					</div>
					@endif
					<div class="box_filter">
						<div class="box_filter_title collapsed" data-toggle="collapse" data-target="#meal-plans-div">Tip masa</div>
						<div class="ba-filter-box-content box_filter_inside collapse" id="meal-plans-div">
							@foreach ($mealPlans as $mealPlan)
							<a class="ba-filter-option-a<?php
							if ($mealPlan -> selected)
								echo ' ba-filter-option-active';
							?>" href="{{$mealPlan->url}}">{{strtoupper(str_replace("_"," ",$mealPlan->name))}}</a>
							@endforeach
						</div>
					</div>
					<div class="box_filter">
						<div class="box_filter_title" data-toggle="collapse" data-target="#stars-div">Stele</div>
						<div class="ba-filter-box-content box_filter_inside collapse in" id="stars-div">
							@for($i = 1; $i <= 5; $i++)
								<a class="ba-filter-option-a<?php if($stars[$i]['selected']) echo ' ba-filter-option-active'; ?>" href="{{{$stars[$i]['url']}}}">
								@for($j = 1; $j <= $i; $j++)
								<i class="fa fa-star"></i>
								@endfor
							@endfor
						</div>
					</div>

		</div>
		<!-- END LEFT FILTERS -->


		<!-- START PACKAGES LIST -->
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
				<!-- START LOCATION  TOP -->
				<div id="country_box_filter">
					@if($searchId == 0)
					<div class="box_filter_inside country_list">
						<div class="ba-filter-box clearfix">
							<?php $locationTypeName = isset($locations['country']) ? $locations['typename'] . "<a " . ($locations['countrySelected'] ? 'font-weight: bold;"' : '') . " href='" . $locations["countryUrl"] . "'>" . $locations['country'] . "</a>" : $locations['typename']; ?>
							<div class="box_filter_title"><?php echo $locationTypeName; ?></div>
							<div class="ba-filter-box-content" id="locations-div">
								<ul class="ba-filter-options-2 clearfix" id="country_filter">
									<?php foreach($locations["data"] as $location){?>
										<li class="col-xs-12 col-sm-12 col-md-6 col-lg-3 <?php
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
				<div class="trip_alert">
						<i class="icon-exclamation-sign"></i>
						<b>Nu exista oferte pentru filtrele selectate!</b>
				</div>
				@endif
				@if($noPackages != 0)
				<!-- START SORT -->
				<div class="box_filter" id="box_sort_filter">
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
								<ul class="ba-filter-options-2 clearfix pagination_style">
									@foreach ($sorting as $sort)
									<li <?php if($sort['selected']) echo 'class="active"'; ?>><a href="{{$sort['url']}}">{{$sort['text']}}</a></li>
									@endforeach
								</ul>
							<div class="clear"></div>
				</div>
				<!-- END SORT -->

				@foreach($packages as $package)
					<?php
			        $location = App\Models\Travel\Geography::getCountryForHotel($package->id_hotel,$package->soap_client);
			        $transport = App\Models\Travel\PackageInfo::getTransportCode($package->is_tour,$package->is_bus,$package->is_flight);
			        $link = "/oferte/".$pageTitle.'/'.$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->hotel_name)))."_".$transport."_".$package->id_hotel."_".$package->soap_client."_sid".$searchId;
			    ?>
				<!-- START ITEM -->
				<div class="trip_list_item">
					<div class="row">
					<!-- TRIP IMAGE -->
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 trip_list_img">
						<a href="{{$link}}" class=""><img alt="" class="" src="{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}"></a>
					</div>
					<!-- END TRIP IMAGE -->


					<!-- ???? -->
							<!-- @if(App\Models\Travel\Hotel::checkIfHasImages($package->id_hotel,$package->soap_client))
							<a href="{{$link}}"><div style="background-image:url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}'); background-size:cover; background-position:center center;" class="nicdark_displaynone_responsive nicdark_overflow nicdark_bg_greydark nicdark_width_percentage30 nicdark_absolute_floatnone nicdark_height100percentage nicdark_focus"></div></a>
							@else
							<a href="{{$link}}"><div style="background-image:url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}');background-size: 260px;background-repeat: no-repeat;background-color: #fff;background-position:center center;" class="nicdark_displaynone_responsive nicdark_overflow nicdark_bg_greydark nicdark_width_percentage30 nicdark_absolute_floatnone nicdark_height100percentage nicdark_focus"></div></a>
							@endif -->
					<!-- ???? -->

					<!-- TRIP INFO -->
					<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<div class="row">
						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<h4 class="trip_list_title"><a href="{{$link}}">{{$package->hotel_name}}</a></h4>
								<p class="trip_list_stars"> @for($i = 0;  $i < $package->stars; $i++)<i class="icon-star3"></i>@endfor</p>
								<p class="simple_trip_row">{{$package->hotel_address}}</p>
								<p class="simple_trip_row"><i class="fa fa-map-marker"></i> {{App\Models\Travel\Geography::getFormatedLocation($package->location)}}</p>
							<div class="trip_list_text">
								<p>{{$package->hotel_description}}</p>
								<div class="sep-10"></div>
								@foreach(App\Models\Travel\FareType::getFareTypesFor($package->id_hotel,$package->soap_client,$package->is_tour,$package->is_bus,$package->is_flight) as $fareType)
								<div class="tryp_list_type"><img src="/images/icons/{{$fareType->fare_type_name}}.png" class="et-tooltip" title="{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}" alt="{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}"/> <span>{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}</span><div class="clear"></div></div>
								@endforeach
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<div class="all_center">
								<div class="sep-20"></div>
								<div class="">
								@if ($package->is_flight)
                	<img src="/images/transport/plane.png" class="et-tooltip" alt="Avion" title="Avion" />
                @elseif ($package->is_bus)
                  <img src="/images/transport/bus.png" class="et-tooltip" alt="Autocar" title="Autocar" />
                @else
                  <img src="/images/transport/individual.png" class="et-tooltip" alt="Individual" title="Individual" />
                @endif
			    			</div>
			    			<div class="sep-20"></div>
			    			<h5 class="trip_list_price">{{(int)$package->min_price}}EUR</h1>
								<div class="sep-20"></div>
								<a href="{{$link}}" class="blue_btn">Detalii</a>
							</div>
						</div>
					</div>
				</div>
				<!-- END TRIP INFO -->
				<div class="clear"></div>
			</div>
			</div>
			<!-- END ITEM -->

			@endforeach

			<div class="clear"></div>
			<div class="pag_bottom">
				<ul class="pagination_style">
					@foreach ($sorting as $sort)
					<li <?php if($sort['selected']) echo 'class="active"'; ?>><a href="{{$sort['url']}}">{{$sort['text']}}</a></li>
					@endforeach
				</ul>
				<div class="clear"></div>
			</div>

			@endif

		</div>
		<!-- END PACKAGES LIST -->

			<div class="clear"></div>
		</div> <!-- end row -->
	</div> <!-- end container -->
</section>
