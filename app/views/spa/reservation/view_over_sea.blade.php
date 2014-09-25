@extends('spa._layouts.default')

@section('bodyId')
{{'spa_overSea'}}
<?php
$titleType = "oversea";
?>
@stop
@section('content')
@include('spa._partials.widget_setContent')
<div id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="#">海外專區</a><span class="arrow"></span>
		<a href="#">海外預約流程</a>
	</div><!-- ======================== breadcrumb end ======================== -->

	<div class="box">
		<div class="title">
			<p>煥儷集結了資歷豐富的醫療團隊， 讓您可以安心在台灣享受美麗蛻變的過程。歡迎海外貴賓親臨煥儷，感受煥儷尊爵的服務。</p>
		</div>
		<div class="pic">
			<img src="<?=asset('spa/img/sign/process/process_01.jpg');?>"></div>
			<div class="detail">
				<div class="icon"><img src="<?=asset('spa/img/sign/process/oversea_time.png');?>" height="51" width="51"></div>
				<div class="word_time">週一至週六：上午12:30~晚間8:30 (周日休診)</div>
			</div>
			<div class="detail">
				<div class="icon"><img src="<?=asset('spa/img/sign/process/oversea_ask.png');?>" height="51" width="51"></div>
				<div class="word"><p>關於整形美容的項目、估價、行程的預約等等，都歡迎您透過以下的</p><p>方式詢問。</p></div>
			</div>
			<div class="detail">
				<div class=word2>
					<p>電話諮詢：<a href="tel:0225623631">+886-225623631</a></p>
					<p>郵件諮詢：<a href="mailto:rebeauty14@gmail.com">rebeauty14@gmail.com</a></p>
					<p>LINE_ID：rebeauty</p>
					<p>微信：rebeauty_clinic</p>
				</div>
				<div class="QRCode"><img src="<?=asset('spa/img/sign/process/QRCode.png');?>" height="107" width="107"></div>
			</div>
			<div class="detail">
				<div class="icon"><img src="<?=asset('spa/img/sign/process/oversea_trans.png');?>" height="51" width="51"></div>
				<div class="word"><p>煥儷美形診所位於台北捷運淡水線中山站3號出口(步行三分鐘)</p><p>海外貴賓可參考以下交通路線:</p></div>
			</div>
			<div class="pic">
				<img src="<?=asset('spa/img/sign/process/process_05.jpg');?>">
			</div>
		</div>
		<div class="btn"><a href="#"></a></div>
	</div>
</div>
@stop