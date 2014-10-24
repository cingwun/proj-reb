@extends('spa_admin._layouts.default')

@section('title')
修改使用者
@stop

@section('main')

@include('admin._partials.notifications')

<form action="/admin/users/{{ $user->id }}" method="post" class="col-lg-6">

	<div class="form-group">
		<label>姓名</label>
		<div>
			<input type="text" class="form-control" id="last_name" name="last_name" size="12" value="{{ $user->last_name }}">
		</div>
	</div>

	<div class="form-group">
		<label>密碼</label>
		<div>
			<input type="password" class="form-control" id="password" name="password" size="12" value="">
		</div>
	</div>

	<div class="form-group">
		<label>身份</label><br>
		@foreach(Sentry::findAllGroups() as $group)
		<label class="radio-inline">
			<input type="radio" name="group" value="{{ $group->id }}" @foreach ($user->groups()->get() as $u_group) @if($group->id == $u_group->id) checked="checked"@endif @endforeach >
			{{ $group->name }}
		</label>
		@endforeach
	</div>

	<div class="form-group">
		<label>狀態</label><br>
		<label class="radio-inline">
			<input type="radio" name="activated" value="1" @if($user->activated) checked="checked" @endif > ON
		</label>
		<label class="radio-inline">
			<input type="radio" name="activated" value="0" @if(!$user->activated) checked="checked" @endif > OFF
		</label>
	</div>

	<div>
		<input type="hidden" name="_method" value="PUT" />
		<button class="btn btn-danger" type="button" onclick="history.back();">取消</button> <button class="btn btn-primary">更改</button>
	</div>

</form>
@stop
