@extends('spa_admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-exclamation-sign"></i> 美麗服務 - 類型列表
@stop

@section('main')
<div class='col-lg-12'>
    <a href='{{$actionURL}}' type="button" class="btn btn-success pull-right">新增</a>
</div>
<div class='col-lg-12' id="clearTop">
    <div class='col-lg-6'>
        <div><label>繁體列表</label></div>
        <table class="table table-bordered" id="sortableTW" data-sortAction="{{$updateSortURL}}" data-deleteAction="{{$categoryDeleteURL}}">
            <thead>
                <tr>
                    <th width="108">圖示</th>
                    <th>分類標題</th>
                    <th width="50">排序</th>
                    <th width="50">狀態</th>
                    <th width="163">功能</th>
                </tr>
            </thead>
            <tbody>
            	@foreach($categorys['tw']['item'] as $cat)
            	<tr id='{{$cat->id}}'>
                    <td>
                        <img src="{{ $cat->image}}?w=90" alt="" class="img-rounded">
                    </td>
            		<td>{{$cat->title}}</td>
            		<td>{{$cat->sort}}</td>
                    <td>
                    @if($cat->display === 'yes')
                    <span style="color: #00AA00">顯示</span>
                    @else
                    隱藏
                    @endif
                    </td>
            		<td>
            			<a href="{{\URL::route('spa.admin.service.article.list', array('lang'=>$cat->lang, 'category'=>$cat->id))}}" title="{{$cat->title}}相關文章" class="btn btn-sm btn-success">文章</a>
                        <a href="{{\URL::route('spa.admin.service.category.action', array('id'=>$cat->id))}}" class="btn btn-sm btn-primary">修改</a>
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
                    <th width="108">圖示</th>
                    <th>分類標題</th>
                    <th width="50">排序</th>
                    <th width="50">狀態</th>
                    <th width="163">功能</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorys['cn']['item'] as $cat)
                <tr id='{{$cat->id}}'>
                    <td>
                        <img src="{{ $cat->image}}?w=90" alt="" class="img-rounded">
                    </td>
                    <td>{{$cat->title}}</td>
                    <td width="50">{{$cat->sort}}</td>
                    <td width="50">
                    @if($cat->display === 'yes')
                    <span style="color: #00AA00">顯示</span>
                    @else
                    隱藏
                    @endif
                    </td>
                    <td width="163">
                        <a href="{{\URL::route('spa.admin.service.article.list', array('lang'=>$cat->lang, 'category'=>$cat->id))}}" title="{{$cat->title}}相關文章" class="btn btn-sm btn-success">文章</a>
                        <a href="{{\URL::route('spa.admin.service.category.action', array('id'=>$cat->id))}}" class="btn btn-sm btn-primary">修改</a>
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
    var tableTW = _sortTable({el: '#sortableTW', role: 'category', sortColumn: 3});
    var tableCN = _sortTable({el: '#sortableCN', role: 'category', sortColumn: 3});
</script>
@stop
