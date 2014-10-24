@extends('spa_admin._layouts.default')

@section('title')
<?php $title = (!isset($article['id']) || $article['id']==null) ? '新增' : '編輯'; ?>
<h2> {{ $title}}文章&nbsp;<span class="btn btn-inverse" onclick="window.history.back();">回上一頁</span></h2>
@stop

@section('main')

@include('admin._partials.notifications')

<div class="col-lg-12">
    <form name="form1" action="<?=URL::route('spa.admin.share.article.write')?>" method="post" enctype="multipart/form-data">

        <div class="form-group">
            @if($changeLang==null)
            <label class="radio-inline">
                <input type="radio" name="lang" value="tw" id="optionsRadiosInline" checked {{($article['language']=='tw') ? 'checked' : ''}}> 繁體
            </label>
            <label class="radio-inline">
                <input type="radio" name="lang" value="cn" id="optionsRadiosInline" {{($article['language']=='cn') ? 'checked' : ''}}> 簡體
            </label>
            @elseif($changeLang=='tw')
                 <label class="radio-inline">
                    <input type="radio" name="lang" value="cn" id="optionsRadiosInline" checked> 簡體
                </label>
            @else
                 <label class="radio-inline">
                    <input type="radio" name="lang" value="tw" id="optionsRadiosInline" checked> 繁體
                </label>
            @endif
        </div>

        <div class="form-group">
            <label for="title">標題</label>
            <div>
                <input type="text" class="form-control" id="title" name="title" style="width: 320px;" value="<?php echo \Arr::get($article, 'title', '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="title">背景色</label>
            <div>
                <input class="form-control colorField " type="text" value="<?php echo \Arr::get($article, 'background_color', '#74a41a') ?>" name="colorField" style="display: inline-table; margin: 1px 5px 1px 0px;"/><input class="form-control" id="background-color" name="background_color" value="<?php echo \Arr::get($article, 'background_color', '#74a41a') ?>">
            </div>
        </div>

        <br>

        <!--The admin._partials.widget_imageUploader's div class had been modified by Kettan.-->
        <div class="col-lg-12">
            <div class="col-lg-6">
                @include('spa_admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-cover', 'title'=>'封面圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
                <!-- image uploader -->
            </div>
            <div class="col-lg-6">
                @include('spa_admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box', 'title'=>'圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
                <!-- image uploader -->
            </div>
            <div class="col-lg-12">
                @include('spa_admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box-gallery', 'title'=>'單文圖片集', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
                <!-- image uploader -->
            </div>
        </div>
        <br>

        <div class="form-group">
            <label for="link">說明文字</label>
            <div>
                <textarea class="form-control" name="description"><?php echo \Arr::get($article, 'description', '') ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="link">狀態</label>
            <label class="radio-inline">
                <input type="radio" name="status" value="1" <?php echo ($article['status']=='1') ? 'checked' : '' ?> />顯示
            </label>
            <label class="radio-inline">
                <input type="radio" name="status" value="0" <?php echo ($article['status']=='0') ? 'checked' : '' ?> />隱藏
            </label>
        </div>

        <div class="form-group">
            <label for="meta">Meta keyword:</label>
            <div>
                <input type="text" class="form-control" id="meta_name" name="meta_name" size="12" value="{{array_get($article, 'meta_name', '')}}">
            </div>
            <label for="meta">Meta description:</label>
            <div>
                <input type="text" class="form-control" id="meta_content" name="meta_content" size="12" value="{{array_get($article, 'meta_content', '')}}">
            </div>
            <label for="meta">Meta title:</label>
            <div>
                <input type="text" class="form-control" id="meta_title" name="meta_title" size="12" value="{{array_get($article, 'meta_title', '')}}">
            </div>
             <label for="meta">h1:</label>
            <div>
                <input type="text" class="form-control" id="h1" name="h1" size="12" value="{{array_get($article, 'h1', '')}}">
            </div>
        </div>

        @include('spa_admin._partials.widget_labels', array('label'=>array('elementId'=>'label-service', 'formTitle'=>'美麗服務', 'fieldName'=>'label_service[]', 'selected'=>$labelSelected['serv'], 'items'=>$labelItems['service'])))
        <!-- label for service -->

        @include('spa_admin._partials.widget_labels', array('label'=>array('elementId'=>'label-product', 'formTitle'=>'美麗產品', 'fieldName'=>'label_product[]', 'selected'=>$labelSelected['prod'], 'items'=>$labelItems['product'])))
        <!-- label for service -->

        @include('spa_admin._partials.widget_tabs', array('tab'=>array('elementId'=>'tab-box', 'formTitle'=>'Tab項目', 'items'=>$tabItems)))
        <!-- tabs -->


        <input type="hidden" name="id" value="<?php echo \Arr::get($article, 'id', null) ?>" />
        @if($changeLang!=null)
        <input type="hidden" name="changeLang" value="{{$article['language']}}" />
        @endif
        <button class="btn btn-danger" type="button" onclick="history.back();">取消</button>
        <button class="btn btn-primary btn-submit">編輯完成</button>
    </form>
</div>
@stop

@section('head')
    {{ HTML::style(asset('css/admin/widgets/tabs/css_widget_tabs.css')) }}
    {{ HTML::style(asset('css/admin/widgets/imageUploader/css_widget_imageUploader.css')) }}
    {{ HTML::style(asset('packages/colorPicker/spectrum.css')) }}
    {{ HTML::style(asset('aesthetics/css/ckeditor.css')) }}
    {{ HTML::style(asset('css/admin/widgets/tabs/css_widget_tabs.css')) }}
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
                imageBoxMeta: {photoFieldName: 'cov[]', descFieldName: 'cov_desc[]', delFieldName: 'deleteImages[]'},
                isMultiple: false,
                files: <?=json_encode($imgUploaderList['cover']['items'])?>
            }),
            imgUploaderAfter = _imageUploader({
                el: '#image-box',
                imageBoxMeta: {photoFieldName: 'img[]', descFieldName: 'img_desc[]', delFieldName: 'deleteImages[]'},
                isMultiple: false,
                files: <?=json_encode($imgUploaderList['image']['items'])?>
            }),
            imgUploaderGallery = _imageUploader({
                el: '#image-box-gallery',
                imageBoxMeta: {photoFieldName: 'galle[]', descFieldName: 'galle_desc[]', delFieldName: 'deleteImages[]'},
                isMultiple: true,
                files: <?=json_encode($imgUploaderList['gallery']['items'])?>
            }),
            labelsService = _labels({
                el: '#label-service'
            }),
            labelsProduct = _labels({
                el: '#label-product'
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
            //bool &= imgUploaderBefore.validate();
            bool &= imgUploaderAfter.validate();
            bool &= imgUploaderGallery.validate();

            if (!bool || !tabs.validate()){
                alert("提醒您:\n\n   您尚未上傳圖片 或 Tab項目，\n\n    圖片可能包含封面圖,說明圖片或單文圖片集");
                return false;
            }

            $('form').submit();
        });
    </script>
@stop
