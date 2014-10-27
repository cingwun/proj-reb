@extends('spa_admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-th"></i> 美麗產品 - @if($action=='create')新增類型@else 修改類型@endif
&nbsp;<span class="btn btn-default" onclick="window.history.back();">回上一頁</span>
@stop

@section('main')
<div id="form-panel">
    <form name="form-category" action="{{$writeURL}}" method="post" >
        <div class="form-group">
            <label class="control-label" for="title">類別標題</label>
            <input type="text" class="form-control" name="title" size="12" value="@if($action=='edit'){{$category->title}}@endif" />
            @include('spa_admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box', 'title'=>'描述圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
            <label class="control-label" for="title">排序 (輸入數字)</label>
            <input type="text" class="form-control" name="sort" size="12" value="@if($action=='edit'){{$category->sort}}@else 1@endif" onkeyup="value=value.replace(/[^\d]/g,'')"/>
            <label>狀態</label><br/>
            <label class="radio-inline">
                <input type="radio" name="display" value="yes" @if($action == 'edit')@if($category->display == 'yes') checked @endif@endif @if($action=='create')checked@endif/>顯示
            </label>
            <label class="radio-inline">
                <input type="radio" name="display" value="no" @if($action == 'edit')@if($category->display == 'no') checked @endif@endif/>隱藏
            </label>
        </div>
        <input type="hidden" name="action" value="{{$action}}"/>
        <a href='javascript:history.back()' type="button" class="btn btn-danger">取消</a>
		<button class="btn btn-primary btn-submit">編輯完成</button>
    </form>
</div>
@stop

@section('head')
    {{ HTML::style(asset('css/admin/widgets/imageUploader/css_widget_imageUploader.css')) }}
@stop

@section('bottom')
{{ HTML::script(asset('packages/jquery-file-upload/js/vendor/jquery.ui.widget.js')) }}
{{ HTML::script(asset('packages/jquery-file-upload/js/jquery.iframe-transport.js')) }}
{{ HTML::script(asset('packages/jquery-file-upload/js/jquery.fileupload.js')) }}
{{ HTML::script(asset('packages/jquery-file-upload/js/jquery.fileupload-process.js')) }}
{{ HTML::script(asset('js/admin/widgets/imageUploader/js_widget_imageUploader.js')) }}
<script type="text/javascript">
    var imgUploaderMain = _imageUploader({
            el: '#image-box',
            imageBoxMeta: {photoFieldName: 'image[]', descFieldName: 'imageDesc[]', delFieldName: 'deleteImages[]'},
            isMultiple: false,
            files: <?php echo json_encode($cateCover) ?>
        });
</script>
@stop
