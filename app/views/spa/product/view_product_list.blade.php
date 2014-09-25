@extends('spa._layouts.default')

@section('bodyId')
{{'spa_products_list'}}
<?php
	$titleType = "product";
?>
@stop

@section('content')
@include('spa._partials.widget_setContent')
<div id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="#">專業產品</a><span class="arrow"></span>
		<a href="#">{{$productCat['title']}}</a>
	</div><!-- ======================== breadcrumb end ======================== -->

	<div class="products_list_pic">
		<!-- @image, for the Products_list Image -->
		<img src="http://placehold.it/700x450">
	</div>

	<div class="contentList">
		@foreach($products as $product)
		<div class="proList">
			<!-- @image, for the Products_list Image -->
			<div class="proPic"><img src="{{$product['image']}}?w=165&h=165"></div>
			<!-- @text, for Products_list Title -->
			<div class="proTitle"><a href="{{$productDetailURL}}/{{$product['id']}}">{{$product['title']}}</a></div>
			<!-- @text, for Products_list Content -->
			<div class="proContent">{{$product['image_desc']}}</div>
			<!-- @text, for Products_list  Capacity and Price -->
			<div class="proPrice">
				<p>容量：<font>{{$product['capacity']}}</font></p>
				<p>價格：<font>{{$product['price']}}</font></p>
			</div>
		</div>
		@endforeach
	</div>
	@include('spa._partials.widget_pager')
</div>
@stop