@extends('spa._layouts.default')

@section('bodyId')
{{'spa_about'}}
<?php $titleType = 'about'; ?>
@stop

@section('content')
@include('spa._partials.widget_setContent')
<article id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="{{URL::route('spa.index')}}">首頁</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.about')}}">關於煥麗</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.about', array('id'=>array_get($article, 'id'), 'title'=>array_get($article, 'title')))}}">{{ array_get($article, 'title')}}</a>
		<!-- <a href="#">經營理念</a> -->
	</div>
	<!-- breadcrumb end -->
	@include('spa._partials.widget_pageTitle')
	<!-- pagedetails -->
	<div id="contentInner">
		<!-- @image, for the Post Image -->
		<!-- <img src="http://placehold.it/680x430"> -->
		<img src="@if($cover){{$cover[0]->image}}?w=680@endif">
		<div class="contentArticle">
			<!-- @text, for About Content -->
			<p>{{ array_get($article, 'content', '對不起，目前沒有內容')}}</p>
		</div>
	</div>
	<!-- pagedetails end -->
</article><!--  mainContent end  -->
@stop

@section('head')
<meta name="keyword" content="{{array_get($article, 'meta_name')}}">
<meta name="description" content="{{array_get($article, 'meta_content')}}">
@stop

@if($article->meta_title!="")
	@section('title')
	<title>{{$article->meta_title}}</title>
	@stop
@endif

@section('h1')
<h1 style="display:none">{{array_get($article, 'h1')}}</h1>
@stop
