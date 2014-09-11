@extends('spa_admin._layouts.default')

@section('title')
文章管理
@stop

@section('main')
<div class="col-lg-3">
	<a href="{{ URL::route('spa.admin.articles.list')}}/1">關於煥麗</a>
	<a href="{{ URL::route('spa.admin.articles.list')}}/2">最新消息</a>
	<a href="{{ URL::route('spa.admin.articles.list')}}/3">美麗分享</a>
	
</div>
<a href="{{ URL::route('spa.admin.articles.action')}}" class="btn" style="float: right;">新增</a>

<div class="row">
	<div class="col-lg-6">
		<?php switch($category) {case 1:$cat = "關於煥麗";break; case 2:$cat = "最新消息";break; case 3:$cat = "美麗消息";break;} ?>
		<h2>{{$cat}}</h2>
		<div class="table-responsive">
			<table class="table table-bordered-table-hover">
				<thead>
					<tr>
						<th>分類</th>
						<th>標題</th>
						<th>上假日期</th>
						<th>狀態</th>
						<th>語言</th>
					</tr>
				</thead>
				<tbody>
					@foreach($selectedArticles as $articles)
					<tr>
						<td>{{ $cat}}</td>
						<td>{{ $articles->title}}</td>
						<td>{{ $articles->open_at}}</td>
						<td><?php echo ($articles->status=='1') ?  '<span style="color: #00AA00">顯示</span>' : '隱藏'?></td>
						<td>{{ ($articles->lan=='zh') ? '繁體' : '簡體'}}</td>
						<td><a type="button" href="{{ URL::route('spa.admin.articles.action')}}/{{$articles->id}}" class="btn btn-sm btn-primary">修改</a>&nbsp;
							<a type="button" href="{{ URL::route('spa.admin.articles.delete')}}/{{$articles->id}}" class="btn btn-sm btn-danger">刪除</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@stop