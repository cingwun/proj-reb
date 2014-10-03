@extends('admin._layouts.default')

@section('title')
預約管理
@stop

@section('main')
<table class="table table-bordered" ng-controller="">
  <thead>
    <tr>
      <th>姓名</th>
      <th>性別</th>
      <th>電話</th>
      <th>Email</th>
      <th>預約項目</th>
      <th>新增時間</th>
    </tr>
  </thead>
  <tbody id="sortable">
    @foreach ($reservations as $reservation)
    <tr>
      <td>{{ $reservation->name }}</td>
      <td>{{ $reservation->sex }}</td>
      <td>{{ $reservation->phone }}</td>
      <td>{{ $reservation->email }}</td>
      <td>{{ $reservation->note }}</td>
      <td>{{ $reservation->created_at }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@include('spa_admin._partials.widget_pager', array('wp'=>$pagerParam))
@stop

@section('bottom')
@stop
