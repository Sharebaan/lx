<!DOCTYPE html>
<!--[if IE 8]>          <html class="ie ie8"> <![endif]-->
<!--[if IE 9]>          <html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->  <html> <!--<![endif]-->
<head>
    <!-- Page Title -->
    <title>404 | Helloholidays</title>
    <!--[if lt IE 9]>
	  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	  <![endif]-->
	  <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" type="image/x-icon"> 
	  
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
    
</head>
<body class="post-404page">
	<div id="main" class="pages">
		<div class="inner" id="page404">
			<img src="{{ asset('sximo/themes/helloholiday/images/hello-holidays.png')}}" alt="" />
			<!-- <div class="error-message">{{{$errorMessage}}}</div> -->
			<div class="error-message">Aceasta pagina nu exista.</div>
			<div class="error-message-404">{{{$errorNumber}}}</div>
			<a class="button btn-small full-width text-center" href="{{ url()}}">Inapoi la pagina principala</a>
			<div class="copyright"><p>&copy; 2016 Helloholidays</p></div>
		</div>
	</div>
</body>
</html>

