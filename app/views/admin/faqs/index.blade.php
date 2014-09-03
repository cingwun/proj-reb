@extends('admin._layouts.default')

@section('main')
<h2>常見問題</h2>
<ul class="nav nav-pills">
  @foreach ( $faqs->categories as $category )
  <li @if (Input::get('category')==$category->id)class="active" @endif>
    <a href="{{ URL::to('admin/faqs?category='.$category->id) }}">{{ $category->title }}</a>
  </li>
  @endforeach
</ul>

<div class="pull-right" ng-controller="faqsCtrl">
  @if (Input::get('category'))
  <a href ng-click="deletefaq_category({{ Input::get('category') }})" class="btn btn-danger">刪除此分類</a>
  <a href="{{ URL::to('admin/faqs/'.Input::get("category").'/edit?type=category') }}" class="btn btn-primary">修改此分類</a>
  @endif
  <a href="{{ URL::to('admin/faqs?type=category') }}" class="btn">分類列表</a>
  <a href="{{ URL::to('admin/faqs/create') }}" class="btn">新增文章</a>
</div>

<table class="table table-bordered" ng-controller="faqsCtrl">
<thead>
                <tr>
                  <th>常見問題標題</th>
                  <th>分類</th>
                  <th width="50">瀏覽數</th>
                  <th width="50">狀態</th>
                  <th width="150">發表日期</th>
                  <th width="200">功能</th>
                </tr>
              </thead>
<tbody id="sortable">
@foreach ($faqs->get as $faq)
                <tr id="{{ $faq->id }}">
                  <td>{{ $faq->title }}</td>
                  <td>{{ faqsController::get_category($faq->_parent) }}</td>
                  <td>{{ $faq->views }}</td>
                  <td>@if($faq->status=='Y') <font color="#00AA00">顯示</font> @elseif($faq->status=='N') 隱藏 @endif</td>
                  <td>{{ date("Y-m-d",strtotime($faq->updated_at)) }}</td>
                  <td><a href="{{ URL::to('admin/faqs/'.$faq->id.'/edit') }}" class="btn btn-primary">修改</a> <a href ng-click="deletefaq({{ $faq->id }})" class="btn btn-danger">刪除</a></td>
                </tr>
@endforeach
</tbody>
</table>

@stop

@section('bottom')
@if($is_category==true)
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
        $helper.children().each(function(index)
        {
          // Set helper cell sizes to match the original sizes
          $(this).width($originals.eq(index).width());
        });
        return $helper;
      },
      update: function( event, ui ) {
        var sort = $(this).sortable("toArray").toString();
        $.ajax({
          type: "POST",
          url: "/admin/faqs/sort",
          data: { sort:sort }
        }).done(function( msg ) {
        });
      }
    });
    $( "#sortable" ).disableSelection();
  });
</script>
@endif
@stop
