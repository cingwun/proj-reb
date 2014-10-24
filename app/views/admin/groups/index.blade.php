@extends('admin._layouts.default')
 
@section('title')
群組管理
@stop

@section('main')
<div class="col-lg-12"><a class="btn btn-success pull-right" href="{{ URL::to('admin/groups/create')}} ">新增</a></div>
<div class="col-lg-12" id="clearTop">
<table class="table table-bordered" ng-controller="groupsCtrl">
<thead>
                <tr>
                  <th>名稱</th>
                  <th>功能</th>
                </tr>
              </thead>
<tbody id="sortable">
@foreach ($groups as $group)
                <tr>
                  <td>{{ $group->name }}</td>
                  <td><a href="{{ URL::to('admin/groups/'.$group->id.'/edit') }}" class="btn btn-primary">修改</a> <a href ng-click="deleteGroup({{ $group->id }})" class="btn btn-danger">刪除</a></td>
                </tr>
@endforeach
</tbody>
</table>
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
/*
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
          url: "/admin/permissions/sort",
          data: { sort:sort }
        }).done(function( msg ) {
        });
      }
    });
    $( "#sortable" ).disableSelection();
  });*/
</script>
@stop
