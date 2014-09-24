@extends('spa._layouts.default')

@section('bodyId')
{{'spa_service_detail'}}
@stop

@section('content')
<div id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="#">服務項目</a><span class="arrow"></span>
		<a href="#">臉部保養</a><span class="arrow"></span>
		<a href="#">活氧特效嫩膚護理</a>
	</div><!-- ======================== breadcrumb end ======================== -->

	<!-- pagedetails -->
	<div id="contentInner">
		<!-- @image, for the Post Image -->
		<img src="http://placehold.it/680x430">
		<div class="contentArticle">
			<!-- @text, for Service Content -->
			<p></p>
		</div>
	</div>
	<div id="hotClasses">
		<div class="bar">
			<img src="<?=asset('spa/img/sign/hotClass.jpg')?>" height="34" width="700">
		</div>
		<div class="class">
			<div class="hotClass">
				<!-- @image for Service Hotclass Image -->
				<div class="pic"><img src="http://placehold.it/160x104"></div>
				<!-- @text, for Service Hotclass Title -->
				<div class="title"><a href="#"></a></div>
				<!-- @text, for Service Hotclass Content -->
				<div class="content"><a href="#">(more)</a></div>
			</div>
		</div>
	</div>
	<!-- pagedetails end -->
</div>
</div>
@stop
