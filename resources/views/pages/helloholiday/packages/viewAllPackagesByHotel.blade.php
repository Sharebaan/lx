@extends('layouts.master')
@section('additional-head')
  <link href="/css/lightslider.css" rel="stylesheet" />
  <link href="/css/roomify.css" rel="stylesheet" />
  <script type="text/javascript" src="/js/lightslider.js"></script>
  <script type="text/javascript" src="/js/custom/roomify.js"></script>
  <script src="//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js"></script>
  <script>
  $(document).ready(function(){
    $('#lightSlider').lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        auto: true,
        pause: 5000,
        slideMargin: 0,
        thumbItem: 5,
        thumbMargin: 0,
        galleryMargin: 0
    });
  });
  </script>
  <script>
  	$(document).ready(function(){
  		$('.tooltip').tooltipster({maxWidth: 350});
  	});
  </script>
  <script>
    $(document).ready(function(){
      $('.datepicker').datepicker();
    });
  </script>
  <script>
    $(document).ready(function(){
      $('#guests').roomify();
    });
  </script>
  <script>
  $(document).ready(function(){
    $('.ba-item-view-search-type-option').click(function(){
      var divToToggle = $($(this).data('toggle'));
      $(this).find('input').attr('checked',true);
      $('#selectedSearchType').val($(this).find('input').val());
      $('.ba-item-view-search-type-div').addClass('ba-hidden');
      $('.ba-item-view-search-type-div').data('hidden',true);
      divToToggle.removeClass('ba-hidden');
      divToToggle.data('hidden',false);
    });
  });
  </script>
  <script type="text/javascript">
  var rememberedRooms = <?php echo $rooms; ?>;
  (function($) {
      $(document).ready(function() {
          var urlSearchAjaxSinglePackageSearch = "/ajax_search/singlePackageSearch";

          $("#searchButton").click(function(){
              var departureDateVal = $("#searchDepartureDate").val();
              var durationVal = $("#searchDuration").val();
              var packageSearchObject = new Object();
              var rooms = $("#guests").data('value');
              var Rooms = [];
              $('body').scrollTo($('#prices'),500);
              $('#pricesOutput').html('<div class="ba-item-view-prices-loading"><center><img src="/images/loader.gif" /><br/>Se cauta oferte...</center></div>')
              $.each(rooms.guests, function(i,room){
                roomTmp = new Object();
                roomTmp.adults = room.adults;
                if(room.kids != null){
                  roomTmp.kids = room.kids;
                }
                Rooms.push(roomTmp);
              });
              packageSearchObject.is_flight = {{{$pSearch['IsFlight']}}};
              packageSearchObject.is_bus = {{{$pSearch['IsBus']}}};
              packageSearchObject.is_tour = {{{$pSearch['IsTour']}}};
              packageSearchObject.hotel = {{{$pSearch['Hotel']}}};
              packageSearchObject.destination = {{{$pSearch['Destination']}}};
              packageSearchObject.departure_point = {{{$pSearch['DeparturePoint']}}};
              packageSearchObject.soap_client = "{{{$pSearch['SoapClient']}}}";
              packageSearchObject.departure_date = departureDateVal;
              packageSearchObject.duration = durationVal;
              packageSearchObject.rooms = Rooms;
              $.get(urlSearchAjaxSinglePackageSearch,{ packageSearch: packageSearchObject },function(data){
                  rememberedRooms = Rooms;
                  var availablePackages = false;
                  var jsonData = $.parseJSON(data);
                  prices = jsonData.prices;
                  var appendedText = "";
                  $.each(prices , function(packageKey,packages) {
                          $('#pricesOutput').html('');
                          availablePackages = true;
                          appendedText = "";
                          $("#pricesOutput").append('<span class="ba-item-view-prices-package-title"> '+jsonData.packages[packageKey].name+' <a href="#packageDescription-'+jsonData.packages[packageKey].id+'">Afla detalii</a></span>');
                          appendedText = '<table class="ba-item-view-prices-table">'+
                                         '<thead>'+
                                         '<tr>'+
                                         '<th class="ba-item-view-prices-table-th-room-type">Tip camera</th>'+
                                         '<th class="ba-item-view-prices-table-th-meal-plan">Tip masa</th>'+
                                         '<th class="ba-item-view-prices-table-th-options"></th>'+
                                         '<th class="ba-item-view-prices-table-th-price">Pret</th>'+
                                         '<th class="ba-item-view-prices-table-th-button"></th>'+
                                         '</tr>'+
                                         '</thead>'+
                                         '<tbody>';
                          $.each(packages, function(priceKey,price){
                          	var text = '<tr>'+
      	                                '<td class="ba-item-view-prices-table-td-room-type">'+price.roomCategory+'</td>'+
      	                                '<td class="ba-item-view-prices-table-td-meal-plan">'+price.mealPlan+'</td>'+
      	                                '<td class="ba-item-view-prices-table-td-options">';
                            if(price.earlyBooking){
                              text += '<img src="/images/icons/early_booking.gif" />';
                            }
                      			if(price.specialOffer){
                      				text += '<img src="/images/icons/special_offer.gif" />';
                      			}
                            if(price.isBookable){
                                text += '<i style="color:green;" class="fa fa-check"></i>';
                            } else {
                                text += '<i class="fa fa-envelope-o"></i>';
                            }
                            text += '</td>'+
                                    '<td class="ba-item-view-prices-table-td-price">€'+price.price+'</td>';
                            if(price.isBookable && price.isMainSoapClient){
                                text += '<td class="ba-item-view-prices-table-td-button"><center><a class="ba-blue-button-xs" href="" onClick="'+price.onClickFunction+'">Rezerva</a></center></td>';
                            } else {
                                text += '<td class="ba-item-view-prices-table-td-button"><center><a class="ba-blue-button-xs" onClick=\''+price.askForOfferFunction+'\'">Cere oferta</a></center></td>';
                            }
                            text += '</div>';
                            appendedText += text;
                          });
                          appendedText += '</tbody>';
                          appendedText += '<tfoot><tr><td class="ba-item-view-prices-table-td-legend" colspan="5">'+
                                          '<span><i style="color:green;" class="fa fa-check"></i> - Disponibila</span>'+
                                          '<span><i class="fa fa-envelope-o"></i> - La cerere</span>'+
                                          '<span><img src="/images/icons/early_booking.gif" /> - Early Booking</span>'+
                                          '<span><img src="/images/icons/special_offer.gif" /> - Special Offer</span>'+
                                          '<span><img src="/images/icons/last_minute.gif" /> - Last Minute</span>'+
                                          '</td></tr></tfoot>';
                          appendedText += '</table>';
                          $("#pricesOutput").append(appendedText);
                  });

                  if(!availablePackages){
                  	$('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>');
                  }
              });
          });


      });

  })(jQuery);

  function askForOffer(packageId,soapClient,roomCategory,mealPlan,rooms,price,departureDate,duration){
      event.preventDefault();
      $ = jQuery;
      var urlAskForOffer = "/ajax_search/askForOffer";
      offerObject = new Object();
      offerObject.offerType = "PACKAGE";
      offerObject.packageId = packageId;
      offerObject.soapClient = soapClient;
      offerObject.roomCategory = roomCategory;
      offerObject.mealPlan = mealPlan;
      offerObject.rooms = rooms;
      offerObject.price = price;
      offerObject.departureDate = departureDate;
      offerObject.duration = duration;
      $.get(urlAskForOffer,{ offer: offerObject},function(response){
          window.location.replace("/cere_oferta/ref"+response);
      });
      event.stopPropagation();
  }

  function book(packageId,categoryId,mealPlanLabel,price,event){
  	  event.preventDefault();
      $ = jQuery;
      var urlSearchAjaxSinglePackageSearchBeforeBooking = "/ajax_search/singlePackageSearchBeforeBooking";
      $("#pricesOutput").html('');
      $('#pricesOutput').html('<div class="ba-item-view-prices-loading"><center><img src="/images/loader.gif" /><br/>Se verifica disponibilitatea...</center></div>')
      var departureDateVal = $("#searchDepartureDate").val();
      var durationVal = $("#searchDuration").val();
      var packageSearchObject = new Object();
      var noRooms = $("#noRooms").val();
      var rooms = $("#guests").data('value');
      var Rooms = [];
      $.each(rooms.guests, function(i,room){
        roomTmp = new Object();
        roomTmp.adults = room.adults;
        if(room.kids != null){
          roomTmp.kids = room.kids;
        }
        Rooms.push(roomTmp);
      });
      packageSearchObject.is_flight = {{{$pSearch['IsFlight']}}};
      packageSearchObject.is_bus = {{{$pSearch['IsBus']}}};
      packageSearchObject.is_tour = {{{$pSearch['IsTour']}}};
      packageSearchObject.hotel = {{{$pSearch['Hotel']}}};
      packageSearchObject.destination = {{{$pSearch['Destination']}}};
      packageSearchObject.departure_date = departureDateVal;
      packageSearchObject.duration = durationVal;
      packageSearchObject.rooms = Rooms;
      packageSearchObject.soap_client = "{{{$pSearch['SoapClient']}}}";
      var oldPriceInfo = new Object();
      oldPriceInfo.packageId = packageId;
      oldPriceInfo.categoryId = categoryId;
      oldPriceInfo.mealPlanLabel = mealPlanLabel;
      oldPriceInfo.price = price;
      $.get(urlSearchAjaxSinglePackageSearchBeforeBooking,{ packageSearch: packageSearchObject , packageId: packageId, oldPriceInfo: oldPriceInfo},function(response){
          var response = $.parseJSON(response);
          if(response.status){
              window.location.replace("/rezerva/pachet/ref"+response.id);
          } else {
              var prices = response.prices;
              var availablePackages = false;
              $("#pricesOutput").html("");
              var appendedText = "";
              $.each(prices , function(packageKey,packages) {
                $('#pricesOutput').html('');
                availablePackages = true;
                appendedText = "";
                $("#pricesOutput").append('<span class="ba-item-view-prices-package-title"> '+response.packages[packageKey].name+' <a href="#packageDescription-'+response.packages[packageKey].id+'">Afla detalii</a></span>');
                appendedText = '<table class="ba-item-view-prices-table">'+
                               '<thead>'+
                               '<tr>'+
                               '<th class="ba-item-view-prices-table-th-room-type">Tip camera</th>'+
                               '<th class="ba-item-view-prices-table-th-meal-plan">Tip masa</th>'+
                               '<th class="ba-item-view-prices-table-th-options"></th>'+
                               '<th class="ba-item-view-prices-table-th-price">Pret</th>'+
                               '<th class="ba-item-view-prices-table-th-button"></th>'+
                               '</tr>'+
                               '</thead>'+
                               '<tbody>';
                $.each(packages, function(priceKey,price){
                  var text = '<tr>'+
                              '<td class="ba-item-view-prices-table-td-room-type">'+price.roomCategory+'</td>'+
                              '<td class="ba-item-view-prices-table-td-meal-plan">'+price.mealPlan+'</td>'+
                              '<td class="ba-item-view-prices-table-td-options">';
                  if(price.earlyBooking){
                    text += '<img src="/images/icons/early_booking.gif" />';
                  }
                  if(price.specialOffer){
                    text += '<img src="/images/icons/special_offer.gif" />';
                  }
                  if(price.isBookable){
                      text += '<i style="color:green;" class="fa fa-check"></i>';
                  } else {
                      text += '<i class="fa fa-envelope-o"></i>';
                  }
                  text += '</td>'+
                          '<td class="ba-item-view-prices-table-td-price">€'+price.price+'</td>';
                  if(price.isBookable && price.isMainSoapClient){
                      text += '<td class="ba-item-view-prices-table-td-button"><center><a class="ba-blue-button-xs" href="" onClick="'+price.onClickFunction+'">Rezerva</a></center></td>';
                  } else {
                      text += '<td class="ba-item-view-prices-table-td-button"><center><a class="ba-blue-button-xs" onClick=\''+price.askForOfferFunction+'\'">Cere oferta</a></center></td>';
                  }
                  text += '</div>';
                  appendedText += text;
                });
                appendedText += '</tbody>';
                appendedText += '<tfoot><tr><td class="ba-item-view-prices-table-td-legend" colspan="5">'+
                                '<span><i style="color:green;" class="fa fa-check"></i> - Disponibila</span>'+
                                '<span><i class="fa fa-envelope-o"></i> - La cerere</span>'+
                                '<span><img src="/images/icons/early_booking.gif" /> - Early Booking</span>'+
                                '<span><img src="/images/icons/special_offer.gif" /> - Special Offer</span>'+
                                '<span><img src="/images/icons/last_minute.gif" /> - Last Minute</span>'+
                                '</td></tr></tfoot>';
                appendedText += '</table>';
                $("#pricesOutput").append(appendedText);
              });
              if(!availablePackages){
                $('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>');
              }
          }
      });
     event.stopPropagation();
  };
  </script>
