@extends('admin._layouts.default')
 
@section('main')
<h2>常見問題 - 分類列表</h2>

<div class="pull-right" ng-controller="faqsCtrl">
  @if (Input::get('category'))
  <a href ng-click="deletefaq_category({{ Input::get('category') }})" class="btn btn-danger">刪除此分類</a>
  <a href="{{ URL::to('admin/faqs/'.Input::get("category").'/edit?type=category') }}" class="btn btn-primary">修改此分類</a>
  @endif
  <a href="{{ URL::to('admin/faqs') }}" class="btn">文章列表</a>
  <a href="{{ URL::to('admin/faqs/create?type=category') }}" class="btn">新增分類</a>
</div>

<table class="table table-bordered" ng-controller="faqsCtrl">
<thead>
                <tr>
                  <th>分類標題</th>
                  <th width="200">功能</th>
                </tr>
              </thead>
<tbody id="sortable">
@foreach ($categories as $category)
                <tr id="{{ $category->id }}">
                  <td>{{ $category->title }}</td>
                  <td><a href="{{ URL::to('admin/faqs/'.$category->id.'/edit?type=category') }}" class="btn btn-primary">修改</a> <a href ng-click="deletefaq_category({{ $category->id }})" class="btn btn-danger">刪除</a></td>
                </tr>
@endforeach
</tbody>
</table>

@stop

@section('bottom')
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
@stop
