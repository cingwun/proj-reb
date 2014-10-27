@extends('spa_admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-dashboard"></i> 系統管理 - 新增使用者
&nbsp;<span class="btn btn-default" onclick="window.history.back();">回上一頁</span>
@stop

@section('main')

@include('admin._partials.notifications')

<form action="/admin/users" method="post" class="col-lg-4">

	<div class="form-group">
		<label>帳號 (Email)</label>
		<div>
			<input type="text" class="form-control" id="email" name="email" size="12" value="{{ Input::old('email') }}">
		</div>
	</div>

	<div class="form-group">
		<label>密碼</label>
		<div>
			<input type="password" class="form-control" id="password" name="password" size="12">
		</div>
	</div>

	<div class="form-group">
		<label>姓名</label>
		<div>
			<input type="text" class="form-control" id="last_name" name="last_name" size="12" value="{{ Input::old('last_name') }}">
		</div>
	</div>

	<div class="form-group">
		<label>身份</label><br>
		@foreach(Sentry::findAllGroups() as $group)
		<label class="radio-inline">
			<input type="radio" name="group" value="{{ $group->id }}">
			{{ $group->name }}
		</label>
		@endforeach
	</div>

	<input type="hidden" name="_method" value="POST" />
	<button class="btn btn-danger" type="button" onclick="history.back();">取消</button> <button class="btn btn-primary">新增</button>

</form>
@stop
