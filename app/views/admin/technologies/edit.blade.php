@extends('admin._layouts.default')

@section('main')
<h2>修改新技術</h2>
@include('admin._partials.notifications')
<form action="/admin/technologies/{{ $technology->id }}" method="post">
<div class="form-group">
<label class="col-sm-3 control-label" for="title">標題</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="title" size="12" value="{{ $technology->title }}">
</div>
</div>


描述圖片(700 x any)：
{{--Upload Widget Start--}}
<input type="hidden" name="image_path" value="{{$technology->image}}"/>
@include('admin._partials.upload_single_OneClick')
{{--Upload Widget End--}}


<div class="form-group">
<label class="col-sm-3 control-label" for="link">連結</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="link" name="link" size="12" value="{{ $technology->link }}">
</div>
</div>

<div class="form-group">
<label class="radio">
  <input type="radio" name="target" value="_self" @if ($technology->target=='_self') checked @endif>
  原視窗
</label>
<label class="radio">
  <input type="radio" name="target" value="_blank" @if ($technology->target=='_blank') checked @endif>
  開新視窗
</label>
</div>

<br/>

<div class="form-group">
<label class="radio">
  <input type="radio" name="status" value="Y" @if ($technology->status=='Y') checked @endif>
  顯示
</label>
<label class="radio">
  <input type="radio" name="status" value="N" @if ($technology->status=='N') checked @endif>
  隱藏
</label>
</div>

<input type="hidden" name="_method" value="PUT" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">更改</button>
</form>
@stop
