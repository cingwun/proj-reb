@extends('admin._layouts.default')

@section('title')
修改排行榜
@stop
@section('main')
@include('admin._partials.notifications')
<div class="col-lg-12">
	<form action="/admin/ranks/{{ $rank->id }}" method="post">
		<div class="form-group">
			<label class="control-label" for="title">標題</label>
			<input type="text" class="form-control" id="title" name="title" size="12" value="{{ $rank->title }}">
		</div>

		<div class="form-group">
			<label class="control-label" for="link">連結</label>
			<input type="text" class="form-control" id="link" name="link" size="12" value="{{ $rank->link }}">
		</div>
		<div class="form-group">
			<label>開啟方式</label><br/>
			<label class="radio-inline">
				<input type="radio" name="target" value="_self" @if ($rank->target=='_self') checked="checked" @endif>
				原視窗
			</label>
			<label class="radio-inline">
				<input type="radio" name="target" value="_blank" @if ($rank->target=='_blank') checked="checked" @endif>
				開新視窗
			</label>
		</div>
		<div class="form-group">
			<input type="hidden" name="_method" value="PUT" />
			<a href='javascript:history.back()' type="button" class="btn btn-danger">取消</a>
			<button class="btn btn-primary btn-submit">編輯完成</button>
		</div>
	</form>
</div>
@stop
