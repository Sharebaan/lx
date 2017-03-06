(function($) {


	$(document).ready(function() {
      	var url = "http://helloholidays.ro";
	   	var transportTypeCircuitField = $("#searchTransportTypeCircuit"),
	   		departurePointCircuitField = $("#searchDeparturePointCircuit"),
	   	   	countryDestinationCircuitField = $("#searchCountryDestinationCircuit"),
	   	   	cityDestinationCircuitField = $("#searchCityDestinationCircuit"),
	   	   	departureDateCircuitField = $("#searchDepartureDateCircuit"),
	   	   	durationCircuitField = $("#searchDurationCircuit"),

	   	   	transportTypeStayField = $("#searchTransportTypeStay"),
	   		departurePointStayField = $("#searchDeparturePointStay"),
	   	   	countryDestinationStayField = $("#searchCountryDestinationStay"),
	   	   	cityDestinationStayField = $("#searchCityDestinationStay"),
	   	   	departureDateStayField = $("#searchDepartureDateStay"),
	   	   	durationStayField = $("#searchDurationStay"),

	   	   	countryDestinationHotelField = $("#searchCountryDestinationHotel"),
	   	   	cityDestinationHotelField = $("#searchCityDestinationHotel"),
	   	   	departureDateHotelField = $("#searchDepartureDateHotel"),
	   	   	arrivalDateHotelField = $("#searchArrivalDateHotel");

	   	var urlSearchAjaxGetTransportTypes = url+"/ajax_search/getTransportTypes",
	   		urlSearchAjaxGetDeparturePoints = url+"/ajax_search/getDeparturePoints",
	   		urlSearchAjaxGetCountryDestination = url+"/ajax_search/getCountryDestination",
	   	   	urlSearchAjaxGetCityDestination = url+"/ajax_search/getCityDestination",
	   	   	urlSearchAjaxGetDepartureDates = url+"/ajax_search/getDepartureDates",
	   	   	urlSearchAjaxGetDurations = url+"/ajax_search/getDurations",
	   	   	urlSearchAjaxPackageSearch = url+"/ajax_search/packageSearch",
	   	   	urlSearchAjaxHotelSearch = url+"/ajax_search/hotelSearch";

	   	var circuitsSearchButton = $("#circuitsSearchButton");
	   	var staysSearchButton = $("#staysSearchButton");
	   	var hotelsSearchButton = $("#hotelsSearchButton");

		$('#circuitsTabActivator').click(function(){
			transportTypeCircuitField.html('<option value="0">* Tip transport</option>');
	   		transportTypeCircuitField.val(0).trigger('change');
	   		$.getJSON(urlSearchAjaxGetTransportTypes+"?callback=?",{ holidayType: 2},function(data){
	   			var transportTypes = data;
	   			for(var i = 0 ; i < transportTypes.length;i++){
	   				transportTypeCircuitField.append($("<option></option>").attr("value",transportTypes[i].id).text(transportTypes[i].name));
	   			}
	   		});
		});
		$('#staysTabActivator').click(function(){
			transportTypeStayField.html('<option value="0">* Tip transport</option>');
	   		transportTypeStayField.val(0).trigger('change');
	   		$.getJSON(urlSearchAjaxGetTransportTypes+"?callback=?",{ holidayType: 1},function(data){
	   			var transportTypes = data;
	   			for(var i = 0 ; i < transportTypes.length;i++){
	   				transportTypeStayField.append($("<option></option>").attr("value",transportTypes[i].id).text(transportTypes[i].name));
	   			}
	   		});
		});
		$('#hotelsTabActivator').click(function(){
			countryDestinationHotelField.html('<option value="0">* Destinatie</option>');
	   		countryDestinationHotelField.val(0).trigger('change');
	   		$.getJSON(urlSearchAjaxGetCountryDestination+"?callback=?",{ holidayType: 3 },function(data){
	   			var countries = data;
	   			for(var i = 0 ; i < countries.length;i++){
	   				countryDestinationHotelField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
	   			}
	   		});
		});


	   	transportTypeCircuitField.change(function(){
		   		departurePointCircuitField.html('<option value="0">* Plecare din</option>');
	   		departurePointCircuitField.val(0).trigger('change');
	   		if(transportTypeCircuitField.val() != 0){
				elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});

					if(transportTypeCircuitField.val() != 3){
						if(departurePointCircuitField.is(":visible") == false){departurePointField.show();}

				   		$.getJSON(urlSearchAjaxGetDeparturePoints+"?callback=?",{ holidayType: 2, transportType: transportTypeCircuitField.val() },function(data){
				   			var countries = data;
				   			for(var i = 0 ; i < countries.length;i++){
				   				departurePointCircuitField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
				   			}
				   		}).done(function(){
								elementSpin.waitMe('hide');
						});
		   		}else{

				   		$.getJSON(urlSearchAjaxGetDeparturePoints+"?callback=?",{ holidayType: 2, transportType: transportTypeCircuitField.val() },function(data){
				   			var countries = data;
				   			for(var i = 0 ; i < countries.length;i++){
				   				countryDestinationCircuitField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
				   			}
				   		}).done(function(){
										departurePointCircuitField.parent.hide();
										elementSpin.waitMe('hide');
								});
					}
				}
	   	});

	   	transportTypeStayField.change(function(){
			   		departurePointStayField.html('<option value="0">* Plecare din</option>');
	   		departurePointStayField.val(0).trigger('change');
				   		if(transportTypeStayField.val() != 0){
					elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
					if(transportTypeStayField.val() != 3){
						if(departurePointStayField.is(":visible") == false){departurePointField.show();}
			   		$.getJSON(urlSearchAjaxGetDeparturePoints+"?callback=?",{ holidayType: 1, transportType: transportTypeStayField.val() },function(data){
			   			var countries = data;
			   			for(var i = 0 ; i < countries.length;i++){
			   				departurePointStayField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
			   			}
			   		}).done(function(){
								elementSpin.waitMe('hide');
						});
					}else{
						$.getJSON(urlSearchAjaxGetDeparturePoints+"?callback=?",{ holidayType: 1, transportType: transportTypeStayField.val() },function(data){
			   			var countries = data;
			   			for(var i = 0 ; i < countries.length;i++){
			   				countryDestinationStayField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
			   			}
			   		}).done(function(){
									departurePointStayField.parent().hide();
									elementSpin.waitMe('hide');
							});
					}
	   		}
	   	});

	   	departurePointCircuitField.change(function(){
				
				
/*
 * * */
	   		countryDestinationCircuitField.html('<option value="0">* Destinatie</option>');
	   		countryDestinationCircuitField.val(0).trigger('change');
	   		if(departurePointCircuitField.val() != 0){
				elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});

		   		$.getJSON(urlSearchAjaxGetCountryDestination+"?callback=?",{ holidayType: 2, transportType: transportTypeCircuitField.val(), departurePoint: departurePointCircuitField.val() },function(data){
		   			var countries = data;
		   			for(var i = 0 ; i < countries.length;i++){
		   				countryDestinationCircuitField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});;
	   		}
	   	});

	   	departurePointStayField.change(function(){
	   		countryDestinationStayField.html('<option value="0">* Destinatie</option>');
	   		countryDestinationStayField.val(0).trigger('change');
	   		if(departurePointStayField.val() != 0){
				elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
		   		$.getJSON(urlSearchAjaxGetCountryDestination+"?callback=?",{ holidayType: 1, transportType: transportTypeStayField.val(), departurePoint: departurePointStayField.val() },function(data){
		   			var countries = data;
		   			for(var i = 0 ; i < countries.length;i++){
		   				countryDestinationStayField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});
	   		}
	   	});

	   	countryDestinationCircuitField.change(function(){
	   		cityDestinationCircuitField.html('<option value="0">* Oras</option>');
	   		cityDestinationCircuitField.val(0).trigger('change');
	   		if(countryDestinationCircuitField.val() != 0){
				elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
		   		$.getJSON(urlSearchAjaxGetCityDestination+"?callback=?",{ holidayType: 2, transportType: transportTypeCircuitField.val() , country: countryDestinationCircuitField.val(), departurePoint: departurePointCircuitField.val() },function(data){
		   			var cities = data;
		   			for(var i = 0 ; i < cities.length;i++){
		   				cityDestinationCircuitField.append($("<option></option>").attr("value",cities[i].id).text(cities[i].name));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});
	   		}
	   	});

		countryDestinationStayField.change(function(){
	   		cityDestinationStayField.html('<option value="0">* Oras</option>');
	   		cityDestinationStayField.val(0).trigger('change');
	   		if(countryDestinationStayField.val() != 0){
				elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
		   		$.getJSON(urlSearchAjaxGetCityDestination+"?callback=?",{ holidayType: 1, transportType: transportTypeStayField.val() , country: countryDestinationStayField.val(), departurePoint: departurePointStayField.val() },function(data){
		   			var cities = data;
		   			for(var i = 0 ; i < cities.length;i++){
		   				cityDestinationStayField.append($("<option></option>").attr("value",cities[i].id).text(cities[i].name));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});
	   		}
	   	});

	   	countryDestinationHotelField.change(function(){
	   		cityDestinationHotelField.html('<option value="0">* Oras</option>');
	   		cityDestinationHotelField.val(0).trigger('change');
	   		if(countryDestinationHotelField.val() != 0){
					elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
		   		$.getJSON(urlSearchAjaxGetCityDestination+"?callback=?",{ holidayType: 3, transportType: 0 , country: countryDestinationHotelField.val(), departurePoint: 0 },function(data){
		   			var cities = data;
		   			for(var i = 0 ; i < cities.length;i++){
		   				cityDestinationHotelField.append($("<option></option>").attr("value",cities[i].id).text(cities[i].name));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});
	   		}
	   	});

	   	cityDestinationCircuitField.change(function(){
	   		departureDateCircuitField.html('<option value="0">* Data plecare</option>');
	   		departureDateCircuitField.val(0).trigger('change');
	   		if(cityDestinationCircuitField.val() != 0){
					elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
		   		$.getJSON(urlSearchAjaxGetDepartureDates+"?callback=?",{ holidayType: 2, transportType: transportTypeCircuitField.val() , city: cityDestinationCircuitField.val(),  departurePoint: departurePointCircuitField.val() },function(data){
		   			var departureDates = data;
		   			for(var i = 0 ; i < departureDates.length;i++){
		   				departureDateCircuitField.append($("<option></option>").attr("value",departureDates[i]).text($.datepicker.formatDate('dd-mm-yy',new Date(departureDates[i]))));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});
	   		}
	   	});

		cityDestinationStayField.change(function(){
	   		departureDateStayField.html('<option value="0">* Data plecare</option>');
	   		departureDateStayField.val(0).trigger('change');
	   		if(cityDestinationStayField.val() != 0){
					elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
		   		$.getJSON(urlSearchAjaxGetDepartureDates+"?callback=?",{ holidayType: 1, transportType: transportTypeStayField.val() , city: cityDestinationStayField.val(),  departurePoint: departurePointStayField.val() },function(data){
		   			var departureDates = data;
		   			for(var i = 0 ; i < departureDates.length;i++){
		   				departureDateStayField.append($("<option></option>").attr("value",departureDates[i]).text($.datepicker.formatDate('dd-mm-yy',new Date(departureDates[i]))));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});
	   		}
	   	});

	   	departureDateCircuitField.change(function(){
	   		durationCircuitField.html('<option value="0">* Durata</option>');
	   		durationCircuitField.val(0).trigger('change');
	   		if(departureDateCircuitField.val() != 0){
					elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
		   		$.getJSON(urlSearchAjaxGetDurations+"?callback=?",{ holidayType: 2, transportType: transportTypeCircuitField.val() , city: cityDestinationCircuitField.val(), departureDate: departureDateCircuitField.val(),  departurePoint: departurePointCircuitField.val() },function(data){
		   			var durations = data;
		   			for(var i = 0 ; i < durations.duration.length;i++){
							dn='';
							if(durations['day_night'][i] == 0){dn=' nopti';}else{dn=' zi';}
		   				durationCircuitField.append($("<option></option>").attr("value",durations.duration[i]).text(durations.duration[i]+dn));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});
	   		}
	   	});

	   	departureDateStayField.change(function(){
	   		durationStayField.html('<option value="0">* Durata</option>');
	   		durationStayField.val(0).trigger('change');
	   		if(departureDateStayField.val() != 0){
					elementSpin = departurePointCircuitField.parent().parent().parent().parent();
					
					elementSpin.waitMe({
						effect: 'ios',
						text: '',
						bg: 'rgba(255,255,255,0.5)',
						color: '#1C75D1',
						sizeW: '',
						sizeH: '',
						source: '',
						onClose: function(){}
					});
		   		$.getJSON(urlSearchAjaxGetDurations+"?callback=?",{ holidayType: 1, transportType: transportTypeStayField.val() , city: cityDestinationStayField.val(), departureDate: departureDateStayField.val(),  departurePoint: departurePointStayField.val() },function(data){
		   			var durations = data;
						
		   			for(var i = 0 ; i < durations.duration.length;i++){
						dn='';
						if(durations['day_night'][i] == 0){dn=' nopti';}else{dn=' zi';}
		   				durationStayField.append($("<option></option>").attr("value",durations.duration[i]).text(durations.duration[i]+dn));
		   			}
		   		}).done(function(){
								elementSpin.waitMe('hide');
						});
	   		}
	   	});

	   	function validateErrors(holidayTypeVal,transportTypeVal,countryDestinationVal,cityDestinationVal,departureDateVal,durationVal,departureDateHotelVal,arrivalDateHotelVal,departurePointVal){
	   		var validate = true;
	   		switch(holidayTypeVal){
	   			case 1:
		   			if(transportTypeVal == 0){
			   			transportTypeStayField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			transportTypeStayField.attr('style', "");
			   		}
			   		if(departurePointVal == 0){
			   			/*departurePointStayField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;*/
							if(transportTypeVal != 3){
								departurePointStayField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
								validate = false;
							}else{
									departurePointStayField.attr('style',"");
							}
			   		} else {
			   			departurePointStayField.attr('style',"");
			   		}
			   		if(countryDestinationVal == 0){
			   			countryDestinationStayField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			countryDestinationStayField.attr('style', "");
			   		}
			   		if(cityDestinationVal == 0){
			   			cityDestinationStayField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			cityDestinationStayField.attr('style', "");
			   		}
			   		if(departureDateVal == 0){
			   			departureDateStayField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			departureDateStayField.attr('style', "");
			   		}
			   		if(durationVal == 0){
			   			durationStayField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			durationStayField.attr('style', "");
			   		}
	   			break;
	   			case 2:
	   				if(transportTypeVal == 0){
			   			transportTypeCircuitField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			transportTypeCircuitField.attr('style', "");
			   		}
			   		if(departurePointVal == 0){
							if(transportTypeVal != 3){
								departurePointCircuitField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
								validate = false;
							}else{
									departurePointCircuitField.attr('style',"");
							}
			   		} else {
			   			departurePointCircuitField.attr('style',"");
			   		}
			   		if(countryDestinationVal == 0){
			   			countryDestinationCircuitField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			countryDestinationCircuitField.attr('style', "");
			   		}
			   		if(cityDestinationVal == 0){
			   			cityDestinationCircuitField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			cityDestinationCircuitField.attr('style', "");
			   		}
			   		if(departureDateVal == 0){
			   			departureDateCircuitField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			departureDateCircuitField.attr('style', "");
			   		}
			   		if(durationVal == 0){
			   			durationCircuitField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			durationCircuitField.attr('style', "");
			   		}
	   			break;
	   			case 3:
		   			if(countryDestinationVal == 0){
			   			countryDestinationHotelField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			countryDestinationHotelField.attr('style', "");
			   		}
			   		if(cityDestinationVal == 0){
			   			cityDestinationHotelField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			cityDestinationHotelField.attr('style', "");
			   		}
			   		if(departureDateHotelVal == 0){
			   			departureDateHotelField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			departureDateHotelField.attr('style', "");
			   		}
			   		if(arrivalDateHotelVal == 0){
			   			arrivalDateHotelField.attr('style', "border:#FF0000 1px solid;");
			   			validate = false;
			   		} else {
			   			arrivalDateHotelField.attr('style', "");
			   		}
	   			break;
	   			default:
	   		}
	   		return validate;
	   	}

	   	circuitsSearchButton.click(function(){
	   		var holidayTypeVal = 2,
	   			transportTypeVal = transportTypeCircuitField.val(),
	   			departurePointVal = departurePointCircuitField.val(),
	   			countryDestinationVal = countryDestinationCircuitField.val(),
	   			cityDestinationVal = cityDestinationCircuitField.val(),
	   			departureDateVal = departureDateCircuitField.val(),
	   			durationVal = durationCircuitField.val(),
	   			departureDateHotelVal = 0,
	   			arrivalDateHotelVal = 0;
	   			var Rooms = [];
	   			var rooms = $("#guests1").data('value');
		        $.each(rooms.guests, function(i,room){
		          roomTmp = new Object();
		          roomTmp.adults = room.adults;
		          if(room.kids != null){
		            roomTmp.kids = room.kids;
		          }
		          Rooms.push(roomTmp);
		        });
	   		if(validateErrors(holidayTypeVal,transportTypeVal,countryDestinationVal,cityDestinationVal,departureDateVal,durationVal,departureDateHotelVal,arrivalDateHotelVal,departurePointVal)){
	   			var packageSearchObject = new Object();
	   			packageSearchObject.is_tour = 1;
	   			switch(transportTypeVal){
	   				case "1":
	   					packageSearchObject.is_flight = 1;
	   					packageSearchObject.is_bus = 0;
	   				break;
	   				case "2":
	   					packageSearchObject.is_flight = 0;
	   					packageSearchObject.is_bus = 1;
	   				break;
	   				case "3":
	   					packageSearchObject.is_flight = 0;
	   					packageSearchObject.is_bus = 0;
	   				break;
	   				default:
	   			}
	   			packageSearchObject.departure_point = departurePointVal;
	   			packageSearchObject.destination = cityDestinationVal;
	   			packageSearchObject.departure_date = departureDateVal;
	   			packageSearchObject.duration = durationVal;
	   			packageSearchObject.rooms = Rooms;
	   			$.getJSON(urlSearchAjaxPackageSearch+"?callback=?",{ packageSearch: packageSearchObject },function(id){
	   				window.location.replace(url+"/oferte/circuite?searchId="+id);
		   		});
	   		}
	   	});

		staysSearchButton.click(function(){
	   		var holidayTypeVal = 1,
	   			transportTypeVal = transportTypeStayField.val(),
	   			departurePointVal = departurePointStayField.val(),
	   			countryDestinationVal = countryDestinationStayField.val(),
	   			cityDestinationVal = cityDestinationStayField.val(),
	   			departureDateVal = departureDateStayField.val(),
	   			durationVal = durationStayField.val(),
	   			departureDateHotelVal = 0,
	   			arrivalDateHotelVal = 0;
	   			var Rooms = [];
	   			var rooms = $("#guests2").data('value');
		        $.each(rooms.guests, function(i,room){
		          roomTmp = new Object();
		          roomTmp.adults = room.adults;
		          if(room.kids != null){
		            roomTmp.kids = room.kids;
		          }
		          Rooms.push(roomTmp);
		        });
	   		if(validateErrors(holidayTypeVal,transportTypeVal,countryDestinationVal,cityDestinationVal,departureDateVal,durationVal,departureDateHotelVal,arrivalDateHotelVal,departurePointVal)){
	   			var packageSearchObject = new Object();
	   			packageSearchObject.is_tour = 0;
	   			switch(transportTypeVal){
	   				case "1":
	   					packageSearchObject.is_flight = 1;
	   					packageSearchObject.is_bus = 0;
	   				break;
	   				case "2":
	   					packageSearchObject.is_flight = 0;
	   					packageSearchObject.is_bus = 1;
	   				break;
	   				case "3":
	   					packageSearchObject.is_flight = 0;
	   					packageSearchObject.is_bus = 0;
	   				break;
	   				default:
	   			}
	   			packageSearchObject.departure_point = departurePointVal;
	   			packageSearchObject.destination = cityDestinationVal;
	   			packageSearchObject.departure_date = departureDateVal;
	   			packageSearchObject.duration = durationVal;
	   			packageSearchObject.rooms = Rooms;
	   			$.getJSON(urlSearchAjaxPackageSearch+"?callback=?",{ packageSearch: packageSearchObject },function(id){
	   				window.location.replace(url+"/oferte/sejururi?searchId="+id);
		   		});
	   		}
	   	});

		hotelsSearchButton.click(function(){
	   		var holidayTypeVal = 3,
	   			transportTypeVal = 0,
	   			departurePointVal = 0,
	   			countryDestinationVal = countryDestinationHotelField.val(),
	   			cityDestinationVal = cityDestinationHotelField.val(),
	   			departureDateVal = 0,
	   			durationVal = 0,
	   			departureDateHotelVal = departureDateHotelField.val(),
	   			arrivalDateHotelVal = arrivalDateHotelField.val();
	   			var Rooms = [];
	   			var rooms = $("#guests3").data('value');
		        $.each(rooms.guests, function(i,room){
		          roomTmp = new Object();
		          roomTmp.adults = room.adults;
		          if(room.kids != null){
		            roomTmp.kids = room.kids;
		          }
		          Rooms.push(roomTmp);
		        });
	   		if(validateErrors(holidayTypeVal,transportTypeVal,countryDestinationVal,cityDestinationVal,departureDateVal,durationVal,departureDateHotelVal,arrivalDateHotelVal,departurePointVal)){
   				var hotelSearchObject = new Object();
   				hotelSearchObject.destination = cityDestinationVal;
   				hotelSearchObject.checkIn = departureDateHotelVal;
   				var date1Array = departureDateHotelVal.split("/");
   				var date2Array = arrivalDateHotelVal.split("/");
   				var date1 = new Date(parseInt(date1Array[2]),parseInt(date1Array[1]),parseInt(date1Array[0]));
   				var date2 = new Date(parseInt(date2Array[2]),parseInt(date2Array[1]),parseInt(date2Array[0]));
   				var timeDiff = Math.abs(date2.getTime() - date1.getTime());
   				hotelSearchObject.stay = Math.ceil(timeDiff / (1000*3600*24));
   				hotelSearchObject.rooms = Rooms;
   				$.getJSON(urlSearchAjaxHotelSearch+"?callback=?",{ hotelSearch: hotelSearchObject },function(id){
	   				window.location.replace(url+"/oferte/hoteluri?searchId="+id);
		   		});
	   		}
	   	});

	});

})(jQuery);
