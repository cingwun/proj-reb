@extends('spa._layouts.default')

@section('bodyId')
{{ 'spa_newsPost_detail'}}
<?php $titleType = 'news';?>
@stop

@section('content')

@include('spa._partials.widget_setContent')
<article id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="{{URL::route('spa.index')}}">首頁</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.news')}}">最新消息</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.news.detail', array($article->id))}}">{{$article->title}}</a>
	</div>
	<!-- breadcrumb end -->
	@include('spa._partials.widget_pageTitle')
	@include('spa._partials.widget_socialIcons')
	<!-- pagedetails -->
	<div id="contentInner">
		<!-- @image, for the Post Image -->
		<img src="{{$cover[0]->image}}?w=700&h=430">
		<div class="contentArticle">
			<!-- @text, for Post Content -->
			<p>
				{{$article->content}}
			</p>
			<!-- @(string) text,  for Post Title -->
			<p class="title">{{$article->title}}</p>
		</div>
		<div class="funcBar">
			<a href="#" class="goTop">回上方</a>
		</div>
	</div>
	<!-- pagedetails end -->
	<div class="postNav">
		<div>
			@if(empty($prevArticle))
			<span class="arrow"></span>
			<a href="{{URL::route('spa.news')}}">回列表</a>
			@else
			上一篇	
			<span class="arrow"></span>
			<a href="{{URL::route('spa.news.detail', array($prevArticle->id))}}">{{$prevArticle->title}}</a>
			@endif
		</div>
		<div>
			@if($nextArticle)
			下一篇
			<span class="arrow"></span>
			<a href="{{URL::route('spa.news.detail', array($nextArticle->id))}}">{{$nextArticle->title}}</a>
			@endif
		</div>
	</div>
	<!-- postNav end  -->
</article>
<!--  mainContent end  -->
@stop

@section('head')
<meta name="keyword" content="{{$article->meta_name}}">
<meta name="description" content="{{$article->meta_content}}">
@stop