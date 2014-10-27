@extends('spa._layouts.default')

@section('bodyId')
{{'spa_shareCase_detail'}}
<?php $titleType = 'share'; ?>
@stop


@section('content')
{{ HTML::style('spa/css/tabBox.css'); }}
<div id="mainContent" class="fullWidth" role="main">
	<div class="breadcrumb">
		<a href="{{URL::route('spa.index')}}">首頁</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.share')}}">美麗分享</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.share.detail', array($article->id))}}">{{$article->title}}</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<div class="allTop_con">
		<div class="shareTop_pic">
			<img src="{{$image[0]->image}}?w=500" alt="{{$image[0]->text}}">
		</div>
		<div class="shareTop_con">
			<div class="classList">
				<div><p>服務項目：</p></div>
				<!-- @text for shareTag -->
				<div class="shareTag">
					@foreach($labelService as $ls)
					<a href="{{URL::route('spa.service.detail', array($ls['id']))}}"><div class="shareTag_btn">{{ \Text::preEllipsize(strip_tags($ls['title']), 5) }}</div></a>
					@endforeach
				</div>
			</div>
			<div class="classPro">
				<div><p>專業產品：</p></div>
				<!-- @text for shareTag -->
					<div class="shareTag">
					@foreach($labelProduct as $lp)
						<a href="{{URL::route('spa.product.detail', array($lp['id']))}}"><div class="shareTag_btn">{{ \Text::preEllipsize(strip_tags($lp['title']), 5) }}</div></a>
					@endforeach
					</div>
				</div>
			<div class="classDes">
				<!-- @text for productDescription -->
				{{$article->description}}
			</div>
		</div>
	</div>
	<!-- sliderBox -->
	<div class="slider" id="sliderBox">
		<!-- <span class="btn-close" alt="關閉">關閉</span>
		<div class="main-title">小護士靠立塑終結萬年小腹</div>
		<div class="photo-num">第<span class="no number">1</span>張&nbsp;/&nbsp;共<span class="number">8</span>張</div> -->
		<div class="clear"></div>
		<div class="wrapper">
			<ul class="container-images">
				@foreach($gallery as $gallery)
				<li>
					<a rel="gallery1" href="{{$gallery->image}}" title="{{$gallery->text}}">
					<!--@images for shareDemo slider-->
					<img src="{{$gallery->image}}?w=130" alt="{{$gallery->text}}">
					<!-- @text for the list title-->
					<p>{{$gallery->text}}</p></a>
				</li>
				@endforeach
			</ul>
		</div><!-- wrapper end -->
		<span class="nex prevCcontrol" id="prev"></span>
		<span class="pre nextCcontrol" id="next"></span>
		<div class="image-block">
			<img src="" class="hidden"/>
			<div class="title hidden"></div>
			<div class="loader hidden"></div>
		</div>
	</div>
	<!-- slider end -->

	<!-- Uses public/spa/css/tabBox.css -->
	<article id="servBox">
		<div class="shareCon">
			 <ul class="tabNav">
	            @foreach($tabs as $index=>$t)
	            <li><a href="#tab{{$index}}" class="{{ ($index===0)?'curr':''}}">{{ $t['title']}}</a></li>
	            @endforeach
	        </ul>

	        @foreach($tabs as $index=>$t)
	            <div class="tabBox {{ ($index===0)?'curr':''}}" id="tab{{ $index}}">{{ $t['content']}}</div>
	        @endforeach
	        <!-- tabBox end -->
		</div>
	</article>

	<div class="postNav">
		<div>
			@if(empty($pervArticle))
			<a href="{{URL::route('spa.share')}}">回列表</a>
			@else
			上一篇
			<span class="arrow"></span>
			<a href="{{URL::route('spa.share')}}/{{$prevArticle->id}}">{{$prevArticle->title}}</a>
			@endif
		</div>
		<div>
			@if(!empty($nextArticle))
			下一篇
			<span class="arrow"></span>
			<a href="{{URL::route('spa.share')}}/{{$nextArticle->id}}">{{$nextArticle->title}}</a>
			@endif
		</div>
	</div>
</div>
@stop

@section('head')
<meta name="keyword" content="{{$article->meta_name}}">
<meta name="description" content="{{$article->meta_content}}">
<meta name="title" content="{{$article->meta_title}}">

@stop


@section('h1')
<h1 style='display:none'>{{$article->h1}}</h1>
@stop
