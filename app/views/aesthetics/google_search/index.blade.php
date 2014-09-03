@extends('aesthetics._layouts.default')

@section('bodyId'){{'services'}}@stop

@section('mainBanner')
@stop

@section('mainContent')
<div id="mainContent" class="postBox" role="main">
<script>
  (function() {
    var cx = '017730374180726895139:6xs9iqhrlp8';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only>
</div><!-- ======================== mainContent end ======================== -->
@stop

@section('aside')
<h3 class="titleRp h3_serv">服務項目</h3>
	<ul class="servList">
		<li>
		<h4>主選單111</h4>
		<ul class="sub">
			<li><a href="#">除廢掉舊曆，葡萄柚最棒了！</a></li>
			<li><a href="#">神所厭棄本無價值。</a></li>
			<li><a href="#">家門有興騰的氣象，丙可憐似的說。</a></li>
			<li><a href="#">繞來穿去。</a></li>
		</ul><!-- servList ul.sub -->

		</li><!-- servList -->
		<li>
		<h4>主選單111</h4>
		<ul class="sub">
			<li><a href="#">也不上你的甜！</a></li>
			<li><a href="#">明夜沒得再看啦！</a></li>
			<li><a href="#">想泡杯燕麥片，透過了衣衫，一年的設定，早餐機投入使用。</a></li>
			<li><a href="#">因為這是親戚間，憑這雙腕！</a></li>
		</ul><!-- servList ul.sub -->

		</li><!-- servList -->
	</ul>
{{-- 美麗排行 --}}
@include('aesthetics._partials.sidebar_rank')
@stop

@section('bottomContent')
@stop
