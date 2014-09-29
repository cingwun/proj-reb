@extends('spa._layouts.default')

@section('bodyId')
{{'index'}}
@stop

@section('content')
<aside id="indexSetContent" class="hotEv">
	<h2 class="titleRp title_spa-hotEv">熱門推薦</h2>
	<img src="http://placehold.it/288x343" alt=""/>  
</aside><!-- ======================== regBox end ======================== -->
<div id="mainContent" role="main">
	<article class="newsList">
		<h2 class="titleRp title_spa-news">最新消息</h2>
		<div class="funcBar"><a href="{{URL::route('spa.news')}}" class="more">more</a></div>
		<ul class="infoList">
			<li>
				<span class="imgWrap"><img src="http://placehold.it/288x343" /></span>
				<div class="newsFirstWrapper">
					<a class="firstLink" href="{{URL::route('spa.news.detail')}}/{{array_get($news[0], 'id')}}">{{ \Text::preEllipsize(strip_tags(array_get($news[0], 'title')), 87) }}</a>
					<time class="firstDate" datetime="{{array_get($news[0], 'open_at')}}">{{array_get($news[0], 'open_at')}}</time>
					<p class="firstArticle">{{ \Text::preEllipsize(strip_tags(array_get($news[0], 'content')), 87) }}<!-- 限兩行 --><a href="{{URL::route('spa.news.detail')}}/{{array_get($news[0], 'id')}}" class="seeMore">詳全文</a></p>
				</div>
			</li>
			@for($i = 1; $i < $newsCount; $i++)
			<li>
				<a href="{{URL::route('spa.news.detail')}}/{{array_get($news[$i], 'id')}}">{{ \Text::preEllipsize(strip_tags(array_get($news[$i], 'title')), 87) }}</a>
				<time datetime="{{array_get($news[$i], 'open_at')}}">{{array_get($news[$i], 'open_at')}}</time>
			</li>
			@endfor
		</ul>
	</article><!-- ======================== newsList end ======================== -->
</div><!-- ======================== mainContent end ======================== -->	

<article class="serviceList">
	<h2 class="titleRp title_spa-service">美麗服務</h2>
	<div class="funcBar"><a href="services.html" class="more">所有服務</a></div>
	<ul>
		@foreach($service as $s)
		<li>
			<a href="{{URL::route('spa.service', array($s->id))}}">
			<img src="{{$s->image}}?w=200"/>
			<h4>{{$s->title}}</h4><!-- 限兩行 -->
			<p class="servContent">{{$s->content}}</p><!-- 限四行 -->
			<p class="more" href="{{URL::route('spa.service', array($s->id))">深入了解</p>
			</a>
		</li>
		@endforeach
	</ul>

</article><!-- ======================== serviceList end ======================== -->

@stop