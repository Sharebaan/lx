//// jquery form
//	function setupLabel() {
//        if ($('.label_check input').length) {
//            $('.label_check').each(function(){ 
//                $(this).removeClass('c_on');
//            });
//            $('.label_check input:checked').each(function(){ 
//                $(this).parent('label').addClass('c_on');
//            });                
//        };
//        if ($('.label_radio input').length) {
//            $('.label_radio').each(function(){ 
//                $(this).removeClass('r_on');
//            });
//            $('.label_radio input:checked').each(function(){ 
//                $(this).parent('label').addClass('r_on');
//            });
//        };
//    };
//    $(document).ready(function(){
//        $('body').addClass('has-js');
//        $('.label_check, .label_radio').click(function(){
//            setupLabel();
//        });
//        setupLabel(); 
//    });
//// jquery form 


// jquery form
	function setupLabel() {
        if ($('.label_check input').length) {
            $('.label_check').each(function(){ 
                $(this).removeClass('c_on');
            });
            $('.label_check input:checked').each(function(){ 
                $(this).parent('label').addClass('c_on');
            });                
        };
        if ($('.label_radio input').length) {
            $('.label_radio').each(function(){ 
                $(this).removeClass('r_on');
            });
            $('.label_radio input:checked').each(function(){ 
                $(this).parent('label').addClass('r_on');
            });
        };
		
		if ($('.label_radio.car input').length) {
            $('.label_radio.car').each(function(){ 
                $(this).removeClass('r_on.car');
            });
            $('.label_radio.car input:checked').each(function(){ 
                $(this).parent('label').addClass('r_on.car');
            });
        };
		
		if ($('.label_radio.plane input').length) {
            $('.label_radio.plane').each(function(){ 
                $(this).removeClass('r_on.plane');
            });
            $('.label_radio.car input:checked').each(function(){ 
                $(this).parent('label').addClass('r_on.plane');
            });
        };
		
		if ($('.label_radio.bus input').length) {
            $('.label_radio.bus').each(function(){ 
                $(this).removeClass('r_on.bus');
            });
            $('.label_radio.bus input:checked').each(function(){ 
                $(this).parent('label').addClass('r_on.bus');
            });
        };
		
		
    };
    $(document).ready(function(){
        $('body').addClass('has-js');
        $('.label_check, .label_radio').click(function(){
            setupLabel();
        });
        setupLabel(); 
    });
// jquery form 