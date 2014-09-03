@extends('admin._layouts.default')

@section('main')
<h2>新增使用者</h2>
@include('admin._partials.notifications')
<form action="/admin/users" method="post">
<div class="form-group">
<label class="col-sm-3 control-label" for="email">帳號 (Email)</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="email" name="email" size="12" value="{{ Input::old('email') }}">
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label" for="password">密碼</label>
<div class="col-sm-5">
<input type="password" class="form-control" id="password" name="password" size="12">
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label" for="last_name">姓名</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="last_name" name="last_name" size="12" value="{{ Input::old('last_name') }}">
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label" for="group">身份</label>
@foreach(Sentry::findAllGroups() as $group)
<label class="radio inline">
  <input type="radio" name="group" value="{{ $group->id }}">
  {{ $group->name }}
</label>
@endforeach
</div>


<input type="hidden" name="_method" value="POST" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">新增</button>
</form>
@stop
