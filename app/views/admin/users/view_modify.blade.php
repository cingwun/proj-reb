@extends('spa_admin._layouts.default')

@section('title')
修改個人資料
@stop

@section('main')

<form action="{{$writeURL}}" method="post" class="col-lg-6">

	<div class="form-group">
		<label>姓名</label>
		<div>
			<input type="text" class="form-control" id="last_name" name="last_name" size="12" value="{{$user->last_name}}">
		</div>
	</div>

	<div class="form-group">
		<label>密碼</label>
		<div>
			<input type="password" class="form-control" id="password" name="password" size="12" value="">
		</div>
	</div>
	<div>
		<button class="btn btn-danger" type="button" onclick="history.back();">取消</button>
		<button class="btn btn-primary">更改</button>
	</div>
</form>
@stop
