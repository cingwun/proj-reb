@extends('spa._layouts.default')

@section('bodyId')
{{'index'}}
@stop

@section('content')
<aside id="indexSetContent" class="hotEv">
	<h2 class="titleRp title_spa-hotEv">熱門推薦</h2>

	<img src="@if($cover){{asset($cover[0]->image)}}?w=288&h=343@endif" alt=""/>
</aside><!-- ======================== regBox end ======================== -->
<div id="mainContent" role="main">
	<article class="newsList">
		<h2 class="titleRp title_spa-news">最新消息</h2>
		<div class="funcBar"><a href="{{URL::route('spa.news')}}" class="more">more</a></div>
		@if($news)
		<ul class="infoList">
			@if($cover)
			<li>
				<span class="imgWrap"><img src="{{$cover[0]->image}}?w=100" /></span>
				<div class="newsFirstWrapper">
					<a class="firstLink" href="{{URL::route('spa.news.detail')}}/{{array_get($news[0], 'id')}}">{{ \Text::preEllipsize(strip_tags(array_get($news[0], 'title')), 87) }}</a>
					<time class="firstDate" datetime="{{array_get($news[0], 'open_at')}}">{{array_get($news[0], 'open_at')}}</time>
					<p class="firstArticle">{{ \Text::preEllipsize(strip_tags(array_get($news[0], 'content')), 87) }}<!-- 限兩行 --><a href="{{URL::route('spa.news.detail')}}/{{array_get($news[0], 'id')}}" class="seeMore">詳全文</a></p>
				</div>
			</li>
			@endif
			@for($i = 1; $i < $newsCount; $i++)
			<li>
				<a href="{{URL::route('spa.news.detail')}}/{{array_get($news[$i], 'id')}}">{{ \Text::preEllipsize(strip_tags(array_get($news[$i], 'title')), 87) }}</a>
				<time datetime="{{array_get($news[$i], 'open_at')}}">{{array_get($news[$i], 'open_at')}}</time>
			</li>
			@endfor
		</ul>
		@else
		<h1>目前沒有資料</h1>
		@endif
	</article><!-- ======================== newsList end ======================== -->
</div><!-- ======================== mainContent end ======================== -->

<article class="serviceList">
	<h2 class="titleRp title_spa-service">美麗服務</h2>
	<div class="funcBar"><a href="{{URL::route('spa.service')}}" class="more">所有服務</a></div>
	<ul>
		@if($service)
		@foreach($service as $s)
		<li>
			<a href="{{URL::route('spa.service.detail', array($s['id']))}}">
			<img src="{{$s['image'] }}?w=200"/>
			<h4>{{ \Text::preEllipsize(strip_tags($s['title']), 26) }}</h4><!-- 限兩行 -->
			<p class="servContent">{{ \Text::preEllipsize(strip_tags($s['image_desc']), 52) }}</p><!-- 限四行 -->
			<p class="more">深入了解</p>
			</a>
		</li>
		@endforeach
		@endif
	</ul>

</article><!-- ======================== serviceList end ======================== -->

@stop