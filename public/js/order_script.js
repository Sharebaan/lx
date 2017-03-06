(function($) {
	$(document).ready(function() {
	    var optionsFF = $("[name='options-ff']"),
	    	divFF = $("#additional-ff"),
	    	onlineMethod = $("#onlineMethod"),
	    	cashMethod = $("#cashMethod"),
	    	transferMethod = $("#transferMethod"),
	    	onlineMethodDiv = $("#online-payement"),
	    	cashMethodDiv = $("#cash-payement"),
	    	transferMethodDiv = $("#transfer-payement");

	    optionsFF.change(function(){
	    	if(optionsFF.attr("checked")){
	    		divFF.show("slow");
	    	} else {
	    		divFF.hide("slow");
	    	}
	    });
	    cashMethod.change(function(){
	    	cashMethodDiv.show();
	    	transferMethodDiv.hide();
	    	onlineMethodDiv.hide();
	    });
	    transferMethod.change(function(){
	    	cashMethodDiv.hide();
	    	transferMethodDiv.show();
	    	onlineMethodDiv.hide();
	    });
	    onlineMethod.change(function(){
	    	cashMethodDiv.hide();
	    	transferMethodDiv.hide();
	    	onlineMethodDiv.show();
	    });

	});
})(jQuery);