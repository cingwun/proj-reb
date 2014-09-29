@extends('spa_admin._layouts.default')

@section('title')
<?php if((array_get($specArticle, 'id')==0)){echo "新增文章";}else{echo "編輯文章";} ?>
@stop

@section('main')

<?php
	if(!empty($changeLang))
		$actionURL = URL::Route('spa.admin.articles.store', array(array_get($specArticle,'id'), $changeLang));
	elseif(array_get($specArticle, 'id')!=0)
		$actionURL = URL::Route('spa.admin.articles.store', array(array_get($specArticle,'id')));
	else
		$actionURL = URL::Route('spa.admin.articles.store');
?>

<div class="col-lg-12">
	<form action="{{$actionURL}}" method="post" role="form">

		<div class="form-group">
			<label for="category">分類</label>
				<select name="category" class="form-control">
					<option value='about' @if(array_get($specArticle, 'category')=='about' || $createCategory == 'about') selected @endif>關於煥麗</option>
					<option value='news' @if(array_get($specArticle, 'category')=='news' || $createCategory == 'news') selected @endif>最新消息</option>
					<option value='oversea' @if(array_get($specArticle, 'category')=='oversea' || $createCategory == 'oversea') selected @endif>海外專區</option>
				</select>
		</div>

		<div class="form-group">
			<label for="title">標題</label>
			<div>
				<input type="text" class="form-control" id="title" name="title" size="12" value="{{array_get($specArticle, 'title', '')}}">
			</div>
		</div>

		<div>
            @include('spa_admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-cover', 'title'=>'封面圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
            <!-- image uploader -->
        </div>

		<div class="form-group">
			<label for="content">內文</label>
			<div>
				<textarea class="form-control ckeditor" id="content" name="content">{{array_get($specArticle, 'content', '')}}</textarea>
			</div>
		</div>

		<div class="form-group">
			<label for="open_at">上架日期</label>    
			<div>
				<input class="datepicker" data-date-format="mm/dd/yyyy" id="open_at" name="open_at" size="12" value="{{array_get($specArticle, 'open_at', date("Y-m-d"))}}">
			</div>
		</div>

		<div class="form-group">
			<label for="status">狀態</label>
			<label class="radio-inline">
				<input type="radio" name="status" value="1" id="optionsRadiosInline" @if(array_get($specArticle, 'status')==1) {{"checked"}} @endif >顯示
			</label>
			<label class="radio-inline">
				<input type="radio" name="status" value="0" id="optionsRadiosInline" @if(array_get($specArticle, 'status')==0) {{"checked"}} @endif >隱藏
			</label>
		</div>

		<div class="form-group">
			<label for="lang">語言</label>
			@if(array_get($specArticle, 'id')==0)
			<label class="radio-inline">
				<input type="radio" name="lang" value="tw" id="optionsRadiosInline" checked> 繁體
			</label>
			<label class="radio-inline">
				<input type="radio" name="lang" value="cn" id="optionsRadiosInline"> 簡體
			</label>
			@elseif(array_get($specArticle, 'id')!=0 && $changeLang==null && array_get($specArticle, 'ref_id')==0)
			<label class="radio-inline">
				<input type="radio" name="lang" value="tw" id="optionsRadiosInline" @if(array_get($specArticle, 'lang')=='tw') {{"checked"}} @endif > 繁體
			</label>
			<label class="radio-inline">
				<input type="radio" name="lang" value="cn" id="optionsRadiosInline" @if(array_get($specArticle, 'lang')=='cn') {{"checked"}} @endif > 簡體
			</label>
			@elseif(array_get($specArticle, 'lang')=='tw')
			<label class="radio-inline">
				<input type="radio" name="lang" value="cn" id="optionsRadiosInline" checked> 簡體
			</label>
			@else
			<label class="radio-inline">
				<input type="radio" name="lang" value="tw" id="optionsRadiosInline" checked> 繁體
			</label>
			@endif
		</div>


		<!--<input type="hidden" name="_method" value="POST" />-->
		<input type="hidden" name="id" value="{{ array_get($specArticle, 'id')}}" method="post"/>
		<button class="btn btn-danger" type="button" onclick="history.back();">取消</button>

		@if(array_get($specArticle, 'id')==0)
		<button class="btn btn-primary btn-submit">新增</button>
		@else
		<button class="btn btn-primary btn-submit">修改</button>
		@endif
	</form>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
$(function() {
  // $( "#open_at" ).datepicker({ dateFormat: "yy-mm-dd" });
  $('.datepicker').datepicker();
});
</script>
{{ HTML::script('packages/ckeditor/ckeditor.js'); }}
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
        var imgUploaderCover = _imageUploader({
                el: '#image-box-cover',
                imageBoxMeta: {photoFieldName: 'cov[]', descFieldName: 'cov_desc[]', delFieldName: 'deleteImages[]'},
                isMultiple: false,
                files: <?=json_encode($imgUploaderList['cover']['items'])?>
            });

        $('.btn-submit').click(function(e){
            e.preventDefault();
            var bool = true;
            bool &= imgUploaderCover.validate();

            if (!bool){
                alert("提醒您:\n\n   您尚未上傳封面圖片");
                return false;
            }

            $('form').submit();
        });
    </script>

@stop