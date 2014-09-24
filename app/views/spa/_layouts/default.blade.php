<!DOCTYPE html>
<html lang="zh-TW">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<!-- <meta name="viewport" content="width=device-width, initial-scale=0.7, maximum-scale=3.0, user-scalable=1" /> -->
	<title>煥儷美顏SPA</title>
	{{ HTML::style(asset('spa/css/layout_spa.css'))}}
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>		
	<![endif]-->
</head>
<body id="@yield('bodyId')">
	<noscript><div class="noscriptNotify">
		<p><i class="fa fa-warning"></i>&nbsp;為了正常瀏覽本站，請開啟瀏覽器的 JavaScript，然後<a href="">再試一次</a>。</p>
	</div></noscript><!-- ======================== JavaScript 停用警告 end ======================== -->
	<div id="wrap">
		@include('spa._partials.header')
		<div id="wrapInner">
			<div id="midWrap">
				@include('spa._partials.mainBanner')
				@yield('content')
				<a id="scrollToTop" href="javascript:void(0)">
					<img src="spa/img//sign/top.png" />
				</a>
			</div><!-- ======================== midWrap end ======================== -->
		</div>
	</div><!-- ======================== wrap end ======================== -->
	
	@include('spa._partials.footer')
	@include('spa._partials.quickReservation')
	<!--[if IE]>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<![endif]-->
	<!--[if !IE]>-->
	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js'); }}
	<!--<![endif]-->
	<!-- // <script type="text/javascript" src="../js/share_slider.js"></script> -->
	{{ HTML::script('spa/js/jq_plugin.js'); }}
	{{ HTML::script('spa/js/isotope.js'); }}
	{{ HTML::script('spa/js/jq_index.js'); }}
	<script type="text/javascript" src="../spa/js/jquery.colorbox.js"></script>
	<script type="text/javascript" src="../spa/js/jq_slider.js"></script>

	<!--<%= livereload_js if ENV["RACK_ENV"] != "production" %>-->
</body>
</html>