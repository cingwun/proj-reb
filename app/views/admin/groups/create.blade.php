@extends('admin._layouts.default')

@section('main')
<h2>新增群組</h2>
@include('admin._partials.notifications')
<form action="/admin/groups" method="post">
<div class="form-group">
<label class="col-sm-3 control-label" for="name">群組名稱</label>
<div class="col-sm-5">
<input type="text" class="form-control" id="name" name="name" size="12" value="{{ Input::old('name') }}">
</div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label" for="permissions">權限</label>

@foreach (Permission::All()->sortBy('sort') as $permission)
<label class="checkbox inline">
  <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"> {{ $permission->title }}
</label>
@endforeach
</div>

<div class="form-group">

</div>
<input type="hidden" name="_method" value="POST" />
<br/>
<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse">新增</button>
</form>
@stop
