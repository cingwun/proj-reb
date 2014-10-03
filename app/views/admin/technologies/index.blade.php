@extends('admin._layouts.default')

@section('title')
美麗新技術
@stop

@section('main')
<div class="col-lg-12">
  <a href="{{ URL::to('admin/technologies/create') }}" class="btn pull-right btn-success">新增</a>
</div>
<div class="col-lg-12">
  @foreach($techsLang as $techs)
  <div class="col-lg-6">
    {{$techs['title']}}
    <table class="table table-bordered" ng-controller="technologiesCtrl">
      <thead>
        <tr>
          <th>圖片</th>
          <th>標題</th>
          <th width="50">狀態</th>
          <th width="130">功能</th>
        </tr>
      </thead>
      <tbody id="sortable">
        @foreach ($techs['data'] as $tech)
        <tr id="{{ $tech->id }}">
          <td><a href="{{ $tech->link }}" target="{{ $tech->target }}"><img src="{{ $tech->image }}?w=200" style="max-width:200px;max-height:200px;" /></a></td>
          <td>{{ $tech->title }}</td>
          <td>@if($tech->status=='Y') <font color="#00AA00">顯示</font> @elseif($tech->status=='N') 隱藏 @endif</td>
          <td><a href="{{ URL::to('admin/technologies/'.$tech->id.'/edit') }}" class="btn btn-primary">修改</a> <a href ng-click="deleteTechnology({{ $tech->id }})" class="btn btn-danger">刪除</a></td>
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
        url: "/admin/technologies/sort",
        data: { sort:sort }
      }).done(function( msg ) {
      });
    }
  });
  $( "#sortable" ).disableSelection();
});
</script>
@stop
