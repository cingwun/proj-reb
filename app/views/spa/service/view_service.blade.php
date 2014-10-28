@extends('spa._layouts.default')

@section('bodyId')
{{'spa_services'}}
@stop

@section('content')
<div id="mainContent" class="fullWidth" role="main">
	<div class="breadcrumb">
		<a href="{{$indexURL}}">首頁</a><span class="arrow"></span>
		<a href="">服務項目</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<div class="serTitle"><img src="<?=asset('spa/img/sign/service_title.png')?>" height="20" width="200"></div>
	<div id="serList_con">
		@foreach($serviceCats as $serviceCatRow)
		<div class="serLine1">
			@foreach($serviceCatRow as $serviceCat)
			<div class="serList">
				<!-- @image for  serList Image-->
				<div class="serList_pic"><img src="{{$serviceCat['cat']['image']}}?w=215&h=140" alt="{{$serviceCat['cat']['image_desc']}}"></div>
				<div class="serOpt noOpt">
					<!-- @text for serList_btn1 title -->
					<a  href="javascript:void(0)">
						<div class="serList_btn1">{{\Text::preEllipsize(strip_tags($serviceCat['cat']['title']), 10)}}
							<img class="side_arrow" src="../spa/img/sign/arrow_yellow.png"/>
							<img class="side_arrow active" src="../spa/img/sign/arrow_white.png"/>
						</div>
					</a>
					<div class="serList_btn2">
						<ul class="btn2_list">
							@foreach($serviceCat['serv'] as $service)
							<!-- @text for serList_btn2 title -->
							<li><a data-src="{{$service['image']}}?w=215&h=140" href="{{URL::route('spa.service.detail', array('id'=>$service['id'], 'title'=>Urlhandler::encode_url($service['title'])))}}">{{\Text::preEllipsize(strip_tags($service['title']), 10)}}</a></li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		@endforeach
	</div>
</div>
@stop
