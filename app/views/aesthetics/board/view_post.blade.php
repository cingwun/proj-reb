@extends('aesthetics._layouts.default')

@section('bodyId'){{'messagePost'}}@stop

@section('mainBanner')
    @include('aesthetics._partials.banner', array('size'=>'medium'))
@stop

@section('mainContent')
<article id="mainContent" role="main" class="commentList">
	<div class="breadcrumb">
		<a href="/">首頁</a><span class="arrow"></span>
		<a href="<?php echo route('frontend.board.list')?>">美麗留言</a><span class="arrow"></span>
		<a href="#">我要發問</a>
	</div>
	<!-- breadcrumb end -->

	<h1><img src="<?php echo asset('aesthetics/img/sign/icon/message.png')?>" alt="about" width="109" height="56" />&nbsp;&nbsp;<?php echo \HTML::decode($board->topic)?></h1>

	<div class="msgBox que">
		<h3>問題內容</h3>
		<?php if ($isShowPost):?>
		<p><?php echo \HTML::decode($board->content)?></p>
		<div class="msgInfo">
			<a href="#" class="name"><?php echo \HTML::decode($board->name)?></a><p>於</p>
			<time datetime="2013-12-01"><?php echo $board->d?></time><p>提出問題</p>
		</div>
		<?php else:?>
		<p>私密留言僅發表人可觀看，如欲觀看請登入會員</p>
		<?php endif;?>
	</div>
	<!-- msgBox que end -->

	<div class="msgBox ans">
		<h3>診所回覆</h3>
		<?php if ($reply!==null):?>
		<?php 	if ($isShowPost):?>
			<p><?php echo \HTML::decode($reply->content)?></p>
			<?php if (sizeof($tags)>0): ?>
			<div class="tags">
				<h4>延伸閱讀：</h4>
				<?php foreach($tags as &$t):?>
				<a href="<?php echo route('frontend.service_faq.article', array('type'=>'faq', 'id'=>$t->id))?>"><?php echo $t->title?></a>
				<?php endforeach;?>
			</div>
			<?php endif;?>
			<div class="msgInfo">
				<a href="#" class="name">煥儷美形診所</a><p>於</p>
				<time datetime="2013-12-01"><?php echo $reply->d?></time><p>回覆問題</p>
			</div>
		<?php 	else:?>
			<p>這是私密回覆，僅供發表人觀看</p>
		<?php   endif;?>
		<?php else:?>
			<p>該留言尚未回覆，將儘快回覆。</p>
		<?php endif;?>
	</div>
	<!-- msgBox ans end -->

	<div class="postNav">
		<?php if (($prev=$list['prev'])!=null):?>
		<div>上一篇<span class="arrow"></span><a href="<?php echo route('frontend.board.post', array($prev->id))?>"><?php echo $prev->topic?></a></div>
		<?php endif;?>
		<?php if (($next=$list['next'])!=null):?>
		<div>下一篇<span class="arrow"></span><a href="<?php echo route('frontend.board.post', array($next->id))?>"><?php echo $next->topic?></a></div>
		<?php endif;?>
	</div>
	<!-- postNav end -->

	<div class="funcBar">
	<a href="<?php echo route('frontend.board.ask')?>" class="">我要發問</a>
	<a href="javascript:window.history.back();" class="">返回美麗留言列表</a></div>
</article>
<!-- mainContent end -->

@stop

@section('aside')
	{{-- 美麗排行 --}}
	@include('aesthetics._partials.sidebar_rank')
@stop