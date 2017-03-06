  <style>
  	.ba-hidden{
  		display: none;
  	}
  	.ba-blue-button-xs{
  		cursor: pointer;
  	}
  </style>
  <link href="/css/roomify.css" rel="stylesheet" />
  <script type="text/javascript" src="/js/roomify.js"></script>
  <script>
    $(document).ready(function(){
      $('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
    });
  </script>
  <script>

    $(document).ready(function(){
      @if(isset($jsonRooms) && $jsonRooms != null)
      $('#guests').roomify(<?php echo $jsonRooms; ?>);
      @else
      $('#guests').roomify(null);
      @endif
    });
  </script>
  <script>
  $(document).ready(function(){
    $('.ba-item-view-search-type-option').click(function(){
      console.log($(this).data('toggle'));
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
    var rememberedRooms = [];
    var rememberedPackageSearchObject = new Object();
    var rememberedHotelSearchObject = new Object();

    $(document).ready(function() {
        var rooms = $("#guests").data('value');
        $.each(rooms.guests, function(i,room){
          roomTmp = new Object();
          roomTmp.adults = room.adults;
          if(room.kids != null){
            roomTmp.kids = room.kids;
          }
          rememberedRooms.push(roomTmp);
        });
        switch($('#selectedSearchType').val()){
          case "{{{App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL}}}":
            rememberedPackageSearchObject.is_flight = 0;
            rememberedPackageSearchObject.is_bus = 0;
            rememberedPackageSearchObject.is_tour = 0;
            rememberedPackageSearchObject.departure_point = $("#searchIndividualDeparturePoint").val();
            rememberedPackageSearchObject.duration = $("#searchIndividualDuration").val();
          break;
          case "{{{App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS}}}":
            rememberedPackageSearchObject.is_flight = 0;
            rememberedPackageSearchObject.is_bus = 1;
            rememberedPackageSearchObject.is_tour = 0;
            rememberedPackageSearchObject.departure_point = $("#searchBusDeparturePoint").val();
            rememberedPackageSearchObject.departure_date = $("#searchBusDepartureDate").val();
            rememberedPackageSearchObject.duration = $("#searchBusDuration").val();
          break;
          case "{{{App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT}}}":
            rememberedPackageSearchObject.is_flight = 1;
            rememberedPackageSearchObject.is_bus = 0;
            rememberedPackageSearchObject.is_tour = 0;
            rememberedPackageSearchObject.departure_point = $("#searchFlightDeparturePoint").val();
            rememberedPackageSearchObject.departure_date = $("#searchFlightDepartureDate").val();
            rememberedPackageSearchObject.duration = $("#searchFlightDuration").val();
          break;
          case "{{{App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL}}}":
            rememberedPackageSearchObject.is_flight = 0;
            rememberedPackageSearchObject.is_bus = 0;
            rememberedPackageSearchObject.is_tour = 1;
            rememberedPackageSearchObject.departure_point = $("#searchIndividualDeparturePoint").val();
            rememberedPackageSearchObject.departure_date = $("#searchIndividualDepartureDate").val();
            rememberedPackageSearchObject.duration = $("#searchIndividualDuration").val();
          break;
          case "{{{App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS}}}":
            rememberedPackageSearchObject.is_flight = 0;
            rememberedPackageSearchObject.is_bus = 1;
            rememberedPackageSearchObject.is_tour = 1;
            rememberedPackageSearchObject.departure_point = $("#searchBusDeparturePoint").val();
            rememberedPackageSearchObject.departure_date = $("#searchBusDepartureDate").val();
            rememberedPackageSearchObject.duration = $("#searchBusDuration").val();
          break;
          case "{{{App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT}}}":
            rememberedPackageSearchObject.is_flight = 1;
            rememberedPackageSearchObject.is_bus = 0;
            rememberedPackageSearchObject.is_tour = 1;
            rememberedPackageSearchObject.departure_point = $("#searchFlightDeparturePoint").val();
            rememberedPackageSearchObject.departure_date = $("#searchFlightDepartureDate").val();
            rememberedPackageSearchObject.duration = $("#searchFlightDuration").val();
          break;
          case "{{{App\Http\Controllers\Travel\OffersController::T_HOTEL}}}":
            rememberedHotelSearchObject.check_in = $('#searchHotelDepartureDate').val();
            rememberedHotelSearchObject.check_out = $('#searchHotelReturnDate').val();
            var date1Array = $('#searchHotelDepartureDate').val().split("/");
            var date2Array = $('#searchHotelReturnDate').val().split("/");
            var date1 = new Date(parseInt(date1Array[2]),parseInt(date1Array[1]),parseInt(date1Array[0]));
            var date2 = new Date(parseInt(date2Array[2]),parseInt(date2Array[1]),parseInt(date2Array[0]));
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            rememberedHotelSearchObject.stay = Math.ceil(timeDiff / (1000*3600*24));
          break;
          default:
        }
        rememberedPackageSearchObject.hotel = {{{$hotel->id}}};
        rememberedPackageSearchObject.destination = {{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$hotel->location)}}};
        rememberedPackageSearchObject.soap_client = "{{{$soapClientId}}}";
        rememberedPackageSearchObject.rooms = rememberedRooms;
        rememberedHotelSearchObject.hotel = {{{$hotel->id}}};
        rememberedHotelSearchObject.destination = {{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$hotel->location)}}};
        rememberedHotelSearchObject.rooms = rememberedRooms;
        rememberedHotelSearchObject.soap_client = "{{{$hotel->soap_client}}}";

        var urlSearchAjaxSinglePackageSearch = "/ajax_search/singlePackageSearch";
        var urlSearchAjaxSingleHotelSearch = "/ajax_search/singleHotelSearch";

        $("#searchButton").click(function(){
            var rooms = $("#guests").data('value');
            var Rooms = [];
            $('#rememberedSelectedSearchType').val($('#selectedSearchType').val());

            $('#pricesOutput').html('<div class="ba-item-view-prices-loading"><center><img src="/images/loader.gif" /><br/>Se cauta oferte...</center></div>');
            $.each(rooms.guests, function(i,room){
              roomTmp = new Object();
              roomTmp.adults = room.adults;
              if(room.kids != null){
                roomTmp.kids = room.kids;
              }
              Rooms.push(roomTmp);
            });
            if($('#selectedSearchType').val() != "{{{App\Http\Controllers\Travel\OffersController::T_HOTEL}}}"){
              var packageSearchObject = new Object();
              switch($('#selectedSearchType').val()){
              	case "{{{App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL}}}":
                  packageSearchObject.is_flight = 0;
                  packageSearchObject.is_bus = 0;
                  packageSearchObject.is_tour = 0;
                  packageSearchObject.departure_point = $("#searchIndividualDeparturePoint").val();
                  packageSearchObject.departure_date = $("#searchIndividualDepartureDate").val();
                  packageSearchObject.duration = $("#searchIndividualDuration").val();
                  $("#searchTransportType").attr('src',"/images/transport/individual.png");
                  $('.ba-package-description-flight').addClass('ba-hidden');
                  $('.ba-package-description-bus').removeClass('ba-hidden');
                  $('.ba-package-description-individual').addClass('ba-hidden');
                break;
                case "{{{App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS}}}":
                  packageSearchObject.is_flight = 0;
                  packageSearchObject.is_bus = 1;
                  packageSearchObject.is_tour = 0;
                  packageSearchObject.departure_point = $("#searchBusDeparturePoint").val();
                  packageSearchObject.departure_date = $("#searchBusDepartureDate").val();
                  packageSearchObject.duration = $("#searchBusDuration").val();
                  $("#searchTransportType").attr('src',"/images/transport/bus.png");
                  $('.ba-package-description-flight').addClass('ba-hidden');
                  $('.ba-package-description-bus').removeClass('ba-hidden');
                  $('.ba-package-description-individual').addClass('ba-hidden');
                break;
                case "{{{App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT}}}":
                  packageSearchObject.is_flight = 1;
                  packageSearchObject.is_bus = 0;
                  packageSearchObject.is_tour = 0;
                  packageSearchObject.departure_point = $("#searchFlightDeparturePoint").val();
                  packageSearchObject.departure_date = $("#searchFlightDepartureDate").val();
                  packageSearchObject.duration = $("#searchFlightDuration").val();
                  $("#searchTransportType").attr('src',"/images/transport/plane.png");
                  $('.ba-package-description-flight').removeClass('ba-hidden');
                  $('.ba-package-description-bus').addClass('ba-hidden');
                  $('.ba-package-description-individual').addClass('ba-hidden');
                break;
                case "{{{App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL}}}":
                  packageSearchObject.is_flight = 0;
                  packageSearchObject.is_bus = 0;
                  packageSearchObject.is_tour = 1;
                  packageSearchObject.departure_point = $("#searchIndividualDeparturePoint").val();
                  packageSearchObject.departure_date = $("#searchIndividualDepartureDate").val();
                  packageSearchObject.duration = $("#searchIndividualDuration").val();
                  $("#searchTransportType").attr('src',"/images/transport/individual.png");
                  $('.ba-package-description-flight').addClass('ba-hidden');
                  $('.ba-package-description-bus').addClass('ba-hidden');
                  $('.ba-package-description-individual').removeClass('ba-hidden');
                break;
                case "{{{App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS}}}":
                  packageSearchObject.is_flight = 0;
                  packageSearchObject.is_bus = 1;
                  packageSearchObject.is_tour = 1;
                  packageSearchObject.departure_point = $("#searchBusDeparturePoint").val();
                  packageSearchObject.departure_date = $("#searchBusDepartureDate").val();
                  packageSearchObject.duration = $("#searchBusDuration").val();
                  $("#searchTransportType").attr('src',"/images/transport/bus.png");
                  $('.ba-package-description-flight').addClass('ba-hidden');
                  $('.ba-package-description-bus').removeClass('ba-hidden');
                  $('.ba-package-description-individual').addClass('ba-hidden');
                break;
                case "{{{App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT}}}":
                  packageSearchObject.is_flight = 1;
                  packageSearchObject.is_bus = 0;
                  packageSearchObject.is_tour = 1;
                  packageSearchObject.departure_point = $("#searchFlightDeparturePoint").val();
                  packageSearchObject.departure_date = $("#searchFlightDepartureDate").val();
                  packageSearchObject.duration = $("#searchFlightDuration").val();
                  $("#searchTransportType").attr('src',"/images/transport/plane.png");
                  $('.ba-package-description-flight').removeClass('ba-hidden');
                  $('.ba-package-description-bus').addClass('ba-hidden');
                  $('.ba-package-description-individual').addClass('ba-hidden');
                break;
                default:
              }
              packageSearchObject.hotel = {{{$hotel->id}}};
              packageSearchObject.destination = {{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$hotel->location)}}};
              packageSearchObject.soap_client = "{{{$soapClientId}}}";
              packageSearchObject.rooms = Rooms;
              rememberedPackageSearchObject = packageSearchObject;

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
                  			if(price.lastMinute){
                  				text += '<img src="/images/icons/last_minute.gif" />';
                  			}
                            if(price.isBookable == true && price.IsAvailable == true){
                                text += '<i style="color:green;" class="fa fa-check"></i>';
                            } else {
                                text += '<i class="fa fa-envelope-o"></i>';
                            }
                            text += '</td>';
                            if(price.price != price.priceWithoutDiscount){
                            	text += '<td class="ba-item-view-prices-table-td-price"><strike>€'+price.priceWithoutDiscount+'</strike> <strong>€'+price.price+'</strong></td>';
                            } else {
                            	text += '<td class="ba-item-view-prices-table-td-price"><strong>€'+price.price+'</strong></td>';
                            }
                            //&& price.isMainSoapClient
                            if(price.isBookable && price.isAvailable ){
                                text += '<td class="ba-item-view-prices-table-td-button"><center><a class="ba-blue-button-xs" href="" onClick="'+price.onClickFunction+'">Rezerva</a></center></td>';
                            } else {
                                text += '<td class="ba-item-view-prices-table-td-button"><center><a class="ba-blue-button-xs" href="" onClick=\''+price.askForOfferFunction+'\'">Cere oferta</a></center></td>';
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
            } else {
              var hotelSearchObject = new Object();
              hotelSearchObject.check_in = $('#searchHotelDepartureDate').val();
              hotelSearchObject.check_out = $('#searchHotelReturnDate').val();
              var date1Array = $('#searchHotelDepartureDate').val().split("/");
              var date2Array = $('#searchHotelReturnDate').val().split("/");
              var date1 = new Date(parseInt(date1Array[2]),parseInt(date1Array[1]),parseInt(date1Array[0]));
              var date2 = new Date(parseInt(date2Array[2]),parseInt(date2Array[1]),parseInt(date2Array[0]));
              var timeDiff = Math.abs(date2.getTime() - date1.getTime());
              hotelSearchObject.stay = Math.ceil(timeDiff / (1000*3600*24));
              hotelSearchObject.hotel = {{{$hotel->id}}};
              hotelSearchObject.destination = {{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$hotel->location)}}};
              hotelSearchObject.rooms = Rooms;
              hotelSearchObject.soap_client = "{{{$hotel->soap_client}}}";
              $('.ba-package-description-flight').addClass('ba-hidden');
              $('.ba-package-description-bus').addClass('ba-hidden');
              $('.ba-package-description-individual').addClass('ba-hidden');
              rememberedHotelSearchObject = hotelSearchObject;
              $("#searchTransportType").attr('src',"/images/transport/individual.png");
              $.get(urlSearchAjaxSingleHotelSearch,{ hotelSearch: hotelSearchObject },function(data){
                  var availablePackages = false;
                  var jsonData = $.parseJSON(data);
                  prices = jsonData.prices;
                  $("#pricesOutput").html("");
                  var appendedText = "";
                  $.each(prices , function(packageKey,packages) {
                          $('#pricesOutput').html('');
                          availablePackages = true;
                          appendedText = "";
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
                            if(price.isBookable){
                                text += '<i style="color:green;" class="fa fa-check"></i>';
                            } else {
                                text += '<i class="fa fa-envelope-o"></i>';
                            }
                            text += '</td>'+
                                    '<td class="ba-item-view-prices-table-td-price">€'+price.price+'</td>';
                            if(price.isBookable && price.isAvailable){
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
            }
        });

    });

    function askForOfferPackage(packageId,soapClient,roomCategory,mealPlan,rooms,price,departureDate,duration,event){
        //evnt = e || window.event
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
        //console.log(offerObject);
        $.get(urlAskForOffer,{ offer: offerObject},function(response){
            //console.log(response);
            window.location.replace("/cere_oferta/ref"+response.id);
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
        offerObject.rooms = rooms;
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

    function bookPackage(packageId,categoryId,mealPlanLabel,price,event){
        event.preventDefault();
        $ = jQuery;
        var urlSearchAjaxSinglePackageSearchBeforeBooking = "/ajax_search/singlePackageSearchBeforeBooking";
        $("#pricesOutput").html('');
        $('#pricesOutput').html('<div class="ba-item-view-prices-loading"><center><img src="/images/loader.gif" /><br/>Se verifica disponibilitatea...</center></div>')
        var packageSearchObject = rememberedPackageSearchObject;
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
                    if(price.isBookable && price.isAvailable){
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

    function bookHotel(hotelId,categoryId,mealPlanLabel,price,event){
        event.preventDefault();
        $ = jQuery;
        var urlSearchAjaxSingleHotelSearchBeforeBooking = "/ajax_search/singleHotelSearchBeforeBooking";
        $("#pricesOutput").html('');
        $('#pricesOutput').html('<div class="ba-item-view-prices-loading"><center><img src="/images/loader.gif" /><br/>Se verifica disponibilitatea...</center></div>')
        var hotelSearchObject = rememberedHotelSearchObject;
        var oldPriceInfo = new Object();
        oldPriceInfo.hotelId = hotelId;
        oldPriceInfo.categoryId = categoryId;
        oldPriceInfo.mealPlanLabel = mealPlanLabel;
        oldPriceInfo.price = price;
        $.get(urlSearchAjaxSingleHotelSearchBeforeBooking,{ hotelSearch: hotelSearchObject , hotelId: hotelId, oldPriceInfo: oldPriceInfo},function(response){
            var response = $.parseJSON(response);
            if(response.status){
                window.location.replace("/rezerva/hotel/ref"+response.id);
            } else {
              var availablePackages = false;
              $("#pricesOutput").html("");
              var appendedText = "";
              var prices = response.prices;
              $.each(prices , function(packageKey,packages) {
                      $('#pricesOutput').html('');
                      availablePackages = true;
                      appendedText = "";
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
                        if(price.isBookable && price.isAvailable){
                            text += '<i style="color:green;" class="fa fa-check"></i>';
                        } else {
                            text += '<i class="fa fa-envelope-o"></i>';
                        }
                        text += '</td>'+
                                '<td class="ba-item-view-prices-table-td-price">€'+price.price+'</td>';
                        if(price.isBookable && price.isAvailable){
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
    }

  </script>
  <script type="text/javascript">
  	$(document).ready(function ($) {

  // $('#checkbox').change(function(){
    // setInterval(function () {
        // moveRight();
    // }, 3000);
  // });

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

<section class="packages">
    <?php
          $packageTmp = App\Models\Travel\PackageInfo::where('id','=',key($prices))->where('soap_client','=',$soapClientId)->first();
    ?>
	<div class="vc_empty_space" style="height: 100px">
		<span class="vc_empty_space_inner"></span>
	</div>
	<div class="nicdark_container nicdark_vc nicdark_clearfix">
		<div class="ba-item-view-heading container clearfix">
	    <div class="ba-item-view-heading-left two-thirds column ba-column-no-margin-left vc_col-sm-9">
	       <h1 class="ba-item-view-title">
           @if($offerType == 'Circuits')
             {{$packageTmp->name}}
           @else
             {{$hotel->name}}
           @endif
         </h1>
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
		                    if(file_exists(public_path()."/images/offers/{$image->name}")){
                          $imgUrl = "/images/offers/{$image->name}";
                        }else{
                          $imgUrl = "/images/offers/{$image->id}.{$type}";
                        }
		                } else {
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


			    <div id="priceSearch" class="ba-item-view-search-title">Verifica Disponibilitatea</div>
			    <div class="ba-item-view-search clearfix">
			      <div class="ba-item-view-search-type clearfix">
			        <input id="selectedSearchType" type="hidden" value="{{{$transportCode}}}" />
			        <input id="rememberedSelectedSearchType" type="hidden" value="{{{$transportCode}}}" />

			        @if(count($departurePointsIndividual) == 0 && count($departureDatesIndividual) >= 1 && count($durationsIndividual) >= 1)
			        <span class="ba-item-view-search-type-option active" data-toggle="#searchIndividual"><input type="radio" name="searchType" value="{{{$IsTour == 0 ? App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL : App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? "checked": ""}}}>
			          @if($IsTour == 0)
			            Sejur Individual
			          @else
			            Circuit Individual
			          @endif
			        </span>
			        @endif
			        @if(count($departurePointsBus) >= 1 && count($departureDatesBus) >= 1 && count($durationsBus) >= 1)
			        <span class="ba-item-view-search-type-option active" data-toggle="#searchBus"><input type="radio" name="searchType" value="{{{$IsTour == 0 ? App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS : App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS ? "checked": ""}}}>
			          @if($IsTour == 0)
			            Sejur Autocar
			          @else
			            Circuit Autocar
			          @endif
			        </span>
			        @endif
			        @if(count($departurePointsPlane) >= 1 && count($departureDatesPlane) >= 1 && count($durationsPlane) >= 1)
			        <span class="ba-item-view-search-type-option" data-toggle="#searchFlight"><input type="radio" name="searchType" value="{{{$IsTour == 0 ? App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT : App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT ? "checked": ""}}}>
			          @if($IsTour == 0)
			            Sejur Avion
			          @else
			            Circuit Avion
			          @endif
			        </span>
			        @endif
			        @if($IsTour == 0)
			        <span class="ba-item-view-search-type-option" data-toggle="#searchHotel"><input type="radio" name="searchType" value="{{{App\Http\Controllers\Travel\OffersController::T_HOTEL}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? "checked": ""}}}> Hotel</span>
			        @endif
			      </div>
			      <div class="ba-item-view-search-in clearfix">
			        @if(count($departurePointsBus) >= 1 && count($departureDatesBus) >= 1 && count($durationsBus) >= 1)
			        <div id="searchBus" class="ba-item-view-search-type-div {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS ? "": "ba-hidden"}}}" data-hidden="{{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS ? "false": "true"}}}">
			          <div class="ba-search-departure-point">
			            <label class="search_label">Plecare din</label>
			            <div class="ba-search-selector-div">
			              <select id="searchBusDeparturePoint" class="ba-search-selector">

			                @foreach($departurePointsBus as $departurePoint)
			                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS))
			                    <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}">{{{$departurePoint->name}}}</option>
			                    @else
			                        @if($departurePoint->id_geography == $departure_point)
			                            <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}" selected>{{{$departurePoint->name}}}</option>
			                        @else
			                            <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}">{{{$departurePoint->name}}}</option>
			                        @endif
			                    @endif
			                @endforeach
			              </select>
			              <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i>
			            </div>
			          </div>
			          <div class="ba-search-departure-date">
			            <label class="search_label">Data plecarii</label>
			            <div class="ba-search-selector-div">
			              <select id="searchBusDepartureDate" class="ba-search-selector">
			                  @foreach($departureDatesBus as $departureDate)
			                      @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS))
			                      <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
			                      @else
			                          @if($departureDate->departure_date == $departure_date)
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
			            <label class="search_label">Durata</label>
			            <div class="ba-search-selector-div">
			              <select id="searchBusDuration" class="ba-search-selector">

			                @foreach($durationsBus as $duration)
			                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS))
			                        <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
			                    @else
			                        @if($duration->duration == $durationSid)
			                            <option value="{{{$duration->duration}}}" selected>{{{$duration->duration}}} zile</option>
			                        @else
			                            <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
			                        @endif
			                    @endif
			                @endforeach
			              </select>
			              <!-- <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i> -->
			            </div>
			          </div>
			          <div class="clear"></div>
			        </div>
			        @endif
			        @if(count($departurePointsPlane) >= 1 && count($departureDatesPlane) >= 1 && count($durationsPlane) >= 1)
			        <div id="searchFlight" class="ba-item-view-search-type-div {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT ? "": "ba-hidden"}}}" data-hidden="{{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT ? "false": "true"}}}">
			          <div class="ba-search-departure-point">
			            <label class="search_label">Plecare din</label>
			            <div class="ba-search-selector-div">
			              <select id="searchFlightDeparturePoint" class="ba-search-selector">
			                @foreach($departurePointsPlane as $departurePoint)
			                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT))
			                    <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}">{{{$departurePoint->name}}}</option>
			                    @else
			                        @if($departurePoint->id_geography == $departure_point)
			                            <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}" selected>{{{$departurePoint->name}}}</option>
			                        @else
			                            <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}">{{{$departurePoint->name}}}</option>
			                        @endif
			                    @endif
			                @endforeach
			              </select>
			              <!-- <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i> -->
			            </div>
			          </div>
			          <div class="ba-search-departure-date">
			            <label class="search_label">Data plecarii</label>
			            <div class="ba-search-selector-div">
			              <select id="searchFlightDepartureDate" class="ba-search-selector">
			                  @foreach($departureDatesPlane as $departureDate)
			                      @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT))
			                      <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
			                      @else
			                          @if($departureDate->departure_date == $departure_date)
			                              <option value="{{{$departureDate->departure_date}}}" selected>{{{$departureDate->departure_date}}}</option>
			                          @else
			                              <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
			                          @endif
			                      @endif
			                  @endforeach
			              </select>
			              <!-- <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i> -->
			            </div>
			          </div>
			          <div class="ba-search-duration">
			            <label class="search_label">Durata</label>
			            <div class="ba-search-selector-div">
			              <select id="searchFlightDuration" class="ba-search-selector">
			                @foreach($durationsPlane as $duration)
			                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT))
			                        <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
			                    @else
			                        @if($duration->duration == $durationSid)
			                            <option value="{{{$duration->duration}}}" selected>{{{$duration->duration}}} zile</option>
			                        @else
			                            <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
			                        @endif
			                    @endif
			                @endforeach
			              </select>
			              <!-- <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i> -->
			            </div>
			          </div>
			          <div class="clear"></div>
			        </div>
			        @endif
			        @if(count($departurePointsPlane) >= 1 && count($departureDatesPlane) >= 1 && count($durationsPlane) >= 1)
			        <div id="searchIndividual" class="ba-item-view-search-type-div {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? "": "ba-hidden"}}}" data-hidden="{{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? "false": "true"}}}">
                <input type="hidden" id="searchIndividualDeparturePoint" value="null">
                <?php  /*<div class="ba-search-departure-point">
			            <label>Plecare din</label>
			            <div class="ba-search-selector-div">
			              <select id="searchIndividualDeparturePoint" class="ba-search-selector">
			                @foreach($departurePointsIndividual as $departurePoint)
			                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL))
			                    <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}">{{{$departurePoint->name}}}</option>
			                    @else
			                        @if($departurePoint->id_geography == $departure_point)
			                            <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}" selected>{{{$departurePoint->name}}}</option>
			                        @else
			                            <option value="{{{App\Models\Travel\Geography::getLocationIdForSoapClient($soapClientId,$departurePoint->id_geography)}}}">{{{$departurePoint->name}}}</option>
			                        @endif
			                    @endif
			                @endforeach
			              </select>
			              <i class="fa fa-chevron-down ba-search-selector-down-arrow"></i>
			            </div>
			          </div> */?>
			          <div class="ba-search-departure-date">
			            <label>Data plecarii</label>
			            <div class="ba-search-selector-div">
			              <select id="searchIndividualDepartureDate" class="ba-search-selector">
			                  @foreach($departureDatesIndividual as $departureDate)
			                      @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL))
			                      <option value="{{{$departureDate->departure_date}}}">{{{$departureDate->departure_date}}}</option>
			                      @else
			                          @if($departureDate->departure_date == $departure_date)
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
			              <select id="searchIndividualDuration" class="ba-search-selector">
			                @foreach($durationsIndividual as $duration)
			                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL))
			                        <option value="{{{$duration->duration}}}">{{{$duration->duration}}} zile</option>
			                    @else
			                        @if($duration->duration == $durationSid)
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
			        <div id="searchHotel" class="ba-item-view-search-type-div {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? "": "ba-hidden"}}}" data-hidden="{{{$transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? "false": "true"}}}">
			          <div class="ba-search-departure-date">
			            <label>Data plecare</label>
			            <div class="ba-search-selector-div">
			              <input type="text" id="searchHotelDepartureDate" value="{{{$searchId != 0 && $transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? $check_in : ""}}}" class="datepicker ba-search-selector" readonly/>
			              <!-- <i class="fa fa-calendar ba-search-selector-date-icon"></i> -->
			            </div>
			          </div>
			          <div class="ba-search-return-date">
			            <label>Data intoarcere</label>
			            <div class="ba-search-selector-div">
			              <input type="text" id="searchHotelReturnDate" value="{{{$searchId != 0 && $transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? $check_out : ""}}}" class="datepicker ba-search-selector" readonly/>
			              <!-- <i class="fa fa-calendar ba-search-selector-date-icon"></i> -->
			            </div>
			          </div>
			        </div>
			        <div class="ba-search-guests">
			          <label>&nbsp;</label>
			          <div id="guests"></div>
			        </div>
			        <div class="ba-search-button">
			          <label>&nbsp;</label>
			          <a href="javascript:void(0);" class="ba-blue-button-m" id="searchButton" title="">Cauta</a>
			        </div>
			        <div class="clear"></div>
			      </div>
			    </div>
			    <div id="prices">
			      <div class="ba-item-view-prices ba-item-view-block ">
			        <span class="ba-item-view-block-title">
			          @if ($transportCode == "02")
			            <img id="searchTransportType" src="/images/transport/plane.png" class="et-tooltip" alt="Avion" title="Avion" height="22px" />
			          @elseif ($transportCode == "01")
			            <img id="searchTransportType" src="/images/transport/bus.png" class="et-tooltip" alt="Autocar" title="Autocar" height="22px" />
			          @elseif ($transportCode == "00" || $transportCode == "03")
			            <img id="searchTransportType" src="/images/transport/individual.png" class="et-tooltip" alt="Individual" title="Individual" height="22px" />
			          @endif
			          Oferte
			        </span>
			        <div class="ba-item-view-block-content">
			          <div class="ba-item-view-prices-package" id="pricesOutput">

			              @if (count($prices) > 0 && $transportCode != App\Http\Controllers\Travel\OffersController::T_HOTEL)
			                @foreach($prices as $package => $packagePrices)
			                  <span class="ba-item-view-prices-package-title">
			                    <?php $packageTmp = App\Models\Travel\PackageInfo::where('id','=',$package)->where('soap_client','=',$soapClientId)->first(); ?>
			                    {{{$packageTmp->name}}} <a href="#packageDescription-{{{$packageTmp->id}}}">Afla detalii</a>
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
			                                @if($mealPlanPrice['lastMinute'])
			                                  <img src="/images/icons/last_minute.gif" />
			                                @endif
			                                @if($mealPlanPrice['isBookable'] == true && $mealPlanPrice['isAvailable'] == true)
			                                  <i style="color:green;" class="fa fa-check"></i>
			                                @else
			                                  <i class="fa fa-envelope-o"></i>
			                                @endif
			                              </td>
			                              <td class="ba-item-view-prices-table-td-price">

			                              	@if($mealPlanPrice['price'] != $mealPlanPrice['priceWithoutDiscount'])
			                              		<strike>€{{{$mealPlanPrice['priceWithoutDiscount']}}}</strike>&nbsp;
			                              	@endif
			                              	<strong>€{{{($mealPlanPrice['price'])}}}</strong>
			                              </td>
			                              <td class="ba-item-view-prices-table-td-button">
			                              @if($mealPlanPrice['isBookable'] && $mealPlanPrice['isAvailable'] )
                                      <center><a class="ba-blue-button-xs" href="" onClick="{{{$mealPlanPrice['function']}}}" title="">Rezerva</a></center>
			                              @else
			                                <center><a class="ba-blue-button-xs" onClick="askForOfferPackage({{{$package}}},'{{{$soapClientId}}}','{{{$roomCategory}}}','{{{$mealPlan}}}',{{{$rooms}}},{{{($mealPlanPrice['price'])}}},'{{{($departureDate->departure_date)}}}',{{{($duration->duration)}}},event)" title="">Cere oferta</a></center>
										                @endif

                                    <script type="text/javascript">
                                      console.log($('#searchFlightDepartureDate').val());
                                    </script>
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
			              @if (count($prices) > 0 && $transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL)
			                @foreach($prices as $package => $packagePrices)
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
			                                @if($mealPlanPrice['isBookable'])
			                                  <i style="color:green;" class="fa fa-check"></i>
			                                @else
			                                  <i class="fa fa-envelope-o"></i>
			                                @endif
			                              </td>
			                              <td class="ba-item-view-prices-table-td-price">€{{{($mealPlanPrice['price'])}}}</td>
			                              <td class="ba-item-view-prices-table-td-button">
			                              <?php /*
			                              @if($mealPlanPrice['isBookable'] && $mealPlanPrice['isMainSoapClient'])
			                                <center><a class="ba-blue-button-xs" href="" onClick="{{{$mealPlanPrice['function']}}}" title="">Rezerva</a></center>
			                              @else */ ?>

			                              <?php  //@endif ?>
                                    @if($mealPlanPrice['isBookable'] && $mealPlanPrice['isAvailable'] )
                                      <center><a class="ba-blue-button-xs" href="" onClick="{{{$mealPlanPrice['function']}}}" title="">Rezerva</a></center>
			                              @else
			                                <center><a class="ba-blue-button-xs" onClick="askForOfferHotel({{{$package}}},'{{{$soapClientId}}}','{{{$roomCategory}}}','{{{$mealPlan}}}',{{{$rooms}}},{{{($mealPlanPrice['price'])}}},'{{{$check_in}}}','{{{$check_out}}}',event)" title="">Cere oferta</a></center>
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
			              @if(count($prices) == 0)
			              <p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>
			              @endif
			          </div>
			        </div>
			      </div>
			    </div>




			    @if((count($hotel->detailedDescriptions) + (isset($hotel->description) ? 1 : 0)) != 0)
			      <div id="hotelDescription" class="ba-item-view-hotel-description ba-item-view-block">
			        <span class="ba-item-view-block-title">
			        @if($IsTour)
			          Descriere Circuit
			        @else
			          Descriere Hotel
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
			            <span class="ba-item-view-block-title">Despre {{{$package->name}}}</span>
			            <div class="ba-item-view-block-content">
			              @foreach($package->detailedDescriptions as $detailedDescription)
			                <div class="ba-item-view-row clearfix">
			                  <div class="ba-item-view-left-column">
			                    {{{$detailedDescription->label}}}
			                  </div>
			                  <div class="ba-item-view-right-column">
			                    <?php echo $detailedDescription->text; ?>
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
			                  </div>
			                </div>
			              @endif
			            <hr/>
                  <div id="priceList" class="ba-item-view-row clearfix">
                    <?php $pricesCachedArray = App\Models\Travel\PackageInfo::getCachedPricesTable($package->id,$package->soap_client); ?>

                    @if(count($pricesCachedArray) != 0)
                    <div class="ba-item-view-left-column">
                      Tarife informative pe persoana<br/><br/>
                    </div>
                    <table class="ba-item-view-prices-table">
                        <thead>
                          <th >Data Plecarii</th>
                          <?php $tmpArrayOptions = array(); ?>
                          @foreach($pricesCachedArray as $k => $v)
                            <th>{{$k}}</th>
                            <?php $tmpArrayOptions[] = $k; ?>
                          @endforeach
                        </thead>
                        <tbody>

                            @foreach($pricesCachedArray[$tmpArrayOptions[0]] as $date => $price)
                                <tr>
                                  <td>

                                    {{$date}}
                                  </td>
                                  @foreach($tmpArrayOptions as $option)
                                    <td>&euro;{{isset($pricesCachedArray[$option][$date]) ? $pricesCachedArray[$option][$date] : "N/A"}}</td>
                                  @endforeach
                                </tr>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                  @endif
                  </div>

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
