@extends('spa_admin._layouts.default')

@section('title')

服務項目<?php echo (!empty($category)) ? sprintf(' ( %s ) ', \Text::preEllipsize(strip_tags($category_array[$category]), 10)) : ' '?>-文章列表

@stop

@section('main')

<div class='col-lg-12'>
	<a href='javascript:history.back()' type="button" class="btn btn-default pull-left">回上一頁</a>
	@if($category == '')
	<div class="col-md-2">
		<select class="form-control" onchange="langList(this)">
			<option value="tw" @if($listLang == 'tw') selected @endif>繁體</option>
			<option value="cn" @if($listLang == 'cn') selected @endif>簡體</option>
		</select>
	</div>
	@endif
	<a href='{{$acrionURL}}?lang={{$listLang}}@if($category != '')&category={{$category}}@endif' type="button" class="btn btn-success pull-right">新增</a>
</div>
<br/>
<div class='col-lg-12' style="margin-top:10px;">
	<table class="table table-bordered table-hover" id="sortable" data-sortAction="{{$updateSortURL}}" data-deleteAction="{{$deleteURL}}">
		<thead>
			<tr>
				<th>服務項目標題</th>
				<th>分類</th>
				<th width="60">瀏覽數</th>
				<th width="50">狀態</th>
				<th width="140">發表/更新日期</th>
				<th width="50">排序</th>
				<th width="210">功能</th>
			</tr>
		</thead>
		<tbody>
			@foreach($services as $service)
			<tr id='{{$service->id}}'>
				<td>{{$service->title}}</td>
				<td>
					@if($service->_parent != "")
					{{$category_array[$service->_parent]}}
					@endif
				</td>
				<td width="60">{{$service->views}}</td>
				<td width="50">
					@if($service->display === 'yes')
					<span style="color: #00AA00">顯示</span>
					@else
					隱藏
					@endif
				</td>
				<td width="140">{{$service->created_at}}<br/>{{$service->updated_at}}</td>
				<td width="50">{{$service->sort}}</td>
				<td width="210">
					<a href="{{$acrionURL}}/{{$service->id}}?lang={{$listLang}}" type="button" class="btn btn-sm btn-primary">修改</a>
					<a href="" type="button" class="btn btn-sm btn-danger btn-delete">刪除</a>
					@if($service->ref_display == 'yes')
					<a href='{{$acrionURL}}/{{$service->ref}}?lang={{$langControlGroup[$listLang]}}&category={{$category}}' type="button" class="btn btn-sm btn-warning">編輯@if($service->lang == 'tw')簡體@else繁體@endif語系</a>
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<input type="hidden" value='{{$category}}' id='category'/>
</div>
@include('spa_admin._partials.widget_pager', array('wp'=>$pagerParam))
@stop
@section('head')
{{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
@stop
@section('bottom')
{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
{{ HTML::script(asset('/spa_admin/js/service/js_article_list.js'))}}
<script type="text/javascript">
    var sortTable = _sortTable({el: '#sortable', role: 'article', sortColumn: 6, hasCategory: <?php echo (!empty($category))?'true':'false'?>});
    function langList(e) {
    	if(e.value == "tw")
    		document.location.href='{{$twListUrl}}';
    	else
    		document.location.href='{{$cnListUrl}}';
	}
</script>
@stop
