(function($) {

	var notShowClass = "ba-not-show";

	$(document).ready(function() {
	    
	    $('#noRooms').on('change', function() {
			var newNoRooms = parseInt($('#noRooms').val());
			for(var i = 2; i <= newNoRooms; i++){
				$("#noAdults-div-"+i).removeClass(notShowClass);
				$("#noKids-div-"+i).removeClass(notShowClass);
				$("#noAdults-"+i).val(2).trigger('change');
				$("#noKids-"+i).val(0).trigger('change');
			}
			for(var i = newNoRooms + 1; i <= 4; i++){
				$("#noAdults-div-"+i).addClass(notShowClass);
				$("#noKids-div-"+i).addClass(notShowClass);
				for(var j = 1; j <= 4; j++){
					$("#noKids-div-"+i+"-c"+j).addClass(notShowClass);
				}
			}

		});

		$('#noKids-1, #noKids-2, #noKids-3, #noKids-4').on('change',function(){
			var id = $(this).attr("id");
			var t = parseInt(id.substr(id.length - 1));
			var value = parseInt($(this).val());
			for(i = 1;i <= value;i++){
				$("#noKids-div-"+t+"-c"+i).removeClass(notShowClass);	
			}
			for(i = value + 1; i <= 4 ; i++){
				$("#noKids-div-"+t+"-c"+i).addClass(notShowClass);
				$("#noKids-"+t+"-c"+i).val(1).trigger('change');	
			}
		});

	});

})(jQuery);