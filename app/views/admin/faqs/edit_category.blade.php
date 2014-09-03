@extends('admin._layouts.default')

@section('main')
<h2>修改常見問題類別</h2>
@include('admin._partials.notifications')
<form name="form1" action="/admin/faqs/{{ $faq->id }}?type=category" method="post" enctype="multipart/form-data">
<div class="form-group">
<label class="col-sm-3 control-label" for="title">類別標題</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="title" size="12" value="{{ $faq->title }}">
</div>
<label class="col-sm-3 control-label" for="title">排序 (輸入數字)</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="title" name="sort" size="12" value="{{ $faq->sort }}" style="width:30px;">
</div>
</div>

<input type="hidden" name="_method" value="PUT" />
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">修改</button>
</form>
@stop
