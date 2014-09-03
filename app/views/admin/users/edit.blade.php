@extends('admin._layouts.default')

@section('main')
<h2>修改使用者</h2>
@include('admin._partials.notifications')
<form action="/admin/users/{{ $user->id }}" method="post">
<div class="form-group">
<label class="col-sm-3 control-label" for="last_name">姓名</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="last_name" name="last_name" size="12" value="{{ $user->last_name }}">
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label" for="group">身份</label>
@foreach(Sentry::findAllGroups() as $group)
<label class="radio inline">
  <input type="radio" name="group" value="{{ $group->id }}" @foreach ($user->groups()->get() as $u_group) @if($group->id == $u_group->id) checked="checked"@endif @endforeach >
  {{ $group->name }}
</label>
@endforeach
</div>

<div>
<label class="col-sm-3 control-label" for="group">狀態</label>
<label class="radio inline">
  <input type="radio" name="activated" value="1" @if($user->activated) checked="checked" @endif > ON
</label>
<label class="radio inline">
  <input type="radio" name="activated" value="0" @if(!$user->activated) checked="checked" @endif > OFF
</label>
</div>

<input type="hidden" name="_method" value="PUT" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">更改</button>
</form>
@stop
