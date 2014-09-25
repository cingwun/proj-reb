@extends('spa._layouts.default')

@section('bodyId')
{{'spa_service_detail'}}
<?php
	$titleType = "service";
	$publish = date("Y/m/d",time($service['created_at']));
	$views = $service['views'];
?>
@stop

@section('content')
@include('spa._partials.widget_setContent')
<div id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="#">服務項目</a><span class="arrow"></span>
		<a href="#">臉部保養</a><span class="arrow"></span>
		<a href="#">{{$service['title']}}</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	@include('spa._partials.widget_pageTitle')
	@include('spa._partials.widget_socialIcons')
	<!-- pagedetails -->
	<div id="contentInner">
		<!-- @image, for the Post Image -->
		<img src="{{$service['image']}}?w=680&h=430">
		<div class="contentArticle">
			<!-- @text, for Service Content -->
			<p>{{$service['content']}}</p>
		</div>
	</div>
	<!--shareCon end-->
	<div id="hotClasses">
		<div class="bar">
			<img src="<?=asset('spa/img/sign/hotClass.jpg')?>" height="34" width="700">
		</div>
		<div class="class">
			@foreach($hotServices as $hotService)
			<div class="hotClass">
				<!-- @image for Service Hotclass Image -->
				<div class="pic"><img src="{{$hotService['image']}}?w=160&h=104"></div>
				<!-- @text, for Service Hotclass Title -->
				<div class="title"><a href="#">{{$hotService['title']}}</a></div>
				<!-- @text, for Service Hotclass Content -->
				<div class="content">aaaa<a href="#">(more)</a></div>
			</div>
			@endforeach
		</div>
	</div>
	<!-- pagedetails end -->
</div>
</div>
@stop
