<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/search_ajax_jsonp.js')}}"></script>
<link rel="stylesheet" href="/sximo/themes/default/js/jquery-loading/waitMe.css" />
<script type="text/javascript" src="/sximo/themes/default/js/jquery-loading/waitMe.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
      	$('#guests1').roomify(null);
      	$('#guests2').roomify(null);
      	$('#guests3').roomify(null);
      	@if($searchId == 0)
      	$('#staysTabActivator').click();
      	@endif
	})
</script>


<div id="main" class="pages packagesblade">
    <div class="inner">

    <aside>
    <section class="search desktop overflow_visible"><div id="aside-search-module">

        <div class="tabs">
            <ul class="tab-links">
            <li id="circuitsTabActivator"><a href="#tab1">Circuite</a></li>
            <li id="staysTabActivator" class="active"><a href="#tab2">Sejururi</a></li>
            <li id="hotelsTabActivator"><a href="#tab3">Hoteluri</a></li>
            </ul>

            <div class="tab-content">
            	<div id="tab1" class="tab">
              	<h4 class="text-center">Caută circuitul dorit</h4>
								<div class="search_section">
							    <div class="selector">
								    <label>Transport</label>
							        <select id="searchTransportTypeCircuit">
						            <option value="0">* Tip transport</option>
							         </select>
								    </div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Plecare din</label>
										<select id="searchDeparturePointCircuit">
											<option value="0">* Plecare din</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Destinatie</label>
										<select id="searchCountryDestinationCircuit">
											<option value="0">* Destinatie</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Oras</label>
										<select id="searchCityDestinationCircuit">
										    <option value="0">* Oras</option>
										 </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Data plecare</label>
										<select id="searchDepartureDateCircuit">
									    <option value="0">* Data plecare</option>
									  </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Durata</label>
										<select id="searchDurationCircuit">
									    <option value="0">* Durata</option>
									  </select>
									</div>
								</div>

								<div class="search_section roomifi_here search_section_full">
									<div class="selector">
										<label>Camere/persoane</label>
										<div id="guests1"></div>
									</div>
								</div>
								<div class="clear"></div>
								<div class="search_button text-center">
								    <button type="submit" id="circuitsSearchButton" class="full-width animated">CAUTĂ</button>
								</div>

            </div>

            <div id="tab2" class="tab active">
                <h4 class="text-center">Caută sejurul dorit</h4>
                <div class="search_section">
							    <div class="selector">
								    <label>Transport</label>
							        <select id="searchTransportTypeStay">
						            <option value="0">* Tip transport</option>
						            @if($searchId != 0)
						            <?php foreach($transportTypesSearch as $transportType){ ?>
                                        <?php if($searchObj->transportType == $transportType->id ){ ?>
                                            <option value="{{{$transportType->id}}}" selected>{{{$transportType->name}}}</option>
                                        <?php } else { ?>
                                            <option value="{{{$transportType->id}}}">{{{$transportType->name}}}</option>
                                        <?php } ?>
                                    <?php } ?>
                                    @endif
							         </select>
								    </div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Plecare din</label>
										<select id="searchDeparturePointStay">
											<option value="0">* Plecare din</option>
											@if($searchId != 0)
											@foreach($departurePoints as $departurePoint){
			                                    @if($searchObj->departure == $departurePoint->id )
			                                        <option value="{{{$departurePoint->id}}}" selected>{{{$departurePoint->name}}}</option>
			                                    @else
			                                        <option value="{{{$departurePoint->id}}}">{{{$departurePoint->name}}}</option>
			                                    @endif
			                                @endforeach
			                                @endif
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Destinatie</label>
										<select id="searchCountryDestinationStay">
											<option value="0">* Destinatie</option>
											@if($searchId != 0)
											@foreach($countriesDestinations as $countriesDestination)
			                                    @if($searchObj->country == $countriesDestination->id )
			                                        <option value="{{{$countriesDestination->id}}}" selected>{{{$countriesDestination->name}}}</option>
			                                    @else
			                                        <option value="{{{$countriesDestination->id}}}">{{{$countriesDestination->name}}}</option>
			                                    @endif
			                                @endforeach
			                                @endif
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Oras</label>
										<select id="searchCityDestinationStay">
										    <option value="0">* Oras</option>
										    @if($searchId != 0)
										    <?php foreach($citiesDestinations as $citiesDestination){ ?>
			                                    <?php if($searchObj->city == $citiesDestination->id ){ ?>
			                                        <option value="{{{$citiesDestination->id}}}" selected>{{{$citiesDestination->name}}}</option>
			                                    <?php } else { ?>
			                                        <option value="{{{$citiesDestination->id}}}">{{{$citiesDestination->name}}}</option>
			                                    <?php } ?>
			                                <?php } ?>
			                                @endif
										 </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Data plecare</label>
										<select id="searchDepartureDateStay">
									    <option value="0">* Data plecare</option>
									    @if($searchId != 0)
									    <?php foreach($departureDates as $departureDate){ ?>
		                                    <?php if($searchObj->departureDate == $departureDate->departure_date ){ ?>
		                                        <option value="{{{$departureDate->departure_date}}}" selected>{{{date('d-m-Y', strtotime($departureDate->departure_date))}}}</option>
		                                    <?php } else { ?>
		                                        <option value="{{{$departureDate->departure_date}}}">{{{date('d-m-Y', strtotime($departureDate->departure_date))}}}</option>
		                                    <?php } ?>
		                                <?php } ?>
		                                @endif
									  </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Durata</label>
										<select id="searchDurationStay">
									    <option value="0">* Durata</option>
									    @if($searchId != 0)
									    <?php foreach($durations as $duration){ ?>
		                                    <?php if($searchObj->duration == $duration->duration ){ ?>
		                                        <option value="{{{$duration->duration}}}" selected>{{{$duration->duration}}} nopti</option>
		                                    <?php } else { ?>
		                                        <option value="{{{$duration->duration}}}">{{{$duration->duration}}} nopti</option>
		                                    <?php } ?>
		                                <?php } ?>
		                                @endif
									  </select>
									</div>
								</div>

								<div class="search_section roomifi_here search_section_full">
									<div class="selector">
										<label>Camere/persoane</label>
										<div id="guests2"></div>
									</div>
								</div>
								<div class="clear"></div>
								<div class="search_button text-center">
								    <button type="submit" id="staysSearchButton" class="full-width animated">CAUTĂ</button>
								</div>
            </div>

            <div id="tab3" class="tab">
                <h4 class="text-center">Caută hotelul dorit</h4>
								<div class="search_section">
									<div class="selector">
										<label>Destinatie</label>
										<select id="searchCountryDestinationHotel">
											<option value="0">* Destinatie</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Oras</label>
										<select id="searchCityDestinationHotel">
										    <option value="0">* Oras</option>
										 </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Checkin</label>
										<input type="text" id="searchDepartureDateHotel" value="* Checkin" class="datepicker" readonly/>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Checkout</label>
										<input type="text" id="searchArrivalDateHotel" value="* Checkout" class="datepicker" readonly/>
									</div>
								</div>

								<div class="search_section roomifi_here search_section_full">
									<div class="selector">
										<label>Camere/persoane</label>
										<div id="guests3"></div>
									</div>
								</div>

								<div class="clear"></div>
								<div class="search_button text-center">
								    <button type="submit" id="hotelsSearchButton" class="full-width animated">CAUTĂ</button>
								</div>
            </div>
          </div>
        </div>

    </div></section>


    	<div class="filters-container">
            <!-- <h4 class="search-results-title cf" onclick="ShowFilters()"><span class="desktop">Filtreaza rezultatele {{$noPackages}}</span> <a href="javascript:void(0)" id="search-filters" class="mobile">Rezultate gasite <em>Afiseaza/Ascunde</em></a></h4> -->
            <h4 class="search-results-title cf" onclick="ShowFilters()"><span class="desktop">{{$noPackages}} rezultate</span> <a href="javascript:void(0)" id="search-filters" class="mobile">Rezultate gasite <em>Afiseaza/Ascunde</em></a></h4>
            <div class="mobile-hidden">
            <div class="panel">
	            <h4 class="panel-title">Tip oferta</h4>
	            <ul class="filters-option">
	            	@foreach ($fareTypes as $fareType)
								<li><a href="{{$fareType->url}}" <?php
								if ($fareType -> selected)
									echo 'class="filter_active"';
								?>>{{strtoupper(str_replace("_"," ",$fareType->name))}}
								</a></li>
								@endforeach
	            </ul>
            </div>
            @if($searchId == 0)
            <div class="panel">
	            <h4 class="panel-title">Tip transport</h4>
	            <ul class="filters-option">
	                <li><a href="{{$transportTypes[1]['url']}}" <?php
									if ($transportTypes[1]['selected']){
										echo 'class="filter_active"';
									}
									?>>Avion</a></li>
	                <li><a href="{{$transportTypes[2]['url']}}" <?php
									if ($transportTypes[2]['selected']){
										echo 'class="filter_active"';
									}
									?>>Autocar</a></li>
	                <li><a href="{{$transportTypes[3]['url']}}" <?php
									if ($transportTypes[3]['selected']){
										echo 'class="filter_active"';
									}
									?>>Transport individual</a></li>
	            </ul>
            </div>
            @endif
            <div class="panel">
	            <h4 class="panel-title">Tip masa</h4>
	            <ul class="filters-option">
	            	@foreach ($mealPlans as $mealPlan)
								<li><a href="{{$mealPlan->url}}" <?php
								if ($mealPlan -> selected)
									echo 'class="filter_active"';
								?>>{{strtoupper(str_replace("_"," ",$mealPlan->name))}}</a></li>
								@endforeach
				 			</ul>
            </div>
					<!--   stele ----->
					<div class="panel last">
						<h4 class="panel-title">Stele</h4>
						<div class="ba-filter-box-content" id="stars-div">
							@foreach($stars as $k=>$v)
							<a href="{{$v['url']}}" <?php if($stars[$k]['selected']) {echo 'class="filter_active"';} ?>>
								@for($j = 1; $j <= $k; $j++)
									<img src="/sximo/themes/helloholiday/images/hotel-star.png" />
								@endfor
								<br>
							</a>
							@endforeach
						</div>
					</div>
					<!--   stele ----->
        </div><!-- desktop -->
        </div>
    </aside>


   <div id="lists" class="content">
     <div class="content-padding">

     	@if($searchId == 0)
			<div class="country_filter_title">
				<?php $locationTypeName = isset($locations['country']) ? $locations['typename'] . "<a " . ($locations['countrySelected'] ? 'font-weight: bold;"' : '') . " href='" . $locations["countryUrl"] . "'>" . $locations['country'] . "</a>" : $locations['typename']; ?>
				<?php echo $locationTypeName; ?>
				<a href="javascript:void(0)" class="mobile" onclick="ShowCountryFilters()"><em>Afiseaza/Ascunde</em></a>
			</div>
			<div class="country_filter_content mobile-hidden-country">
					<ul class="ba-filter-options-2" id="country_filter" style="display: block;">
						@foreach($locations["data"] as $location)
							<li class="box-25 <?php
							if(isset($location->selected) && $location->selected) echo 'active'; ?>">
								<a href="{{$location->url}}">{{$location->name}}</a>
							</li>
						@endforeach
					</ul>
					<div class="clear sep-20"></div>
					@if(isset($locations['backToCountrySelectionUrl']))
						<a style="font-weight:bold;display: block;" href="{{$locations['backToCountrySelectionUrl']}}">&laquo; Inapoi la selectarea tarii</a>
					@endif
					<div class="clear"></div>
			</div>
		@endif
     	@if($noPackages == 0)
     		<div class="row cf">
				<div class="trip_alert">
						<b>Nu exista oferte pentru filtrele selectate!</b>
				</div>
			</div>
		@endif
		
		@if($noPackages != 0)
        <div class="row cf">

                <!-- <ul class="sort-bar box-30"> -->
                	<ul class="sort-bar box-50">
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
                <!-- <div class="box-30">&nbsp;</div> -->

                <!-- <ul class="pagination box-40"> -->
              	<ul class="pagination box-50">
                  @foreach ($sorting as $sort)
									<li <?php if($sort['selected']) echo 'class="active"'; ?>><a href="{{$sort['url']}}">{{$sort['text']}}</a></li>
									@endforeach
               </ul>
         </div>


        <div class="spacer-20">&nbsp;</div>

        <!-- START ITEM -->
        @foreach($packages as $package)
					<?php
			        $location = App\Models\Travel\Geography::getCountryForHotel($package->id_hotel,$package->soap_client);
			        $transport = App\Models\Travel\PackageInfo::getTransportCode($package->is_tour,$package->is_bus,$package->is_flight);
			        $link = "/oferte/".$pageTitle.'/'.$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->hotel_name)))."_".$transport."_".$package->id_hotel."_".$package->soap_client."_sid".$searchId;
			    ?>
		    <article class="listing-box cf">
        	<figure class="box-25">
          	<a href="{{$link}}" class="four-third" style="background: url({{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}); /*background-size: cover;*/"><div class="img-content">&nbsp;</div></a>
					</figure>
          <div class="listing-box-details box-75 listing-row-height">
          	<div class="listing-row cf">
              <div class="row-inner">
                  <h1 class="box-title"><a href="{{$link}}">{{$package->hotel_name}}</a><span>{{App\Models\Travel\Geography::getFormatedLocation($package->location)}}</span></h1>
                  <p class="description">{{str_limit($package->hotel_description,255)}}</p>
                  @foreach(App\Models\Travel\FareType::getFareTypesFor($package->id_hotel,$package->soap_client,$package->is_tour,$package->is_bus,$package->is_flight) as $fareType)
									<!-- <img src="/images/icons/{{$fareType->fare_type_name}}.gif" class="et-tooltip" title="{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}" alt="{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}"/> <div class="ba-list-article-fare-type-text">{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}</div> -->
									<span class="{{$fareType->fare_type_name}}">{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}</span>
									@endforeach

                  <div class="listing-right listing-row-height">
                  <div class="price-transportation-row">
                  <span class="transportation">
                		@if ($package->is_flight)
                			<em class="element"><img src="/sximo/themes/helloholiday/images/transport/plane.png" alt="plane" /></em>
                    @elseif ($package->is_bus)
                    	<em class="element"><img src="/sximo/themes/helloholiday/images/transport/bus.png" alt="bus" /></em>
                    @else
                    	<em class="element"><img src="/sximo/themes/helloholiday/images/transport/individual.png" alt="individual" /></em>
                    @endif
                  </span>
                      <span class="price"><em>de la</em> {{(int)$package->min_price}}@if($package->currency == 0) &euro; @else LEI @endif</span>
                  </div>
                  <a class="vezi-oferta" title="" href="{{$link}}"><span class="details-left">Vezi oferta</span></a>
                  </div>
              </div>
          	</div>

          </div>
        </article>
        @endforeach
		    <!-- END START ITEM -->
		    <div class="spacer-20">&nbsp;</div>

		    <div class="row cf">
              <ul class="pagination">
                  @foreach ($sorting as $sort)
									<li <?php if($sort['selected']) echo 'class="active"'; ?>><a href="{{$sort['url']}}">{{$sort['text']}}</a></li>
									@endforeach
               </ul>
         </div>

         <div class="spacer-20">&nbsp;</div>
		@endif

		</div>
    </div>


  </div><!--  end .inner -->
</div><!-- end #main -->
