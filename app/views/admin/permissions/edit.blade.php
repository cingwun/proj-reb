@extends('admin._layouts.default')

@section('main')
<h2>修改權限</h2>
@include('admin._partials.notifications')
<form action="/admin/permissions/{{ $permission->id }}" method="post">
<div class="form-group">
<label class="col-sm-3 control-label" for="name">權限</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="name" name="name" size="12" value="{{ $permission->name }}" readOnly>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label" for="title">標題</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="title" size="12" value="{{ $permission->title }}">
</div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label" for="status">狀態</label>
<label class="radio inline">
  <input type="radio" name="status" value="1" @if ($permission->status==1) checked="checked" @endif> ON
</label>
<label class="radio inline">
  <input type="radio" name="status" value="0" @if ($permission->status==0) checked="checked" @endif> OFF
</label>
</div>

<div class="form-group">

</div>
<input type="hidden" name="_method" value="PUT" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">更改</button>
</form>
@stop
