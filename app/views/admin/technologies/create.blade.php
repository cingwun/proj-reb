@extends('admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-certificate"></i>  美麗新技術 - 新增
@stop

@section('main')
<div class="col-lg-12">
	<form name="form1" action="/admin/technologies" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label class="control-label" for="title">標題</label>
			<input type="text" class="form-control" id="title" name="title" size="12" value="{{ Input::old('title') }}">
		</div>
		<div class="form-group">
			<label class="control-label" for="link">連結</label>
			<input type="text" class="form-control" id="link" name="link" size="12" value="{{ Input::old('link') }}">
		</div>

		@include('spa_admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box', 'title'=>'描述圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))

		<div class="form-group">
			<label>開啟方式</label><br/>
			<label class="radio-inline">
				<input type="radio" name="target" value="_self" checked>
				原視窗
			</label>
			<label class="radio-inline">
				<input type="radio" name="target" value="_blank">
				開新視窗
			</label>
		</div>
		<div class="form-group">
			<label>狀態</label><br/>
			<label class="radio-inline">
				<input type="radio" name="status" value="Y" checked>
				顯示
			</label>
			<label class="radio-inline">
				<input type="radio" name="status" value="N">
				隱藏
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
            files: <?php echo json_encode(array()) ?>
        });
</script>
@stop
