<link rel="stylesheet" href="{{ asset('sximo/themes/helloholiday/css/remodal.css')}}" />
<link rel="stylesheet" href="{{ asset('sximo/themes/helloholiday/css/remodal-default-theme.css')}}" />
<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/remodal.min.js')}}"></script>
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
		$("input[name='transportType']").click(function(){
			$(".ba-item-view-search-type-div").removeClass('ba-hidden');
			$(".ba-item-view-search-type-div").addClass('ba-hidden');
			$('#selectedSearchType').val($(this).val());
			switch($(this).val()){
				case "00":
					$("#searchHotel").removeClass('ba-hidden');
				break;
				case "01":
					$("#searchBus").removeClass('ba-hidden');
				break;
				case "02":
					$("#searchFlight").removeClass('ba-hidden');
				break;
				case "03":
					$("#searchIndividual").removeClass('ba-hidden');
				break;
				case "11":
					$("#searchBus").removeClass('ba-hidden');
				break;
				case "12":
					$("#searchFlight").removeClass('ba-hidden');
				break;
				case "13":
					$("#searchIndividual").removeClass('ba-hidden');
				break;
				default:
			}
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

        $("#searchButton").click(function(e){
          e.preventDefault();
        	$('#offersTab').click();
          	$('.packageDescription').removeClass('ba-hidden');
          	$('.packageDescription').addClass('ba-hidden');
            var rooms = $("#guests").data('value');
            var Rooms = [];
            $('#rememberedSelectedSearchType').val($('#selectedSearchType').val());

            $('#pricesOutput').html('<div class="ba-item-view-prices-loading"><center><img src="/sximo/themes/helloholiday/images/loader.gif" /><br/>Se cauta oferte...</center></div>');
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
                  packageSearchObject.departure_point = 0;
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
                  packageSearchObject.departure_point = 0;
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
                  var packagesFound = jsonData.packagesFound;
                  $(packagesFound).each(function(id,val){
                  	$('#packageDescription-'+val).removeClass('ba-hidden');
                  });
                  var appendedText = "";
                  $.each(prices , function(packageKey,packages) {
                  	 var countPricest = 0;
                  	 var check_rezerva = 0;
                          $('#pricesOutput').html('');
                          availablePackages = true;
                          appendedText = "";
                          $("#pricesOutput").append('<span class="ba-item-view-prices-package-title"> '+jsonData.packages[packageKey].name+'</span>');
                          appendedText = '<table class="ba-item-view-prices-table">'+
                                         '<thead>'+
                                         '<tr>'+
                                         '<th class="ba-item-view-prices-table-th-room-type">Tip camera</th>'+
                                         '<th class="ba-item-view-prices-table-th-meal-plan">Tip masa</th>'+
                                         '<th class="ba-item-view-prices-table-th-options"></th>'+
                                         '<th class="ba-item-view-prices-table-th-price">Pret Catalog</th>'+
                                         '<th class="ba-item-view-prices-table-th-price">Pret</th>'+
                                         '<th class="ba-item-view-prices-table-th-button"></th>'+
                                         '</tr>'+
                                         '</thead>'+
                                         '<tbody>';
                          $.each(packages, function(priceKey,price){
                          	countPricest++;
                          	var text = '<tr>'+
      	                                '<td class="ba-item-view-prices-table-td-room-type">'+price.roomCategory+'</td>'+
      	                                '<td class="ba-item-view-prices-table-td-meal-plan">'+price.mealPlan+'</td>'+
      	                                '<td class="ba-item-view-prices-table-td-options">';
                            if(price.earlyBooking){
                              text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-early-booking.png">';
                            }
		                  			if(price.specialOffer){
		                  				text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-oferta-speciala.png">';
		                  			}
                            if(price.isBookable == true && price.isAvailable == true ){
                                text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-disponibil.png">';
                            } else {
                                text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png">';
                            }
                            text += '</td>';
                            if(price.priceWithoutDiscount != price.price){
                            	text +='<td class="ba-item-view-prices-table-td-price"><strike>€'+price.priceWithoutDiscount+'</strike></td>';
                            } else {
                            	text +='<td class="ba-item-view-prices-table-td-price">€'+price.priceWithoutDiscount+'</td>';
                            }
                            if(price.discounts.length != 0){
                            	text +='<td class="ba-item-view-prices-table-td-price"><span class="discountLink" onClick=\'openDiscountModal('+JSON.stringify(price.discounts)+')\'>€'+price.price+'</span></td>';
                            } else {
                            	text +='<td class="ba-item-view-prices-table-td-price">€'+price.price+'</td>';
                            }

                            //console.log(price.isBookable ,price.isAvailable ,price.isMainSoapClient); && price.isMainSoapClient
                            if(price.isBookable == true && price.isAvailable == true ){
                            	check_rezerva++;
                                text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" href="" onClick="'+price.onClickFunction+'">Rezerva</a></td>';
                            } else {
                                text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" href="" onClick=\''+price.askForOfferFunction+'\'">Cere Oferta</a></td>';
                            }

                           	//text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" onClick=\''+price.askForOfferFunction+'\'">Rezerva</a></td>';
                            //text += '</div>';
                            appendedText += text;
                          });
                          appendedText += '</tbody></table>';

                          if(countPricest > 4){
                          	appendedText += '<div class="more_offers"><a href="javascript:void(0)" class="ba-blue-button-xs" id="more_offers" onClick="Showoffers()">Vezi mai multe preturi</a></div>';
                          }

                          if(check_rezerva > 0){
                          	appendedText += '<p class="red_box_text">Alege plata online</p>';
                          	appendedText += '<p class="red_tex">Pentru o rezervare ferma, achita un avans de 30% din contravaloarea vacantei tale.</p>';
                          }

                          appendedText += '<div class="table-wrapper legend">'+
		                        		  '<h6 class="text-dark-grey">Legenda</h6>'+
		                        		  '<div class="table-row">'+
										  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-oferta-speciala.png" /><span>Oferta Speciala</span></div>'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-early-booking.png" /><span>Early Booking</span></div>'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png" /><span>La cerere</span></div>'+
		                        		  '</div>'+
		                        		  '<div class="table-row">'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-free-child.png" /><span>Free child</span></div>'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-disponibil.png" /><span>Disponibil</span></div>'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-indisponibil.png" /><span>Indisponibil</span></div>'+
		                        		  '</div></div></div>';
                          appendedText += '</table>';
                          $("#pricesOutput").append(appendedText);
                  });

                  if(!availablePackages){
                  	// $('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>');
                  	$('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Alege data la care doresti sa calatoresti.</p>');
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
                  	var countPricest = 0;
                  	var check_rezerva = 0;
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
                          	countPricest++;
                            var text = '<tr>'+
      	                                '<td class="ba-item-view-prices-table-td-room-type">'+price.roomCategory+'</td>'+
      	                                '<td class="ba-item-view-prices-table-td-meal-plan">'+price.mealPlan+'</td>'+
      	                                '<td class="ba-item-view-prices-table-td-options">';

                            if(price.earlyBooking){
                              text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-early-booking.png">';
                            }
		                  			if(price.specialOffer){
		                  				text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-oferta-speciala.png">';
		                  			}
                            if(price.isBookable == true && price.isAvailable == true ){
                                text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-disponibil.png">';
                            } else {
                                text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png">';
                            }
                            text += '</td>'+
                                    '<td class="ba-item-view-prices-table-td-price">€'+price.price+'</td>';
                                    //&& price.isMainSoapClient
                            if(price.isBookable  && price.isAvailable){
                            	check_rezerva++;
                                text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" href="" onClick="'+price.onClickFunction+'">Rezerva</a></td>';
                            } else {
                                text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" onClick=\''+price.askForOfferFunction+'\'">Cere Oferta</a></td>';
                            }
                            text += '</div>';
                            appendedText += text;
                          });
                          appendedText += '</tbody></table>';
                          if(countPricest > 4){
                          	appendedText += '<div class="more_offers"><a href="javascript:void(0)" class="ba-blue-button-xs" id="more_offers" onClick="Showoffers()">Vezi mai multe preturi</a></div>';
                          }

                          if(check_rezerva > 0){
                          	appendedText += '<p class="red_box_text">Alege plata online</p>';
                          	appendedText += '<p class="red_text">Pentru o rezervare ferma, achita un avans de 30% din contravaloarea vacantei tale.</p>';
                          }

                          appendedText += '<div class="table-wrapper legend">'+
		                        		  '<h6 class="text-dark-grey">Legenda</h6>'+
		                        		  '<div class="table-row">'+
										  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-oferta-speciala.png" /><span>Oferta Speciala</span></div>'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-early-booking.png" /><span>Early Booking</span></div>'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png" /><span>La cerere</span></div>'+
		                        		  '</div>'+
		                        		  '<div class="table-row">'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-free-child.png" /><span>Free child</span></div>'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-disponibil.png" /><span>Disponibil</span></div>'+
		                        		  '<div class="table-cell box-33"><img src="/sximo/themes/helloholiday/images/hotel-icons-indisponibil.png" /><span>Indisponibil</span></div>'+
		                        		  '</div></div></div>';
                          $("#pricesOutput").append(appendedText);
                  });
                  if(!availablePackages){
                  	//$('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>');
                  	$('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Alege data la care doresti sa calatoresti.</p>');
                  }
              });
            }
        });

    });

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
            window.location.replace("/cere_oferta/ref"+response.id);
        });
        event.stopPropagation();
    }

    function bookPackage(packageId,categoryId,mealPlanLabel,price,event){
        event.preventDefault();
        $ = jQuery;
        var urlSearchAjaxSinglePackageSearchBeforeBooking = "/ajax_search/singlePackageSearchBeforeBooking";
        $("#pricesOutput").html('');
        $('#pricesOutput').html('<div class="ba-item-view-prices-loading"><center><img src="/sximo/themes/helloholiday/images/loader.gif" /><br/>Se verifica disponibilitatea...</center></div>')
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
                  $("#pricesOutput").append('<span class="ba-item-view-prices-package-title"> '+response.packages[packageKey].name+'</span>');
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
                    if(price.isBookable && price.isAvailable){
                        text += '<i style="color:green;" class="fa fa-check"></i>';
                    } else {
                        text += '<i class="fa fa-envelope-o"></i>';
                    }
                    text += '</td>'+
                            '<td class="ba-item-view-prices-table-td-price">€'+price.price+'</td>';
                            //&& price.isMainSoapClient
                    if(price.isBookable && price.isAvailable ){
                        text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" href="" onClick="'+price.onClickFunction+'">Rezerva</a></td>';
                    } else {
                        text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" onClick=\''+price.askForOfferFunction+'\'">Cere Oferta</a></td>';
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
                  //$('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>');
                  $('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Alege data la care doresti sa calatoresti.</p>');
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
        $('#pricesOutput').html('<div class="ba-item-view-prices-loading"><center><img src="/sximo/themes/helloholiday/images/loader.gif" /><br/>Se verifica disponibilitatea...</center></div>')
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
                            text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-disponibil.png">';
                        } else {
                            text += '<img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png">';
                        }
                        text += '</td>'+
                                '<td class="ba-item-view-prices-table-td-price">€'+price.price+'</td>';
                                //&& price.isMainSoapClient
                        if(price.isBookable == true && price.isAvailable == true){
                            text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" href="" onClick="'+price.onClickFunction+'">Rezerva</a></td>';
                        } else {
                            text += '<td class="ba-item-view-prices-table-td-button"><a class="ba-blue-button-xs" onClick=\''+price.askForOfferFunction+'\'">Cere Oferta</a></td>';
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
                //$('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>');
                $('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Alege data la care doresti sa calatoresti.</p>');
              }
            }
        });
       event.stopPropagation();
    }

    function openDiscountModal(discounts){
    	var inst = $('[data-remodal-id=discountModal]').remodal();
    	var discountModal = $("#discountModalDiv");
    	discountModal.html("");
    	$.each(discounts,function(i,discount){
    		if(i != 0) discountModal.append("<br/><br/>")
    		discountModal.append("<p>Discount: "+discount.Label+"</p>");
    		if(discount.Percent != "0.00"){
    			discountModal.append("<p>Valoare: "+discount.Percent+"%</p>");
    		}
    		if(discount.Text){
    			discountModal.append("<p>Descriere: "+discount.Text+"</p>");
    		}
    	});
    	inst.open();
    }

  </script>

