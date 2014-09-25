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
		<a href="{{URL::route('spa.about', array(array_get($article, 'id')))}}">{{ array_get($article, 'title')}}</a>
		<!-- <a href="#">經營理念</a> -->
	</div>
	<!-- breadcrumb end -->
	@include('spa._partials.widget_pageTitle')
	<!-- pagedetails -->
	<div id="contentInner">
		<!-- @image, for the Post Image -->
		<!-- <img src="http://placehold.it/680x430"> -->
		<div class="contentArticle">
			<!-- @text, for About Content -->
			<p>{{ array_get($article, 'content', '對不起，目前沒有內容')}}</p>
		</div>
	</div>
	<!-- pagedetails end -->
</article><!--  mainContent end  -->
@stop