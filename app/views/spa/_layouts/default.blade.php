<!DOCTYPE html>
<html lang="zh-TW">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	@yield('head')
	@yield('title','<title>煥儷美顏SPA</title>')

	<!-- <meta name="viewport" content="width=device-width, initial-scale=0.7, maximum-scale=3.0, user-scalable=1" /> -->
	{{ HTML::style(asset('spa/css/layout_spa.css'))}}
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body id="@yield('bodyId')">
	@yield('h1')

	<noscript><div class="noscriptNotify">
		<p><i class="fa fa-warning"></i>&nbsp;為了正常瀏覽本站，請開啟瀏覽器的 JavaScript，然後<a href="">再試一次</a>。</p>
	</div></noscript><!-- ======================== JavaScript 停用警告 end ======================== -->
	<div id="wrap">
		@include('spa._partials.widget_header')
		<div id="wrapInner">
			<div id="midWrap">
				@include('spa._partials.widget_mainBanner')
				@yield('content')
				<a id="scrollToTop" href="javascript:void(0)">
					<img src="<?=asset('spa/img/sign/top.png')?>" />
				</a>
			</div><!-- ======================== midWrap end ======================== -->
		</div>
	</div><!-- ======================== wrap end ======================== -->

	@include('spa._partials.widget_footer')
	@include('spa._partials.widget_quickReservation')

	{{ HTML::script(asset('spa_admin/js/jquery-1.11.0.js'))}}
	@yield('bottom')
	<!--[if IE]>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<![endif]-->
	<!--[if !IE]>-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<!--<![endif]-->
	<!-- // <script type="text/javascript" src="../js/share_slider.js"></script> -->
	{{ HTML::script('spa/js/jq_plugin.js'); }}
	{{ HTML::script('spa/js/isotope.js'); }}
	{{ HTML::script('spa/js/jq_index.js'); }}
	{{ HTML::script('spa/js/jquery.colorbox.js'); }}
	{{ HTML::script('spa/js/jq_slider.js'); }}
	<!--<%= livereload_js if ENV["RACK_ENV"] != "production" %>-->
</body>
</html>