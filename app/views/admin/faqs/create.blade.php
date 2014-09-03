@extends('admin._layouts.default')

@section('main')
<h2>新增常見問題</h2>
@include('admin._partials.notifications')
<form name="form1" action="/admin/faqs" method="post" enctype="multipart/form-data">
<div class="form-group">
<label class="col-sm-3 control-label" for="title">標題</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="title" size="12" value="{{ Input::old('title') }}">
</div>
</div>

描述圖片： (300x300以下)
{{--Upload Widget Start--}}
<input type="hidden" name="image_path"/>
@include('admin._partials.upload_single_OneClick')
{{--Upload Widget End--}}

<div class="form-group">
<label class="col-sm-3 control-label" for="link">說明文字</label>
<div class="col-sm-7">
	<textarea class="form-control" name="content"></textarea>
</div>
</div>


<div class="form-group">
	<label class="col-sm-3 control-label" for="link">所屬分類</label>
<select name="category">
	@foreach ($categories as $category)
	<option value="{{ $category->id }}">{{ $category->title }}</option>
	@endforeach
</select>
</div>

<div class="form-group">
<label class="radio">
  <input type="radio" name="status" value="Y" checked>
  顯示
</label>
<label class="radio">
  <input type="radio" name="status" value="N">
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

<input type="hidden" name="_method" value="POST" />
<input type="hidden" name="sid" value="null" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">新增</button>
</form>
@stop

@section('bottom')
	{{ HTML::script(asset('js/admin/services/js_create.js')) }}
@stop