@stop
@section('content')
<div class="ba-item-view sixteen columns">
  <div class="ba-item-view-heading container clearfix">
    <div class="ba-item-view-heading-left two-thirds column ba-column-no-margin-left">
       <span class="ba-item-view-title">
         {{{$hotel->name}}}
       </span>
       <span class="ba-item-view-stars">
         @for($i = 0;  $i < $hotel->class; $i++)
           <img src="/images/star.png" />
         @endfor
       </span>
       <br/>
       <span class="ba-item-view-location">
         <i class="fa fa-map-marker" style="color:#008d9a;"></i> {{{Geography::getFormatedLocation($hotel->location)}}}
       </span>
       <span class="ba-item-view-address">
         {{{$hotel->address}}}
       </span>
    </div>
    <div class="ba-item-view-heading-right one-third column ba-column-no-margin-right">
      <span class="ba-item-view-estimated-price">
        de la <span>&euro;{{{$estimatedPrice}}}</span>
      </span>
    </div>
  </div>
  <div class="ba-item-view-top container clearfix">
    <div class="ba-item-view-top-left two-thirds column ba-column-no-margin-left">
      <ul id="lightSlider">
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
          <li data-thumb="{{{$imgUrl}}}">
              <img style="width: 640px;height: 360px;" src="{{{$imgUrl}}}" />
          </li>
          @endforeach
      </ul>
    </div>
    <div class="ba-item-view-top-right one-third column ba-column-no-margin-right">
      <span class="ba-item-view-show-prices clearfix">
        <a href="#priceSearch" class="ba-blue-button-m ba-right">Vezi Oferte</a>
      </span>
      <span class="ba-item-view-mini-menu">
        <a href="#hotelDescription" class="ba-mini-menu-link">Hotel</a>
        <a href="#hotelDescription" class="ba-mini-menu-link">Pachete</a>
        <a href="#location" class="ba-mini-menu-link last">Locatie</a>
      </span>
      <span class="ba-item-view-mini-map">
        <embed height="170px"
          frameborder="0" style="border:0"
          src="{{{$hotel->getGoogleLocationUrl()}}}">
        </embed>
      </span>
      <div class="ba-item-view-extra clearfix">
        <div class="ba-item-view-extra-item alpha">
          <span class="ba-item-view-extra-item-image">
            <img src="/images/icons/item_view_extra_1.png" />
          </span>
          <span class="ba-item-view-extra-item-description">
            Cel mai mic pret
          </span>
        </div>
        <div class="ba-item-view-extra-item alpha">
          <span class="ba-item-view-extra-item-image">
            <img style="padding-top: 13px;" src="/images/icons/item_view_extra_2.png" />
          </span>
          <span class="ba-item-view-extra-item-description">
            Autocare noi
          </span>
        </div>
        <div class="ba-item-view-extra-item alpha">
          <span class="ba-item-view-extra-item-image">
            <img style="padding-top: 5px;" src="/images/icons/item_view_extra_3.png" />
          </span>
          <span class="ba-item-view-extra-item-description">
            Loc in autocar rezervat
          </span>
        </div>
        <div class="ba-item-view-extra-item">
          <span class="ba-item-view-extra-item-image">
            <img style="padding-top: 5px;" src="/images/icons/item_view_extra_4.png" />
          </span>
          <span class="ba-item-view-extra-item-description">
            Cazari garantate
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="ba-item-view-content container clearfix">
    <div id="priceSearch" class="ba-item-view-search-title">Verifica Disponibilitatea</div>
    <div class="ba-item-view-search clearfix">
      <div class="ba-item-view-search-type clearfix">
        <input id="selectedSearchType" type="hidden" value="0" />
        @if(count($departurePointsBus) >= 1 && count($departureDatesBus) >= 1 && count($durationsBus) >= 1)
        <span class="ba-item-view-search-type-option active" data-toggle="#searchBus"><input type="radio" name="searchType" value="{{{$pSearch['IsTour'] == 0 ? '01' : '11'}}}" checked>
          @if($pSearch['IsTour'] == 0)
            Autocar + Hotel
          @else
            Circuit Autocar
          @endif
        </span>
        @endif
        @if(count($departurePointsPlane) >= 1 && count($departureDatesPlane) >= 1 && count($durationsPlane) >= 1)
        <span class="ba-item-view-search-type-option" data-toggle="#searchFlight"><input type="radio" name="searchType" value="{{{$pSearch['IsTour'] == 0 ? '02' : '12'}}}">
          @if($pSearch['IsTour'] == 0)
            Avion + Hotel
          @else
            Circuit Avion
          @endif
        </span>
        @endif
        @if($pSearch['IsTour'] == 0)
        <span class="ba-item-view-search-type-option" data-toggle="#searchHotel"><input type="radio" name="searchType" value="00"> Hotel</span>
        @endif
      </div>
      <div class="ba-item-view-search-in clearfix">
        @if(count($departurePointsBus) >= 1 && count($departureDatesBus) >= 1 && count($durationsBus) >= 1)
        <div id="searchBus" class="ba-item-view-search-type-div" data-hidden="false">
          <div class="ba-search-departure-point">
            <label>Plecare din</label>
            <div class="ba-search-selector-div">
              <select id="searchBusDeparturePoint" class="ba-search-selector">
                @foreach($departurePointsBus as $departurePoint)
                    @if($searchId == 0)
                    <option value="{{{$departurePoint->id_geography}}}">{{{$departurePoint->name}}}</option>
                    @else
                        @if($departurePoint->id_geography == $departure_point && $pSearch->IsBus == 1)
                            <option value="{{{$departurePoint->id_geography}}}" selected>{{{$departurePoint->name}}}</option>
                        @else
                            <option value="{{{$departurePoint->id_geography}}}">{{{$departurePoint->name}}}</option>
                        @endif
                    @endif
                @endforeach
              </select>
              <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i>
            </div>
          </div>
          <div class="ba-search-departure-date">
            <label>Data plecarii</label>
            <div class="ba-search-selector-div">
              <select id="searchBusDepartureDate" class="ba-search-selector">
                  @foreach($departureDatesBus as $departureDate)
                      @if($searchId == 0)
                      <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
                      @else
                          @if($departureDate->departure_date == $departure_date && $pSearch->IsBus == 1)
                              <option value="{{{$departureDate->departure_date}}}" selected>{{{$departureDate->departure_date}}}</option>
                          @else
                              <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
                          @endif
                      @endif
                  @endforeach
              </select>
              <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i>
            </div>
          </div>
          <div class="ba-search-duration">
            <label>Durata</label>
            <div class="ba-search-selector-div">
              <select id="searchBusDuration" class="ba-search-selector">
                @foreach($durationsBus as $duration)
                    @if($searchId == 0)
                        <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
                    @else
                        @if($duration->duration == $durationSid && $pSearch->IsBus == 1)
                            <option value="{{{$duration->duration}}}" selected>{{{$duration->duration}}} zile</option>
                        @else
                            <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
                        @endif
                    @endif
                @endforeach
              </select>
              <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i>
            </div>
          </div>
        </div>
        @endif
        @if(count($departurePointsPlane) >= 1 && count($departureDatesPlane) >= 1 && count($durationsPlane) >= 1))
        <div id="searchFlight" class="ba-item-view-search-type-div ba-hidden" data-hidden="true">
          <div class="ba-search-departure-point">
            <label>Plecare din</label>
            <div class="ba-search-selector-div">
              <select id="searchFlightDeparturePoint" class="ba-search-selector">
                @foreach($departurePointsPlane as $departurePoint)
                    @if($searchId == 0)
                    <option value="{{{$departurePoint->id_geography}}}">{{{$departurePoint->name}}}</option>
                    @else
                        @if($departurePoint->id_geography == $departure_point && $pSearch->IsBus == 1)
                            <option value="{{{$departurePoint->id_geography}}}" selected>{{{$departurePoint->name}}}</option>
                        @else
                            <option value="{{{$departurePoint->id_geography}}}">{{{$departurePoint->name}}}</option>
                        @endif
                    @endif
                @endforeach
              </select>
              <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i>
            </div>
          </div>
          <div class="ba-search-departure-date">
            <label>Data plecarii</label>
            <div class="ba-search-selector-div">
              <select id="searchFlightDepartureDate" class="ba-search-selector">
                  @foreach($departureDatesPlane as $departureDate)
                      @if($searchId == 0)
                      <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
                      @else
                          @if($departureDate->departure_date == $departure_date && $pSearch->IsFlight == 1)
                              <option value="{{{$departureDate->departure_date}}}" selected>{{{$departureDate->departure_date}}}</option>
                          @else
                              <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
                          @endif
                      @endif
                  @endforeach
              </select>
              <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i>
            </div>
          </div>
          <div class="ba-search-duration">
            <label>Durata</label>
            <div class="ba-search-selector-div">
              <select id="searchFlightDuration" class="ba-search-selector">
                @foreach($durationsPlane as $duration)
                    @if($searchId == 0)
                        <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
                    @else
                        @if($duration->duration == $durationSid && $pSearch->IsFlight == 1)
                            <option value="{{{$duration->duration}}}" selected>{{{$duration->duration}}} zile</option>
                        @else
                            <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
                        @endif
                    @endif
                @endforeach
              </select>
              <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i>
            </div>
          </div>
        </div>
        @endif
        <div id="searchHotel" class="ba-item-view-search-type-div ba-hidden" data-hidden="true">
          <div class="ba-search-departure-date">
            <label>Data plecare</label>
            <div class="ba-search-selector-div">
              <input type="text" id="searchHotelDepartureDate" class="datepicker ba-search-selector" readonly/>
              <i class="fa fa-calendar ba-search-selector-date-icon"></i>
            </div>
          </div>
          <div class="ba-search-return-date">
            <label>Data intoarcere</label>
            <div class="ba-search-selector-div">
              <input type="text" id="searchHotelReturnDate" class="datepicker ba-search-selector" readonly/>
              <i class="fa fa-calendar ba-search-selector-date-icon"></i>
            </div>
          </div>
        </div>
        <div class="ba-search-guests">
          <label>&nbsp;</label>
          <div id="guests"></div>
        </div>
        <div class="ba-search-button">
          <label>&nbsp;</label>
          <a class="ba-blue-button-m" id="searchButton" title="">Cauta</a>
        </div>
      </div>
    </div>
    <div id="prices">
      <div class="ba-item-view-prices ba-item-view-block ">
        <span class="ba-item-view-block-title">
          @if ($transportCode == "02")
            <img src="/images/transport/plane.png" class="tooltip" alt="Avion" title="Avion" height="22px" />
          @elseif ($transportCode == "01")
            <img src="/images/transport/bus.png" class="tooltip" alt="Autocar" title="Autocar" height="22px" />
          @elseif ($transportCode == "00")
            <img src="/images/transport/individual.png" class="tooltip" alt="Individual" title="Individual" height="22px" />
          @endif
          Oferte
        </span>
        <div class="ba-item-view-block-content">
          <div class="ba-item-view-prices-package" id="pricesOutput">
              @if (count($prices) > 0)
                @foreach($prices as $package => $packagePrices)
                  <span class="ba-item-view-prices-package-title">
                    {{{$packages[$package]->name}}} <a href="#packageDescription-{{{$packages[$package]->id}}}">Afla detalii</a>
                  </span>
                  <table class="ba-item-view-prices-table">
                    <thead>
                      <tr>
                        <th class="ba-item-view-prices-table-th-room-type">Tip camera</th>
                        <th class="ba-item-view-prices-table-th-meal-plan">Tip masa</th>
                        <th class="ba-item-view-prices-table-th-options"></th>
                        <th class="ba-item-view-prices-table-th-price">Pret</th>
                        <th class="ba-item-view-prices-table-th-button"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($packagePrices as  $roomCategory => $roomCategoryPrices)
                          @foreach($roomCategoryPrices as  $mealPlan => $mealPlanPrice)
                            <tr>
                              <td class="ba-item-view-prices-table-td-room-type">{{{$roomCategory}}}</td>
                              <td class="ba-item-view-prices-table-td-meal-plan">{{{$mealPlan}}}</td>
                              <td class="ba-item-view-prices-table-td-options">
                                @if($mealPlanPrice['earlyBooking'])
                                  <img src="/images/icons/early_booking.gif" />
                                @endif
                                @if($mealPlanPrice['specialOffer'])
                                  <img src="/images/icons/special_offer.gif" />
                                @endif
                                @if($mealPlanPrice['isBookable'])
                                  <i style="color:green;" class="fa fa-check"></i>
                                @else
                                  <i class="fa fa-envelope-o"></i>
                                @endif
                              </td>
                              <td class="ba-item-view-prices-table-td-price">€{{{($mealPlanPrice['price'])}}}</td>
                              <td class="ba-item-view-prices-table-td-button">
                              @if($mealPlanPrice['isBookable'] && $mealPlanPrice['isMainSoapClient'])
                                <center><a class="ba-blue-button-xs" href="" onClick="{{{$mealPlanPrice['function']}}}" title="">Rezerva</a></center>
                              @else
                                <center><a class="ba-blue-button-xs" href="" onClick="askForOffer({{{$package}}},'{{{$soapClientId}}}','{{{$roomCategory}}}','{{{$mealPlan}}}',{{{$rooms}}},{{{($mealPlanPrice['price'])}}},'{{{$departureDate}}}',{{{$duration}}})" title="">Cere oferta</a></center>
                              @endif
                              </td>
                              </tr>
                          @endforeach
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <td class="ba-item-view-prices-table-td-legend" colspan="5">
                          <span><i style="color:green;" class="fa fa-check"></i> - Disponibila</span>
                          <span><i class="fa fa-envelope-o"></i> - La cerere</span>
                          <span><img src="/images/icons/early_booking.gif" /> - Early Booking</span>
                          <span><img src="/images/icons/special_offer.gif" /> - Special Offer</span>
                          <span><img src="/images/icons/last_minute.gif" /> - Last Minute</span>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                @endforeach
              @endif
          </div>
        </div>
      </div>
      <hr/>
    </div>
    @if((count($hotel->detailedDescriptions) + (isset($hotel->description) ? 1 : 0)) != 0)
      <div id="hotelDescription" class="ba-item-view-hotel-description ba-item-view-block">
        <span class="ba-item-view-block-title">Descriere Hotel</span>
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
                <?php
                    $doc = new DOMDocument();
                    $doc->loadHTML($detailedDescription->text);
                    echo $doc->saveHTML();
                ?>
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
          <div id="packageDescription-{{{$package->id}}}" class="ba-item-view-package-description ba-item-view-block">
            <span class="ba-item-view-block-title">Despre {{{$package->name}}}</span>
            <div class="ba-item-view-block-content">
              @foreach($package->detailedDescriptions as $detailedDescription)
                <div class="ba-item-view-row clearfix">
                  <div class="ba-item-view-left-column">
                    {{{$detailedDescription->label}}}
                  </div>
                  <div class="ba-item-view-right-column">
                    <?php
                        $doc = new DOMDocument();
                        $doc->loadHTML($detailedDescription->text);
                        echo $doc->saveHTML();
                    ?>
                    <hr/>
                  </div>
                </div>
              @endforeach
              @if(isset($package->included_services))
                <div class="ba-item-view-row clearfix">
                  <div class="ba-item-view-left-column">
                    Servicii incluse
                  </div>
                  <div class="ba-item-view-right-column">
                    <?php
                        $doc = new DOMDocument();
                        $doc->loadHTML($package->included_services);
                        echo $doc->saveHTML();
                    ?>
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
                    <?php
                        $doc = new DOMDocument();
                        $doc->loadHTML($package->not_included_services);
                        echo $doc->saveHTML();
                    ?>
                  </div>
                </div>
              @endif
            </div>
          </div>
          <hr/>
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
@stop
