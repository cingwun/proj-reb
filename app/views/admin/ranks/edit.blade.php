@extends('admin._layouts.default')

@section('main')
<h2>修改排行榜</h2>
@include('admin._partials.notifications')
<form action="/admin/ranks/{{ $rank->id }}" method="post">
<div class="form-group">
<label class="col-sm-3 control-label" for="title">標題</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="title" size="12" value="{{ $rank->title }}">
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label" for="link">連結</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="link" name="link" size="12" value="{{ $rank->link }}">
</div>
</div>

<div class="form-group">
<label class="radio">
  <input type="radio" name="target" value="_self" @if ($rank->target=='_self') checked="checked" @endif>
  原視窗
</label>
<label class="radio">
  <input type="radio" name="target" value="_blank" @if ($rank->target=='_blank') checked="checked" @endif>
  開新視窗
</label>
</div>

<div class="form-group">

</div>
<input type="hidden" name="_method" value="PUT" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">更改</button>
</form>
@stop
