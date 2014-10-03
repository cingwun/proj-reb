@extends('admin._layouts.default')

@section('title')
修改新技術
@stop

@section('main')
@include('admin._partials.notifications')
<div class="col-lg-12">
	<form action="{{ URL::route('admin.technologies.update',array('id'=>$technology->id)) }}" method="post">
		<div class="form-group">
			<label class="control-label" for="title">標題</label>
				<input type="text" class="form-control" id="title" name="title" size="12" value="{{ $technology->title }}">
		</div>

		@include('spa_admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box', 'title'=>'描述圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
		
		<div class="form-group">
			<label class="control-label" for="link">連結</label>
				<input type="text" class="form-control" id="link" name="link" size="12" value="{{ $technology->link }}">
		</div>

		<div class="form-group">
			<label>開啟方式</label><br/>
			<label class="radio-inline">
				<input type="radio" name="target" value="_self" @if ($technology->target=='_self') checked @endif>
				原視窗
			</label>
			<label class="radio-inline">
				<input type="radio" name="target" value="_blank" @if ($technology->target=='_blank') checked @endif>
				開新視窗
			</label>
		</div>

		<br/>

		<div class="form-group">
			<label>狀態</label><br/>
			<label class="radio-inline">
				<input type="radio" name="status" value="Y" @if ($technology->status=='Y') checked @endif>
				顯示
			</label>
			<label class="radio-inline">
				<input type="radio" name="status" value="N" @if ($technology->status=='N') checked @endif>
				隱藏
			</label>
		</div>
		<div class="form-group">
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
            files: <?php echo json_encode($techImage) ?>
        });
</script>
@stop
