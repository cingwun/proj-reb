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
	<div class="serTitle"><img src="../spa/img/sign/service_title.png" height="20" width="200"></div>
	<div id="serList_con">
		<div class="serLine1">
			<div class="serList">
				<!-- @image for  serList Image-->
				<div class="serList_pic"><img src="http://placehold.it/215x140"></div>
				<div class="serOpt noOpt">
					<!-- @text for serList_btn1 title -->
					<a  href="javascript:void(0)">
						<div class="serList_btn1">111
							<img class="side_arrow" src="../spa/img/sign/arrow_yellow.png"/>
							<img class="side_arrow active" src="../spa/img/sign/arrow_white.png"/>  
						</div>
					</a>
					<div class="serList_btn2">
						<ul class="btn2_list">
							<!-- @text for serList_btn2 title -->
							<li><a data-src="http://placehold.it/215x140/C00" href="#">111111111</a></li>
							<li><a data-src="http://placehold.it/215x140/FFF" href="#">111111111</a></li>
							<li><a data-src="http://placehold.it/215x140/000" href="#">111111111</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="serLine2">
			<div class="serList">
				<!-- @image for  serList Image-->
				<div class="serList_pic"><img src="http://placehold.it/215x140"></div>
				<div class="serOpt">
					<!-- @text for serList_btn1 title -->
					<a  href="javascript:void(0)">
						<div class="serList_btn1">111
							<img class="side_arrow" src="../spa/img/sign/arrow_yellow.png"/>
							<img class="side_arrow active" src="../spa/img/sign/arrow_white.png"/>
						</div>
					</a>
					<div class="serList_btn2">
						<ul class="btn2_list">
							<!-- @text for serList_btn2 title -->
							<li><a data-src="http://placehold.it/215x140/C00" href="#">111111111</a></li>
							<li><a data-src="http://placehold.it/215x140/FFF" href="#">111111111</a></li>
							<li><a data-src="http://placehold.it/215x140/000" href="#">111111111</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop