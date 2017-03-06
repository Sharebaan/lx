<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en-US"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en-US"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en-US"> <!--<![endif]-->
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> {{ CNF_APPNAME }} | {{ $pageTitle}} </title>
	<meta name="keywords" content="{{ $pageMetakey }}">
	<meta name="description" content="{{ $pageMetadesc }}"/>
	<link rel="shortcut icon" href="" type="image/x-icon">

	<!--[if lt IE 9]>
	<script src="{{ asset('sximo/themes/default/js/main/html5.js') }}"></script>
	<![endif]-->

	<!--FAVICONS-->
    <link rel="shortcut icon" href="{{ asset('sximo/themes/default/img/favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('sximo/themes/default/img/favicon/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('sximo/themes/default/img/favicon/apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('sximo/themes/default/img/favicon/apple-touch-icon-114x114.png') }}">
  <!--END FAVICONS-->




   <script>
        /* You can add more configuration options to webfontloader by previously defining the WebFontConfig with your options */
        if ( typeof WebFontConfig === "undefined" ) {
            WebFontConfig = new Object();
        }
        WebFontConfig['google'] = {families: ['Open+Sans', 'Montez&amp;subset=latin']};

        (function() {
            var wf = document.createElement( 'script' );
            wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.5.3/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName( 'script' )[0];
            s.parentNode.insertBefore( wf, s );
        })();
    </script>


	<style id='rs-plugin-settings-inline-css' type='text/css'>
	.tp-caption a{color:#ff7302;text-shadow:none;-webkit-transition:all 0.2s ease-out;-moz-transition:all 0.2s ease-out;-o-transition:all 0.2s ease-out;-ms-transition:all 0.2s ease-out}.tp-caption a:hover{color:#ffa902}
	</style>
<!-- ================================================= -->

      <link rel='stylesheet'  href="/sximo/themes/default/css/style.css?ver=4.2.2" type='text/css' media='all' />
      <link rel='stylesheet'  href="/sximo/themes/default/css/nicdark_responsive.css?ver=4.2.2" type='text/css' media='all' />
      <link rel='stylesheet'  href="/sximo/themes/default/css/nicdark_style.css" type='text/css' media='all' />
      <link rel='stylesheet'  href="/sximo/themes/default/elusive-icons/elusive-icons.css?ver=4.2.2" type='text/css' media='all' />
      <link rel='stylesheet'  href="/sximo/themes/default/css/js_composer.css?ver=4.5.1" type='text/css' media='all' />
      <link rel='stylesheet'  href="/sximo/themes/default/css/bogeo.css?ver=1.0" type='text/css' media='all' />


<!-- ================================================= -->
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery-2.2.2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/default/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('sximo/js/plugins/parsley.js') }}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/default/js/fancybox/source/jquery.fancybox.js') }}"></script>
	<script type="text/javascript" src="{{ asset('sximo/themes/default/js/jquery.mixitup.min.js') }}"></script>
<!-- ================================================= -->

    <script type="text/javascript" src="/sximo/themes/default/js/main/excanvas.js"></script>
    <script type="text/javascript" src="/sximo/themes/default/js/main/jquery-ui.js"></script>
<!-- ================================================= -->
	<!-- <script type="text/javascript" src="{{ asset('/sximo/themes/veratravel/js/main/jquery-1.11.2.min.js') }}"></script> -->


	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body id="start_nicdark_framework">


