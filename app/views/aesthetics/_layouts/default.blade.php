<!DOCTYPE html>
<html lang="zh-TW">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		@yield('head')
		@yield('title','<title>煥儷美形診所</title>')
		<!-- <meta name="viewport" content="width=device-width, initial-scale=0.7, maximum-scale=3.0, user-scalable=1" /> -->
		<!-- <title>煥儷美形診所</title> -->

        {{ HTML::style('aesthetics/css/layout.css'); }}
        {{ HTML::style('aesthetics/css/ckeditor.css'); }}
		<!--[if lt IE 9]>
		<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		@yield('headContent')
	</head>
	<body id="@yield('bodyId')">
		@yield('h1')

		<noscript>
			<div class="noscriptNotify">
				<p><i class="fa fa-warning"></i>&nbsp;為了正常瀏覽本站，請開啟瀏覽器的 JavaScript，然後<a href="">再試一次</a>。</p>
			</div>
		</noscript>
		<!-- JavaScript 停用警告 end -->
		<div id="wrap">
	        <header id="header" role="banner">
				<div class="innerWrap">
					<h1><a href="/">煥儷美形診所</a></h1>
					<div class="languageSwitch">
						<a href="{{Host::getLangURL('tw')}}">繁體中文</a>
						<a class="last" href="{{Host::getLangURL('cn')}}">简体中文</a>
						<form class="searchBox" action="{{URL::to('/')}}/search" method="get"><input type="text" name="q" placeholder="請輸入關鍵字" /><button type="submit"><span>搜尋</span></button></form>
					</div>
					<div class="memberFunc funcBar">
						<span>會員獨享</span>
						<?php if (!Auth::check()):?>
						<a href="<?php echo Social::login('google')?>">登入</a>
						<a href="#">註冊</a>
						<?php else:
								$name = Auth::user()->name;
								if (empty($name))
									echo '您未設定名稱，<a href="#">編輯</a>';
								else
									echo $name . '，您好!';
							  endif;
						?>
						<a class="fb" href="https://www.facebook.com/RebeautyClinic" target="_blank"></a>
					</div>
				</div>
				{{-- navbar --}}
				@include('aesthetics._partials.navbar')
			</header>
			<!-- header end -->

			<div id="midWrap">
				@yield('mainBanner')
				<!-- main banner end -->

				@yield('mainContent')
				<aside id="setContent" role="complementary">
					@section('aside')

					{{-- 美麗排行 --}}
					@include('aesthetics._partials.sidebar_rank')
					{{-- 美麗見證 --}}
					@include('aesthetics._partials.sidebar_case')

					@show
				</aside>
			</div>
			<!-- midWrap end -->
		</div>
		<!-- wrap end -->

		<footer id="footer">
			<!--<ul class="footerNav" role="navigation">
                <li><a href="demo.html">集團院所</a></li><li><a href="demo.html">關係企業</a></li><li><a href="demo.html">隱私權聲明</a></li><li><a href="demo.html">網站地圖</a></li>
            </ul>
			-->
            <ul class="footerNav" role="navigation">
                <li><a>集團院所</a></li>
                <li><a>關係企業</a></li>
                <li><a>隱私權聲明</a></li>
                <li><a>網站地圖</a></li>
            </ul>
			<a class="footer_logo" href="/">煥儷美形診所</a>
			<small role="contentinfo">
				電話:02-2562-3631  傳真:02-2562-0871<br />地址:台北市中山區中山北路一段145號7樓<br />營業時間: PM12:30-20:30
			</small>
		</footer>
		<!-- footer end -->

		{{-- 舊版瀏覽器公告 --}}
		@include('aesthetics._partials.no_ie')

		{{-- 快速預約 --}}
        @include('aesthetics._partials.reservation')

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&appId=435336489944403&version=v2.0";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <!-- facebook app owner eric huang, domain: rd2-test.yam.com-->

		<!--[if IE]>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<![endif]-->
		<!--[if !IE]>-->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<!--<![endif]-->
		{{ HTML::script('aesthetics/js/jq_plugin.js'); }}
		{{ HTML::script('aesthetics/js/jq_index.js'); }}
        {{ HTML::script(asset('js/index/js_index.js')) }}

        <script type="text/javascript">
            $(function(){
                //add reservation
                $('#addReservation').on('click',function(){
                    var flag = false;
                    $('#quickReservationForm input').each(function(){
                        if($(this).attr('name')!='sex'){
                            if(!$.trim($(this).val()))flag=true;
                        }
                    });

                    if(flag){
                        alert('請詳細填寫資料');
                        return false;
                    }

                    $( "#quickReservationForm").submit();
                });
            });
        </script>

        @section('bottomContent')
        @show

		{{-- Google Tag Manager --}}
		@include('aesthetics._partials.googletagmanager')
	</body>
</html>
