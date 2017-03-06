(function($) {


	$(document).ready(function() {
      	var url = "http://luxuria.infora.ro";
	   	var transportTypeField = $("#searchTransportType"),
	   		departurePointField = $("#searchDeparturePoint"), 
	   	   	countryDestinationField = $("#searchCountryDestination"),
	   	   	cityDestinationField = $("#searchCityDestination"),
	   	   	departureDateField = $("#searchDepartureDate"),
	   	   	durationField = $("#searchDuration"),
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

	   	var searchButton = $("#searchButton");

	   	$('.input-hotel').hide();

	   	$('input[name="searchHolidayType"]').change(function(){
	   		
	   		elementSpin = departurePointField.parent().parent().parent();
			
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
	   		
	   		if($('input[name="searchHolidayType"]:checked').val() != 3){
		   		$('.input-hotel').hide();
		   		$('.input-others').show();
		   		transportTypeField.html('<option value="0">* Tip transport</option>');
		   		transportTypeField.val(0).trigger('change');
		   		$.getJSON(urlSearchAjaxGetTransportTypes+"?callback=?",{ holidayType: $('input[name="searchHolidayType"]:checked').val() },function(data){
		   			var transportTypes = data;
		   			for(var i = 0 ; i < transportTypes.length;i++){
		   				transportTypeField.append($("<option></option>").attr("value",transportTypes[i].id).text(transportTypes[i].name));
		   			}
		   		}).done(function(){
					elementSpin.waitMe('hide');
				});
	   		} else {
	   			$('.input-hotel').show();
		   		$('.input-others').hide();
		   		countryDestinationField.html('<option value="0">* Destinatie</option>');
		   		countryDestinationField.val(0).trigger('change');
		   		$.getJSON(urlSearchAjaxGetCountryDestination+"?callback=?",{ holidayType: $('input[name="searchHolidayType"]:checked').val() },function(data){
		   			var countries = data;
		   			for(var i = 0 ; i < countries.length;i++){
		   				countryDestinationField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
		   			}
		   		}).done(function(){
		   			departurePointField.parent().hide();
					elementSpin.waitMe('hide');
				});
	   		}
	   	});

	   	transportTypeField.change(function(){
	   		departurePointField.html('<option value="0">* Plecare din</option>');
	   		departurePointField.val(0).trigger('change');
	   		if(transportTypeField.val() != 0){
	   			
	   			elementSpin = transportTypeField.parent().parent().parent();
				
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
	   			
		   		$.getJSON(urlSearchAjaxGetDeparturePoints+"?callback=?",{ holidayType: $('input[name="searchHolidayType"]:checked').val(), transportType: transportTypeField.val() },function(data){
		   			var countries = data;
		   			for(var i = 0 ; i < countries.length;i++){
		   				departurePointField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
		   			}
		   		}).done(function(){
					elementSpin.waitMe('hide');
				});
	   		}
	   	});
	   	
	   	departurePointField.change(function(){
	   		countryDestinationField.html('<option value="0">* Destinatie</option>');
	   		countryDestinationField.val(0).trigger('change');
	   		if(departurePointField.val() != 0){
		
	   			elementSpin = departurePointField.parent().parent().parent();
				
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
	   			
	   			$.getJSON(urlSearchAjaxGetCountryDestination+"?callback=?",{ holidayType: $('input[name="searchHolidayType"]:checked').val(), transportType: transportTypeField.val(), departurePoint: departurePointField.val() },function(data){
		   			var countries = data;
		   			for(var i = 0 ; i < countries.length;i++){
		   				countryDestinationField.append($("<option></option>").attr("value",countries[i].id).text(countries[i].name));
		   			}
		   		}).done(function(){
					elementSpin.waitMe('hide');
				});
	   		}
	   	});

	   	countryDestinationField.change(function(){
	   		cityDestinationField.html('<option value="0">* Oras</option>');
	   		cityDestinationField.val(0).trigger('change');
	   		if(countryDestinationField.val() != 0){
	   			
	   			elementSpin = departurePointField.parent().parent().parent();
				
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
				
		   		$.getJSON(urlSearchAjaxGetCityDestination+"?callback=?",{ holidayType: $('input[name="searchHolidayType"]:checked').val(), transportType: transportTypeField.val() , country: countryDestinationField.val(), departurePoint: departurePointField.val() },function(data){
		   			var cities = data;		   			
		   			for(var i = 0 ; i < cities.length;i++){
		   				cityDestinationField.append($("<option></option>").attr("value",cities[i].id).text(cities[i].name));
		   			}
		   		}).done(function(){
					elementSpin.waitMe('hide');
				});
	   		}
	   	});

	   	cityDestinationField.change(function(){
	   		if($('input[name="searchHolidayType"]:checked').val() != 3){
		   		departureDateField.html('<option value="0">* Data plecare</option>');
		   		departureDateField.val(0).trigger('change');
		   		if(cityDestinationField.val() != 0){
		   			
		   			elementSpin = departurePointField.parent().parent().parent();
					
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
					
			   		$.getJSON(urlSearchAjaxGetDepartureDates+"?callback=?",{ holidayType: $('input[name="searchHolidayType"]:checked').val(), transportType: transportTypeField.val() , city: cityDestinationField.val(),  departurePoint: departurePointField.val() },function(data){
			   			var departureDates = data;		   			
			   			for(var i = 0 ; i < departureDates.length;i++){
			   				departureDateField.append($("<option></option>").attr("value",departureDates[i]).text(departureDates[i]));
			   			}
			   		}).done(function(){
						elementSpin.waitMe('hide');
					});
		   		}
	   		}
	   	});

	   	departureDateField.change(function(){
	   		durationField.html('<option value="0">* Durata</option>');
	   		durationField.val(0).trigger('change');
	   		
	   		if(departureDateField.val() != 0){
	   			
	   			elementSpin = departurePointField.parent().parent().parent();
				
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
				
		   		$.getJSON(urlSearchAjaxGetDurations+"?callback=?",{ holidayType: $('input[name="searchHolidayType"]:checked').val(), transportType: transportTypeField.val() , city: cityDestinationField.val(), departureDate: departureDateField.val(),  departurePoint: departurePointField.val() },function(data){
		   			var durations = data;

		   			for(var i = 0 ; i < durations.duration.length;i++){
		   				dn='';
						if(durations.day_night[i] == 0){dn=' nopti';}else{dn=' zi';}
						
		   				durationField.append($("<option></option>").attr("value",durations.duration[i]).text(durations.duration[i]+dn));
		   			}
		   		}).done(function(){
					elementSpin.waitMe('hide');
				});
	   		}
	   	});

	   	function validateErrors(holidayTypeVal,transportTypeVal,countryDestinationVal,cityDestinationVal,departureDateVal,durationVal,departureDateHotelVal,arrivalDateHotelVal,departurePointVal){
	   		var validate = true;
	   		if(holidayTypeVal != 3){
		   		if(transportTypeVal == 0){
		   			transportTypeField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			transportTypeField.attr('style', "");
		   		}
		   		if(departurePointVal == 0){
		   			departurePointField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			departurePointField.attr('style',"");
		   		}
		   		if(countryDestinationVal == 0){
		   			countryDestinationField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			countryDestinationField.attr('style', "");
		   		}
		   		if(cityDestinationVal == 0){
		   			cityDestinationField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			cityDestinationField.attr('style', "");
		   		}
		   		if(departureDateVal == 0){
		   			departureDateField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			departureDateField.attr('style', "");
		   		}
		   		if(durationVal == 0){
		   			durationField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			durationField.attr('style', "");
		   		}
	   		} else {
	   			if(countryDestinationVal == 0){
		   			countryDestinationField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			countryDestinationField.attr('style', "");
		   		}
		   		if(cityDestinationVal == 0){
		   			cityDestinationField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			cityDestinationField.attr('style', "");
		   		}
		   		if(departureDateHotelVal == 0){
		   			departureDateHotelField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			departureDateHotelField.attr('style', "");
		   		}
		   		if(arrivalDateHotelVal == 0){
		   			arrivalDateHotelField.attr('style', "border-radius: 5px; border:#FF0000 1px solid;");
		   			validate = false;
		   		} else {
		   			arrivalDateHotelField.attr('style', "");
		   		}
	   		}
	   		return validate;
	   	}

	   	searchButton.click(function(){
	   		var holidayTypeVal = $('input[name="searchHolidayType"]:checked').val(),
	   			transportTypeVal = transportTypeField.val(),
	   			departurePointVal = departurePointField.val(),
	   			countryDestinationVal = countryDestinationField.val(),
	   			cityDestinationVal = cityDestinationField.val(),
	   			departureDateVal = departureDateField.val(),
	   			durationVal = durationField.val(),
	   			departureDateHotelVal = departureDateHotelField.val(),
	   			arrivalDateHotelVal = arrivalDateHotelField.val();
	   			var Rooms = [];
	   			var rooms = $("#guests").data('value');
		        $.each(rooms.guests, function(i,room){
		          roomTmp = new Object();
		          roomTmp.adults = room.adults;
		          if(room.kids != null){
		            roomTmp.kids = room.kids;
		          }
		          Rooms.push(roomTmp);
		        });
	   		if(validateErrors(holidayTypeVal,transportTypeVal,countryDestinationVal,cityDestinationVal,departureDateVal,durationVal,departureDateHotelVal,arrivalDateHotelVal,departurePointVal)){
	            /*vex.open({
				  content: '<center><img src="/images/logo.jpg" /></center><div style="height: 30px;"></div><center><img src="/images/loader.gif" /></center>',
				  showCloseButton: false,
			      escapeButtonCloses: false,
			      overlayClosesOnClick: false,
				  input:'',
				  callback: function(data) {
				  }
				});*/
	   			
	   			$('body').waitMe({
					effect: 'ios',
					text: '',
					bg: 'rgba(109,109,109,0.5)',
					color: '#1C75D1',
					sizeW: '',
					sizeH: '',
					source: '',
					onClose: function(){}
				});
	   			
	            if(holidayTypeVal != 3){
		   			var packageSearchObject = new Object();
		   			packageSearchObject.is_tour = holidayTypeVal - 1;
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
		   				switch(holidayTypeVal){
		   					case "1":
		   						window.location.replace(url+"/oferte/sejururi?searchId="+id);
		   					break;
		   					case "2":
		   						window.location.replace(url+"/oferte/circuite?searchId="+id);
		   					break;
		   					default:
		   						window.location.replace(url+"/oferte/sejururi?searchId="+id);
		   				}
			   		});
	   			} else {
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
	   		}
	   	});

	});

})(jQuery);