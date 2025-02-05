@extends('admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-exclamation-sign"></i>
<?php
$id = Arr::get($article, 'id', null);
$title = ($id==null) ? '新增' : '編輯';
$title .= ($type=='service') ? '服務項目' : '常見問題';
echo $title;
?>
 <span class="btn btn-default" onclick="window.history.back();">回上一頁</span>
@stop

@section('main')
@include('admin._partials.notifications')
<form name="form1" action="<?php echo URL::route('admin.service_faq.article.write', array('type'=>$type))?>" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label class="=control-label" for="title">標題</label>
    <input type="text" class="form-control" id="title" name="title" size="12" value="<?php echo Arr::get($article, 'title', '')?>">
  </div>

   描述圖片： (300x300以下)
   {{--Upload Widget Start--}}
   <input type="hidden" name="image_path" value="<?php echo Arr::get($article, 'image', '')?>"/>
   @include('admin._partials.upload_single_OneClick')
   {{--Upload Widget End--}}

  <div class="form-group">
    <label class="control-label" for="link">說明文字</label>
    <textarea class="form-control" name="content"><?php echo Arr::get($article, 'content', '')?></textarea>
  </div>

  <div class="form-group">
  	<label class="control-label" for="link">所屬分類</label><br/>
    <select class="form-control" name="category">
     <?php foreach ($categories as $category):?>
     <option value="<?php echo $category->id?>" <?php echo (isset($article['_parent']) && $category->id==$article['_parent']) ? 'selected' : '';?>><?php echo $category->title?></option>
   <?php endforeach;?>
  </select>
  </div>

  <div class="form-group">
    <label>狀態</label><br/>
    <label class="radio-inline">
      <input type="radio" name="status" value="Y" <?php echo ($article['status']=='Y')?'checked':''?> />
      顯示
    </label>
    <label class="radio-inline">
      <input type="radio" name="status" value="N" <?php echo ($article['status']=='N')?'checked':''?>>
      隱藏
    </label>
  </div>

  @include('spa_admin._partials.widget_imageUploader', array('options'=>array('elementId'=>'image-box', 'title'=>'圖片', 'uploadURL'=>fps::getUploadURL(), 'deleteURL'=>fps::getDeleteURL())))
  <!-- image uploader -->

  @include('spa_admin._partials.widget_labels', array('label'=>$label))
  <!-- labels -->

  @include('spa_admin._partials.widget_tabs', array('tabs'=>$tab))
  <!-- tabs -->
  <div class="form-group">
    <label>title tag:</label>
    <textarea class="form-control" rows="1" name="meta_title"><?php echo Arr::get($article, 'meta_title', '')?></textarea>
  </div>
  <div class="form-group">
    <label>Meta keywords:</label>
    <input class="form-control" type="text" name="meta_keywords" value="<?php echo Arr::get($article, 'meta_keywords', '')?>"/>
  </div>
  <div class="form-group">
    <label>Meta description:</label>
    <textarea class="form-control" rows="6" name="meta_desc"><?php echo Arr::get($article, 'meta_desc', '')?></textarea>
  </div>
  <div class="form-group">
    <label>h1標籤:</label>
    <textarea class="form-control" rows="1" name="h1"><?php echo Arr::get($article, 'h1', '')?></textarea>
  </div>

  <input type="hidden" name="id" value="<?php echo Arr::get($article, 'id', null)?>" />
  <input type="hidden" name="articleLang" value="{{$articleLang}}"/>
  <a href='javascript:history.back()' type="button" class="btn btn-danger">取消</a>
  <button class="btn btn-primary btn-submit">編輯完成</button>
</form>
@stop


@section('head')
{{ HTML::style(asset('css/admin/widgets/tabs/css_widget_tabs.css')) }}
{{ HTML::style(asset('css/admin/widgets/imageUploader/css_widget_imageUploader.css')) }}
@stop

@section('bottom')
{{ HTML::script(asset('packages/jquery-file-upload/js/vendor/jquery.ui.widget.js')) }}
{{ HTML::script(asset('packages/jquery-file-upload/js/jquery.iframe-transport.js')) }}
{{ HTML::script(asset('packages/jquery-file-upload/js/jquery.fileupload.js')) }}
{{ HTML::script(asset('packages/jquery-file-upload/js/jquery.fileupload-process.js')) }}
{{ HTML::script(asset('packages/ckeditor/ckeditor.js')) }}
{{ HTML::script(asset('packages/ckeditor/adapters/jquery.js')) }}
{{ HTML::script(asset('js/admin/widgets/labels/js_widget_labels.js')) }}
{{ HTML::script(asset('js/admin/widgets/tabs/js_widget_tabs.js')) }}
{{ HTML::script(asset('js/admin/widgets/imageUploader/js_widget_imageUploader.js')) }}
{{ HTML::script(asset('js/admin/service_faq/js_action.js')) }}
<script type="text/javascript">
var imgUploader = _imageUploader({
  el: '#image-box',
  imageBoxMeta: {photoFieldName: 'images[]', descFieldName: 'imageDesc[]', delFieldName: 'deleteImages[]'},
  isMultiple: true,
  files: <?php echo json_encode($images) ?>
});
</script>
@stop