<div id="main" class="hotel-pages has-js remodal-bg">

  <div class="remodal" data-remodal-id="discountModal">
	  <button data-remodal-action="close" class="remodal-close"></button>
	  <h1><font color="red">Discounturi</font></h1>
	  <div style="text-align:left !important" id="discountModalDiv">
	  </div>
  </div>
  <div class="inner">
   <div id="hotel" class="content">
		<div class="hotel-aside">
    	<a href="#gallery-1" class="desktop hotel-aside-image cf"><img class="rsTmb" src="{{$hotel->getBasePhotoLink()}}" alt="" /></a>

    	<div class="hotel-aside-inner">
    		<div class="view_offers">
	      	<h2 style="padding-bottom:0px;">Selectati optiunea de transport</h2>
	        <input id="selectedSearchType" type="hidden" value="{{{$transportCode}}}" />
                <span class="transportation">
			        @if(count($departurePointsPlane) >= 1 && count($departureDatesPlane) >= 1 && count($durationsPlane) >= 1)
                    <em class="element"><label class="label_radio plane"><input type="radio" name="transportType" value="{{{$IsTour == 0 ? App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT : App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT ? "checked": ""}}}><span>
                    	@if($IsTour == 0)
                    		Sejur Avion
                    	@else
                    		Circuit Avion
                    	@endif
                    </span></label></em>
                    @endif
			        @if(count($departurePointsBus) >= 1 && count($departureDatesBus) >= 1 && count($durationsBus) >= 1)
                    <em class="element"><label class="label_radio bus"><input type="radio" name="transportType" value="{{{$IsTour == 0 ? App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS : App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS ? "checked": ""}}}><span>
                    	@if($IsTour == 0)
                    		Sejur Autocar
                    	@else
                    		Circuit Autocar
                    	@endif
                    </span></label></em>
                    @endif
                    @if($IsTour == 0)
					        @if(count($departureDatesIndividual) >= 1 && count($durationsIndividual) >= 1)
		                    <em class="element"><img src="/sximo/themes/helloholiday/images/transport/listing-car.png" alt="bus" />Transport Individual
		                    	<div>

		                        <em class="selection  text-16"><label class="label_radio car"><input type="radio" name="transportType" value="{{{$IsTour == 0 ? App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL : App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? "checked": ""}}}><span>Sejur</span></label></em><em class="element-spacer">/</em>
		                        <em class="selection text-16"><label class="label_radio car"><input type="radio" class="label_radio" name="transportType" value="{{{App\Http\Controllers\Travel\OffersController::T_HOTEL}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? "checked": ""}}}><span>Hotel</span></label></em>
		                        </div>
		                    </em>
		                    @else
		                    	<em class="element"><label class="label_radio car"><input type="radio" name="transportType" value="{{{App\Http\Controllers\Travel\OffersController::T_HOTEL}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? "checked": ""}}}><span>Hotel</span></label></em>
	                    	@endif
                    @else
			        	@if(count($departurePointsIndividual) >= 1 && count($departureDatesIndividual) >= 1 && count($durationsIndividual) >= 1)
                    		<em class="element"><label class="label_radio bus"><input type="radio" name="transportType" value="{{{$IsTour == 0 ? App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL : App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL}}}" {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? "checked": ""}}}><span>Circuit Transport Individual</span></label></em>
                    	@endif
                    @endif
                </span>

                <div class="hotel-aside-calendar cf">

                	@if(count($departurePointsBus) >= 1 && count($departureDatesBus) >= 1 && count($durationsBus) >= 1)
			        <div id="searchBus" class="ba-item-view-search-type-div {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS ? "": "ba-hidden"}}}" data-hidden="{{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS ? "false": "true"}}}">
		         		<div class="row">
                            <div class="box-100">
                            	<label>Plecare din</label>
                                <select id="searchBusDeparturePoint" class="full-width">
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="box-50">
                            	<label>Data plecare</label>
                                <select id="searchBusDepartureDate" class="full-width">
	                                @foreach($departureDatesBus as $departureDateBus)
				                      @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS))
				                      <option value="{{{$departureDateBus->departure_date}}}">{{{date('d-m-Y', strtotime($departureDateBus->departure_date))}}}</option>
				                      @else
				                          @if($departureDateBus->departure_date == $departure_date)
				                              <option value="{{{$departureDateBus->departure_date}}}" selected>{{{date('d-m-Y', strtotime($departureDateBus->departure_date))}}}</option>
				                          @else
				                              <option value="{{{$departureDateBus->departure_date}}}">{{{date('d-m-Y', strtotime($departureDateBus->departure_date))}}}</option>
				                          @endif
				                      @endif
				                  	@endforeach
                                </select>
							</div>
                            <div class="box-50">
                            <label>Durata</label>
								<select id="searchBusDuration" class="full-width">
									@foreach($durationsBus as $durationBus)
					                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_BUS && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_BUS))
					                        <option value="{{{$durationBus->duration}}}">{{{$durationBus->duration}}} nopti</option>
					                    @else
					                        @if($durationBus->duration == $duration)
					                            <option value="{{{$durationBus->duration}}}" selected>{{{$durationBus->duration}}} nopti</option>
					                        @else
					                            <option value="{{{$durationBus->duration}}}">{{{$durationBus->duration}}} nopti</option>
					                        @endif
					                    @endif
					                @endforeach
                                </select>
                            </div>
						</div>
			        </div>
			        @endif
			        @if(count($departurePointsPlane) >= 1 && count($departureDatesPlane) >= 1 && count($durationsPlane) >= 1)
			        <div id="searchFlight" class="ba-item-view-search-type-div {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT ? "": "ba-hidden"}}}" data-hidden="{{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT ? "false": "true"}}}">
			          <div class="row">
                            <div class="box-100">
                            	<label>Plecare din</label>
                                <select id="searchFlightDeparturePoint" class="full-width">
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="box-50">
                            	<label>Data plecare</label>
                                <select id="searchFlightDepartureDate" class="full-width">
	                                @foreach($departureDatesPlane as $departureDateBus)
				                      @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT))
				                      <option value="{{{$departureDateBus->departure_date}}}">{{{date('d-m-Y', strtotime($departureDateBus->departure_date))}}}</option>
				                      @else
				                          @if($departureDateBus->departure_date == $departure_date)
				                              <option value="{{{$departureDateBus->departure_date}}}" selected>{{{date('d-m-Y', strtotime($departureDateBus->departure_date))}}}</option>
				                          @else
				                              <option value="{{{$departureDateBus->departure_date}}}">{{{date('d-m-Y', strtotime($departureDateBus->departure_date))}}}</option>
				                          @endif
				                      @endif
				                  	@endforeach
                                </select>
							</div>
                            <div class="box-50">
                            <label>Durata</label>
								<select id="searchFlightDuration" class="full-width">
									@foreach($durationsPlane as $durationBus)
					                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT))
					                        <option value="{{{$durationBus->duration}}}">{{{$durationBus->duration}}} nopti</option>
					                    @else
					                        @if($durationBus->duration == $duration)
					                            <option value="{{{$durationBus->duration}}}" selected>{{{$durationBus->duration}}} nopti</option>
					                        @else
					                            <option value="{{{$durationBus->duration}}}">{{{$durationBus->duration}}} nopti</option>
					                        @endif
					                    @endif
					                @endforeach
                                </select>
                            </div>
						</div>
			        </div>
			        @endif
			        @if(count($departureDatesIndividual) >= 1 && count($durationsIndividual) >= 1)
			        <div id="searchIndividual" class="ba-item-view-search-type-div {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? "": "ba-hidden"}}}" data-hidden="{{{$transportCode == App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL || $transportCode == App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL ? "false": "true"}}}">
                        <div class="row">
                            <div class="box-50">
                            	<label>Data plecare</label>
                                <select id="searchIndividualDepartureDate" class="full-width">
	                                @foreach($departureDatesIndividual as $departureDateIndividual)
				                      @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_INDIVIDUAL && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_INDIVIDUAL))
				                      <option value="{{{$departureDateIndividual->departure_date}}}">{{{date('d-m-Y', strtotime($departureDateIndividual->departure_date))}}}</option>
				                      @else
				                          @if($departureDateIndividual->departure_date == $departure_date)
				                              <option value="{{{$departureDateIndividual->departure_date}}}" selected>{{{date('d-m-Y', strtotime($departureDateIndividual->departure_date))}}}</option>
				                          @else
				                              <option value="{{{$departureDateIndividual->departure_date}}}">{{{date('d-m-Y', strtotime($departureDateIndividual->departure_date))}}}</option>
				                          @endif
				                      @endif
				                  	@endforeach
                                </select>
							</div>
                            <div class="box-50">
                            <label>Durata</label>
								<select id="searchIndividualDuration" class="full-width">
									@foreach($durationsIndividual as $durationIndividual)
					                    @if($searchId == 0 || ($transportCode != App\Http\Controllers\Travel\OffersController::T_PACKAGES_FLIGHT && $transportCode != App\Http\Controllers\Travel\OffersController::T_CIRCUITS_FLIGHT))
					                        <option value="{{{$durationIndividual->duration}}}">{{{$durationIndividual->duration}}} nopti</option>
					                    @else
					                        @if($durationIndividual->duration == $duration)
					                            <option value="{{{$durationIndividual->duration}}}" selected>{{{$durationIndividual->duration}}} nopti</option>
					                        @else
					                            <option value="{{{$durationIndividual->duration}}}">{{{$durationIndividual->duration}}} nopti</option>
					                        @endif
					                    @endif
					                @endforeach
                                </select>
                            </div>
						</div>
			        </div>
			        @endif

                	<div id="searchHotel" class="ba-item-view-search-type-div {{{$transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? "": "ba-hidden"}}}" data-hidden="{{{$transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? "false": "true"}}}">
			          	<div class="row">
	                        <div class="box-100 selector">
	                        	<label style="font-size: 100%;color:#333;">Checkin</label>
	                            <div class="ba-search-selector-div">
					            	<input type="text" id="searchHotelDepartureDate" value="{{{$searchId != 0 && $transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? $check_in : ""}}}" class="datepicker hasDatePicker" readonly/>
					            </div>
	                        </div>
                      	</div>
                      	<div class="row" style="padding-top: 0px;">
	                        <div class="box-100 selector">
	                        	<label style="font-size: 100%;color:#333;">Checkout</label>
					            <div class="ba-search-selector-div">
					            	<input type="text" id="searchHotelReturnDate" value="{{{$searchId != 0 && $transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL ? $check_out : ""}}}" class="datepicker hasDatePicker" readonly/>
					            </div>
	                        </div>
                      	</div>
			        </div>
			        <div class="row" style="padding: 0;">
                    	<div class="ba-search-guests">
				          <label>Camere</label>
				          <div id="guests"></div>
				        </div>
                    </div>
                    <div class="sep-10"></div>
                    <div class="row text-center">
                    	<a href="" id="searchButton" class="uppercase">Confirma datele</a>
                    </div>

                </div><!-- .hotel-aside-calendar -->
       	 	</div>
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
                <li class="active"><a id="offersTab" href="#tab1">Oferta</a></li>
                <li><a href="#tab2">Informatii</a></li>
                <li><a href="#tab3">Localizare</a></li>
                </ul>
            <div class="tab-content">
                <div id="tab1" class="tab active">
                	<div id="pricesOutput">
                	@if (count($prices) > 0 && $transportCode != App\Http\Controllers\Travel\OffersController::T_HOTEL)
                		 <?php $count_prices = 0; ?>
			                @foreach($prices as $package => $packagePrices)
			                  <span class="ba-item-view-prices-package-title">
			                    <?php $packageTmp = App\Models\Travel\PackageInfo::where('id','=',$package)->where('soap_client','=',$soapClientId)->first(); ?>
			                    {{{$packageTmp->name}}}
			                  </span>
			                  <table class="ba-item-view-prices-table">
			                    <thead>
			                      <tr>
			                        <th class="ba-item-view-prices-table-th-room-type">Tip camera</th>
			                        <th class="ba-item-view-prices-table-th-meal-plan">Tip masa</th>
			                        <th class="ba-item-view-prices-table-th-options"></th>
			                        <th class="ba-item-view-prices-table-th-price">Pret Catalog</th>
			                        <th class="ba-item-view-prices-table-th-price">Pret</th>
			                        <th class="ba-item-view-prices-table-th-button"></th>
			                      </tr>
			                    </thead>
			                    <tbody>

			                      @foreach($packagePrices as  $roomCategory => $roomCategoryPrices)
			                          @foreach($roomCategoryPrices as  $mealPlan => $mealPlanPrice)
			                          <?php $count_prices++; ?>
			                            <tr>
			                              <td class="ba-item-view-prices-table-td-room-type">{{{$roomCategory}}}</td>
			                              <td class="ba-item-view-prices-table-td-meal-plan">{{{$mealPlan}}}</td>
			                              <td class="ba-item-view-prices-table-td-options">
			                                @if($mealPlanPrice['earlyBooking'])
			                                  <img src="/sximo/themes/helloholiday/images/hotel-icons-early-booking.png">
			                                @endif
			                                @if($mealPlanPrice['specialOffer'])
			                                  <img src="/sximo/themes/helloholiday/images/hotel-icons-oferta-speciala.png">
			                                @endif
			                                @if($mealPlanPrice['isBookable'] && $mealPlanPrice['isAvailable'])
			                                  <img src="/sximo/themes/helloholiday/images/hotel-icons-disponibil.png">
			                                @else
			                                  <img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png">
			                                @endif
			                              </td>
			                              <td class="ba-item-view-prices-table-td-price">

			                              	@if($mealPlanPrice['priceWithoutDiscount'] != $mealPlanPrice['price'])
				                              	<strike>€{{{($mealPlanPrice['priceWithoutDiscount'])}}}</strike>
				                            @else
			                              	  	€{{{($mealPlanPrice['priceWithoutDiscount'])}}}
				                            @endif
			                              </td>
			                              <td class="ba-item-view-prices-table-td-price">
			                              	@if(count($mealPlanPrice['discounts']) != 0)
			                              		<span class="discountLink" onClick="openDiscountModal({{{json_encode($mealPlanPrice['discounts'])}}})">€{{{($mealPlanPrice['price'])}}}</span>
			                              	@else
			                              		€{{{($mealPlanPrice['price'])}}}
			                              	@endif
			                              </td>
			                              <td class="ba-item-view-prices-table-td-button">
			                              @if($mealPlanPrice['isBookable'] && $mealPlanPrice['isAvailable'])
			                                <a class="ba-blue-button-xs" href="" onClick="{{{$mealPlanPrice['function']}}}" title="">Rezerva</a>
			                              @else
			                                <a class="ba-blue-button-xs" href="" onClick="askForOfferPackage({{{$package}}},'{{{$soapClientId}}}','{{{$roomCategory}}}','{{{$mealPlan}}}',{{{$rooms}}},{{{($mealPlanPrice['price'])}}},'{{{($departureDate)}}}',{{{($duration)}}},event)" title="">Cere Oferta</a>
										                @endif
			                              </td>
			                              </tr>
			                          @endforeach
			                      @endforeach
			                    </tbody>
			                    <!-- <tfoot>
			                      <tr>
			                        <td class="ba-item-view-prices-table-td-legend" colspan="5">
			                          <span><i style="color:green;" class="fa fa-check"></i> - Disponibila</span>
			                          <span><i class="fa fa-envelope-o"></i> - La cerere</span>
			                          <span><img src="/images/icons/early_booking.gif" /> - Early Booking</span>
			                          <span><img src="/images/icons/special_offer.gif" /> - Special Offer</span>
			                          <span><img src="/images/icons/last_minute.gif" /> - Last Minute</span>
			                        </td>
			                      </tr>
			                    </tfoot> -->
			                  </table>
			                @endforeach
			                @if($count_prices > 4)
			                <div class="more_offers"><a href="javascript:void(0)" class="ba-blue-button-xs" id="more_offers" onClick="Showoffers()">Vezi mai multe preturi</a></div>
			                @endif
			                @if($mealPlanPrice['isBookable'] == true && $mealPlanPrice['isAvailable'] == true )
			                	<p class="red_box_text">Alege plata online</p>
			                  <p class="red_text">Pentru o rezervare ferma, achita un avans de 30% din contravaloarea vacantei tale.</p>
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
			              @endif
			              @if (count($prices) > 0 && $transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL)
			              	<?php $count_prices = 0; ?>
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
			                          <?php $count_prices++; ?>
			                            <tr>
			                              <td class="ba-item-view-prices-table-td-room-type">{{{$roomCategory}}}</td>
			                              <td class="ba-item-view-prices-table-td-meal-plan">{{{$mealPlan}}}</td>
			                              <td class="ba-item-view-prices-table-td-options">
			                                @if($mealPlanPrice['isBookable'] == true && $mealPlanPrice['isAvailable'] == true)
			                                  <img src="/sximo/themes/helloholiday/images/hotel-icons-disponibil.png" />
			                                @else
			                                  <img src="/sximo/themes/helloholiday/images/hotel-icons-la-cerere.png" />
			                                @endif
			                              </td>
			                              <td class="ba-item-view-prices-table-td-price">€ {{{($mealPlanPrice['price'])}}}</td>
			                              <td class="ba-item-view-prices-table-td-button">
                                      @if($mealPlanPrice['isBookable'] && $mealPlanPrice['isAvailable'])
  			                                <a class="ba-blue-button-xs" href="" onClick="{{{$mealPlanPrice['function']}}}" title="">Rezerva</a>
  			                              @else
  			                                <a class="ba-blue-button-xs" href="" onClick="askForOfferHotel({{{$package}}},'{{{$soapClientId}}}','{{{$roomCategory}}}','{{{$mealPlan}}}',{{{$rooms}}},{{{($mealPlanPrice['price'])}}},'{{{$check_in}}}','{{{$check_out}}}',event)" title="">Cere Oferta</a>
  										                @endif
                                    </td>
			                              </tr>
			                          @endforeach
			                      @endforeach
			                    </tbody>
			                  </table>
			                @endforeach
			                @if(count($prices) > 4)
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
			              @endif
			              @if(count($prices) == 0)
			              <!-- <p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p> -->
			              <p style="color:red;font-weight: bold;margin-top:20px;">Alege data la care doresti sa calatoresti.</p>
			              @endif
                	</div>
                    @foreach($packages as $package)
				        @if((count($package->detailedDescriptions) + (isset($package->included_services) ? 1 : 0) + (isset($package->not_included_services) ? 1 : 0)) != 0)
				          <div id="packageDescription-{{$package->id}}" class="packageDescription {{($transportCode == App\Http\Controllers\Travel\OffersController::T_HOTEL) || !in_array($package->id,$packagesFound) ? 'ba-hidden' : ''}}">
				            <h2>{{{$package->name}}}</h2>
					            <p class="description_dark_blue"><strong>Transport: </strong>
				              @if ($package->is_flight == 1)
						            Avion
						          @elseif ($package->is_bus == 1)
						            Autocar
						          @else
						            Individual
						          @endif
						          </p>

					            <p class="description_dark_blue"><strong>Durata: </strong>{{{$package->duration}}} nopti</p>


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

			              <div id="priceList" class="">
				          	<?php $pricesCachedArray = App\Models\Travel\PackageInfo::getCachedPricesTable($package->id,$package->soap_client); ?>
				            @if(count($pricesCachedArray) != 0)
					            <div class="sep-10"></div>
			                    <p class="description_dark_blue"><strong>Tarife informative pe persoana</strong></p>
					            <table class="ba-item-view-prices-table">
					                <thead>
					                  <th>Data Plecarii</th>
					                  <?php $tmpArrayOptions = array(); ?>
					                  @foreach($pricesCachedArray as $k => $v)
					                    <th><center>{{$k}}</center></th>
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
					                            <td style="vertical-align: middle;"><center>&euro;{{isset($pricesCachedArray[$option][$date]) ? $pricesCachedArray[$option][$date] : "N/A"}}</center></td>
					                          @endforeach
					                        </tr>
					                    </tr>
					                    @endforeach

					                </tbody>
					            </table>
					          @endif
				          </div>
				        @endif
				        </div>
				      @endforeach
								</div>
					@if(Session::has('extracomponents'))	
					@if(count(Session::get('extracomponents')))
			        <div id="priceList" class="">
					<div class="sep-10"></div>
			                    <p class="description_dark_blue"><strong>Servicii Suplimentare</strong></p>
					<table class="ba-item-view-prices-table">
					<thead>
					                  <th>Serviciu</th>
					                  <th>Descriere</th>
					                  <th>Pret</th>
					                  
					                </thead>
					@foreach(Session::get('extracomponents') as $v)
							
								<tbody>
											                        <tr>
								
					                          <td>
												{{$v->Label}}
					                          </td>
					                          <td>
												{{$v->Description}}
					                          </td>
												<td>
												&euro;{{$v->Price}}
					                          </td>
					                        </tr>

													
					                
					                </tbody>
					@endforeach
					</table>
					</div>
					@endif
					@endif
                <div id="tab2" class="tab">
                    @if(isset($hotel->description))
			          	<h2>Descriere Hotel</h2>
			          	<p>{{$hotel->description}}</p>
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
		          <a class="rsImg" data-rsbigimg="{{{$imgUrl}}}" href="{{{$imgUrl}}}"><img class="rsTmb" src="{{{$imgUrl}}}"  alt="slider_img" /></a>
		          @endforeach
		        @endif
        </div>
        </article>
		</div>

      <div class="clear"></div>
    </div> <!-- END id="hotel" class="content" -->
  </div><!--  end .inner -->
</div><!-- end #main -->
