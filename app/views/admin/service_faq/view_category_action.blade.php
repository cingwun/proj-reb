@extends('admin._layouts.default')

@section('title')
類型列表-@if($action=='create')新增@else編輯@endif
@stop

@section('main')
<div id="form-panel">
    <form name="form-category" action="{{$writeURL}}" method="post" >
        <div class="form-group">
            <label class="control-label" for="title">類別標題</label>
            <input type="text" class="form-control" name="title" size="12" value="@if($action=='edit'){{$category->title}}@endif" />
            
            <label class="control-label" for="title">排序 (輸入數字)</label>
            <input type="text" class="form-control" name="sort" size="12" value="@if($action=='edit'){{$category->sort}}@else 1@endif" onkeyup="value=value.replace(/[^\d]/g,'')"/>
            <label>狀態</label><br/>
            <label class="radio-inline">
                <input type="radio" name="status" value="Y" @if($action == 'edit')@if($category->status == 'Y') checked @endif@endif @if($action=='create')checked@endif/>顯示
            </label>
            <label class="radio-inline">
                <input type="radio" name="status" value="N" @if($action == 'edit')@if($category->status == 'N') checked @endif@endif/>隱藏
            </label>
        </div>
        <input type="hidden" name="id" value="@if($action=='edit'){{$category->id}}@endif"/>
        <a href='javascript:history.back()' type="button" class="btn btn-danger">取消</a>
		<button class="btn btn-primary btn-submit">編輯完成</button>
    </form>
</div>
@stop

@section('head')
    
@stop

@section('bottom')
@stop