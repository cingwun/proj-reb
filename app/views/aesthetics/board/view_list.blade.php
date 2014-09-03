@extends('aesthetics._layouts.default')

@section('bodyId'){{'message'}}@stop

@section('mainBanner')
    @include('aesthetics._partials.banner', array('size'=>'medium'))
@stop

@section('mainContent')
<article id="mainContent" role="main" class="commentList">
	<div class="breadcrumb">
		<a href="/">首頁</a><span class="arrow"></span>
		<a href="<?php echo route('frontend.board.list')?>">美麗留言</a>
	</div>
	<!-- breadcrumb end -->

	<h2 class="titleRp h2_comment">美麗留言</h2>
	<div class="funcBar"><a href="<?php echo route('frontend.board.ask')?>">我要發問</a></div>
	<table class="infoList">
		<thead>
			<tr>
				<th class="postTime">發表時間</th>
				<th>問題</th>
				<th class="postUser">發表人</th>
				<th>瀏覽人數</th>
			</tr>
		</thead>
		<tbody>
			<!-- loop -->
			<?php foreach($boards as $b):?>
			<tr>
				<td><time datetime="<?php echo $b->d?>"><?php echo $b->d?></time></td>
				<td><a href="<?php echo route('frontend.board.post', array($b->id))?>"><span><?php echo $b->topic?></span></a></td>
				<td class="userName"><?php echo $b->name?></td>
				<td class="viewCount"><?php echo $b->count_num?></td>
			</tr>
			<?php endforeach;?>
			<!-- loop -->
		</tbody>
	</table>

	@include('aesthetics._partials.widget_pager', $wp)
</article>
<!-- mainContent end -->
@stop

@section('aside')
	{{-- 美麗排行 --}}
	@include('aesthetics._partials.sidebar_rank')
@stop