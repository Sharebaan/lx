
  <script type="text/javascript">
  	$(document).ready(function ($) {

	var slideCount = $('#slider ul li').length;
	var slideWidth = $('#slider ul li').width();
	var slideHeight = $('#slider ul li').height();
	var sliderUlWidth = slideCount * slideWidth;

	$('#slider').css({ width: slideWidth, height: slideHeight });

	$('#slider li').css({ width: slideWidth, height: slideHeight });

	$('#slider ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });

    $('#slider ul li:last-child').prependTo('#slider ul');

    function moveLeft() {
        $('#slider ul').animate({
            left: + slideWidth
        }, 400, function () {
            $('#slider ul li:last-child').prependTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    };

    function moveRight() {
        $('#slider ul').animate({
            left: - slideWidth
        }, 400, function () {
            $('#slider ul li:first-child').appendTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    };

    $('a.control_prev').click(function () {
        moveLeft();
    });

    $('a.control_next').click(function () {
        moveRight();
    });

});

  </script>
  <script type="text/javascript">
  	function askForOfferPackage(packageId,soapClient,roomCategory,mealPlan,rooms,price,departureDate,duration,event){
        event.preventDefault();
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
        event.stopPropagation();
    }

    function askForOfferHotel(hotelId,soapClient,roomCategory,mealPlan,rooms,price,departureDate,returnDate,event){
        event.preventDefault();
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
        event.stopPropagation();
    }
  </script>
  <?php //==========================================  $package->prices for table ===================================== ?>
<section class="packages pack_local">
	<div class="vc_empty_space" style="height: 100px">
		<span class="vc_empty_space_inner"></span>
	</div>
	<div class="nicdark_container nicdark_vc nicdark_clearfix">
		<div class="ba-item-view-heading container clearfix">
	    <div class="ba-item-view-heading-left two-thirds column ba-column-no-margin-left vc_col-sm-9">
	       <h1 class="ba-item-view-title">{{{$hotel->name}}}</h1>
	       <span class="ba-item-view-stars">
	         @for($i = 0;  $i < $hotel->class; $i++)
	           <img src="/images/star.png" />
	         @endfor
	       </span>
	       <br/>
	       <span class="ba-item-view-location">
	         <i class="fa fa-map-marker"></i> {{{App\Models\Travel\Geography::getFormatedLocation($hotel->location)}}}
	       </span>
	       <span class="ba-item-view-address">
	         {{{$hotel->address}}}
	       </span>
	    </div>
	    <div class="ba-item-view-heading-right one-third column ba-column-no-margin-right vc_col-sm-3">
	      @if($transportCode != App\Http\Controllers\Travel\OffersController::T_HOTEL && $estimatedPrice != 0)
	      <span class="ba-item-view-estimated-price">
	        de la <span>&euro;{{{$estimatedPrice}}}</span>
	      </span>
	      @endif
	    </div>
	    <div class="clear"></div>
	  </div>
	  <div class="sep-30"></div>
		<div class="ba-item-view-top container clearfix">
	    <div class="ba-item-view-top-left two-thirds column ba-column-no-margin-left vc_col-sm-8">
	    	<div id="slider">
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
		                    //$imgUrl = "/images/offers/{$image->name}";
                        if(file_exists(public_path()."/images/offers/{$image->name}")){
                          $imgUrl = "/images/offers/{$image->name}";
                        }else{
                          $imgUrl = "/images/offers/{$image->id}.{$type}";
                        }
		                } else {
		                    //$imgUrl = "/images/offers/{$hotel->soap_client}/{$image->name}";
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
				</div>
				<!-- <div class="slider_option">
				  <input type="checkbox" id="checkbox">
				  <label for="checkbox">Autoplay Slider</label>
				</div>  -->
	    </div>
	    <div class="ba-item-view-top-right one-third column ba-column-no-margin-right vc_col-sm-4">
	    	<div class="text_right">
		      <span class="ba-item-view-show-prices clearfix">
		        <a href="#priceSearch" class="ba-blue-button-m ba-right vezi_oferte">Vezi Oferte</a>
		      </span>
	     	</div>
	     	<div class="sep-20"></div>
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
	      <div class="ba-item-view-extra clearfix">
	        <div class="ba-item-view-extra-item alpha">
	          <span class="ba-item-view-extra-item-image">
	            <img src="/images/icons/item_view_extra_1.png" />
	          </span>
	          <span class="ba-item-view-extra-item-description">
	            Travel wise
	          </span>
	          <div class="clear"></div>
	        </div>
	        <div class="ba-item-view-extra-item alpha">
	          <span class="ba-item-view-extra-item-image">
	            <img style="padding-top: 13px;" src="/images/icons/item_view_extra_2.png" />
	          </span>
	          <span class="ba-item-view-extra-item-description">
	            Excursii inedite
	          </span>
	          <div class="clear"></div>
	        </div>
	        <div class="ba-item-view-extra-item alpha">
	          <span class="ba-item-view-extra-item-image">
	            <img style="padding-top: 5px;" src="/images/icons/item_view_extra_3.png" />
	          </span>
	          <span class="ba-item-view-extra-item-description">
	            Confirmare imediata
	          </span>
	          <div class="clear"></div>
	        </div>
	        <div class="ba-item-view-extra-item">
	          <span class="ba-item-view-extra-item-image">
	            <img style="padding-top: 5px;" src="/images/icons/item_view_extra_4.png" />
	          </span>
	          <span class="ba-item-view-extra-item-description">
	            Cazari garantate
	          </span>
	          <div class="clear"></div>
	        </div>
	      </div>
	    </div>
	    <div class="clear"></div>
	  </div>
	  <div class="sep-30"></div>
			<div class="ba-item-view sixteen columns vc_col-sm-12">
			  <div class="ba-item-view-content container clearfix">
			    @if((count($hotel->detailedDescriptions) + (isset($hotel->description) ? 1 : 0)) != 0)
			      <div id="hotelDescription" class="ba-item-view-hotel-description ba-item-view-block">
			        <span class="ba-item-view-block-title">
			        @if($IsTour)
			          {{{$hotel->name}}}
			        @else
			          {{{$hotel->name}}}
			        @endif
			        </span>
			        <div class="ba-item-view-block-content">
			          @if(isset($hotel->description))
			          <div class="ba-item-view-row clearfix">
			            <div class="ba-item-view-left-column">
			              Informatii generale
			            </div>
			            <div class="ba-item-view-right-column">
			              {{{$hotel->description}}}
			              <hr/>
			            </div>
			          </div>
			          @endif
			          <?php
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
			          @endforeach
			        </div>
			      </div>
			      <hr/>
			    @endif
			    <div id="packagesDescription">
			      @foreach($packages as $package)
			        @if((count($package->detailedDescriptions) + (isset($package->included_services) ? 1 : 0) + (isset($package->not_included_services) ? 1 : 0)) != 0)
			          @if($package->is_flight == 1 && $package->is_bus == 0)
			          <div id="packageDescription-{{{$package->id}}}" class="ba-item-view-package-description ba-item-view-block ba-package-description-flight {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT ? '' : 'ba-hidden'}}}">
			          @elseif($package->is_flight == 0 && $package->is_bus == 1)
			          <div id="packageDescription-{{{$package->id}}}" class="ba-item-view-package-description ba-item-view-block ba-package-description-bus {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS ? '' : 'ba-hidden'}}}">
			          @else
			          <div id="packageDescription-{{{$package->id}}}" class="ba-item-view-package-description ba-item-view-block ba-package-description-individual {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? '' : 'ba-hidden'}}}">
			          @endif
			            <span class="ba-item-view-block-title">{{{$package->name}}}</span>
			            <div class="ba-item-view-block-content">
			              <div class="ba-item-view-row clearfix">
				            <div class="ba-item-view-left-column">
				              Transport
				            </div>
				            <div class="ba-item-view-right-column">
				              @if ($package->is_flight == 1)
					            <img id="searchTransportType" style="background-color:#1671ff;" src="/images/transport/plane.png" class="et-tooltip" alt="Avion" title="Avion" height="22px" /> Avion
					          @elseif ($package->is_bus == 1)
					            <img id="searchTransportType" style="background-color:#1671ff;" src="/images/transport/bus.png" class="et-tooltip" alt="Autocar" title="Autocar" height="22px" /> Autocar
					          @else
					            <img id="searchTransportType" style="background-color:#1671ff;" src="/images/transport/individual.png" class="et-tooltip" alt="Individual" title="Individual" height="22px" /> Individual
					          @endif
				              <hr/>
				            </div>
				          </div>
			              <div class="ba-item-view-row clearfix">
				            <div class="ba-item-view-left-column">
				              Durata
				            </div>
				            <div class="ba-item-view-right-column">
				              {{{$package->duration}}} zile
				              <hr/>
				            </div>
				          </div>
				          @if(isset($package->description))
			                <div class="ba-item-view-row clearfix">
			                  <div class="ba-item-view-left-column">
			                    Descriere
			                  </div>
			                  <div class="ba-item-view-right-column">
			                  	<?php echo $package->description; ?>
			                    <hr/>
			                  </div>
			                </div>
			              @endif
			              @if(isset($package->included_services))
			                <div class="ba-item-view-row clearfix">
			                  <div class="ba-item-view-left-column">
			                    Servicii incluse
			                  </div>
			                  <div class="ba-item-view-right-column">
			                  	<?php echo $package->included_services; ?>
			                    <hr/>
			                  </div>
			                </div>
			              @endif
			              @if(isset($package->not_included_services))
			                <div class="ba-item-view-row clearfix">
			                  <div class="ba-item-view-left-column">
			                    Servicii neincluse
			                  </div>
			                  <div class="ba-item-view-right-column">
			                    <?php echo $package->not_included_services; ?>
			                    <hr/>
			                  </div>
			                </div>
			              @endif
			              @foreach($package->detailedDescriptions as $i => $detailedDescription)
			                <div class="ba-item-view-row clearfix">
			                  <div class="ba-item-view-left-column">
			                    {{{$detailedDescription->label}}}
			                  </div>
			                  <div class="ba-item-view-right-column">
			                    <?php echo $detailedDescription->text; ?>
			                    @if($i != (count($package->detailedDescription) - 1))
			                    <hr/>
			                    @endif
			                  </div>
			                </div>
			              @endforeach

			            @if(count($package->prices) != 0)
			            <div class="ba-item-view-row clearfix">
			            <div class="ba-item-view-left-column">
			              Oferte
			            </div>
			            <div class="ba-item-view-right-column">
			            	<br/>
			          		<div class="ba-item-view-prices-package" id="pricesOutput">
					           <table class="ba-item-view-prices-table">
			                    <thead>
			                      <tr>
			                        <th class="ba-item-view-prices-table-th-room-type">Tip camera</th>
			                        <th class="ba-item-view-prices-table-th-meal-plan">Tip masa</th>
			                        <th class="ba-item-view-prices-table-th-departure-date">Data de plecare</th>
			                        <th class="ba-item-view-prices-table-th-options"></th>
			                        <th class="ba-item-view-prices-table-th-price">Pret</th>
			                        <th class="ba-item-view-prices-table-th-button"></th>
			                      </tr>
			                    </thead>
			                    <tbody>
			                    	@foreach($package->prices as $price)
			                    		<tr>
			                              <td class="ba-item-view-prices-table-td-room-type">{{{$price->roomCategory->name}}}</td>
			                              <td class="ba-item-view-prices-table-td-meal-plan">{{{$price->mealPlan->name}}}</td>
			                              <td class="ba-item-view-prices-table-td-departure-date">{{{$price->departure_date}}}</td>
			                              <td class="ba-item-view-prices-table-td-options">
			                              	<i class="fa fa-envelope-o"></i>
			                              </td>
			                              <td class="ba-item-view-prices-table-td-price">
                                      @if($price->currency == 0)
                                      €
                                      @else
                                      RON
                                      @endif
                                      {{{$price->gross + $price->tax}}}</td>
			                              <td class="ba-item-view-prices-table-td-button">
			                                <center><a class="ba-blue-button-xs" href="" onClick="askForOfferPackage({{{$package->id}}},'{{{$soapClientId}}}','{{{$price->roomCategory->name}}}','{{{$price->mealPlan->name}}}','',{{{$price->gross + $price->tax}}},'{{{$price->departure_date}}}',{{{$package->duration}}},event)" title="">Cere oferta</a></center>
										  </td>
				                         </tr>
			                    	@endforeach
			                    </tbody>
			                    <tfoot>
			                      <tr>
			                        <td class="ba-item-view-prices-table-td-legend" colspan="6">
			                          <span><i style="color:green;" class="fa fa-check"></i> - Disponibila</span>
			                          <span><i class="fa fa-envelope-o"></i> - La cerere</span>
			                          <span><img src="/images/icons/early_booking.gif" /> - Early Booking</span>
			                          <span><img src="/images/icons/special_offer.gif" /> - Special Offer</span>
			                          <span><img src="/images/icons/last_minute.gif" /> - Last Minute</span>
			                        </td>
			                      </tr>
			                    </tfoot>
			                  	</table>
		                  </div>
			            </div>
			          </div>
			          @endif
			            </div>
			          </div>

			        @endif
			      @endforeach
			    </div>
			    <div id="location" class="ba-item-view-hotel-description ba-item-view-block">
			      <span class="ba-item-view-block-title">Locatie</span>
			      <div class="ba-item-view-block-content">
			        <embed width="940px" height="520px"
			          frameborder="0" style="border:0"
			          src="{{{$hotel->getGoogleLocationUrl()}}}">
			        </embed>
			      </div>

			    </div>
			  </div>
			</div>
			</div>
		</div>
	</div> <!-- end: nicdark_container nicdark_vc nicdark_clearfix -->
</section>
