<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title> {{ $pageTitle}} | {{ CNF_APPNAME }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" type="image/x-icon">
		<link href="https://fonts.googleapis.com/css?family=Rubik:400,500,700,900" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link href="{{ asset('sximo/themes/luxuria/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('sximo/themes/luxuria/css/style.css')}}" rel="stylesheet">
    <link href="{{ asset('sximo/themes/luxuria/css/font-icons.css')}}" rel="stylesheet">
    <link href="{{ asset('sximo/themes/luxuria/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('sximo/themes/luxuria/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset('sximo/themes/luxuria/js/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('sximo/themes/luxuria/css/jquery-ui.css')}}" />
    <link rel="stylesheet" href="{{ asset('sximo/themes/luxuria/css/melon.datepicker.css')}}" />
    <link rel="stylesheet" href="{{ asset('sximo/themes/luxuria/css/roomify.css') }}" />


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('sximo/themes/luxuria/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('sximo/themes/luxuria/js/jquery.mixitup.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('sximo/js/plugins/parsley.js') }}"></script>
    <script type="text/javascript" src="{{ asset('sximo/themes/luxuria/js/fancybox/source/jquery.fancybox.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/roomify.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
		jQuery(function() {
			jQuery( ".datepicker" ).datepicker({
				dateFormat: 'dd/mm/yy',
				inline: true,
				showOtherMonths: true,
				minDate: 0
			});
		});
		</script>
		<script>
		jQuery(document).ready(function () {
			jQuery( "#searchDepartureDateHotel" ).change(function() {
				// var singleValues = $( "#searchDepartureDateHotel" ).val();
			  // //alert( singleValues );
			  // $("#searchArrivalDateHotel").val(singleValues);
			  var date2 = $('#searchDepartureDateHotel').datepicker('getDate', '+1d');
			  date2.setDate(date2.getDate()+2);
			  $('#searchArrivalDateHotel').datepicker('setDate', date2);
			});
			jQuery( "#searchHotelDepartureDate" ).change(function() {
				// var singleValues = $( "#searchHotelDepartureDate" ).val();
			  // //alert( singleValues );
			  // $("#searchArrivalDateHotel").val(singleValues);
			  var date2 = $('#searchHotelDepartureDate').datepicker('getDate', '+1d');
			  date2.setDate(date2.getDate()+2);
			  $('#searchHotelReturnDate').datepicker('setDate', date2);
			});
		});
		</script>
  </head>
  <body>

   <header role="banner" id="top" class="navbar navbar-static-top bs-docs-nav">
    <div class="container">
      <div class="navbar-header">
        <button aria-expanded="false" aria-controls="bs-navbar" data-target="#bs-navbar" data-toggle="collapse" type="button" class="navbar-toggle collapsed">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="" href="{{ url()}}">
          <img src="{{ asset('sximo/themes/luxuria/images/logo.png')}}">
        </a>
      </div>
      	<div class="header_contact">
      		<a href="mailto:info@eco.ro"><i class="icon-mail"></i>rezervari@luxuriatrans.ro</a>
      		<a href="tel:0318019010"><i class="icon-phone3"></i>0040 734 489 107</a>
      	</div>
        <nav class="collapse navbar-collapse" id="bs-navbar">
          @include('layouts/luxuria/topbar')

           <!-- <ul class="nav navbar-nav navbar-right">
            @if(CNF_MULTILANG ==1)
            <li  class="user dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag-o"></i><i class="caret"></i></a>
               <ul class="dropdown-menu dropdown-menu-right icons-right">
                @foreach(SiteHelpers::langOption() as $lang)
                  <li><a href="{{ URL::to('home/lang/'.$lang['folder'])}}"><i class="icon-flag"></i> {{  $lang['name'] }}</a></li>
                @endforeach
              </ul>
            </li>
            @endif
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> My Account <span class="caret"></span></a>
            <ul class="dropdown-menu">
            @if(!Auth::check())
              <li><a href="{{ url('user/login')}}">Sign In</a></li>
              <li><a href="{{ url('user/register')}}">Sign Up</a></li>
            @else

              <li><a href="{{ url('dashboard')}}"><i class="fa fa-desktop"></i> Dashboard</a></li>
              <li><a href="{{ url('user/profile')}}"><i class="fa fa-user"></i> My Account</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="{{ url('user/logout')}}">Logout</a></li>
            @endif
            </ul>
        </li>
           </ul> -->
        </nav>
    </div>
  </header>

  <!-- Start dinamyc page -->

   @include($pages)
  <!-- End dinamyc page -->

  <footer class="footer">
    <div class="container">
      <div class="row">
         <div class="col-md-6 footer_column">
         	<h3>Despre Noi</h3>
         	<p>Suntem o familie tanara ce am infiintat aceasta Agentie de Turism cu scopul ca toti prietenii nostri sa calatoreasca cu noi si toti cei ce vor calatori cu noi sa ne devina prieteni!</p>
          <p>Copyright &copy; 2017 {{ CNF_APPNAME }}</p>
         </div>
         <div class="col-md-3 footer_column">
						<h3>Suport & Ajutor</h3>
						<ul class="footer_menu">
							<li><a href="/sximo/themes/luxuria/doc/cui.jpg" target="_blank">Certificat Inmatriculare</a></li>
							<li><a href="/sximo/themes/luxuria/doc/licenta_turism.jpg" target="_blank">Licenta de turism</a></li>
							<li><a href="/sximo/themes/luxuria/doc/manager.jpg" target="_blank">Brevet de Turism</a></li>
							<li><a href="/sximo/themes/luxuria/doc/asigurare-insolventa-page-001.jpg" target="_blank">Polita de asigurare</a></li>
							<li><a href="/page/contractul-cu-turistul/">Contractul cu turistul</a></li>
							<li><a href="/page/despre-noi/">Despre noi</a></li>
							<li><a href="/contact/">Contact</a></li>
						</ul>
         </div>
         <div class="col-md-3 footer_column">
						<h3>Informatii</h3>
						<ul class="footer_menu">
							<li><a href="http://turism.gov.ro/informatii-publice/" target="_blank">Turism.gov.ro</a></li>
							<li><a href="http://www.anpc.gov.ro/" target="_blank">Anpc.gov.ro; Infocons: 0219551</a></li>
							<li><a href="http://www.dataprotection.ro/" target="_blank">Autorizatie ANSPDCP Nr. 33054</a></li>
							<li><a href="/page/informatii-de-calatorie/">Informatii de calatorie</a></li>
							<li><a href="/page/documente-utile/">Documente utile</a></li>
							<li><a href="/page/termeni-si-conditii/">Termeni si conditii</a></li>
							<li><a href="/page/program/">Program</a></li>
						</ul>
         </div>
				<div class="clear"></div>
				<div class="col-md-6 footer_column">
					<a class="social-icon si-small si-borderless si-facebook" href="https://www.facebook.com/www.luxuriatrans.ro" target="_blank">
            <span class="fa-stack fa-lg">
              <i class="fa fa-square fa-stack-2x"></i>
              <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
            </span>
          </a>

          <!-- <a class="social-icon si-small si-borderless si-twitter" href="#">
            <span class="fa-stack fa-lg">
              <i class="fa fa-square fa-stack-2x"></i>
              <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
            </span>
          </a> -->
				</div>
				<div class="col-md-6 footer_column">
					<!-- Begin MailChimp Signup Form -->
					<!-- <link href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css" rel="stylesheet" type="text/css">
					<div id="mc_embed_signup">
					<form action="//luxuria.us6.list-manage.com/subscribe/post?u=d8eec02f3b1c18d1fb8966d36&amp;id=43897fbd52" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					    <div id="mc_embed_signup_scroll">
						
						<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email" required>
					    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_d8eec02f3b1c18d1fb8966d36_43897fbd52" tabindex="-1" value=""></div>
					    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
					    </div>
					</form>
					</div> -->
					
					<!--End mc_embed_signup-->
				</div>
				<div class="clear"></div>
      </div>
      <!-- <div class="footer_info">S.C. luxuria S.R.L. | RO 18496749 | J40/ 4560/ 20.03.2006 | Licenta turism nr: 3932/ 09.12.2010/ Polita asigurare nr: 28660/ 17.04.2015 – 16.04.2016 OMNIASIG VIENNA INSURANCE GROUP | Brevet turism nr: 17346/ 18.11.2010 – MATEI CARMEN LUMINITA | A.N.S.P.D.C.P. nr. 36215 | M.D.R.T - 0800.86.82.82 | A.N.P.C. – Telefonul Consumatorului: (021) 9551</div> -->
    </div>
  </footer>

  </body>
</html>
