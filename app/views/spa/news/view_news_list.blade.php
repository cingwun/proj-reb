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
	@foreach($news as $index=>$n)
	<article>
		<h2>
			<!-- @(string) text,  for Post Title -->
			<a href="{{URL::route('spa.news.detail', array('id'=>$n->id, 'title'=>Urlhandler::encode_url($n->title)))}}"> {{$n->title}}</a>
		</h2>
		<!-- @text, for Post Content -->
		<p>{{ \Text::preEllipsize(strip_tags($n->content), 120) }}</p>
		<div class="listInfo">
			<!-- @image, for the Post Image -->
			<img src="../spa/img/sign/arrow_l.png" height="12" width="6">
			<time datetime="2013-12-08">
			<span>發表日期：
			<!-- @date, for Post Date -->
			</span>{{$n->open_at}}</time>
			<!-- @href, for the Post Link -->
			<a class="btn" href="{{URL::route('spa.news.detail', array($n->id, 'title'=>Urlhandler::encode_url($n->title)))}}">詳全文</a>
		</div>
	</article>
	@endforeach
	<!-- pagedetails end -->

	@if ($news->getLastPage() > 1)
	<div class="pager">
		<a class="firstPage pagerIcon" href="{{ URL::to('news?page=1') }}">第一頁</a>
		<a class="prevPage pagerIcon" href="@if($news->getCurrentPage()>1) {{ URL::to('news?page='.($news->getCurrentPage()-1)) }} @else javascript:;@endif">上一頁</a>
		&nbsp;&nbsp;
		@for ($i = 1; $i <= $news->getLastPage(); $i++)
		@if($i == $news->getCurrentPage())
			<span>{{ $i }}</span>
		@else
		<a href="{{ URL::to('news?page='.$i) }}">{{ $i }}</a>
		@endif
		@endfor
			&nbsp;&nbsp;
		<a class="nextPage pagerIcon" href="@if($news->getCurrentPage()<$news->getLastPage()) {{ URL::to('news?page='.($news->getCurrentPage()+1)) }} @else javascript:;@endif">下一頁</a>
		<a class="lastPage pagerIcon" href="{{ URL::to('news?page='.$news->getLastPage()) }}">最後一頁</a>
	</div>
	@endif
	<!-- ======================== pageNav end ======================== -->

</article><!--  mainContent end  -->
@stop
