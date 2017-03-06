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
	})
</script>
<div id="main" class="pages hotelsblade">
  <div class="inner">
  	

<aside>
    <section class="search desktop overflow_visible"><div id="aside-search-module">

        <div class="tabs">
            <ul class="tab-links">
            <li id="circuitsTabActivator"class="active"><a href="#tab1">Circuite</a></li>
            <li id="staysTabActivator"><a href="#tab2">Sejururi</a></li>
            <li id="hotelsTabActivator" ><a href="#tab3">Hoteluri</a></li>
            </ul>

            <div class="tab-content">
            	<div id="tab1" class="tab active">
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

            <div id="tab2" class="tab">
                <h4 class="text-center">Caută sejurul dorit</h4>
                <div class="search_section">
							    <div class="selector">
								    <label>Transport</label>
							        <select id="searchTransportTypeStay">
						            <option value="0">* Tip transport</option>
							         </select>
								    </div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Plecare din</label>
										<select id="searchDeparturePointStay">
											<option value="0">* Plecare din</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Destinatie</label>
										<select id="searchCountryDestinationStay">
											<option value="0">* Destinatie</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Oras</label>
										<select id="searchCityDestinationStay">
										    <option value="0">* Oras</option>
										 </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Data plecare</label>
										<select id="searchDepartureDateStay">
									    <option value="0">* Data plecare</option>
									  </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Durata</label>
										<select id="searchDurationStay">
									    <option value="0">* Durata</option>
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
            <h4 class="search-results-title cf" onclick="ShowFilters()"><span class="desktop">{{$noHotels}} rezultate</span> <a href="javascript:void(0)" id="search-filters" class="mobile">Rezultate gasite <em>Afiseaza/Ascunde</em></a></h4>
            <div class="mobile-hidden">

            <div class="panel last">
            <h4 class="panel-title">Tip masa</h4>
            <ul class="filters-option">
            	@foreach ($mealPlans as $mealPlan)
            				<li><a href="{{{$mealPlan->url}}}" >{{strtoupper(str_replace("_"," ",$mealPlan->name))}}</a></li>
		        @endforeach
			 </ul>
            </div>

        </div><!-- desktop -->
        </div>
    </aside>



  	<div id="lists" class="content">
     <div class="content-padding">
     	@if($noHotels == 0)
     		<div class="row cf">
					<div class="trip_alert">
							<b>Nu exista oferte pentru filtrele selectate!</b>
					</div>
				</div>
			@endif
			
			@if($noHotels != 0)
        <div class="row cf">
                <!-- <ul class="listing-country box-30">
                    <li><a href="javascript:void(0)" class="sort-arrow-down">Filtreaza dupa tara</a>
                        <ul>
                            <li><a href="#">Bulgaria</a></li>
                            <li><a href="#">Cehia</a></li>
                            <li><a href="#">Egipt</a></li>
                            <li><a href="#">Emiratele Arabe</a></li>
                            <li><a href="#">Grecia</a></li>
                            <li><a href="#">Iordania</a></li>
                            <li><a href="#">Italia</a></li>
                            <li><a href="#">Spania</a></li>
                            <li><a href="#">Turcia</a></li>
                        </ul>
                    </li>
                </ul> -->

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

                <ul class="pagination box-50">
                	@foreach (array_reverse($pagesArray) as $page)
										<li <?php if($page['selected']) echo 'class="active"'; ?> style="float:right;"><a href="{{{$page['url']}}}">{{{$page['text']}}}</a></li>
									@endforeach
               </ul>
         </div>


        <div class="spacer-20">&nbsp;</div>

        <!-- START ITEM -->

        @foreach($hotels as $hotel)
				<?php
				//$trType = $hotel->is_tour == 1?1:($hotel->is_bus == 1?3:2);.$trType.
		        $location = App\Models\Travel\Geography::getCountryForHotel($hotel->id,$hotel->soap_client);
		        $link = "/oferte/".$pageTitle.'/'.$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$hotel->hotel_name)))."_00_".$hotel->id."_".$hotel->soap_client."_sid0";
		    ?>

		    <article class="listing-box cf">
		    	<figure class="box-25">
		    		@if(App\Models\Travel\Hotel::checkIfHasImages($hotel->id,$hotel->soap_client))
							<a href="{{$link}}" class="four-third" style="background: url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($hotel->id,$hotel->soap_client)}}'); /*background-size: cover;*/"><div class="img-content">&nbsp;</div></a>
							@else
							<a href="{{$link}}" class="four-third" style="background: url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($hotel->hotel_name,$hotel->soap_client)}}'); /*background-size: cover;*/"><div class="img-content">&nbsp;</div></a>
							@endif
					</figure>
					<div class="listing-box-details box-75 listing-row-height">
              <div class="listing-row cf">
                  <div class="row-inner">
                      <h1 class="box-title"><a href="{{$link}}">{{{$hotel->hotel_name}}}</a><span>{{{App\Models\Travel\Geography::getFormatedLocation($hotel->location)}}}</span></h1>
                      <p class="description">{{{$hotel->hotel_description}}}</p>
                      <!-- <span class="oferta-speciala">Oferta Speciala</span><span class="early-booking">Early Booking</span> -->
							<?php /*	@foreach(App\Models\Travel\FareType::getFareTypesFor($hotel->id,$hotel->soap_client,$hotel->is_tour,$hotel->is_bus,$hotel->is_flight) as $fareType)
									<span class="{{$fareType->fare_type_name}}">{{ucfirst(str_replace("_"," ",$fareType->fare_type_name))}}</span>
							 */?>
                      <div class="listing-right listing-row-height">
                      <div class="price-transportation-row">
                      <span class="transportation">
                          <em class="element"><img src="/sximo/themes/helloholiday/images/transport/individual.png" alt="individual" /></em>
                          <span class="price"><em>de la</em> &euro;{{{(int)$hotel->min_price}}}</span>
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
                  @foreach (array_reverse($pagesArray) as $page)
										<li <?php if($page['selected']) echo 'class="active"'; ?> style="float:right;"><a href="{{{$page['url']}}}">{{{$page['text']}}}</a></li>
									@endforeach
               </ul>
         </div>

         <div class="spacer-20">&nbsp;</div>
		@endif

		</div>
    </div>

	</div><!--  end .inner -->
</div><!-- end #main -->
