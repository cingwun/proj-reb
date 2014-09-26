@extends('spa._layouts.default')

@section('bodyId')
{{'spa_shareCase_detail'}}
<?php $titleType = 'share'; ?>
@stop


@section('content')
<div id="mainContent" class="fullWidth" role="main">
	<div class="breadcrumb">
		<a href="{{URL::route('spa.index')}}">首頁</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.share')}}">美麗分享</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.share.detail', array($article->id))}}">{{$article->title}}</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<div class="allTop_con">
		<div class="shareTop_pic"><img src="http://placehold.it/500x300"></div>
		<div class="shareTop_con">
			<div class="classList">
				<div><p>課程項目：</p></div>
				<!-- @text for shareTag -->
				<div class="shareTag">
					@foreach($labelService as $ls)
					<a href="#"><div class="shareTag_btn">{{$ls['title']}}</div></a>
					@endforeach
				</div>
			</div>
			<div class="classPro">
				<div><p>專業產品：</p></div>
				<!-- @text for shareTag -->
				@foreach($labelProduct as $lp)
					<a href="#"><div class="shareTag_btn">{{$lp['title']}}</div></a>
				@endforeach
			</div>
			<div class="classDes">
				<!-- @text for productDescription -->
				{{$article->description}}
			</div>
		</div>
	</div>
	<!-- sliderBox -->
	<div class="slider" id="sliderBox">
		<span class="btn-close" alt="關閉">關閉</span>
		<div class="main-title">小護士靠立塑終結萬年小腹</div>
		<div class="photo-num">第<span class="no number">1</span>張&nbsp;/&nbsp;共<span class="number">8</span>張</div>
		<div class="clear"></div>
		<div class="wrapper">
			<ul class="container-images">
				<!--@images for colorbox
				    @text for the lightbox title fetch from following <p>
			    -->
				<?php $cover = json_decode($article->gallery); ?>
				@foreach($cover as $cover)
				<li>
					<a rel="gallery1" href="../img/demo/shareDemo.jpg" title="yo rock">
					<!--@images for shareDemo slider-->
					<img src="{{$cover->image}}?w=130">
					<!-- @text for the list title-->
					<p>{{$cover->text}}</p></a>
				</li>
				@endforeach
			</ul>
		</div><!-- wrapper end -->
		<span class="nex prevCcontrol" id="prev"></span>
		<span class="pre nextCcontrol" id="next"></span>
		<div class="image-block">
			<img src="" class="hidden"/>
			<div class="title hidden"></div>
			<div class="loader hidden"></div>
		</div>
	</div>
	<!-- slider end -->

	<div class="shareCon">
		@foreach($tabs as $t)
		<div class="exempleTag">{{$t['title']}}</div>
		<div class="shareWord">
			{{$t['content']}}
		</div>
		@endforeach
	</div>

	<div class="postNav">
		<div>
			@if(empty($pervArticle))
			<a href="{{URL::route('spa.share')}}">回列表</a>
			@else
			上一篇
			<span class="arrow"></span>
			<a href="{{URL::route('spa.share')}}/{{$prevArticle->id}}">{{$prevArticle->title}}</a>
			@endif
		</div>
		<div>
			@if(!empty($nextArticle))
			下一篇
			<span class="arrow"></span>
			<a href="{{URL::route('spa.share')}}/{{$nextArticle->id}}">{{$nextArticle->title}}</a>
			@endif
		</div>
	</div>
</div>
@stop