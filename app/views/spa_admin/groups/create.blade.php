@extends('spa_admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-dashboard"></i> 系統管理 - 新增群組
&nbsp;<span class="btn btn-default" onclick="window.history.back();">回上一頁</span>
@stop

@section('main')

@include('admin._partials.notifications')

<form action="/admin/groups" method="post">
	<div class="form-group col-lg-3">
		<label>群組名稱</label>
		<div>
			<input type="text" class="form-control" id="name" name="name" size="12" value="{{ Input::old('name') }}">
		</div>
	</div>


	<div class="form-group col-lg-12">
		<label>權限</label>
		<div>
			@foreach (Permission::All()->sortBy('sort') as $permission)
			<label class="checkbox-inline">
				<input type="checkbox" name="permissions[]" value="{{ $permission->name }}"> {{ $permission->title }}
			</label>
			@endforeach
		</div>
	</div>

	<div class="form-group col-lg-5">
		<input type="hidden" name="_method" value="POST" />
		<br/>
		<button class="btn btn-danger" type="button" onclick="history.back();">取消</button> <button class="btn btn-primary">新增</button>
	</div>
</form>
@stop
