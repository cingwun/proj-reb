@extends($layout)

@section('title')
重設密碼( {{$user->email}} )
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
		<button class="btn btn-primary" type="reset">重設</button>
		<button class="btn btn-danger" type="button" onclick="history.back();">編輯</button>
	</div>
</form>
@stop
