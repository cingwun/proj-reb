@extends('spa_admin._layouts.default')

@section('title')
新增預約項目
@stop

@section('main')
<form action='{{$writeURL}}' method='post' enctype='multipart/form-data'>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">基本資料</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label>姓名</label>
				<input class="form-control" type="text" name="name" value="@if($action == 'edit'){{$reservation->name}} @endif"/>
			</div>
			<div class="form-group">
				<label>性別</label>
				<div class="radio" class="form-control">
					<label>
						<input type="radio" name="sex"  value="male" @if($action == 'edit' && $reservation->sex == 'male') checked @endif/>男
					</label>
					<label>
						<input type="radio" name="sex"  value="women" @if($action == 'edit' && $reservation->sex == 'women') checked @endif @if($action == 'create') checked @endif/>女
					</label>
				</div>
			</div>
			<div class="form-group">
				<label>國家</label>
				<input class="form-control" type="text" name="country" value="@if($action == 'edit'){{$reservation->country}} @endif" />
			</div>
			<div class="form-group">
				<label>聯絡方式</label>
				<ul class="list-group">
					<li class="list-group-item">
						<label>電話</label>
						<input class="form-control" type="text" name="phone" value="@if($action == 'edit' && isset($contact[0])) {{$contact[0]}}@endif"/>
					</li>
					<li class="list-group-item">
						<label>Line ID</label>
						<input class="form-control" type="text" name="line" placeholder="非必填" value="@if($action == 'edit' && isset($contact[1])) {{$contact[1]}}@endif"/>
					</li>
					<li class="list-group-item">
						<label>WeChat</label>
						<input class="form-control" type="text" name="wechat" placeholder="非必填" value="@if($action == 'edit' && isset($contact[2])) {{$contact[2]}}@endif"/>
					</li>
					<li class="list-group-item">
						<label>QQ</label>
						<input class="form-control" type="text" name="qq" placeholder="非必填" value="@if($action == 'edit' && isset($contact[3])) {{$contact[3]}}@endif"/>
					</li>
				</ul>
			</div>
			<div class="form-group">
				<label>生日</label>
				<div class="input-daterange input-group" id="datepicker">
				    <input type="text" class="input-sm form-control" name="birthday" value="@if($action == 'edit') {{$reservation->birthday}}@endif" />
				</div>
			</div>
			<div class="form-group">
				<label>E-Mail</label>
				<input class="form-control" type="text" name="email" value="@if($action == 'edit') {{$reservation->email}}@endif" />
			</div>
			<div class="form-group">
				<label >預計在台停留時間</label>
				<div>
	                <div class='input-datetime input-group date'>
	                    <input type='text' class="form-control" name="stay_start_date" value="@if($action == 'edit') {{$dateTimeArray['stay_start_date']}}@endif"/>
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
		        </div>
		        <div>到</div>
				<div>
	                <div class='input-datetime input-group date'>
	                    <input type='text' class="form-control" name="stay_exit_date" value="@if($action == 'edit') {{$dateTimeArray['stay_exit_date']}}@endif"/>
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
		        </div>
			</div>
			<div class="form-group">
				<label>希望安排諮詢／療程時間</label>
				<div>
	                <div class='input-datetime input-group date'>
	                    <input type='text' class="form-control" name="service_date" value="@if($action == 'edit') {{$dateTimeArray['service_date']}}@endif"/>
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
		        </div>
			</div>
			<div class="form-group">
				<label>方便聯繫的時間</label><br/>
				<select name="contact_time">
					<option value="morning" @if($action == 'edit' && $reservation->contact_time == "morning")selected@endif>早上</option>
					<option value="noon" @if($action == 'edit' && $reservation->contact_time == "noon")selected@endif>中午</option>
					<option value="afternoon" @if($action == 'edit' && $reservation->contact_time == "afternoon")selected@endif>下午</option>
					<option value="night" @if($action == 'edit' && $reservation->contact_time == "night")selected@endif>晚上</option>
				</select>
			</div>
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">預約諮詢資料</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label>想改善的項目</label>
				<input class="form-control" type="text" name="improve_item" value="@if($action == 'edit') {{$reservation->improve_item}}@endif" />
			</div>
			<div class="form-group">
				<label>其他補充</label>
				<textarea class="form-control" rows="6" name="other_notes">@if($action == 'edit') {{$reservation->other_notes}}@endif</textarea>
			</div>
		</div>
	</div>
	<div class="form-group">
		<input type="hidden" name="action" value="{{$action}}"/>
		<a href='javascript:history.back()' type="button" class="btn btn-danger">取消</a>
		<button class="btn btn-primary btn-submit">編輯完成</button>
	</div>
</from>
@stop

@section('head')
{{ HTML::style(asset('packages/datetimepicker/build/css/bootstrap-datetimepicker.min.css')) }}
{{ HTML::style(asset('packages/timepicker/css/datepicker.css')) }}
@stop

@section('bottom')
{{ HTML::script('/packages/moment/moment.js')}}
{{ HTML::script('/packages/datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}
{{ HTML::script('/packages/timepicker/js/bootstrap-datepicker.js')}}
<script type="text/javascript">
$('.input-datetime').datetimepicker();
$('.input-daterange').datepicker({
	    format: "yyyy-mm-dd",
	    todayBtn: true,
	    language: "zh-TW"
	});

</script>
@stop