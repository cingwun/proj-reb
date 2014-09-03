@extends('admin._layouts.default')

@section('main')
<h2>新增服務類別</h2>
@include('admin._partials.notifications')
<form name="form1" action="/admin/faqs?type=category" method="post" enctype="multipart/form-data">
<div class="form-group">
<label class="col-sm-3 control-label" for="title">類別標題</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="title" size="12" value="{{ Input::old('title') }}">
</div>
<label class="col-sm-3 control-label" for="title">排序 (輸入數字)</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="sort" size="12" value="0" style="width:30px;">
</div>
</div>

<input type="hidden" name="_method" value="POST" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">新增</button>
</form>
@stop
