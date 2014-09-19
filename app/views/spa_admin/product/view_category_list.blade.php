@extends('spa_admin._layouts.default')

@section('title')
產品項目-類型列表
@stop

@section('main')
<div class="col-lg-8">
    <table class="table table-bordered" id="sortable" data-sortAction="{{$update_sort_url}}" data-deleteAction="{{$category_delete_url}}">
        <thead>
            <tr>
                <th>分類標題</th>
                <th>排序</th>
                <th>語系</th>
                <th class="col-lg-4">功能</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($category_list as $cat)
        	<tr id='{{$cat->id}}'>
        		<td>{{$cat->title}}</td>
        		<td>{{$cat->sort}}</td>
                <td id='{{$cat->lang}}'>
                    @if($cat->lang == 'tw')繁體@else簡體@endif
                </td>
        		<td>
        			<a href="{{$service_list_url}}?category={{$cat->id}}" title="{{$cat->title}}相關文章" class="btn btn-sm btn-success">文章</a>
                    <span class="btn btn-sm btn-primary btn-modify">修改</span>
                    <span href="#" class="btn btn-sm btn-danger btn-delete">刪除</span>
                    @if($cat->ref == '0')
                    <span class="btn btn-sm btn-warning btn-lang">新增語系</span>
                    @endif
        		</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
</div>
<div class="col-lg-4" id="form-panel">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="panel-title">新增分類</th>
            </tr>
        </thead>
        <tbody><tr><td>
            <form name="form-category" action="{{$category_action_url}}" method="post" >
                <div class="form-group">
                    <label class="control-label" for="title">類別標題</label>
                    <input type="text" class="form-control" name="title" size="12" value="" />
                    <label class="control-label" for="title">排序 (輸入數字)</label>
                    <input type="text" class="form-control" name="sort" size="12" value="1" onkeyup="value=value.replace(/[^\d]/g,'')"/>
                    <label>語系</label><br/>
                    <label class="radio-inline" id="lang_tw">
                        <input type="radio" name="lang" value="tw"/>繁體
                    </label>
                    <label class="radio-inline" id="lang_cn">
                        <input type="radio" name="lang" value="cn"/>簡體
                    </label>
                    <input type="hidden" name="id" value="null" />
                    <input type="hidden" name="ref" value="null" />
                </div>
                <button class="btn-reset btn" type="button">重設</button> <button class="btn btn-inverse btn-submit">新增</button>
            </form>
        </td></tr></tbody>
    </table>
</div>
@stop
{{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
@section('bottom')
{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
{{ HTML::script(asset('spa_admin/js/service/js_category_list.js'))}}
@stop