<div class="nicdark_site">
	<div class="nicdark_site_fullwidth_boxed nicdark_site_fullwidth nicdark_clearfix">
		<div class="nicdark_section nicdark_navigation nicdark_upper_level2 slowdown">
			<div class="nicdark_menu_fullwidth_boxed nicdark_menu_fullwidth">


				<!-- START TOP BAR BEFORE MENU -->
				<!-- END TOP BAR BEFORE MENU -->


				<!-- START MUTI COLOR LINE -->
				<div class="nicdark_space3 nicdark_bg_gradient"></div>
				<!-- END MUTI COLOR LINE -->
				<!-- START MAIN MENU -->
				<div class="nicdark_bg_white nicdark_section nicdark_border_grey nicdark_sizing">
					<div class="nicdark_container nicdark_clearfix">
						<div class="grid grid_12 percentage">
							<!-- <div class="nicdark_space20"></div> -->
							<div class="nicdark_logo nicdark_marginleft10">
								<a href="{{ url()}}"><img alt="logo" src="{{ asset('sximo/themes/default/img/logo_mini.jpg') }}"></a>
							</div>

							<div class="menu-main-menu-container">
								@include('layouts/default/topbar')
							</div><!--/nav-collapse -->

						</div>
					</div>
				</div>
				<!-- END MAIN MENU -->
			</div>
		</div>
		<!-- <div class="nicdark_space160"></div> -->
	</div>
</div>



<div style="min-height:400px;">
@include($pages)
</div>





<section class="nicdark_section nicdark_bg_greydark nicdark_dark_widgets">
	<!--start nicdark_container-->
	<div class="nicdark_container nicdark_clearfix">
		<div class="nicdark_space30"></div>
		<div class="grid grid_3 nomargin percentage">
			<div class="nicdark_space20"></div>
			<div class="nicdark_margin10">
				<div id="text-2" class="widget widget_text">
					<h2>DEMO TRAVEL</h2>
					<div class="textwidget">
						<ul class="nicdark_list">
							<li>
								<p class="nicdark_margin100 white nicdark_margintop0">
									<i class="icon-location"></i>&nbsp;&nbsp;Bucuresti, Romania
								</p>
							</li>
							<li>
								<p class="nicdark_margin100 white">
									<i class="icon-phone"></i>&nbsp;&nbsp;Telefon/Fax: +4 0300 0 0 0 00
								</p>
							</li>
							<li>
								<p class="nicdark_margin100 white">
									<i class="icon-mail"></i>&nbsp;&nbsp;&nbsp;Email: office@travelian.ro
								</p>
							</li>
						</ul>
						<div class="nicdark_space20"></div>
						<!-- <a href="#" class="nicdark_btn_icon nicdark_bg_violet small white"> <i class="icon-paper-plane nicdark_rotate"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" class="nicdark_btn_icon nicdark_bg_blue small white"> <i class="icon-skype nicdark_rotate"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" class="nicdark_btn_icon nicdark_bg_green small white"> <i class="icon-chat nicdark_rotate"></i> </a> -->
					</div>
				</div>
			</div>
		</div>
		<div class="grid grid_3 nomargin percentage">
			<div class="nicdark_space20"></div>
			<div class="nicdark_margin10">
				<div id="tag_cloud-2" class="widget widget_tag_cloud">
					<h2>INFO</h2>
					<!-- <div class="tagcloud">
						<a href="#" class="tag-link-74" title="3 topics" style="font-size: 15px;">Adventure</a>
						<a href="#" class="tag-link-75" title="1 topic" style="font-size: 15px;">Cars</a>
						<a href="#" class="tag-link-76" title="4 topics" style="font-size: 15px;">Cruise</a>
						<a href="#" class="tag-link-77" title="1 topic" style="font-size: 15px;">Cultural</a>
						<a href="#" class="tag-link-78" title="1 topic" style="font-size: 15px;">Honeymoon</a>
						<a href="#" class="tag-link-79" title="1 topic" style="font-size: 15px;">Package</a>
						<a href="#" class="tag-link-80" title="1 topic" style="font-size: 15px;">Relax</a>
						<a href="#" class="tag-link-81" title="1 topic" style="font-size: 15px;">Rent</a>
						<a href="#" class="tag-link-82" title="1 topic" style="font-size: 15px;">Tour</a>
						<a href="#" class="tag-link-83" title="4 topics" style="font-size: 15px;">Travel</a>
					</div> -->
					<ul class="pdf">
						<li><a href="" target="_blank">Licenta turism</a></li>
						<li><a href="" target="_blank">Brevet Turism</a></li>
						<li><a href="" target="_blank">Polita Insolvabilitate</a></li>
						<li><a href="" target="_blank">Certificat inmatriculare</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="grid grid_3 nomargin percentage">

			<div class="nicdark_space20"></div>

			<div class="nicdark_margin10">
				<div id="text-4" class="widget widget_text">
					<h2>DESPRE NOI</h2>
					<!-- <div class="textwidget"><img alt="" class="nicdark_activity nicdark_padding14_right nicdark_sizing nicdark_width_percentage33" src="{{ asset('sximo/themes/veratravel/img/love-travel-5-200.jpg') }}">

						<img alt="" class="nicdark_activity nicdark_padding07 nicdark_sizing nicdark_width_percentage33" src="{{ asset('sximo/themes/veratravel/img/love-travel-1-200.jpg') }}">

						<img alt="" class="nicdark_activity nicdark_padding14_left nicdark_sizing nicdark_width_percentage33" src="{{ asset('sximo/themes/veratravel/img/love-travel-2-200.jpg') }}">

						<img alt="" class="nicdark_margintop20 nicdark_activity nicdark_padding14_right nicdark_sizing nicdark_width_percentage33" src="{{ asset('sximo/themes/veratravel/img/love-travel-3-200.jpg') }}">

						<img alt="" class="nicdark_margintop20 nicdark_activity nicdark_padding07 nicdark_sizing nicdark_width_percentage33" src="{{ asset('sximo/themes/veratravel/img/love-travel-4-200.jpg') }}">

						<img alt="" class="nicdark_margintop20 nicdark_activity nicdark_padding14_left nicdark_sizing nicdark_width_percentage33" src="{{ asset('sximo/themes/veratravel/img/love-travel-6-200.jpg') }}">
					</div> -->
					<div class="textwidget">
						<p>Travelian este o platforma de management al ofertelor turistice.</p>
					</div>
				</div>
			</div>
		</div>

		<div class="grid grid_3 nomargin percentage">
			<div class="nicdark_space20"></div>
			<div class="nicdark_margin10">
				<div id="text-3" class="widget widget_text">
					<h2>INFO</h2>
					<ul class="pdf">
						<li><a href="/contact">Contact</a></li>
						<li><a href="http://www.anpc.gov.ro" target="_blank">Anpc</a></li>
						<li><a href="" >Asigurari</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="nicdark_space10"></div>
	</div>
	<!--end nicdark_container-->
