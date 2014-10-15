@extends('admin._layouts.default')

@section('title')
新增排行榜
@stop

@section('main')
@include('admin._partials.notifications')
<div class="col-lg-12">
	<form action="/admin/ranks" method="post">
		<div class="form-group">
			<label class="control-label" for="title">標題</label>
				<input type="text" class="form-control" id="title" name="title" size="12" value="{{ Input::old('title') }}">
		</div>

		<div class="form-group">
			<label class="control-label" for="link">連結</label>
				<input type="text" class="form-control" id="link" name="link" size="12" value="{{ Input::old('link') }}">
		</div>

		<div class="form-group">
			<label>開啟方式</label><br/>
			<label class="radio-inline">
				<input type="radio" name="target" value="_self" checked="checked">
				原視窗
			</label>
			<label class="radio-inline">
				<input type="radio" name="target" value="_blank">
				開新視窗
			</label>
		</div>
		<div class="form-group">
			<input type="hidden" name="_method" value="POST" />
			<a href='javascript:history.back()' type="button" class="btn btn-danger">取消</a>
			<button class="btn btn-primary btn-submit">編輯完成</button>
		</div>
	</form>
</div>
@stop
