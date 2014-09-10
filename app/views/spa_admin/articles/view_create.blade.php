@extends('spa_admin._layouts.default')

@section('title')
新增文章
@stop

@section('main')
<h2>新增文章</h2>
@include('admin._partials.notifications')
<div class="col-lg-50">
<form action="<?=URL::Route('spa.admin.articles.store')?>" method="post" role="form">

<div class="form-group">
	<label for="category">分類</label>
	<div class="form-control" id="Select">
		<select name="category" class="form-control">
			<option value="1">最新消息</option>
			<option value="2">關於煥麗</option>
			<option value="3">美麗分享</option>
		</select>
	</div>
</div>
&nbsp;

<div class="row">
<label class="col-sm-3 control-label" for="title">標題</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="title" size="12" value="{{ Input::old('title') }}">
</div>
</div>
&nbsp;

<div class="row">
<label class="col-sm-3 control-label" for="content">內文</label>
<div class="col-sm-5">
<textarea class="form-control ckeditor" id="content" name="content"></textarea>
</div>
</div>
&nbsp;

<div class="row">
<label class="col-sm-3 control-label" for="open_at">上架日期</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="open_at" name="open_at" size="12" value="{{ date('Y-m-d') }}">
</div>
</div>
&nbsp;

<div class="form-group">
<label class="col-sm-3 control-label" for="status">狀態</label>
<label class="radio inline">
  <input type="radio" name="status" value="1" id="optionsRadiosInline" checked> ON
</label>
<label class="radio inline">
  <input type="radio" name="status" value="0" id="optionsRadiosInline"> OFF
</label>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label" for="lan">語言</label>
	<label class="radio inline">
		<input type="radio" name="lan" value="zh" id="optionRadiosInline" checked> 繁體
	</label>
	<label class="radio inline">
		<input type="radio" name="lan" value="cn" id="optionRadioInline"> 簡體
	</label>
</div>


<input type="hidden" name="_method" value="POST" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">新增</button>
</form>
</div>


<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
$(function() {
  $( "#open_at" ).datepicker({ dateFormat: "yy-mm-dd" });
});
</script>
{{ HTML::script('packages/ckeditor/ckeditor.js'); }}
@stop


