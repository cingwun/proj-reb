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
		<a href="{{$indexURL}}">首頁</a><span class="arrow"></span>
		<a href="{{$serviceURL}}">服務項目</a><span class="arrow"></span>
		<a href="{{$serviceURL}}">臉部保養</a><span class="arrow"></span>
		<a href="#">{{\Text::preEllipsize(strip_tags($service['title']), 10)}}</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	@include('spa._partials.widget_pageTitle')
	@include('spa._partials.widget_socialIcons')
	<!-- pagedetails -->
	<div id="contentInner">
		<!-- @image, for the Post Image -->
		<img src="{{$service['image']}}?w=680&h=430" alt="{{$service['image_desc']}}">
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
				<div class="pic"><img src="{{$hotService['image']}}?w=160&h=104" alt="{{$hotService['image_desc']}}"></div>
				<!-- @text, for Service Hotclass Title -->
				<div class="title"><a href="{{$serviceDetailURL}}/{{$hotService['id']}}">{{\Text::preEllipsize(strip_tags($hotService['title']), 10)}}</a></div>
				<!-- @text, for Service Hotclass Content -->
				<div class="content"><a href="{{$serviceDetailURL}}/{{$hotService['id']}}">(more)</a></div>
			</div>
			@endforeach
		</div>
	</div>
	<!-- pagedetails end -->
</div>
</div>
@stop

@section('head')
<meta name="keywords" content="{{$service['meta_name']}}">
<meta name="description" content="{{$service['meta_content']}}">
@stop


@if($article->meta_title!="")
	@section('title')
	<title>{{$service['meta_title']}}</title>
@stop

@section('h1')
<h1 style='display:none;'>{{$service['h1']}}</h1>
@stop