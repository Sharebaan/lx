  <script type="text/javascript">
  	function askForOfferPackage(packageId,soapClient,roomCategory,mealPlan,rooms,price,departureDate,duration){
        // event.preventDefault();
        $ = jQuery;
        var urlAskForOffer = "/ajax_search/askForOffer";
        offerObject = new Object();
        offerObject.offerType = "PACKAGE";
        offerObject.packageId = packageId;
        offerObject.soapClient = soapClient;
        offerObject.roomCategory = roomCategory;
        offerObject.mealPlan = mealPlan;
        offerObject.rooms = null;
        offerObject.price = price;
        offerObject.departureDate = departureDate;
        offerObject.duration = duration;
        $.get(urlAskForOffer,{ offer: offerObject},function(response){
            window.location.replace("/cere_oferta/ref"+response);
        });
        // event.stopPropagation();
    }

    function askForOfferHotel(hotelId,soapClient,roomCategory,mealPlan,rooms,price,departureDate,returnDate){
        // event.preventDefault();
        $ = jQuery;
        var urlAskForOffer = "/ajax_search/askForOffer";
        offerObject = new Object();
        offerObject.offerType = "HOTEL";
        offerObject.hotelId = hotelId;
        offerObject.soapClient = soapClient;
        offerObject.roomCategory = roomCategory;
        offerObject.mealPlan = mealPlan;
        offerObject.rooms = null;
        offerObject.price = price;
        departureDateArray = departureDate.split('/');
        departureDate = departureDateArray[2]+"-"+departureDateArray[1]+"-"+departureDateArray[0];
        returnDateArray = returnDate.split('/');
        returnDate = returnDateArray[2]+"-"+returnDateArray[1]+"-"+returnDateArray[0];
        offerObject.departureDate = departureDate;
        offerObject.returnDate = returnDate;
        $.get(urlAskForOffer,{ offer: offerObject},function(response){
            window.location.replace("/cere_oferta/ref"+response);
        });
        // event.stopPropagation();
    }
  </script>

