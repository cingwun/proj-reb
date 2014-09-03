<div class="form-group img-upload-box" id="<?=$options['elementId']?>" data-deleteURL="<?=$options['deleteURL']?>">
    <label class="col-sm-3 control-label" for="image"><?=$options['title']?></label>
    <div class="col-sm-5">
        <ul class="photo-list"></ul>
        @include('admin._partials.widget_uploader', array('options'=>$options))
        <!-- labels -->
    </div>
</div>
<!-- image uploader -->