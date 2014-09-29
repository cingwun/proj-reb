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
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="{{$productURL}}">專業產品</a><span class="arrow"></span>
		<a href="#">{{$productCat['title']}}</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<!-- @image, for the Products_detail Image -->
	<div class="products_detail_pic">
		<img src="{{$product['image']}}?w=700&h=450">
	</div>
	<!-- @text, for Products_detail Content -->
	<div class="products_detail_content">
		{{$product['content']}}
	</div>
</div>
@stop