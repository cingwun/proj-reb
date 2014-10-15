@extends('admin._layouts.default')

@section('title')
{{ ($isNew) ? '新增' : '編輯'}}圖片
@stop

@section('main')

<form action="{{URL::route('admin.wintness.gallery.write')}}" method="post">
    <div class="form-group">
        <label for="image">圖片</label>

        <div>
            <div class="uploader">
                <img src="" id="thumb"/>
                <div class="toolbar">
                    <button type="button" class="btn btn-success btn-select">上傳檔案</button>
                    <input id="fileupload" type="file" name="files[]" data-url="{{ fps::getUploadURL()}}" style="display: none;" />
                    <div id="progress" class="progress progress-striped">
                        <div class="bar progress-bar progress-bar-success"></div>
                    </div>
                </div>
                <input type="hidden" name="imageURL" value="{{ Arr::get($data, 'imageURL', '')}}"/>
            </div>
        </div>
    </div>
    <!-- image uploader -->

    <div class="form-group">
        <label for="title">標題</label>
        <div>
            <input type="text" class="form-control" id="title" name="title" size="64" value="{{ Arr::get($data, 'title', '')}}" placeholder="請輸入標題" />
        </div>
    </div>
    <!-- title -->

    <div class="form-group">
        <label for="link">連結</label>
        <div>
            <input type="text" class="form-control" id="link" name="link" size="12" value="{{ Arr::get($data, 'link', '')}}" placeholder="請輸入連結，預設: #" />
        </div>
    </div>
    <!-- link -->

    <div class="form-group">
        <label for="link">連結視窗</label>
        <div>
            <label class="radio-inline">
                <input type="radio" name="target" id="inlineCheckbox1" value="_self" {{ ($isNew || (Arr::get($data, 'target')=='_self')) ? 'checked="checked"' : ''}}> 原視窗
            </label>
            <label class="radio-inline">
                <input type="radio" name="target" id="inlineCheckbox2" value="_blank" {{ (Arr::get($data, 'target', '')=='_blank') ? 'checked="checked"' : ''}}> 另開視窗
            </label>
        </div>
    </div>
    <!-- link target -->

    <div class="form-group">
        <label for="link">排序</label>
        <div>
            <input type="text" class="form-control" id="sort" name="sort" size="12" value="{{ Arr::get($data, 'sort', 1)}}" placeholder="請輸入排序，預設: 1" />
        </div>
    </div>
    <!-- sort -->

    <div class="form-group">
        <label for="link">狀態</label>
        <label class="radio-inline">
            <input type="radio" name="status" value="1" {{ (($status=Arr::get($data, 'status', null))=='1' || $status==null) ? 'checked="checked"' : ''}}>顯示
        </label>
        <label class="radio-inline">
            <input type="radio" name="status" value="0" {{ (Arr::get($data, 'status')=='0') ? 'checked="checked"' : ''}}>隱藏
        </label>
    </div>
    <!-- status -->

    <div class="form-group">
        <button class="btn btn-danger" type="button" onclick="history.back();">取消</button> <button class="btn btn-primary btn-submit">編輯完成</button>
    </div>
    <input type="hidden" name="imglist" value=""/>
    <input type="hidden" name="id" value="{{ Arr::get($data, 'id', null)}}" />
</form>
@stop

@section('head')
@stop

@section('bottom')
{{ HTML::script('/packages/jquery-file-upload/js/vendor/jquery.ui.widget.js')}}
{{ HTML::script('/packages/jquery-file-upload/js/jquery.iframe-transport.js')}}
{{ HTML::script('/packages/jquery-file-upload/js/jquery.fileupload.js')}}
{{ HTML::script('/packages/timepicker/js/bootstrap-datepicker.js')}}
{{ HTML::script('/js/admin/wintness/js_gallery_action.js')}}
@stop