</section>
<div class="nicdark_section nicdark_bg_greydark2 ">

	<!--start nicdark_container-->
	<div class="nicdark_container nicdark_clearfix">

		<div class="grid grid_6 nicdark_aligncenter_iphoneland nicdark_aligncenter_iphonepotr">
			<div class="nicdark_space20"></div>

			<p class="white">
				Copyright 2015 {{ CNF_APPNAME }} . ALL Rights Reserved.
			</p>
		</div>

		<div class="grid grid_6">
			<div class="nicdark_focus right nicdark_aligncenter_iphoneland nicdark_aligncenter_iphonepotr">

				<div class="nicdark_margin10">
					<a href="#" class="nicdark_press right nicdark_btn_icon nicdark_bg_blue small white"><i class="icon-twitter-1"></i></a>
				</div>
				<!-- <div class="nicdark_margin10">
					<a href="#" class="nicdark_press right nicdark_btn_icon nicdark_bg_red small white"><i class="icon-youtube-play"></i></a>
				</div> -->
				<div class="nicdark_margin10">
					<a href="https://www.facebook.com/veratravelagency" class="nicdark_press right nicdark_btn_icon nicdark_facebook small white" target="_blank"><i class="icon-facebook"></i></a>
				</div>
				<!--back to top-->

				<div class="nicdark_margin10">
					<a href="#start_nicdark_framework" class="nicdark_zoom nicdark_internal_link right nicdark_btn_icon nicdark_bg_greydark2 small nicdark_radius white"><i class="icon-up-open"></i></a>
				</div>
				<!--back to top-->
			</div>
		</div>
	</div>
	<!--end nicdark_container-->
</div>



<script>
	jQuery(document).ready(function() {

		window.prettyPrint && prettyPrint();
	});
</script>
</body>
</html>
