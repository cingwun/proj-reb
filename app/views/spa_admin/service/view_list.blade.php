@extends('spa_admin._layouts.default')

@section('title')

服務項目-文章列表

@stop

@section('main')

<div>
	<a href='javascript:history.back()' type="button" class="btn btn-default pull-lift">回上一頁</a>
	<a href='{{$acrion_url}}' type="button" class="btn btn-success pull-right">新增</a>
</div>

<table class="table table-bordered table-hover" id="sortable">
	<thead>
		<tr>
			<th>服務項目標題</th>
			<th>分類</th>
			<th>瀏覽數</th>
			<th>顯示狀態</th>
			<th>發表/更新日期</th>
			<th>語系</th>
			<th class="col-lg-3">功能</th>
		</tr>
	</thead>
	<tbody>
		@foreach($service_list as $service)
		<tr>
			<td>{{$service->title}}</td>
			<td>{{$category_array[$service->_parent]}}</td>
			<td>{{$service->views}}</td>
			<td>
				@if($service->display === 'yes')
				<span style="color: #00AA00">顯示</span>
				@else
				隱藏
				@endif
			</td>
			<td>{{$service->created_at}}<br/>{{$service->updated_at}}</td>
			<td>
				@if($service->lan == 'zh')
				繁體
				@else
				簡體
				@endif
			</td>
			<td>
				<a href="{{$acrion_url}}/{{$service->id}}" type="button" class="btn btn-sm btn-primary">修改</a>
				<a href="{{$delete_url}}/{{$service->id}}" type="button" class="btn btn-sm btn-danger">刪除</a>
				@if($service->ref_id == '0')
				<a href="{{$acrion_url}}/{{$service->id}}/create_lan" type="button" class="btn btn-sm btn-warning">新增@if($service->lan == 'zh')簡體@else繁體@endif語系</a>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
	

@stop
@section('bottom')
	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'); }}
    {{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
    {{ HTML::script(asset('js/admin/service_faq/js_article_list.js'))}}
    <script type="text/javascript">
        var sortTable = _sortTable({el: '#sortable', role: 'article', sortColumn: 6, hasCategory: true});
    </script>
@stop
