@extends('admin._layouts.default')

<?php
    $title = (!isset($article['id']) || $article['id']==null) ? '新增' : '編輯';
?>

@section('main')
<h2><?php
echo $title ?>文章&nbsp;<span class="btn btn-inverse" onclick="window.history.back();">回上一頁</span></h2>
@include('admin._partials.notifications')
<form name="form1" action="<?=URL::route('admin.wintness.article.write')?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-sm-3 control-label" for="title">標題</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" id="title" name="title" style="width: 320px;" value="<?php echo Arr::get($article, 'title', '') ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label" for="title">背景色</label>
        <div class="col-sm-12">
            <input class="form-control colorField " type="text" value="<?php echo Arr::get($article, 'background_color', '#CCC') ?>" name="colorField" style="display: inline-table; margin: 1px 5px 1px 0px;"/><input class="form-control" id="background-color" name="background_color" value="<?php echo Arr::get($article, 'background_color', '#CCC') ?>">
        </div>
    </div>

    <div class="row">
        <div class="span4">
            @include('admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-cover', 'title'=>'封面圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
            <!-- image uploader -->
        </div>
        <div class="span4">
            @include('admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-before', 'title'=>'before圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
            <!-- image uploader -->
        </div>
        <div class="span4">
            @include('admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-after', 'title'=>'after圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
            <!-- image uploader -->
        </div>
    </div>

    @include('admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-gallery', 'title'=>'單文圖片集', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
    <!-- image uploader -->

    <div class="form-group">
        <label class="col-sm-3 control-label" for="link">說明文字</label>
        <div class="col-sm-7">
            <textarea class="form-control" name="description"><?php echo Arr::get($article, 'description', '') ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label" for="link">狀態</label>
        <label class="radio inline">
            <input type="radio" name="status" value="1" <?php echo ($article['status']=='1') ? 'checked' : '' ?> />顯示
        </label>
        <label class="radio inline">
            <input type="radio" name="status" value="0" <?php echo ($article['status']=='0') ? 'checked' : '' ?> />隱藏
        </label>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label" for="link">顯示至Siderbar</label>
        <label class="radio inline">
            <input type="radio" name="isInSiderbar" value="1" <?php echo ($article['isInSiderbar']=='1') ? 'checked' : '' ?> />顯示
        </label>
        <label class="radio inline">
            <input type="radio" name="isInSiderbar" value="0" <?php echo ($article['isInSiderbar']=='0') ? 'checked' : '' ?> />不顯示
        </label>
    </div>

    @include('admin._partials.widget_labels', array('label'=>array('elementId'=>'label-service', 'formTitle'=>'改善問題', 'fieldName'=>'label_service[]', 'selected'=>$labelSelected, 'items'=>$labelItems['service'])))
    <!-- label for service -->

    @include('admin._partials.widget_labels', array('label'=>array('elementId'=>'label-faq', 'formTitle'=>'治療項目', 'fieldName'=>'label_faq[]', 'selected'=>$labelSelected, 'items'=>$labelItems['faq'])))
    <!-- label for faq -->

    @include('admin._partials.widget_tabs', array('tab'=>array('elementId'=>'tab-box', 'formTitle'=>'Tab項目', 'items'=>$tabItems)))
    <!-- tabs -->

    <input type="hidden" name="id" value="<?php
    echo Arr::get($article, 'id', null) ?>" />
    <button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse btn-submit">編輯完成</button>
</form>
@stop

@section('head')
    {{ HTML::style(asset('css/admin/widgets/tabs/css_widget_tabs.css')) }}
    {{ HTML::style(asset('css/admin/widgets/imageUploader/css_widget_imageUploader.css')) }}
    {{ HTML::style(asset('packages/colorPicker/spectrum.css')) }}
@stop

@section('bottom')
    {{ HTML::script(asset('packages/jquery-file-upload/js/vendor/jquery.ui.widget.js')) }}
    {{ HTML::script(asset('packages/jquery-file-upload/js/jquery.iframe-transport.js')) }}
    {{ HTML::script(asset('packages/jquery-file-upload/js/jquery.fileupload.js')) }}
    {{ HTML::script(asset('packages/jquery-file-upload/js/jquery.fileupload-process.js')) }}
    {{ HTML::script(asset('packages/ckeditor/ckeditor.js')) }}
    {{ HTML::script(asset('packages/colorPicker/spectrum.js')) }}
    {{ HTML::script(asset('js/admin/widgets/labels/js_widget_labels.js')) }}
    {{ HTML::script(asset('js/admin/widgets/tabs/js_widget_tabs.js')) }}
    {{ HTML::script(asset('js/admin/widgets/imageUploader/js_widget_imageUploader.js')) }}

    <script type="text/javascript">
        var imgUploaderCover = _imageUploader({
                el: '#image-box-cover',
                imageBoxMeta: {photoFieldName: 'cover[]', descFieldName: 'cover_desc[]', delFieldName: 'deleteImages[]'},
                isMultiple: false,
                files: <?=json_encode($imgUploaderList['cover']['items'])?>
            }),
            imgUploaderBefore = _imageUploader({
                el: '#image-box-before',
                imageBoxMeta: {photoFieldName: 'img_before[]', descFieldName: 'img_before_desc[]', delFieldName: 'deleteImages[]'},
                isMultiple: false,
                files: <?=json_encode($imgUploaderList['before']['items'])?>
            }),
            imgUploaderAfter = _imageUploader({
                el: '#image-box-after',
                imageBoxMeta: {photoFieldName: 'img_after[]', descFieldName: 'img_after_desc[]', delFieldName: 'deleteImages[]'},
                isMultiple: false,
                files: <?=json_encode($imgUploaderList['after']['items'])?>
            }),
            imgUploaderGallery = _imageUploader({
                el: '#image-box-gallery',
                imageBoxMeta: {photoFieldName: 'gallery[]', descFieldName: 'gallery_desc[]', delFieldName: 'deleteImages[]'},
                isMultiple: true,
                files: <?=json_encode($imgUploaderList['gallery']['items'])?>
            }),
            labelsService = _labels({
                el: '#label-service'
            }),
            labelsFaq = _labels({
                el: '#label-faq'
            }),
            tabs = _tabs({
                el: '#tab-box'
            }),
            $bgColor = $('#background-color'),
            $colorField = $('.colorField');

        $bgColor.spectrum({
            cancelText: '取消',
            chooseText: '確定',
            change: function(color){
                $bgColor.val(color);
                $colorField.val(color);
            }
        });

        $colorField.change(function(e){
            var val = $(this).val(),
                reg = /#[\w]{3,6}/i;
            if (val.length>0){
                if (reg.test(val))
                    $bgColor.spectrum('set', val);
            }

        });

        $('.btn-submit').click(function(e){
            e.preventDefault();
            var bool = true;
            bool &= imgUploaderCover.validate();
            bool &= imgUploaderBefore.validate();
            bool &= imgUploaderAfter.validate();
            bool &= imgUploaderGallery.validate();

            if (!bool || !tabs.validate()){
                alert("提醒您:\n\n   您尚未上傳圖片 或 Tab項目，\n\n    圖片可能包含封面圖片, before圖片, after圖片或單文圖片集");
                return false;
            }

            $('form').submit();
        });
    </script>
@stop
