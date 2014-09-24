@extends('spa._layouts.default')

@section('bodyId')
{{'spa_about'}}
@stop

@section('content')
@include('spa._partials.widget_setContent')
<article id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="#">關於煥麗</a><span class="arrow"></span>
		@foreach($article as $article)
		<a href="#">{{ array_get($article, 'title')}}
		@endforeach
		<!-- <a href="#">經營理念</a> -->
	</div>
	<!-- breadcrumb end -->
	@include('spa._partials.widget_pageTitle')
	<!-- pagedetails -->
	<div id="contentInner">
		<!-- @image, for the Post Image -->
		<img src="http://placehold.it/680x430">
		<div class="contentArticle">
			<!-- @text, for About Content -->
			<p>文章文章文章文章文章文章文章文章文章內容內容內容內容內容內容內容</p>
		</div>
	</div>
	<!-- pagedetails end -->
</article><!--  mainContent end  -->
@stop