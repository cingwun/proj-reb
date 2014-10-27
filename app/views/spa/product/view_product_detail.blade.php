@extends('spa._layouts.default')

@section('bodyId')
{{'spa_products_detail'}}
<?php
	$titleType = "product";
?>
@stop

@section('content')
@include('spa._partials.widget_setContent')
<div id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="{{$indexURL}}">首頁</a><span class="arrow"></span>
		<a href="{{$productURL}}">專業產品</a><span class="arrow"></span>
		<a href="{{$productListURL}}">{{\Text::preEllipsize(strip_tags($productCat['title']), 10)}}</a><span class="arrow"></span>
		<a href="">{{\Text::preEllipsize(strip_tags($product['title']), 10)}}</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<!-- @image, for the Products_detail Image -->
	<div class="products_detail_pic">
		<img src="{{$product['image']}}?w=700&h=450" alt="{{$product['image_desc']}}">
	</div>
	<!-- @text, for Products_detail Content -->
	<div class="products_detail_content">
		{{$product['content']}}
	</div>
</div>
@stop

@section('head')
<meta name="keywords" content="{{$product['meta_name']}}">
<meta name="description" content="{{$product['meta_content']}}">
@stop

@if($article->meta_title!="")
	@section('title')
	<title>{{$product['meta_title']}}</title>
	@stop
@endif

@section('h1')
<h1 style="display:none">{{$product['h1']}}</h1>
@stop