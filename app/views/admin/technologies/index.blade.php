@extends('admin._layouts.default')
 
@section('main')
<h2>美麗新技術</h2>
<div class="pull-right"><a href="{{ URL::to('admin/technologies/create') }}" class="btn">新增</a></div>
<table class="table table-bordered" ng-controller="technologiesCtrl">
<thead>
                <tr>
                  <th>圖片</th>
                  <th>標題</th>
                  <th width="50">狀態</th>
                  <th width="200">功能</th>
                </tr>
              </thead>
<tbody id="sortable">
@foreach ($technologies as $tech)
                <tr id="{{ $tech->id }}">
                  <td><a href="{{ $tech->link }}" target="{{ $tech->target }}"><img src="{{ $tech->image }}?w=200" style="max-width:200px;max-height:200px;" /></a></td>
                  <td>{{ $tech->title }}</td>
                  <td>@if($tech->status=='Y') <font color="#00AA00">顯示</font> @elseif($tech->status=='N') 隱藏 @endif</td>
                  <td><a href="{{ URL::to('admin/technologies/'.$tech->id.'/edit') }}" class="btn btn-primary">修改</a> <a href ng-click="deleteTechnology({{ $tech->id }})" class="btn btn-danger">刪除</a></td>
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
