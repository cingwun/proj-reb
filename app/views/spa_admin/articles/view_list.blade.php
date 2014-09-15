@extends('spa_admin._layouts.default')

@section('title')
文章管理
@stop

@section('main')
<div>
	<a type="button" @if($category==1)class="btn btn-lg btn-info" @else class="btn btn-lg btn-default" @endif href="{{ URL::route('spa.admin.articles.list')}}/1">關於煥麗</a>
	<a type="button" @if($category==2)class="btn btn-lg btn-info" @else class="btn btn-lg btn-default" @endif href="{{ URL::route('spa.admin.articles.list')}}/2">最新消息</a>
	<a type="button" @if($category==3)class="btn btn-lg btn-info" @else class="btn btn-lg btn-default" @endif href="{{ URL::route('spa.admin.articles.list')}}/3">美麗分享</a>
</div>



		<?php switch($selectedArticles[0]) {case 1:$cat = "關於煥麗";break; case 2:$cat = "最新消息";break; case 3:$cat = "美麗分享";break;} ?>
		<div>
			<a href="{{ URL::route('spa.admin.articles.action')}}/0/0/{{$selectedArticles[0]}}" class="btn btn-success" style="float:right;">新增</a>		
		</div>
		<div class="table-responsive">
			<table class="table table-bordered" id="sortable" data-sortAction="<?php echo URL::route('spaAdmin.service_faq.sort.update', array('type'=>$type))?>" data-deleteAction="<?php echo URL::route('admin.service_faq.delete', array('type'=>$type))?>">
			<table class="table table-bordered" ng-controller="ranksCtrl">
				<thead>
					<tr>
						<th>標題</th>
						<th>上架日期</th>
						<th>狀態</th>
						<th>時間</th>
						<th>語言</th>
					</tr>
				</thead>
				<tbody id="sortable">
					@foreach($selectedArticles[1] as $articles)
					<tr id="{{ $articles->id}}">
						<td>{{ $articles->title}}</td>
						<td>{{ $articles->open_at}}</td>
						<td><?php echo ($articles->status=='1') ?  '<span style="color: #00AA00">顯示</span>' : '隱藏'?></td>
						<td>{{ "建立:  ".$articles->created_at."<br>"."修改:  ".$articles->updated_at}}</td>
						<td>{{($articles->lan=='zh') ? '繁體' : '簡體'}}</td>
						<td><a type="button" href="{{ URL::route('spa.admin.articles.action')}}/{{$articles->id}}" class="btn btn-sm btn-primary">修改</a>
							<a type="button" href="{{ URL::route('spa.admin.articles.delete')}}/{{$articles->id}}" class="btn btn-sm btn-danger">刪除</a>
							@if($articles->ref_id==0)
							<a type="button" href="{{ URL::route('spa.admin.articles.action')}}/{{$articles->id}}/modifyLanguage" class="btn btn-sm btn-warning">新增{{($articles->lan=='zh') ? '簡體' : '繁體'}}語系</a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>


<!--{{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}	
{{ HTML::script(asset('js/admin/service_faq/js_category_list.js'))}}-->



@stop



@section('bottom')

	{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
    {{ HTML::script(asset('js/admin/service_faq/js_article_list.js'))}}
    <script type="text/javascript">
        var sortTable = _sortTable({el: '#sortable', role: 'article', sortColumn: 6, hasCategory: <?php echo (!empty($category))?'true':'false'?>});
    </script>

<!--{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'); }}
{{ HTML::script('packages/angularjs/angular.min.js'); }}
{{ HTML::script('js/admin/app.js'); }}

<style>
.table tbody tr 
{
  cursor:move;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<script>
$(function() {
    $( "#sortable" ).sortable({
    	helper: function(e, tr) {
    		var $originals = tr.children();
        	var $helper = tr.clone();
        	$helper.children().each(function(index){
        		// Set helper cell sizes to match the original sizes
        		$(this).width($originals.eq(index).width());
        	});
        return $helper;
    },
      update: function( event, ui ) {
      	var sort = $(this).sortable("toArray").toString();
      	console.log("a");
      	$.ajax({
      		type: "POST",
      		url: "/admin/spa/articles/sort",
      		data: { sort:sort }
      	}).done(function( msg ) {
        });
      }
    });
    $( "#sortable" ).disableSelection();
});
</script>-->

@stop