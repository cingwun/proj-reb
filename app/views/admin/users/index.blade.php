@extends('admin._layouts.default')
 
@section('main')
<h2>使用者列表{{ Session::get('where')}}</h2>
<div class="pull-right"><a href="{{ URL::to('admin/users/create') }}" class="btn">新增使用者</a></div>
<table class="table table-bordered" ng-controller="usersCtrl">
<thead>
                <tr>
                  <th>Email</th>
                  <th>姓名</th>
                  <th>權限</th>
                  <th>最後登入時間</th>
                  <th>狀態</th>
                  <th>功能</th>
                </tr>
              </thead>
<tbody>
@foreach ($users as $user)
                <tr>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->last_name }}</td>
                  <td>
                  @foreach ($user->groups()->get() as $group)
	          {{ $group->name }}
                  @endforeach
                  </td>
                  <td>{{ $user->last_login }}</td>
                  <td>
                  {{ ($user->activated == 1)?'ON':'OFF' }}
                  </td>
                  <td><a href="{{ URL::to('admin/users/'.$user->id.'/edit') }}" class="btn btn-primary">修改</a> <a href ng-click="deleteUser('{{ $user->id}}')" class="btn btn-danger">刪除</a></td>
                </tr>
@endforeach
</tbody>
</table>
@stop
