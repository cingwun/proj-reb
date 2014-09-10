@extends('spa_admin._layouts.default')

@section('title')
文章管理
@stop

@section('main')
<div class="col-lg-3">
	<a href="{{ URL::route('spa.admin.articles.list')}}/1">關於煥麗</a>
	<a href="{{ URL::route('spa.admin.articles.list')}}/2">最新消息</a>
	<a href="{{ URL::route('spa.admin.articles.list')}}/3">美麗分享</a>
	{{$category}}
</div>
<a href="{{ URL::route('spa.admin.articles.action')}}" class="btn" style="float: right;">新增</a>

<div class="row">
	<div class="col-lg-6">
		<h2>文章內容</h2>
		<div class="table-responsive">
			<table class="table table-bordered-table-hover">
				<thead>
					<tr>
						<td>分類</td>
						<td>標題</td>
						<td>上假日期</td>
						<td>狀態</td>
						<td>語言</td>
					</tr>
				</thead>
				<tbody>
					@foreach($selectedArticles as $articles)
					<tr>
						<td>{{ $articles->category}}</td>
						<td>{{ $articles->title}}</td>
						<td>{{ $articles->open_at}}</td>
						<td>{{ $articles->status}}</td>
						<td>{{ $articles->lan}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@stop