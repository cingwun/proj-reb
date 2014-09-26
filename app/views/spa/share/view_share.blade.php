@extends('spa._layouts.default')

@section('bodyId')
{{'spa_shareCase'}}
<?php $titleType = 'share'; ?>
@stop

@section('content')
<div id="mainContent" class="fullWidth" role="main">
	<div class="breadcrumb">
		<a href="{{URL::route('spa.index')}}">首頁</a>
		<span class="arrow"></span>
		<a href="{{URL::route('spa.share')}}">美麗分享</a>
		<span class="arrow"></span>
	</div><!-- ======================== breadcrumb end ======================== -->
	<!-- isotope start -->
	<div id="isoCon">
		@foreach($shares as $share)
		<a href="{{URL::route('spa.share.detail', array($share['share']['id']))}}">
		<div class="isoItem">
			<div class="itemWrapper" style="background:{{$share['share']['background_color']}}">
				<div class="itemTop">
					<!-- @text for  each shareCase title -->
					<div class="itemTop_title">{{$share['share']['title']}}</div>
					<div class="itemTop_pic">
						<!-- @img for each shareCase imgs-->
						<?php $cover = json_decode($share['share']['cover']); ?>
						<img src="" data-src="{{$cover[0]->image}}?w=209" alt="" class="img-rounded">
					</div>
					<!-- @strings for each shareCase description -->
					<div class="itemTop_con">{{$share['share']['description']}}</div>
				</div>
				<!-- @text for each shareCase class list -->
				<div class="itemDown">
					課程項目:@foreach($share['labelProduct'] as $l){{$l['title']." "}}@endforeach
							 @foreach($share['labelService'] as $l){{$l['title']." "}}@endforeach
				</div>
			</div>
		</div>
		</a>
		@endforeach
	</div>
	<!-- isotope end -->
</div>
@stop