@extends('admin._layouts.default')

@section('main')
<h2>修改文章</h2>
@include('admin._partials.notifications')
<form action=" {{ URL::to('admin/articles/'.$article->id.'?category='.Input::get('category').'&lang='.Input::get('lang')) }} " method="post">

	<div class="form-group">
		<label for="category">分類</label>
		<select class="form-control" id="category" name="category">
			@foreach(helper::article_category() as $key=>$category)
			<option value="{{ $key }}" @if($key==$article->category) selected @endif>{{ $category }}</option>
			@endforeach
		</select>
	</div>

	<div class="form-group">
		<label for="title">標題</label>
		<input type="text" class="form-control" id="title" name="title" size="12" value="{{ $article->title }}">
	</div>

	<div class="form-group">
		<label for="description">內文</label>
		<textarea class="form-control ckeditor" id="description" name="description">{{ $article->description }}</textarea>
	</div>

	<div class="form-group">
		<label for="open_at">上架日期</label>
		<input type="text" class="form-control" id="open_at" name="open_at" size="12" value="{{ $article->open_at }}">
	</div>


	<div class="form-group">
		<label for="status">狀態</label>
		<label class="radio-inline">
			<input type="radio" name="status" value="1" @if($article->status==1) checked="checked" @endif> ON
		</label>
		<label class="radio-inline">
			<input type="radio" name="status" value="0" @if($article->status==0) checked="checked" @endif> OFF
		</label>
	</div>

	<div class="form-group">
		<label for="status">語言</label>
		<label class="radio-inline">
			<input type="radio" checked="checked"> <?php echo ($article->lang=='tw') ? '繁體' : '簡體' ; ?>
		</label>
	</div>

	<div class="form-group">
		<label for="meta_name">Meta Name: </label>
		<input type="text" class="form-control" id="meta_name" name="meta_name" size="12" value="{{ $article->meta_name }}">
	</div>

	<div class="form-group">
		<label for="meta_content">Meta Content: </label>
		<input type="text" class="form-control" id="meta_content" name="meta_content" size="12" value="{{ $article->meta_content }}">
	</div>

	<input type="hidden" name="_method" value="PUT" />
	<button class="btn" type="button" onclick="history.back();">取消</button>
	<button class="btn btn-inverse">更改</button>
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
