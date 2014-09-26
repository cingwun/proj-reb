@extends('spa._layouts.default')

@section('bodyId')
{{'spa_newsPost'}}
<?php $titleType = 'news'; ?>
@stop

@section('content')
@include('spa._partials.widget_setContent')
<article id="mainContent"  role="main">
	<div class="breadcrumb">
		<a href="{{URL::route('spa.index')}}">首頁</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.news')}}">最新消息</a>
	</div>
	<!-- breadcrumb end -->
	<!-- pagedetails -->
	@foreach($news as $n)
	<article>
		<h2>
			<!-- @(string) text,  for Post Title -->
			<a href="{{URL::route('spa.news.detail', array(array_get($n, 'id')))}}"> {{$n['title']}}</a>
		</h2>
		<!-- @text, for Post Content -->
		<p>{{ \Text::preEllipsize(strip_tags($n['content']), 120) }}</p>
		<div class="listInfo">
			<!-- @image, for the Post Image -->
			<img src="../spa/img/sign/arrow_l.png" height="12" width="6">
			<time datetime="2013-12-08">
			<span>發表日期：
			<!-- @date, for Post Date -->
			</span>{{$n['open_at']}}</time>
			<!-- @href, for the Post Link -->
			<a class="btn" href="{{URL::route('spa.news.detail', array(array_get($n, 'id')))}}">詳全文</a>
		</div>
	</article>
	@endforeach
	<!-- pagedetails end -->
	<%= render :partial => "pager" %>
	@include('spa._partials.widget_pager')
</article><!--  mainContent end  -->
@stop