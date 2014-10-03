@extends('admin._layouts.default')

@section('title')
美麗排行榜
@stop
@section('main')
<div class='col-lg-12'>
  <a href="{{ URL::to('admin/ranks/create') }}" class="btn pull-right btn-success">新增</a>
</div>
<div class='col-lg-12'>
  @foreach ($ranksLang as $ranks)
  <div class='col-lg-6'>
    {{$ranks['title']}}
    <table class="table table-bordered" ng-controller="ranksCtrl">
      <thead>
        <tr>
          <th>標題</th>
          <th>連結</th>
          <th width="130">功能</th>
        </tr>
      </thead>
      <tbody class="sortable">
        @foreach ($ranks['data'] as $rank)
        <tr id="{{ $rank->id }}">
          <td>{{ $rank->title }}</td>
          <td>{{ $rank->link }}</td>
          <td width="130">
            <a href="{{ URL::to('admin/ranks/'.$rank->id.'/edit') }}" class="btn btn-primary">修改</a>
            <a href ng-click="deleteRank({{ $rank->id }})" class="btn btn-danger">刪除</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endforeach
</div>
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
  $( ".sortable" ).sortable({
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
        url: "/admin/ranks/sort",
        data: { sort:sort }
      }).done(function( msg ) {
      });
    }
  });
  $( "#sortable" ).disableSelection();
});
</script>
@stop
