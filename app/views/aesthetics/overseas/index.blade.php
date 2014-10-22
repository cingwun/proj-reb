@extends('aesthetics._layouts.default')

@section('bodyId'){{'about'}}@stop

@section('mainBanner')
@include('aesthetics._partials.banner',array('size'=>'medium'))
@stop

@section('mainContent')
<article id="mainContent" role="main">
	<div class="breadcrumb">
		<a href="/">首頁</a><span class="arrow"></span>
		<span>海外專區</span><span class="arrow"></span>
		<span>{{ $article->title }}</span>
	</div><!-- ======================== breadcrumb end ======================== -->

	<h1><img src="/aesthetics/img/sign/icon/reservation.png" alt="about" width="109" height="56" />&nbsp;&nbsp;{{ $article->title }}</h1>

	<div class="contentInfo">
		<time datetime="{{ date('Y-m-d',time($article->created_at)) }}">發表日期：{{ date('Y/m/d',time($article->created_at)) }}</time>
		<span>瀏覽：{{ $article->views }}</span>
	</div>

	<div class="contentArticle ckeditor-style">
                {{ $article->description }}
	</div>
	<div class="funcBar"><a href="#" class="goTop">回上方</a></div>
</article><!-- ======================== mainContent end ======================== -->
@stop


@section('bottomContent')
@stop

@section('head')
<meta name="keyword" content="{{$article->meta_name}}">
<meta name="description" content="{{$article->meta_content}}">
<meta name="title" content="{{$article->meta_title}}">

@stop