@extends('spa_admin._layouts.default')

@section('title')
修改權限
@stop

@section('main')
@include('admin._partials.notifications')
<form action="/admin/permissions/{{ $permission->id }}" method="post" class="col-lg-5">
	<div class="form-group">
		<label>權限</label>
		<div>
			<input type="text" class="form-control" id="name" name="name" size="12" value="{{ $permission->name }}" readOnly>
		</div>
	</div>

	<div class="form-group">
		<label>標題</label>
		<div>
			<input type="text" class="form-control" id="title" name="title" size="12" value="{{ $permission->title }}">
		</div>
	</div>


	<div class="form-group">
		<label>狀態</label><br>
		<label class="radio-inline">
			<input type="radio" name="status" value="1" @if ($permission->status==1) checked="checked" @endif> ON
		</label>
		<label class="radio-inline">
			<input type="radio" name="status" value="0" @if ($permission->status==0) checked="checked" @endif> OFF
		</label>
	</div>

	<div class="form-group">

	</div>
	<input type="hidden" name="_method" value="PUT" />
	<button class="btn btn-danger" type="button" onclick="history.back();">取消</button> <button class="btn btn-primary">更改</button>
</form>
@stop
