@extends('admin._layouts.default')

@section('title')
新增權限
@stop

@section('main')
@include('admin._partials.notifications')
<form action="/admin/permissions" method="post" class="col-lg-5">
	<div class="form-group">
		<label>權限</label>
		<div>
			<input type="text" class="form-control" id="name" name="name" size="12" value="{{ Input::old('name') }}">
		</div>
	</div>

	<div class="form-group">
		<label>標題</label>
		<div>
			<input type="text" class="form-control" id="title" name="title" size="12" value="{{ Input::old('title') }}">
		</div>
	</div>

	<div class="form-group">
		<label>狀態</label><br>
		<label class="radio-inline">
			<input type="radio" name="status" value="1" checked="checked"> ON
		</label>
		<label class="radio-inline">
			<input type="radio" name="status" value="0"> OFF
		</label>
	</div>

	<div class="form-group">

	</div>
	<input type="hidden" name="_method" value="POST" />
	<button class="btn btn-danger" type="button" onclick="history.back();">取消</button> <button class="btn btn-primary">新增</button>
</form>
@stop
