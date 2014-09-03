@extends('aesthetics._layouts.default')

@section('bodyId'){{'news'}}@stop

@section('mainBanner')
@stop

@section('mainContent')
<article id="mainContent" class="listBox" role="main">
	<h1 class="titleRp h2_news">最新消息</h1>

@include('aesthetics._partials.banner',array('size'=>'small'))

	<div class="breadcrumb">
		<a href="/">首頁</a><span class="arrow"></span>
		<a href="/news">最新消息</a>
	</div><!-- ======================== breadcrumb end ======================== -->

@foreach($articles as $article)
	<article>
		<h2><a href="{{ URL::to('articles/'.$article->id) }}">{{ $article->title }}</a></h2>
                <p>
                {{ \Text::preEllipsize(strip_tags($article->description), 120) }}
                </p>
		<div class="listInfo">
		<time datetime="{{ date('Y-m-d',time($article->created_at)) }}"><span>發表日期：</span>{{ $article->open_at }}</time>
			<a class="btn" href="{{ URL::to('articles/'.$article->id) }}">詳全文</a>
		</div>
	</article>
@endforeach

@if ($articles->getLastPage() > 1)
	<div class="pager">
	<a class="firstPage pagerIcon" href="{{ URL::to('news?page=1') }}">第一頁</a>
	<a class="prevPage pagerIcon" href="@if($articles->getCurrentPage()>1) {{ URL::to('news?page='.($articles->getCurrentPage()-1)) }} @else javascript:;@endif">上一頁</a>
	&nbsp;&nbsp;
@for ($i = 1; $i <= $articles->getLastPage(); $i++)
@if($i == $articles->getCurrentPage())
	<span>{{ $i }}</span>
@else
<a href="{{ URL::to('news?page='.$i) }}">{{ $i }}</a>
@endif
@endfor
	&nbsp;&nbsp;
	<a class="nextPage pagerIcon" href="@if($articles->getCurrentPage()<$articles->getLastPage()) {{ URL::to('news?page='.($articles->getCurrentPage()+1)) }} @else javascript:;@endif">下一頁</a>
	<a class="lastPage pagerIcon" href="{{ URL::to('news?page='.$articles->getLastPage()) }}">最後一頁</a>
</div><!-- ======================== pageNav end ======================== -->
@endif

</article><!-- ======================== mainContent end ======================== -->
@stop


@section('bottomContent')
@stop
