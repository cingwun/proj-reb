@extends('spa._layouts.default')

@section('bodyId')
{{'spa_products'}}
@stop

@section('content')
<div id="mainContent" class="fullWidth" role="main">
	<div class="breadcrumb">
		<a href="{{$indexURL}}">首頁</a><span class="arrow"></span>
		<a href="#">專業產品</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<div class="prodTitle">
		<img src="<?=asset('spa/img/sign/product_title.png');?>" height="20" width="200">
	</div>
	<div id="contain_inner">
		@foreach($products as $product)
		<!-- @href for the link to product list pages -->
		<a class="products_categories" href="{{$detailURL}}/{{$product['id']}}">
			<!-- @src for the product list images -->
			<img class="products_img" src="{{$product['image']}}?w=310&h=215"/>
			<!-- @text for the product names -->
			<p class="products_name">[{{\Text::preEllipsize(strip_tags($product['title']), 10)}}]</p>
		</a>
		@endforeach
	</div>
</div>
@stop