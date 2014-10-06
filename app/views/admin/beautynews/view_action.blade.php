@extends('admin._layouts.default')

@section('title')
<?php

if (!isset($article['id']) || $article['id']==null){
    $title = '新增';
    $isNew = true;
}else{
    $title = '編輯';
    $isNew = false;
}
?>
<?php echo $title ?> - 新知文章
@stop
@section('main')
@include('admin._partials.notifications')
<div class="col-lg-12">
    <form name="form1" action="<?=URL::route('admin.beautynews.write')?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label class="control-label" for="title">標題</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo Arr::get($article, 'title', '') ?>">
        </div>
        <!-- title -->

        <div class="form-group">
            <label class="control-label" for="title">樣式選擇</label>
            <select class="form-control" name="style">
                <option value="1">樣式一</option>
            </select>
        </div>
        <!-- style -->

        @include('admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-cover', 'title'=>'封面圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
        <!-- image uploader -->

        @include('admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-fb', 'title'=>'facebook圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
        <!-- image uploader -->

        <div class="form-group">
            <label class="control-label" for="link">連結</label>
            <input type="text" class="form-control" id="link" name="link" size="12" value="<?=Arr::get($article, 'link', '')?>" placeholder="請輸入連結，預設: #" />
        </div>
        <!-- link -->

        <div class="form-group">
            <label class="control-label" for="link">連結視窗</label><br/>
            <label class="radio-inline">
                <input type="radio" name="target" id="inlineCheckbox1" value="_self" <?=($isNew || (Arr::get($article, 'target')=='_self')) ? 'checked="checked"' : ''?>> 原視窗
            </label>
            <label class="radio-inline">
                <input type="radio" name="target" id="inlineCheckbox2" value="_blank" <?=(Arr::get($article, 'target', '')=='_blank') ? 'checked="checked"' : ''?>> 另開視窗
            </label>
        </div>
        <!-- link target -->

        <div class="form-group">
            <label class="control-label" for="link">狀態</label><br/>
            <label class="radio-inline">
                <input type="radio" name="status" value="1" <?php echo ($article['status']=='1') ? 'checked' : '' ?> />顯示
            </label>
            <label class="radio-inline">
                <input type="radio" name="status" value="0" <?php echo ($article['status']=='0') ? 'checked' : '' ?> />隱藏
            </label>
        </div>
        <!-- status -->

        <div class="form-group">
            <label class="control-label" for="title">排序</label>
            <input type="text" class="form-control" id="sort" name="sort" value="<?php echo Arr::get($article, 'sort', 1) ?>">
        </div>
        <!-- title -->

        <div class="form-group">
            <label class="control-label" for="link">說明文字</label>
            <textarea class="form-control" name="description" style="min-width: 400px; height: 150px;"><?php echo strip_tags(Arr::get($article, 'description', ''))?></textarea>
        </div>
        <!-- description -->

        <input type="hidden" name="id" value="<?php echo Arr::get($article, 'id', null) ?>" />
        <input type="hidden" name="lang" value="{{$lang}}"/>
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
{{ HTML::script(asset('packages/ckeditor/ckeditor.js')) }}
{{ HTML::script(asset('js/admin/widgets/imageUploader/js_widget_imageUploader.js')) }}

<script type="text/javascript">
var imgUploaderCover = _imageUploader({
    el: '#image-box-cover',
    imageBoxMeta: {photoFieldName: 'cover[]', descFieldName: 'cover_desc[]', delFieldName: 'deleteImages[]'},
    isMultiple: false,
    files: <?=json_encode($imgUploaderList['cover']['items'])?>
}),
imgUploaderFB = _imageUploader({
    el: '#image-box-fb',
    imageBoxMeta: {photoFieldName: 'fb[]', descFieldName: 'fb_desc[]', delFieldName: 'deleteImages[]'},
    isMultiple: false,
    files: <?=json_encode($imgUploaderList['fb']['items'])?>
});

$('.btn-submit').click(function(e){
    e.preventDefault();
    var bool = true;
    bool &= imgUploaderCover.validate();
    bool &= imgUploaderFB.validate();

    if (!bool){
        alert("提醒您:\n\n   您尚未上傳圖片，可能包含封面圖片或facebook圖片");
        return false;
    }

    $('form').submit();
});
</script>
@stop
