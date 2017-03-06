<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title> {{ $pageTitle}} </title>
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  	<meta name="keywords" content="{{$pageMetakey}}"/>
	<meta name="description" content="{{$pageMetadesc}}">
	<meta name="author" content="{{ CNF_APPNAME }}">

  <link rel="shortcut icon" href="{{ asset('sximo/themes/helloholiday/favicon.ico')}}" type="image/x-icon"> 
  
	<link rel="stylesheet" href="{{ asset('sximo/themes/helloholiday/css/reset.css')}}" />
	<link rel="stylesheet" href="{{ asset('sximo/themes/helloholiday/css/style.css')}}" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
	<link rel="stylesheet" type="text/css" media="screen and (max-width: 980px)" href="{{ asset('sximo/themes/helloholiday/css/mobile.css')}}" />
	<link href="{{ asset('sximo/themes/helloholiday/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
	<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" /> -->
	<link rel="stylesheet" href="{{ asset('sximo/themes/helloholiday/css/jquery-ui.css')}}" />
	<link rel="stylesheet" href="{{ asset('sximo/themes/helloholiday/css/melon.datepicker.css')}}" />
	
	
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800,700%7cOpen+Sans+Condensed:300' rel='stylesheet' type='text/css' />
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300%7cRoboto:400,700,300' rel='stylesheet' type='text/css' />
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/jquery.matchHeight.js')}}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/theia-sticky-sidebar.js')}}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/accordion.js')}}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/tabs.js')}}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/scroll.js')}}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/forms.js')}}"></script>
	
	<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/roomify.js')}}"></script>
	<link rel="stylesheet" href="{{ asset('sximo/themes/helloholiday/css/roomify.css')}}" />
	<!-- <script type="text/javascript">
		$(document).ready(function(){
  		$('#guests1').roomify(null);
  		$('#guests2').roomify(null);
  		$('#guests3').roomify(null);
		})
	</script> -->

	<script>        
		$(document).ready(function() {
			$('#sidebar, #col-left, .diverse-dotari-wrapp').theiaStickySidebar({
				additionalMarginTop: 30,
				additionalMarginBottom: 30
			});
		});
	</script>

	<script type="text/javascript">
		$(function() {
	    	$('.box').matchHeight();
			$('.box header').matchHeight();
			$('.promo').matchHeight();
			$('.listing-row-height').matchHeight();
		});
	</script>
	
	<!-- responsive -->
	<script>
		$(function() {
			var pull 		= $('#pull');
				menu 		= $('.main-menu');
				menuHeight	= menu.height();
		
			$(pull).on('click', function(e) {
				e.preventDefault();
				menu.slideToggle();
			});
		
			$(window).resize(function(){
				var w = $(window).width();
				if(w > 320 && menu.is(':hidden')) {
					menu.removeAttr('style');
				}
			});
		});
	</script>
	<!-- responsive -->

	<!-- slider -->
	<script class="rs-file" src="{{ asset('sximo/themes/helloholiday/slider/jquery.royalslider.min.js')}}"></script>
	<link class="rs-file" href="{{ asset('sximo/themes/helloholiday/slider/royalslider.css')}}" rel="stylesheet">
	<link class="rs-file" href="{{ asset('sximo/themes/helloholiday/slider/rs-minimal-white.css')}}" rel="stylesheet"> 
	
	<script>
	jQuery(document).ready(function($) {
	  $('#full-width-slider').royalSlider({
	    arrowsNav: true,
	    loop: true,
	    keyboardNavEnabled: true,
	    controlsInside: false,
	    imageScaleMode: 'fill',
	    arrowsNavAutoHide: false,
	    autoScaleSlider: true, 
	    autoScaleSliderWidth: 1200,     
	    autoScaleSliderHeight: 394,
	    controlNavigation: 'bullets',
	    thumbsFitInViewport: false,
	    navigateByClick: true,
	    startSlideId: 0,
		slidesSpacing: 0,
	    transitionType:'move',
	    globalCaption: false,
	    deeplinking: {
	      enabled: true,
	      change: false,
	    },
		autoPlay: {
	            enabled: true,
	            pauseOnHover: true,
				delay: 6000,
				transitionSpeed: 600,
	        },
	    imgWidth: 1200,
	    imgHeight: 394,
	  });
	});
	</script>
	
	<link class="rs-file" href="{{ asset('sximo/themes/helloholiday/slider/rs-default.css')}}" rel="stylesheet"> 
	<script>
	jQuery(document).ready(function($) {
	  $('#gallery-1').royalSlider({
	    fullscreen: {
	      enabled: false,
	      nativeFS: true
	    },
	    controlNavigation: 'thumbnails',
	    autoScaleSlider: true, 
	    //autoScaleSliderWidth: 960,     
	    autoScaleSliderHeight: 640,
	    loop: true,
	    imageScaleMode: 'fit-if-smaller',
	    navigateByClick: true,
	    numImagesToPreload:2,
	    arrowsNav:true,
	    arrowsNavAutoHide: true,
	    arrowsNavHideOnTouch: true,
	    keyboardNavEnabled: true,
	    fadeinLoadedSlide: true,
	    globalCaption: false,
	    globalCaptionInside: false,
	    thumbs: {
	      appendSpan: true,
	      firstMargin: true,
	      paddingBottom: 0
	    }
	  });
	});
	</script>
	<!-- slider -->					
	<script>

	$(function() {	
		$( ".datepicker" ).datepicker({
			dateFormat: 'dd/mm/yy',
			inline: true,
			showOtherMonths: true,
			minDate: 0
		});
	});
	$(function() {	
		$( ".datepicker_b" ).datepicker({
			dateFormat: 'dd/mm/yy',
			inline: true,
			showOtherMonths: true,
			changeYear: true,
			yearRange: "-100:+0",
			maxDate: 0
		});
	});
	</script>
	<script>
		jQuery(document).ready(function () {
			jQuery( "#searchDepartureDateHotel" ).change(function() {
			  var date2 = $('#searchDepartureDateHotel').datepicker('getDate', '+1d');
			  date2.setDate(date2.getDate()+7);
			  $('#searchArrivalDateHotel').datepicker('setDate', date2);
			});
			jQuery( "#searchHotelDepartureDate" ).change(function() {
			  var date2 = $('#searchHotelDepartureDate').datepicker('getDate', '+1d');
			  date2.setDate(date2.getDate()+7);
			  $('#searchHotelReturnDate').datepicker('setDate', date2);
			});
		});
		function Showoffers(){
			jQuery('.ba-item-view-prices-table tr').show();
			jQuery('#more_offers').hide();
		}
		function ShowFilters(){
			if($('.mobile-hidden').is(':visible')) {
				jQuery('.mobile-hidden').hide();
			}else{
				jQuery('.mobile-hidden').show();
			}
		}
		function ShowCountryFilters(){
			if($('.mobile-hidden-country').is(':visible')) {
				jQuery('.mobile-hidden-country').hide();
			}else{
				jQuery('.mobile-hidden-country').show();
			}
		}
		</script>

