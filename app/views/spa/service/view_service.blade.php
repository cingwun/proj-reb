@extends('spa._layouts.default')

@section('bodyId')
{{'spa_services'}}
@stop
@section('content')
<div id="mainContent" class="fullWidth" role="main">
	<div class="breadcrumb">
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="#">美麗分享</a><span class="arrow"></span>
		<a href="#" id="last-bc">3D</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<div class="serTitle"><img src="spa/img/sign/service_title.png" height="20" width="200"></div>
	<div id="serList_con">
		@foreach($serviceCats as $serviceCatRow)
		<div class="serLine1">
			@foreach($serviceCatRow as $serviceCat)
			<div class="serList">
				<!-- @image for  serList Image-->
				<div class="serList_pic"><img src="{{$serviceCat['cat']['image']}}?w=215&h=140"></div>
				<div class="serOpt noOpt">
					<!-- @text for serList_btn1 title -->
					<a  href="javascript:void(0)">
						<div class="serList_btn1">{{$serviceCat['cat']['title']}}
							<img class="side_arrow" src="../spa/img/sign/arrow_yellow.png"/>
							<img class="side_arrow active" src="../spa/img/sign/arrow_white.png"/>  
						</div>
					</a>
					<div class="serList_btn2">
						<ul class="btn2_list">
							@foreach($serviceCat['serv'] as $service)
							<!-- @text for serList_btn2 title -->
							<li><a data-src="{{$service['image']}}?w=215&h=140" href="{{$detailURL}}/{{$service['id']}}">{{$service['title']}}</a></li>
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