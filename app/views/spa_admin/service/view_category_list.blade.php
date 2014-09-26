@extends('spa_admin._layouts.default')

@section('title')
服務項目-類型列表
@stop

@section('main')
<div class='col-lg-12'>
    <a href='{{$actionURL}}' type="button" class="btn btn-success pull-right">新增</a>
</div>
<div>
    <div class='col-lg-6'>
        <div><label>繁體列表</label></div>
        <table class="table table-bordered" id="sortableTW" data-sortAction="{{$updateSortURL}}" data-deleteAction="{{$categoryDeleteURL}}">
            <thead>
                <tr>
                    <th>分類標題</th>
                    <th>排序</th>
                    <th>語系</th>
                    <th>功能</th>
                </tr>
            </thead>
            <tbody>
            	@foreach($categorys['tw'] as $cat)
            	<tr id='{{$cat->id}}'>
            		<td>{{$cat->title}}</td>
            		<td>{{$cat->sort}}</td>
                    <td id='{{$cat->lang}}'>
                        @if($cat->lang == 'tw')繁體@else簡體@endif
                    </td>
            		<td>
            			<a href="{{$serviceListURL}}?lang={{$cat->lang}}&category={{$cat->id}}" title="{{$cat->title}}相關文章" class="btn btn-sm btn-success">文章</a>
                        <a href="{{$actionURL}}/{{$cat->id}}" class="btn btn-sm btn-primary">修改</a>
                        <span class="btn btn-sm btn-danger btn-delete">刪除</span>
            		</td>
            	</tr>
            	@endforeach
            </tbody>
        </table>
    </div>
    <div class='col-lg-6'>
        <div><label>簡體列表</label></div>
        <table class="table table-bordered" id="sortableCN" data-sortAction="{{$updateSortURL}}" data-deleteAction="{{$categoryDeleteURL}}">
            <thead>
                <tr>
                    <th>分類標題</th>
                    <th>排序</th>
                    <th>語系</th>
                    <th>功能</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorys['cn'] as $cat)
                <tr id='{{$cat->id}}'>
                    <td>{{$cat->title}}</td>
                    <td>{{$cat->sort}}</td>
                    <td id='{{$cat->lang}}'>
                        @if($cat->lang == 'tw')繁體@else簡體@endif
                    </td>
                    <td>
                        <a href="{{$serviceListURL}}?lang={{$cat->lang}}&category={{$cat->id}}" title="{{$cat->title}}相關文章" class="btn btn-sm btn-success">文章</a>
                        <a href="{{$actionURL}}/{{$cat->id}}" class="btn btn-sm btn-primary">修改</a>
                        <span class="btn btn-sm btn-danger btn-delete">刪除</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
{{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
@section('bottom')
{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
{{ HTML::script(asset('spa_admin/js/service/js_category_list.js'))}}
<script type="text/javascript">
    var tableTW = _sortTable({el: '#sortableTW', role: 'category', sortColumn: 2});
    var tableCN = _sortTable({el: '#sortableCN', role: 'category', sortColumn: 2});
</script>
@stop