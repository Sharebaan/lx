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
		$('.input-others').hide();
		$('.input-hotel').show();
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
		<div id="column_filters" class="vc_col-sm-3 nicdark_displaynone_responsive wpb_column vc_column_container ">
			<div class="wpb_wrapper">
				<div class="ba-filters-left">
					<!-- SEARCH -->
					<div class="ba-item-view-search clearfix home_search_box">
					  <div class="ba-item-view-search-type clearfix">
				        <span class="ba-item-view-search-type-option"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="1"> Sejururi</span>
				        <span class="ba-item-view-search-type-option"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="2"> Circuite</span>
				        <span class="ba-item-view-search-type-option search_active"><input style="display: none;" type="radio" class="ba-input-search-type" name="searchHolidayType" value="3" checked> Hoteluri</span>
				        <div class="clear"></div>
				      </div>
				      <div class="ba-item-view-search-in clearfix">
				          <div class="ba-search-transport-type input-others">
				            <div class="ba-search-selector-div">
				              <select id="searchTransportType" class="ba-search-selector">
				              		<option value="0">* Tip transport</option>
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
				              <input type="text" id="searchDepartureDateHotel" value="{{$searchObj->checkIn}}" class="datepicker ba-search-selector" readonly/>
				            </div>
				          </div>
				          <div class="ba-search-return-date input-hotel">
				            <div class="ba-search-selector-div">
				              <input type="text" id="searchArrivalDateHotel" value="{{$searchObj->checkOut}}" class="datepicker ba-search-selector" readonly/>
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
					<div class="box_filter">
					<div class="box_filter_title">
						<span class="">Filtreaza rezultatele</span>
						<span class=""><span class="ba-color-blue">{{{($page-1) * 10 + 1}}} -  {{{( (int) ($noHotels/10 + 1) == $page) ? $noHotels : ($page * 10)}}}</span> din <span class="ba-color-blue">{{{$noHotels}}}</span> oferte</span>
					</div>
					<div class="ba-filter-box clearfix">
						<div class="ba-filter-box-title ba-collapse" data-collapse="#price-div" data-collapsed="true">Pret</div>
						<div class="ba-filter-box-content" id="price-div">
							<p>
			                  <label for="amount">Pret</label>
			                  <input type="text" id="amount" readonly style="border:0; font-weight:bold;">
			                </p>
			                <div id="slider-range"></div>
			                <script>
			                    jQuery( "#slider-range" ).slider({
			                      range: true,
			                      min: {{{$minPrice}}},
			                      max: {{{$maxPrice}}},
			                      values: [ {{{$leftPrice}}}, {{{$rightPrice}}} ],
			                      slide: function( event, ui ) {
			                        jQuery( "#amount" ).val( "€" + ui.values[ 0 ] + " - €" + ui.values[ 1 ] );
			                        var div = document.createElement('div');
			                        div.innerHTML = "{{{$priceUrl}}}";
			                        var decodedURL = div.firstChild.nodeValue;
			                        jQuery( "#price-filter-button" ).attr("href", decodedURL+"&priceFrom="+ui.values[0]+"&priceTo="+ui.values[1]);
			                      }
			                    });
			                    jQuery( "#amount" ).val( "€" + jQuery( "#slider-range" ).slider( "values", 0 ) +
			                      " - €" + jQuery( "#slider-range" ).slider( "values", 1 ) );
			                    jQuery( document ).ready(function() {
			                        var div = document.createElement('div');
			                        div.innerHTML = "{{{$priceUrl}}}";
			                        var decodedURL = div.firstChild.nodeValue;
			                        jQuery( "#price-filter-button" ).attr("href", decodedURL+"&priceFrom="+jQuery( "#slider-range" ).slider( "values", 0 )+"&priceTo="+jQuery( "#slider-range" ).slider( "values", 1 ));
			                    });
			                </script>
			                <br/>
			                <a id="price-filter-button" class="button btn-small text-center" style="float:right;" title="" href="rwar">Filtreaza</a>
			     					<div class="clear"></div>
						</div>
					</div>
					</div>
					<div class="box_filter">
					<div class="ba-filter-box">
						<div class="ba-filter-box-title ba-collapse" data-collapse="#meal-plans-div" data-collapsed="false">Tip masa</div>
						<div class="ba-filter-box-content" id="meal-plans-div">
							@foreach ($mealPlans as $mealPlan)
		                    <a class="ba-filter-option-a" href="{{{$mealPlan->url}}}">
			                    <div class="ba-filter-option<?php if($mealPlan->selected) echo ' ba-filter-option-active'; ?>">
			                    	<input type="checkbox" class="ba-checkbox"<?php if ($mealPlan->selected) echo ' checked'; ?>>
			                    	<span class="tickbox<?php if($mealPlan->selected) echo '-active'; ?>"> </span>
			                    	<label>{{{strtoupper(str_replace("_"," ",$mealPlan->name))}}}</label>
			                    </div>
		                	</a>
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
		</div>
		<div class="vc_col-sm-9 nicdark_sidebar_fixed_container nicdark_width100_responsive wpb_column vc_column_container ">
			<div class="wpb_wrapper">
				<div class="ba-filter-container-margin-top">
			@if($noHotels == 0)
				<div style="position: relative;" class="nicdark_masonry_container">
					<div class="grid grid_12 percentage nicdark_masonry_item nicdark_sizing trip_item">
						<center><font color="red"><b>Nu exista oferte pentru filtrele selectate!</b></font></center>
					</div>
				</div>
			@endif
			@if($noHotels != 0)
			<div class="clearfix nicdark_bg_grey nicdark_border_grey" id="box_sort_filter">
			<div class="ba-filter-box clearfix">
				<div class="ba-filter-box-content">
					<div class="seven columns alpha clearfix">
						<div class="ba-sort-title">Sorteaza dupa </div>
						<ul class="ba-filter-options-2" style="margin-top:3px;" id="sort_filter">
	                        <li class="sort-by-date{{{$sort['date']['additionalSelected']}}}"><a href="{{{$sort['date']['url']}}}"><span>
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
	                        <li class="sort-by-name{{{$sort['name']['additionalSelected']}}}"><a href="{{{$sort['name']['url']}}}"><span>
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
	                        <li class="sort-by-price{{{$sort['price']['additionalSelected']}}}"><a href="{{{$sort['price']['url']}}}"><span>
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
							@foreach (array_reverse($pagesArray) as $page)
							<li <?php if($page['selected']) echo 'class="active"'; ?> style="float:right;"><a href="{{{$page['url']}}}">{{{$page['text']}}}</a></li>
							@endforeach
						</ul>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			</div>

			@foreach($hotels as $hotel)
				<?php
		        $location = App\Models\Travel\Geography::getCountryForHotel($hotel->id,$hotel->soap_client);
		        $link = "/oferte/".$pageTitle.'/'.$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$hotel->hotel_name)))."_00_".$hotel->id."_".$hotel->soap_client."_sid".$searchId;
		    ?>

		    <!-- <div class="ba-list-article clearfix">
		    	<div class="ba-list-article-top clearfix">
		    		<div class="ba-list-article-top-left">
		    			<div class="ba-list-article-title"><a href="{{{$link}}}">{{{$hotel->hotel_name}}}</a></div>
		    			<div class="ba-list-article-stars">
		    			@for($i = 0;  $i < $hotel->stars; $i++)
		    				<img src="/images/star.png" />
		    			@endfor
		    			</div>
		    			<div class="ba-list-article-address">
		    				{{{$hotel->hotel_address}}}
		    			</div>
		    			<div class="ba-list-article-location">
		    				<i class="fa fa-map-marker" style="color:#008d9a;"></i> {{{App\Models\Travel\Geography::getFormatedLocation($hotel->location)}}}
		    			</div>
		    		</div>
		    		<div class="ba-list-article-top-right">
		    			<div class="ba-list-article-transport-type">
	               <img src="/images/transport/individual.png" class="et-tooltip" alt="Individual" title="Individual" height="20px" />
		    			</div>
		    			<div class="ba-list-article-price-description">Pret de la</div>
		    			<div class="ba-list-article-price">€{{{(int)$hotel->min_price}}}</div>
		    		</div>
		    	</div>
		    	<div class="ba-list-article-bottom">
		    		<div class="ba-list-article-image-preview">
		    			<a href="{{{$link}}}" class="ba-list-article-image" style="background: url('{{{App\Models\Travel\Hotel::getBasePhotoLinkFor($hotel->id,$hotel->soap_client)}}}') center center no-repeat; background-size: cover;">
	    				</a>
		    		</div>
		    		<div class="ba-list-article-description">
		    			<strong>Descriere hotel: </strong><br/><br/>
		    			{{{$hotel->hotel_description}}}
		    		</div>
		    		<a href="{{{$link}}}" class="ba-list-article-select-offer">Vezi Detalii</a>
		    	</div>

		    </div> -->



		    <div class="nicdark_masonry_container" style="position: relative;">
		    	<div class="grid grid_12 percentage nicdark_masonry_item nicdark_sizing trip_item">
		    		<div class="nicdark_focus nicdark_bg_red nicdark_relative">
							<div class="nicdark_displaynone_responsive nicdark_width_percentage30 nicdark_focus">
								<div class="nicdark_space1"></div>
							</div>
							<!-- aici imaginea -->
							@if(App\Models\Travel\Hotel::checkIfHasImages($hotel->id,$hotel->soap_client))
							<a href="{{$link}}"><div style="background-image:url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($hotel->id,$hotel->soap_client)}}'); background-size:cover; background-position:center center;" class="nicdark_displaynone_responsive nicdark_overflow nicdark_bg_greydark nicdark_width_percentage30 nicdark_absolute_floatnone nicdark_height100percentage nicdark_focus"></div></a>
							@else
							<a href="{{$link}}"><div style="background-image:url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($hotel->id,$hotel->soap_client)}}');background-size: 260px;background-repeat: no-repeat;background-color: #fff;background-position:center center;" class="nicdark_displaynone_responsive nicdark_overflow nicdark_bg_greydark nicdark_width_percentage30 nicdark_absolute_floatnone nicdark_height100percentage nicdark_focus"></div></a>
							@endif

							<div class="nicdark_width100_responsive nicdark_width_percentage50 nicdark_focus nicdark_bg_white nicdark_border_grey nicdark_sizing list_text">
								<div class="nicdark_textevidence nicdark_bg_grey nicdark_borderbottom_grey">
									<h4 class="grey nicdark_margin20 list_title"><a style="color: #1671ff !important;font-size: 20px !important;" href="{{$link}}">{{{$hotel->hotel_name}}}</a><br /><p> @for($i = 0;  $i < $hotel->stars; $i++)<img src="/images/star.png" />@endfor</p></h4>
									<p class="simple_trip_row">{{{$hotel->hotel_address}}}</p>
									<p class="simple_trip_row"><i class="fa fa-map-marker"></i> {{{App\Models\Travel\Geography::getFormatedLocation($hotel->location)}}}</p>
								</div>
								<div class="nicdark_margin20">
									<p>{{{$hotel->hotel_description}}}</p>
									<div class="nicdark_space20"></div>
								</div>
							</div>

		    			<div class="nicdark_displaynone_responsive nicdark_width_percentage20 nicdark_height100percentage nicdark_absolute_floatnone right">
								<div class="nicdark_filter nicdark_display_table nicdark_height100percentage center">

									<div class="nicdark_cell nicdark_vertical_middle">
										<div class="ba-list-article-transport-type">
											<img src="/images/transport/individual.png" class="et-tooltip" alt="Individual" title="Individual" />
					    			</div>
					    			<div class="nicdark_space20"></div>
					    			<h1 class="white">{{{(int)$hotel->min_price}}}€</h1>
										<div class="nicdark_space20"></div><a href="{{$link}}" class="nicdark_border_white white nicdark_btn nicdark_outline medium ">Detalii</a>
									</div>

								</div>
							</div>
		    			<div class="clear"></div>
	    			</div>
		    	</div>
	    	</div>
	    	<div class="clear"></div>
			@endforeach


			<div class-"clear"></div>
			<div class="pag_bottom">
					<div classs="five columns alpha">
						<ul class="ba-filter-options-2 clearfix pagination_style">
							@foreach (array_reverse($pagesArray) as $page)
							<li <?php if($page['selected']) echo 'class="active"'; ?> style="float:right;"><a href="{{{$page['url']}}}">{{{$page['text']}}}</a></li>
							@endforeach
						</ul>
					</div>
					<div class="clear"></div>
				</div>
			@else
			<div class="ba-list-article clearfix">
				<center><strong><font color="#ff004d" style="font-size: 16px; margin: 20px 0; display: block;">Nu exista oferte disponibile pentru filtrele selectate.</font></strong></center>
			</div>
			@endif
		</div>
			</div>
		</div>
		<div class="clear"></div>






	</div>



<div class="ba-main sixteen columns half-bottom clearfix">
	<div class="four columns alpha">
		<div class="ba-filters-left">

		</div>
	</div>
	<div class="twelve columns alpha" style="width: 710px;margin-right: 0px;">

	</div>
</div>


</section>
