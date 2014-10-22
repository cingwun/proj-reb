@extends('aesthetics._layouts.default')

@section('bodyId'){{'news'}}@stop

@section('mainBanner')
@stop

@section('mainContent')
<article id="mainContent" class="postBox" role="main">
	<h1 class="titleRp h2_news">最新消息</h1>
	<div class="breadcrumb">
		<a href="/">首頁</a><span class="arrow"></span>
		<a href="/news">最新消息</a><span class="arrow"></span>
		<span>{{ $article->title }}</span>
	</div><!-- ======================== breadcrumb end ======================== -->

	<h2>{{ $article->title }}</h2>
	<div class="contentInfo">
		<time datetime="{{ date('Y-m-d',time($article->created_at)) }}">發表日期：{{ $article->open_at }}</time>
		<span>瀏覽：{{ $article->views }}</span>
	</div><!-- ======================== contentInfo end ======================== -->

	<div class="contentArticle ckeditor-style">
		{{ $article->description }}
	</div>
	<div class="funcBar"><a href="#" class="goTop">回上方</a></div>
	<div class="postNav">
@if ($prev)
		<div>上一篇<span class="arrow"></span><a href="{{ URL::to('articles/'.$prev->id) }}">{{ $prev->title }}</a></div>
@endif
@if ($next)
		<div>下一篇<span class="arrow"></span><a href="{{ URL::to('articles/'.$next->id) }}">{{ $next->title }}</a></div>
@endif
	</div><!-- ======================== postNav end ======================== -->
</article><!-- ======================== mainContent end ======================== -->
@stop

@section('bottomContent')
@stop

@section('head')
<meta name="keyword" content="{{$article->meta_name}}">
<meta name="description" content="{{$article->meta_content}}">
<meta name="title" content="{{$article->meta_title}}">

@stop