<!-- slider -->
<script class="rs-file" src="/js/slider/jquery.royalslider.min.js"></script>
<link class="rs-file" href="/js/slider/royalslider.css" rel="stylesheet">
<link class="rs-file" href="/js/slider/rs-default.css" rel="stylesheet">
<script>
jQuery(document).ready(function($) {
  $('#gallery-1').royalSlider({
    fullscreen: {
      enabled: false,
      nativeFS: true
    },
    controlNavigation: 'thumbnails',
    autoScaleSlider: true,
    //autoScaleSliderWidth: 960,
    autoScaleSliderHeight: 640,
    loop: true,
    imageScaleMode: 'fit-if-smaller',
    navigateByClick: true,
    numImagesToPreload:2,
    arrowsNav:true,
    arrowsNavAutoHide: true,
    arrowsNavHideOnTouch: true,
    keyboardNavEnabled: true,
    fadeinLoadedSlide: true,
    globalCaption: false,
    globalCaptionInside: false,
    thumbs: {
      appendSpan: true,
      firstMargin: true,
      paddingBottom: 0
    }
  });
});
</script>
<!-- slider -->
<section class="packages pack_local">
	<div class="container">
		<div class="row">
			<!-- TRIP TOP -->
			<div class="container">
				<div class="bg_white border_lr border_top">
				<div class="trip_view_top">
					<div class="from_price"><span>de la</span> {{{$estimatedPrice}}}EUR</div>
					<h1 class="trip_view_title">{{{$hotel->name}}}</h1>
		     	<span class="ba-item-view-stars">
		       @for($i = 0;  $i < $hotel->class; $i++)
		         <i class="icon-star3"></i>
		       @endfor
		     	</span>
		     	<div class="sep-10"></div>
		     	<span class="trip_view_location">
		       	<i class="icon-location"></i> {{{App\Models\Travel\Geography::getFormatedLocation($hotel->location)}}}
		     	</span>
		     	<span class="trip_view_address">
		       	{{{$hotel->address}}}
		     	</span>
		  	</div>
		  	<div class="clear"></div>
		  </div>
		 </div>
		 <!-- END TRIP TOP -->

		 <!-- TRIP MIDDLE -->
		 <div class="trip_view_mid">
		    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
		    	<div id="gallery-1" class="royalSlider rsDefault">
	        	@if(count($hotel->images) == 0)
	        		<a class="rsImg" data-rsbigimg="/images/640x360.jpg" href="/images/640x360.jpg"><img class="rsTmb" src="/images/640x360.jpg"  alt="slider_img" /></a>
			      	@else
			          @foreach($hotel->images as $image)
			            <?php
			                $mimeArray = explode('/', $image->mime_type);
			                $type = $mimeArray[count($mimeArray)-1];
			                if($hotel->soap_client == "HO"){
			                    $imgUrl = "/images/offers/{$image->id}.{$type}";
			                } else {
			                    $imgUrl = "/images/offers/{$hotel->soap_client}/{$image->id}.{$type}";
			                }
			            ?>
			          <a class="rsImg" data-rsbigimg="{{{$imgUrl}}}" href="{{{$imgUrl}}}"><img class="rsTmb" src="{{{$imgUrl}}}"  alt="slider_img" /></a>
			          @endforeach
			        @endif
	        </div>
		    	<!-- <div id="slider">
					  <a href="#" class="control_next">></a>
					  <a href="#" class="control_prev"><</a>
					  <ul>
					   @if(count($hotel->images) == 0)
			          <li data-thumb="/images/640x360.jpg">
			              <img src="/images/640x360.jpg" />
			          </li>
			      	@else
			          @foreach($hotel->images as $image)
			            <?php
			                $mimeArray = explode('/', $image->mime_type);
			                $type = $mimeArray[count($mimeArray)-1];
			                if($hotel->soap_client == "HO"){
			                    //$imgUrl = "/images/offers/{$image->id}.{$type}";
                          if(file_exists(public_path()."/images/offers/{$image->name}")){
                            $imgUrl = "/images/offers/{$image->name}";
                          }else{
                            $imgUrl = "/images/offers/{$image->id}.{$type}";
                          }
			                } else {
			                    //$imgUrl = "/images/offers/{$hotel->soap_client}/{$image->id}.{$type}";
                          if(file_exists(public_path()."/images/offers/{$hotel->soap_client}/{$image->name}")){
                            $imgUrl = "/images/offers/{$hotel->soap_client}/{$image->name}";
                          }else{
                            $imgUrl = "/images/offers/{$hotel->soap_client}/{$image->id}.{$type}";
                          }
			                }
			            ?>
			          <li data-thumb="{{{$imgUrl}}}">
			              <img src="{{{$imgUrl}}}" />
			          </li>
			          @endforeach
			        @endif
					  </ul>
					</div> -->
					<!-- <div class="slider_option">
					  <input type="checkbox" id="checkbox">
					  <label for="checkbox">Autoplay Slider</label>
					</div>  -->
		    </div>
		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
		    	<!-- <div class="text_right">
			      <span class="ba-item-view-show-prices clearfix">
			        <a href="#priceSearch" class="ba-blue-button-m ba-right vezi_oferte">Vezi Oferte</a>
			      </span>
		     	</div> -->
		     	<!-- <div class="sep-20"></div> -->
		     	<div class="anchors_menu">
			      <span class="ba-item-view-mini-menu">
			      	<ul id="anchors_menu">
			        <li><a href="#hotelDescription" class="ba-mini-menu-link">
			        @if($IsTour)
			          Circuit
			        @else
			          Hotel
			        @endif
			        </a></li>
			        <li><a href="#hotelDescription" class="ba-mini-menu-link">Pachete</a></li>
			        <li><a href="#location" class="ba-mini-menu-link last">Locatie</a></li>
			        </ul>
			      </span>
		      </div>
		      <div class="sep-20"></div>
		      <span class="ba-item-view-mini-map">
		        <embed height="250px"
		          frameborder="0" style="border:0"
		          src="{{{$hotel->getGoogleLocationUrl()}}}">
		        </embed>
		      </span>
		      <div class="sep-20"></div>
		      <div class="trip_view_extra">
		        <p><i class="icon-check"></i> Travel wise</p>
		        <p><i class="icon-check"></i> Excursii inedite</p>
		        <p><i class="icon-check"></i> Confirmare imediata</p>
		        <p><i class="icon-check"></i> Cazari garantate</p>
		      </div>
		    </div>
		    <div class="clear"></div>
		  </div>
		  <!-- END TRIP MIDDLE -->

		  <!-- TRIP BOTTOM -->
		  <div class="container">
		  	<div class="trip_view_bottom">
		  			<!-- hotelDescription -->
				    @if((count($hotel->detailedDescriptions) + (isset($hotel->description) ? 1 : 0)) != 0)
			      <div id="hotelDescription" class="">
				        <h3 class="block-title">
				        @if($IsTour)
				          {{{$hotel->name}}}
				        @else
				          {{{$hotel->name}}}
				        @endif
				        </h3>
				        <div class="block-content">
				          @if(isset($hotel->description))
				              <p><strong>Informatii generale</strong></p>
				              {{{$hotel->description}}}
				          @endif

				          <!-- ???? -->
				          <!-- <?php
				            $i = 0;
				            $last = count($hotel->detailedDescriptions) - 1;
				          ?>
				          @foreach($hotel->detailedDescriptions as $detailedDescription)
				            <div class="ba-item-view-row clearfix">
				              <div class="ba-item-view-left-column">
				                {{{$detailedDescription->label}}}
				              </div>
				              <div class="ba-item-view-right-column">
				                <?php echo $detailedDescription->text; ?>
				                @if($i != $last)
				                <hr/>
				                @endif
				                <?php $i++; ?>
				              </div>
				            </div>
				          @endforeach -->
				          <!-- ???? -->
				        </div>
			      </div>
				    @endif
				    <!-- end hotelDescription -->

				    <!-- packagesDescription -->
				    <div id="packagesDescription">
				      @foreach($packages as $package)
				        @if((count($package->detailedDescriptions) + (isset($package->included_services) ? 1 : 0) + (isset($package->not_included_services) ? 1 : 0)) != 0)
				          <!-- @if($package->is_flight == 1 && $package->is_bus == 0)
				          <div id="packageDescription-{{{$package->id}}}" class="ba-item-view-package-description ba-item-view-block ba-package-description-flight {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT ? '' : 'ba-hidden'}}}">
				          @elseif($package->is_flight == 0 && $package->is_bus == 1)
				          <div id="packageDescription-{{{$package->id}}}" class="ba-item-view-package-description ba-item-view-block ba-package-description-bus {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS ? '' : 'ba-hidden'}}}">
				          @else
				          <div id="packageDescription-{{{$package->id}}}" class="ba-item-view-package-description ba-item-view-block ba-package-description-individual {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? '' : 'ba-hidden'}}}">
				          @endif -->

				            <h3 class="block-title">{{{$package->name}}}</h3>
				            <div class="block-content">
					            <p><strong>Transport: </strong>
				              @if ($package->is_flight == 1)
						            Avion
						          @elseif ($package->is_bus == 1)
						            Autocar
						          @else
						            Individual
						          @endif
						          </p>

					            <p><strong>Durata: </strong>{{{$package->duration}}} zile</p>


					          @if(isset($package->description))
				              <p><strong>Descriere</strong></p>
	                  	<?php echo $package->description; ?>
				            @endif

			              @if(isset($package->included_services))
	                    <p><strong>Servicii incluse</strong></p>
	                  	<?php echo $package->included_services; ?>
			              @endif

			              @if(isset($package->not_included_services))
	                    <p><strong>Servicii neincluse</strong></p>
	                    <?php echo $package->not_included_services; ?>
			              @endif

			              @foreach($package->detailedDescriptions as $i => $detailedDescription)
	                    <p><strong>{{{$detailedDescription->label}}}</strong></p>
	                    <?php echo $detailedDescription->text; ?>
	                    @if($i != (count($package->detailedDescription) - 1))
	                    @endif
			              @endforeach


				            @if(count($package->prices) != 0)
				            <br />
				            <p><strong>Oferte</strong></p>
				           	<table class="prices-table t1">
				                      <tr>
				                        <th class="ba-item-view-prices-table-th-room-type">Tip camera</th>
				                        <th class="ba-item-view-prices-table-th-meal-plan">Tip masa</th>
				                        <th class="ba-item-view-prices-table-th-departure-date">Data de plecare</th>
				                        <th class="ba-item-view-prices-table-th-options"></th>
				                        <th class="ba-item-view-prices-table-th-price">Pret</th>
				                        <th class="ba-item-view-prices-table-th-button"></th>
				                      </tr>
				                    	@foreach($package->prices as $price)
				                    		<tr>
				                              <td class="ba-item-view-prices-table-td-room-type">{{{$price->roomCategory->name}}}</td>
				                              <td class="ba-item-view-prices-table-td-meal-plan">{{{$price->mealPlan->name}}}</td>
				                              <td class="ba-item-view-prices-table-td-departure-date">{{{$price->departure_date}}}</td>
				                              <td class="ba-item-view-prices-table-td-options">
				                              	<img src="/images/icons/la_cerere.png" />
				                              </td>
				                              <td class="ba-item-view-prices-table-td-price">€{{{$price->gross + $price->tax}}}</td>
				                              <td class="ba-item-view-prices-table-td-button">
				                                <center><a class="yellow_big_btn" href="" onClick="askForOfferPackage({{{$package->id}}},'{{{$soapClientId}}}','{{{$price->roomCategory->name}}}','{{{$price->mealPlan->name}}}','',{{{$price->gross + $price->tax}}},'{{{$price->departure_date}}}',{{{$package->duration}}})" title="">Cere oferta</a></center>
											  </td>
					                         </tr>
				                    	@endforeach
				                      <tr>
				                        <td class="ba-item-view-prices-table-td-legend" colspan="6">
				                          <span><img src="/images/icons/disponibil.png" /> - Disponibila</span>
				                          <span><img src="/images/icons/la_cerere.png" /> - La cerere</span>
				                          <span><img src="/images/icons/early_booking.png" /> - Early Booking</span>
				                          <span><img src="/images/icons/special_offer.png" /> - Special Offer</span>
				                          <span><img src="/images/icons/last_minute.png" /> - Last Minute</span>
				                        </td>
				                      </tr>
				                  	</table>
			          @endif
				            </div>
				        @endif
				      @endforeach
				    </div>
				    <!-- packagesDescription -->
            <h3 class="block-title" id="location">Locatie</h3>
			      <div class="block-content">
			        <embed height="520px"
			          frameborder="0" style="border:0"
			          src="{{{$hotel->getGoogleLocationUrl()}}}">
			        </embed>
			      </div>
						<!-- end packagesDescription -->

				  </div>
				</div>
		  <!-- END TRIP BOTTOM -->

		</div> <!-- end row -->
	</div> <!-- end container -->
</section>
