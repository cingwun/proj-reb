@extends('spa._layouts.default')

@section('bodyId')
{{'spa_overSea_form'}}
<?php
$titleType = "oversea";
?>
@stop

@section('content')
@include('spa._partials.widget_setContent')
<div id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="{{$indexURL}}">首頁</a><span class="arrow"></span>
		<a href="{{$ovewSeaURL}}">海外專區</a><span class="arrow"></span>
		<a href="#">海外貴賓來台預約表</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<div class="infoList overSeaForm">
		<div class="overTitle"><img src="<?=asset('spa/img/sign/overSea/overSea_form.png');?>" height="20" width="197"></div>
		<!-- @text for infoList titlePost -->
		<p>感謝您即將從海外來到台灣造訪煥儷美顏SPA，為了提供您最好的服務品質，需要您協助提供以下資料，我們將盡快與您聯繫並確認您的預約。</p>
		<div class="overTitle2"><img src="<?=asset('spa/img/sign/overSea/overSea_word.png');?>" height="14" width="124"><font>下欄為必填資料</font></div>

		<form action='{{$writeURL}}' method='post' enctype='multipart/form-data'>
			<label for="name">姓名：</label>
			<input type="text" name="name" maxlength="20"><br/>

			<label>性別：</label>
			<input value='male' type="radio" name="sex" id="man"><label for="sex">男</label>
			<input value='women' type="radio" name="sex" id="fem" checked=""><label for="sex">女</label><br/>

			<label for="country">國家：</label><input type="text" name="country" maxlength="20"><br/>

			<label class="contactWay">聯絡方式：<br/>(至少一種)&nbsp;</label>
			<div class="formW">
				<label for="phone">電話：</label>
				<input type="text" name="phone" class="phone" onkeyup="value=value.replace(/[^\d]/g,'')" maxlength="13">
				<label for="line">Line ID：</label>
				<input type="text" name="line" class="line" placeholder="非必填">
				<label for="wechat">WeChat：</label>
				<input type="text" name="wechat" class="wechat"placeholder="非必填">
				<label for="qq">QQ：</label>
				<input type="text" name="qq" class="qq" placeholder="非必填">
			</div><br/>

			<label for="birth">生日：</label>西元
			<select name="birth_year">
				@for($i=(date("Y")-100) ; $i<=date("Y") ; $i++)
				<option>{{$i}}</option>
				@endfor
			</select>
			<select name="birth_month">
				@for($i=1;$i<=12;$i++)
				<option>{{$i}}</option>
				@endfor
			</select>
			<span> 月</span>
			<select name="birth_day">
				@for($i=1;$i<=31;$i++)
				<option>{{$i}}</option>
				@endfor
			</select>
			<span> 日</span><br/>

			<label for="email">Email：</label><input type="text" name="email" class="email"><br/>

			<label class="contactWay">預計在&nbsp;&nbsp;&nbsp;<br/>台停留時間：</label>
			<label class="dates" for="">日期：</label>
			<input class="datepicker" data-date-format="yyyy/mm/dd" id="open_at" name="stay_start_date" size="9" value="">
			<select name="stay_start_time">
				@for($i=0 ; $i<24 ; $i++)
				<option @if($i=='12')selected @endif>{{$i}}</option>
				@endfor
			</select><span>點 到</span>
			<input class="datepicker" data-date-format="yyyy/mm/dd" id="open_at" name="stay_exit_date" size="9" value="">
			<select name="stay_exit_time">
				@for($i=0 ; $i<24 ; $i++)
				<option @if($i=='12')selected @endif>{{$i}}</option>
				@endfor
			</select><span>點</span><br/>

			<label class="contactWay">希望安排&nbsp;&nbsp;&nbsp;<br/>諮詢／療程時間：</label>
			<label class="dates" for="">日期：</label>
			<input class="datepicker" data-date-format="yyyy/mm/dd" id="open_at" name="service_date" size="9" value="">
			<select>
				@for($i=13 ; $i<21 ; $i++)
				<option>{{$i}}</option>
				@endfor
			</select><span>點</span><br/>

			<label class="contactWay">方便聯繫的時間：</label>
			<select name="contact_time">
				<option value="morning">早上</option>
				<option value="afternoon">下午</option>
				<option value="night">晚上</option>
			</select>

			<div class="overTitle3"><img src="<?=asset('spa/img/sign/overSea/overSea_word02.png');?>" height="14" width="156"></div>
			<label for="ask">您想改善的項目：</label>
			<input type="text" name="improve_item" class="askC" placeholder="非必填"><br/>

			<label for="message">其他補充：</label>
			<textarea name="other_notes" placeholder="非必填"></textarea><br/>

			<div class="funcBar">
				<button type='submit' class="sent"><!-- 送出預約 --></button>
				<button type='reset' class="rewrite"><!-- 重新填寫 --></button>
			</div>
		</form>
	</div><!-- ======================== infoList end ======================== -->
</div>
@stop
@section('bottom')
{{ HTML::script('spa/js/reservation/js_form.js'); }}
{{ HTML::script('/packages/timepicker/js/bootstrap-datepicker.js')}}
<script type="text/javascript">
var form = _reservation({el: '.overSeaForm', mode: 'page'});
$(function() {
  // $( "#open_at" ).datepicker({ dateFormat: "yy-mm-dd" });
  $('.datepicker').datepicker();
});
</script>
@stop

@section('head')
{{ HTML::style(asset('packages/timepicker/css/datepicker.css')) }}
@stop
