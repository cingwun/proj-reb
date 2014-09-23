@extends('spa_admin._layouts.default')

@section('title')

產品項目<?php echo (!empty($category)) ? sprintf(' ( %s ) ', $category_array[$category]) : ' '?>-文章列表

@stop

@section('main')

<div>
	<a href='javascript:history.back()' type="button" class="btn btn-default pull-lift">回上一頁</a>
	<a href='{{$actionURL}}@if($category != '')?category={{$category}}@endif' type="button" class="btn btn-success pull-right">新增</a>
</div>
<br/>
<table class="table table-bordered table-hover" id="sortable" data-sortAction="{{$updateSortURL}}" data-deleteAction="{{$deleteURL}}">
	<thead>
		<tr>
			<th>產品項目標題</th>
			<th>分類</th>
			<th>瀏覽數</th>
			<th>容量</th>
			<th>價格</th>
			<th>顯示狀態</th>
			<th>發表/更新日期</th>
			<th>語系</th>
			<th>排序</th>
			<th class="col-lg-3">功能</th>
		</tr>
	</thead>
	<tbody>
		@foreach($products as $product)
		<tr id='{{$product->id}}'>
			<td>{{$product->title}}</td>
			<td>{{$category_array[$product->_parent]}}</td>
			<td>{{$product->views}}</td>
			<td>{{$product->capacity}}</td>
			<td>{{$product->price}}</td>
			<td>
				@if($product->display === 'yes')
				<span style="color: #00AA00">顯示</span>
				@else
				隱藏
				@endif
			</td>
			<td>{{$product->created_at}}<br/>{{$product->updated_at}}</td>
			<td>
				@if($product->lang == 'tw')
				繁體
				@else
				簡體
				@endif
			</td>
			<td>{{$product->sort}}</td>
			<td>
				<a href="{{$actionURL}}/{{$product->id}}" type="button" class="btn btn-sm btn-primary">修改</a>
				<a href="#" type="button" class="btn btn-sm btn-danger btn-delete">刪除</a>
				@if($product->ref == '0')
				<a href="{{$actionURL}}/{{$product->id}}/create_lang?category={{$category}}" type="button" class="btn btn-sm btn-warning">新增@if($product->lang == 'tw')簡體@else繁體@endif語系</a>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<input type="hidden" value='{{$category}}' id='category'/>
@include('spa_admin._partials.widget_pager', array('wp'=>$pagerParam))
@stop
@section('head')
{{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
@stop
@section('bottom')
{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
{{ HTML::script(asset('js/admin/service_faq/js_article_list.js'))}}
<script type="text/javascript">
    var sortTable = _sortTable({el: '#sortable', role: 'article', sortColumn: 9, hasCategory: <?php echo (!empty($category))?'true':'false'?>});
</script>
@stop
