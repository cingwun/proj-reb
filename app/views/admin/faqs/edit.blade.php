@extends('admin._layouts.default')

@section('main')
<h2>編輯常見問題</h2>
@include('admin._partials.notifications')
<form name="form1" action="/admin/faqs/{{$model->id}}" method="post" enctype="multipart/form-data">
<div class="form-group">
<label class="col-sm-3 control-label" for="title">標題</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="title" size="12" value="<?=$model->title?>">
</div>
</div>

描述圖片： (300x300以下)
<input type="hidden" name="image_path" value="{{$model->image}}"/>
@include('admin._partials.upload_single_OneClick')

<div class="form-group">
<label class="col-sm-3 control-label" for="link">說明文字</label>
<div class="col-sm-7">
	<textarea class="form-control" name="content">{{$model->content}}</textarea>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label" for="title">發表日期</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="date" name="date" size="12" value="<?php echo date("Y-m-d",strtotime($model->updated_at));?>">
</div>

<div class="form-group">
	<label class="col-sm-3 control-label" for="link">所屬分類</label>
<select name="category">
	@foreach ($categories as $category)
	<option value="{{ $category->id }}" <?=($category->id==$model->_parent)?'selected="selected"':''?>>{{ $category->title }}</option>
	@endforeach
</select>
</div>

<div class="form-group">
<label class="radio">
  <input type="radio" name="status" value="Y" @if ($model->status=='Y') checked @endif>
  顯示
</label>
<label class="radio">
  <input type="radio" name="status" value="N" @if ($model->status=='N') checked @endif>
  隱藏
</label>
</div>

{{--Images Widget Start--}}
	<script type="text/javascript">
		var images_input_name = "images_path[]";
		var descriptions_input_name = "image_desc[]";
	</script>
	@include('admin._partials.images_upload')
{{--Images Widget End--}}

@include('admin.faqs.create_others', array('label'=>$label, 'tab'=>$tab))

<input type="hidden" name="_method" value="PUT" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">編輯完成</button>
</form>
@stop

@section('bottom')
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
$(function() {
  $( "#date" ).datepicker({ dateFormat: "yy-mm-dd" });
});
</script>
	{{ HTML::script(asset('js/admin/services/js_create.js')) }}
@stop