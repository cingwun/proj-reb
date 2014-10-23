@extends('admin._layouts.default')

@section('title')
新增文章
@stop

@section('main')
@include('admin._partials.notifications')
<form action="{{ URL::to('admin/articles?category='.Input::get('category').'&lang='.Input::get('lang')) }}" method="post" role="form">

	<div class="form-group">
		<label for="category">分類</label>
			<select class="form-control" name="category">
				@foreach(helper::article_category() as $key=>$category)
				<option value="{{ $key }}" @if (Input::get('category')==$key) selected @endif>{{ $category }}</option>
				@endforeach
			</select>
	</div>

	<div class="form-group">
		<label for="title">標題</label>
			<input type="text" class="form-control" id="title" name="title" size="12" value="{{ Input::old('title') }}">
	</div>

	<div class="form-group">
		<label for="description">內文</label>
			<textarea class="form-control ckeditor" id="description" name="description"></textarea>
	</div>

	<div class="form-group">
		<label for="open_at">上架日期</label>
			<input type="text" class="form-control" id="open_at" name="open_at" size="12" value="{{ date('Y-m-d') }}">
	</div>

	<div class="form-group">
		<label for="status">狀態</label>
		<label class="radio-inline">
			<input type="radio" name="status" value="1" checked="checked"> ON
		</label>
		<label class="radio-inline">
			<input type="radio" name="status" value="0"> OFF
		</label>
	</div>

	<div class="form-group">
		<label for="lang">語言</label>
		<label class="radio-inline">
			<input type="radio" name="lang" value="tw" checked="checked"> 繁體
		</label>
		<label class="radio-inline">
			<input type="radio" name="lang" value="cn"> 簡體
		</label>
	</div>

	<div class="form-group">
		<label for="meta_name">Meta keyword: </label>
			<input type="text" class="form-control" id="meta_name" name="meta_name" size="12" value="{{ Input::old('meta_name') }}">
	</div>

	<div class="form-group">
		<label for="meta_content">Meta description: </label>
			<input type="text" class="form-control" id="meta_content" name="meta_content" size="12" value="{{ Input::old('meta_content') }}">
	</div>

	<div class="form-group">
		<label for="meta_title">Meta title: </label>
			<input type="text" class="form-control" id="meta_title" name="meta_title" size="12" value="{{ Input::old('meta_title') }}">
	</div>
		
	<div class="form-group">
		<label for="h1">h1: </label>
			<input type="text" class="form-control" id="h1" name="h1" size="12" value="{{ Input::old('h1') }}">
	</div>	

	<input type="hidden" name="_method" value="POST" />
	<button class="btn btn-danger" type="button" onclick="history.back();">取消</button>
	<button class="btn btn-primary">編輯完成</button>
</form>
@stop

@section('bottom')
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