</head>
<body>
	<!-- Google Tag Manager -->

<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PLS8T7"

height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

})(window,document,'script','dataLayer','GTM-PLS8T7');</script>

<!-- End Google Tag Manager -->
<div class="page-wrapper">
   <header class="cf"><div class="inner">
    <div class="row">
    
    <div class="box-30 text-center">
            <h2 class="text-center"><a href="{{ url()}}"  class="cf header-logo"><img src="{{ asset('sximo/themes/helloholiday/images/hello-holidays.png')}}" alt="" /><span>Circuite si Sejururi Grecia Turcia</span></a></h2>
            <p class="tagline text-center">Spune "Hello" vacantelor tale</p>
     </div>
     
      <div class="box-70">
           <nav class="secondary-menu cf">
            <ul>
                <li class="lifted left"><a href="{{ url()}}" class="acasa">Acasă</a></li>
                <li class="rounded-left"><a href="/page/despre" class="despre-noi">Despre Noi</a></li>
                <li><a href="http://galerie.helloholidays.ro/" class="galerie">Galerie</a></li>
                <li><a href="http://spunehellovacantelortale.ro/" target="_blank" class="blog">Blog</a></li>
                <li ><a href="/page/impresii" class="impresii">Impresii</a></li>
                <li class="rounded-right"><a href="/page/contact" class="contact">Contact</a></li>
                <li class="lifted right"><a href="http://b2b.helloholidays.ro/" class="acces-parteneri" target="_blank">Acces Parteneri</a></li>
            </ul>
            </nav>
            
            <div>
            <div class="call-center"><div class="call-center-inner">Call Center <span><a href="tel:0040737520840">073 752 08 40</a><em class="tel-separator">,&nbsp;</em><a href="tel:0040213165367">021 316 53 67</a></span></div></div>
            <div class="search_row">
							<form  action="/search" method="get" >
								<input type="text" name="search_input" id="searchbar" class="search_input" placeholder="Cauta">
							</form>
						</div>
					  <script type="text/javascript">
					      var names = [];
					      var all = [];
			
					      $('input#searchbar').autocomplete({
					       source: function (req ,res){
					        $.get('/ajax_search/suggestions/',{search:$('#searchbar').val()}).done(function(data){
					         
					         $.each(data.r,function(i,v){
					          names[i]=v.name;
					          all[i] = v;
					         });
					         res(names);
					        });
			
					       },
					       select:function(a,b){
					        $.each(all,function(i,v){
					         if(v.name === b.item.value){
					          $(location).attr('href', window.location.origin+'/hotel/'+v.id);
					         }
					        });
					       }
			
					      });
			
					   </script>
            </div>
        </div>
      </div>
        </div>
        
        <a href="javascript:void(0)" id="pull" rel="nofollow">Meniu</a>
        <nav class="main-menu">
        	@include('layouts/helloholiday/topbar')
        </nav>
    </header>   
   
  <!-- Start dinamyc page -->
   @include($pages)
  <!-- End dinamyc page -->

  <footer><div class="inner">
    <div class="row">
    	<div class="footer-left box-30 text-center">
        <a href="#"><img src="{{ asset('sximo/themes/helloholiday/images/hello-holidays-footer-logo.png')}}" alt=""/></a>
        <ul class="footer-social-media">
        	<li><a href="https://www.facebook.com/agentia.helloholidays/" target="_blank"><img src="{{ asset('sximo/themes/helloholiday/images/sm-facebook.png')}}" alt="Facebook" /></a></li>
          <li><a href="#"><img src="{{ asset('sximo/themes/helloholiday/images/sm-youtube.png')}}" alt="YouTube" /></a></li>
        </ul>
        
        <ul class="footer-partners">
        	<li><a href="http://anat.ro" target="_blank"><img src="{{ asset('sximo/themes/helloholiday/images/logo-anat.png')}}" alt="" /></a></li>
          <li><a href="http://agentiideturism.infoturism.ro/hello-holidays_2060/" target="_blank"><img src="{{ asset('sximo/themes/helloholiday/images/logo-infoturism.png')}}" alt="" /></a></li>
          <li><a href="https://www.poleasy2.at/tip/tip/Whl?wid=66334019&start=true&lang=RRO" target="_blank"><img src="{{ asset('sximo/themes/helloholiday/images/logo-mondial.jpg')}}" alt="" /></a></li>
        </ul>
        <div class="newsletter_area">
        	<!-- Begin MailChimp Signup Form -->
					<link href="//cdn-images.mailchimp.com/embedcode/slim-10_7.css" rel="stylesheet" type="text/css">
					<style type="text/css">
						#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
						/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
						   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
					</style>
					<div id="mc_embed_signup">
					<form action="//helloholidays.us8.list-manage.com/subscribe/post?u=0d86c735c72c39fe3c8cb06aa&amp;id=8799bf51ac" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					    <div id="mc_embed_signup_scroll">
						
						<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
					    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_0d86c735c72c39fe3c8cb06aa_8799bf51ac" tabindex="-1" value=""></div>
					    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
					    </div>
					</form>
					</div>
					<iframe class="" src="https://www.facebook.com/plugins/like_box.php?app_id=&amp;channel=http%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter%2Fr%2FuN4_cXtJDGb.js%3Fversion%3D42%23cb%3Df311587998cd912%26domain%3Dwww.helloholidays.ro%26origin%3Dhttp%253A%252F%252Fwww.helloholidays.ro%252Ffe08abe411d6c%26relation%3Dparent.parent&amp;container_width=303&amp;header=false&amp;href=https%3A%2F%2Fwww.facebook.com%2Fagentia.helloholidays&amp;locale=en_GB&amp;sdk=joey&amp;show_border=false&amp;show_faces=true&amp;stream=false&amp;width=292" style="border: medium none; visibility: visible; width: 292px; height: 214px;" title="fb:like_box Facebook Social Plugin" scrolling="no" allowfullscreen="true" allowtransparency="true" name="f117e9142a13724" frameborder="0" height="1000px" width="292px"></iframe>
					
					<!--End mc_embed_signup-->
        </div>
      </div>
        
        <div class="box-60 footer-right">
        <nav class="footer-menu">
        	<ul>
                <li><a href="/oferte/sejururi">Sejururi</a></li>
                <li><a href="#">Circuite</a></li>
                <li><a href="/oferte/sejururi?page=1&offerTypes=2&sortBy=price&sortOrder=ASC">Oferte Speciale</a></li>
                <li><a href="/oferte/sejururi?offerTypes=1">Early Booking</a></li>
                <li><a href="/oferte/circuite?page=1&locationFiltering=child&locationId=161&sortBy=price&so">Turism Intern</a></li>
                <li><a href="/oferte/circuite?categoryId=8">Revelion 2017</a></li>
                <li><a href="/oferte/circuite?categoryId=7">Excursii de 1 zi</a></li>
            </ul>
        </nav>
        
        <div class="box-100">
        <nav class="footer-lists">
        	<h1>Agentie</h1>
			<ul>
                <li><a href="#">Site Map</a></li>
                <li><a href="http://b2b.helloholidays.ro/" target="_blank">Login Parteneri</a></li>
                <li><a href="#">Parteneri</a></li>
                <li><a href="/page/cataloage">Cataloage</a></li>
                <li><a href="/page/cariere">Cariere</a></li>
                <li><a href="/page/contact">Contact</a></li>
            </ul>
        </nav>
        
        <nav class="footer-lists">
        	<h1>Informatii generale</h1>
			<ul>
                <li><a href="{{ asset('sximo/themes/helloholiday/doc/Contract-de-comercializare-a-pachetelor-turistice.doc')}}" target="_blank">Contract HH</a></li>
                <li><a href="/page/protectia-datelor">Protecţia datelor</a></li>
                <li><a href="http://www.anpc.gov.ro/" target="_blank">ANPC</a></li>
                <li><a href="https://www.politiadefrontiera.ro/" target="_blank">Poliţia de frontieră</a></li>
                <li><a href="http://www.bnr.ro" target="_blank">Curs BNR</a></li>
                <li><a href="http://www.accuweather.com/en/world-weather" target="_blank">Meteo</a></li>
            </ul>
        </nav>
        
        <nav class="footer-lists">
        	<h1>Informatii utile</h1>
			<ul>
                <li><a href="{{ asset('sximo/themes/helloholiday/doc/licenta-turism.pdf')}}" target="_blank">Licenţa de turism</a></li>
                <li><a href="{{ asset('sximo/themes/helloholiday/doc/polita-asigurare-2016-hello-holidays.pdf')}}" target="_blank">Poliţa de asigurare</a></li>
                <li><a href="{{ asset('sximo/themes/helloholiday/doc/BREVET-TURISM.PDF')}}" target="_blank">Brevet de turism</a></li>
                <li><a href="{{ asset('sximo/themes/helloholiday/doc/CUI-HH.PDF')}}" target="_blank">Certificat de înregistrare</a></li>
                <li><a href="#">Informaţii Hello Holidays</a></li>
                <li><a href="http://www.asppc.ro/" target="_blank">Protecţia Consumatorului</a></li>
                <li><a href="/page/termeni-si-conditii">Termeni şi condiţii</a></li>
            </ul>
        </nav>
        </div>
        </div>
        </div>
        
        <div class="row">
        <nav class="footer-sites">
            <ul>
                <li><a href="http://www.ofertesejurgrecia.ro" target="_blank">ofertesejurgrecia.ro</a></li>
                <li><a href="http://www.parga.ro" target="_blank">parga.ro</a></li>
                <li><a href="http://www.sarti.ro" target="_blank">sarti.ro</a></li>
                <li><a href="http://www.olympicbeach.ro" target="_blank">olympicbeach.ro</a></li>
                <li><a href="http://www.insulazakynthos.ro" target="_blank">insulazakynthos.ro</a></li>
                <li><a href="http://www.insulalefkada.ro" target="_blank">insulalefkada.ro</a></li>
                <li><a href="http://www.vacantathassos.ro" target="_blank">vacantathassos.ro</a></li>
                <li><a href="http://www.helloholidaystransport.ro" target="_blank">helloholidaystransport.ro</a></li>
                <li><a href="http://www.circuiteautocar.ro" target="_blank">circuiteautocar.ro</a></li>
            </ul>
        </nav>
        </div>
        
        <div class="row text-center">
        	<p class="copyright">Copyright © Hello Holidays, 2016. Toate drepturile sunt rezervate.</p>
        	<p class="copyright">Dezvoltat de <a href="http://www.infora.ro/?utm_source=HelloHolidays&utm_medium=site&utm_campaign=HelloHolidays" target="_blank">Infora</a>.</p>
        </div>
        
    </div></footer>
	</div>
	<a href="#0" class="cd-top">Sus</a>
</body>
</html>
