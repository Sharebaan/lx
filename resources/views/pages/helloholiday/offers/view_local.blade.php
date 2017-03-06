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
            window.location.replace("/cere_oferta/ref"+response.id);
        });
        event.stopPropagation();
    }
//hotel-icons-disponibil.png
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
    //price->roomCategory->name
  </script>

<div id="main" class="hotel-pages has-js view_local">
	<div class="inner">
     <div id="hotel" class="content cf">
				<div class="hotel-aside">
        	<a href="#gallery-1" class="desktop hotel-aside-image cf"><img class="rsTmb" src="{{$hotel->getBasePhotoLink()}}" alt="" /></a>
        	@foreach($packages as $package)
        	<div class="hotel-aside-inner">
            <h2>Transport</h2>
            <span class="transportation local_view">
            	@if ($package->is_flight == 1)
		            <em class="element"><img src="/images/listing-plane.png" alt="plane" /></em>
		          @elseif ($package->is_bus == 1)
		            <em class="element"><img src="/images/listing-bus.png" alt="bus" /></em>
		          @else
		            <em class="element"><img src="/images/listing-car.png" alt="car" /></em>
		          @endif
          	</span>
          </div>
        	@endforeach

          <div class="view_offers" id="oferte_speciale">
          	<h2>Oferte speciale</h2>
            @if(isset($featuredPackages))
              @foreach($featuredPackages as $package)
                <?php
      			        $location = App\Models\Travel\Geography::getCountryForHotel($package->id_hotel,$package->soap_client);
      			        $transport = App\Models\Travel\PackageInfo::getTransportCode($package->is_tour,$package->is_bus,$package->is_flight);
      			        $link = "/oferte/".$pageTitle.'/'.$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->name)))."_".$transport."_".$package->id_hotel."_".$package->soap_client."_sid".$searchId;
      			    ?>
                <div class="special_offer_item">
              		<div class="special_offer_info_left">
	                	<a href="{{$link}}" class="special_offer_img" style="background-image: url('{{App\Models\Travel\Hotel::getBasePhotoLinkFor($package->id_hotel,$package->soap_client)}}');"></a>
               		</div>
	                <div class="special_offer_info">
                 		<a href="{{$link}}">{{$package->name}}</a>
               		</div>
                  <div class="clear"></div>
                  <!-- <p class="special_offer_price">@if($package->minPrice()) Pret de la {{intval($package->minPrice())}}€ @endif <a href="{{$link}}" class="special_offer_more">Detalii</a></p> -->
                  <p class="special_offer_price"><a href="{{$link}}" class="special_offer_more">Detalii</a><div class="clear"></div></p>
                  <div class="clear"></div>
                </div>
              @endforeach
            @endif

          </div>

        </div>

     <div class="hotel-main">
        <article class="hotel-box cf">
        <h1>{{{$hotel->name}}}</h1>
        @for($i = 0;  $i < $hotel->class; $i++)
           <em><img src="/sximo/themes/helloholiday/images/hotel-star.png" alt="" /></em>
         @endfor
        <p class="hotel-pinpoint">{{{App\Models\Travel\Geography::getFormatedLocation($hotel->location)}}}</p>

            <div class="tabs tabs-hotel">
                <ul class="tab-links">
                <li class="active"><a href="#tab1">Oferta</a></li>
                <li><a href="#tab2">Informatii</a></li>
                <li><a href="#tab3">Localizare</a></li>
                </ul>

            <div class="tab-content">
                <div id="tab1" class="tab active">
                	<div id="pricesOutput">
	              		@foreach($packages as $package)
	              		<?php $count_prices = 0; ?>
			        			@if((count($package->detailedDescriptions) + (isset($package->included_services) ? 1 : 0) + (isset($package->not_included_services) ? 1 : 0)) != 0)
				            <span class="ba-item-view-prices-package-title">{{{$package->name}}}</span>
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
												
                          <?php 
                            $count_prices++; 

                            $pdate = explode('-',$price->departure_date);
                            $cdate = explode('-',Carbon\Carbon::now()->toDateString());
                            
                            $p = $pdate[0].$pdate[1].$pdate[2];
                            $c = $cdate[0].$cdate[1].$cdate[2]; 
                          ?>
                        @if($p >= $c)
  												@foreach($price->roomCategories as $k=>$v)
      												<tr>
      													<td class="ba-item-view-prices-table-td-room-type">{{{$v->name}}}</td>
      													@if($price->mealPlan != null)<td class="ba-item-view-prices-table-td-meal-plan">{{{$price->mealPlan->name}}}</td>@endif
      													<td class="ba-item-view-prices-table-td-departure-date">{{{date('d-m-Y', strtotime($price->departure_date))}}}</td>
      													<td class="ba-item-view-prices-table-td-options"><img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png"></i></td>
      													<td class="ba-item-view-prices-table-td-price">@if($price->currency == 0) € @else LEI @endif{{{$price->gross + $price->tax}}}</td>
      													<td class="ba-item-view-prices-table-td-button">
      													@if($price->mealPlan != null)<center>
      														<a class="ba-blue-button-xs" href="" onClick="askForOfferPackage({{{$package->id}}},'{{{$soapClientId}}}','{{{$v->name}}}','{{{$price->mealPlan->name}}}','',{{{$price->gross + $price->tax}}},'{{{$price->departure_date}}}',{{{$package->duration}}},event)" title="">Cere Oferta</a>
      													</center></td>@endif
      												</tr> 
                          @endforeach
                        @endif  
												@endforeach
											</tbody>
										</table>
										@if($count_prices > 4)
										<div class="more_offers"><a href="javascript:void(0)" class="ba-blue-button-xs" id="more_offers" onClick="Showoffers()">Vezi mai multe preturi</a></div>
										@endif
										<div class="table-wrapper legend">
	                      <h6 class="text-dark-grey">Legenda</h6>
	                      <div class="table-row">
													<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-oferta-speciala.png" /><span>Oferta Speciala</span></div>
	                      	<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-early-booking.png" /><span>Early Booking</span></div>
	                      	<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png" /><span>La cerere</span></div>
	                      </div>
	                      <div class="table-row">
	                      	<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-free-child.png" /><span>Free child</span></div>
	                      	<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-disponibil.png" /><span>Disponibil</span></div>
	                      	<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-indisponibil.png" /><span>Indisponibil</span></div>
	                      </div>
	                   </div>
				            <p class="description_dark_blue"><strong>Transport: </strong>
			              @if ($package->is_flight == 1)
					            Avion
					          @elseif ($package->is_bus == 1)
					            Autocar
					          @else
					            Individual
					          @endif
					          </p>
                    
				            <p class="description_dark_blue"><strong>Durata: </strong>{{{$package->duration}}} 
                    @if($package->day_night == 1)
                      @if($package->duration > 1)
                        Zile
                      @else
                        Zi
                      @endif
                    @else
                      @if($package->duration > 1)
                        Nopti
                      @else
                        Noapte
                      @endif
                    @endif

                    </p>

					          @if(isset($package->description))
					          	<div class="sep-10"></div>
				              <p class="description_dark_blue"><strong>Descriere</strong></p>
	                  	<?php echo $package->description; ?>
				            @endif

			              @if(isset($package->included_services))
			              	<div class="sep-10"></div>
	                    <p class="description_dark_blue"><strong>Servicii incluse</strong></p>
	                  	<?php echo $package->included_services; ?>
			              @endif

			              @if(isset($package->not_included_services))
			              	<div class="sep-10"></div>
	                    <p class="description_dark_blue"><strong>Servicii neincluse</strong></p>
	                    <?php echo $package->not_included_services; ?>
			              @endif

			              @foreach($package->detailedDescriptions as $i => $detailedDescription)
			              	<div class="sep-10"></div>
		                    <p class="description_dark_blue"><strong>{{{$detailedDescription->label}}}</strong></p>
		                    <?php echo $detailedDescription->text; ?>
			              @endforeach
		              @endif
		              @endforeach
                	</div>
								</div>

                <div id="tab2" class="tab">
					
                    @if(isset($hotel->description))
                    <h2>Informatii</h2>
                    <p>{{{$hotel->description}}}</p>
					@endif
					<br/>
		          	@foreach($hotel->detailedDescriptions as $detailedDescription)
		            	<h2>{{{$detailedDescription->label}}}</h2>
		            	<p><?php echo $detailedDescription->text; ?></p>
		          		<br/>
		          	@endforeach
                </div>

                <div id="tab3" class="tab">
                	<h2>Localizare</h2>
                  	<embed width="100%" height="400px"
						          frameborder="0" style="border:0"
						          src="{{{$hotel->getGoogleLocationUrl()}}}">
						        </embed>
                </div>
            </div>
        </div>
        <div id="gallery-1" class="royalSlider rsDefault">
        	@if(count($hotel->images) == 0)
        		<a class="rsImg" data-rsbigimg="/images/640x360.jpg" href="/images/640x360.jpg"><img class="rsTmb" src="/images/640x360.jpg"  alt="slider_img" /></a>
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
		          <a class="rsImg" data-rsbigimg="{{{$imgUrl}}}" href="{{{$imgUrl}}}"><img class="rsTmb" src="{{{$imgUrl}}}"  alt="slider_img" /></a>
		          @endforeach
		        @endif
        </div>
        </article>
		</div>


        </div>
  </div><!--  end .inner -->
</div>
