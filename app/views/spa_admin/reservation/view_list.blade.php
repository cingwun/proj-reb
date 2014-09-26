@extends('spa_admin._layouts.default')

@section('title')
預約管理
@stop

@section('main')
<div>
    <a href='{{$actionURL}}' type="button" class="btn btn-success pull-right">新增</a>
</div>
<table class="table table-bordered table-hover" id="reservationTable" data-detailsAction="{{$detailsURL}}" data-deleteAction="{{$deleteURL}}">
	<thead>
		<tr>
			<th>姓名</th>
            <th>性別</th>
			<th>國家</th>
			<th>聯絡方式</th>
            <th>時段</th>
			<th>E-Mail</th>
            <th>諮詢／療程時間</th>
			<th>新增時間</th>
			<th>功能</th>
		</tr>
	</thead>
	<tbody>
		@foreach($reservations as $key => $reservation)
		<tr id="{{$reservation->id}}">
			<td>{{$reservation->name}}</td>
            <td>{{$sexArray[$reservation->sex]}}</td>
			<td>@if($reservation->country != ""){{$reservation->country}} @endif</td>
			<td>
            @for ($i = 0; $i < $contactArray[$key]['count']; $i++)
                {{$styleArray[$i]}}{{$contactArray[$key]['data'][$i]}}a<br/>
            @endfor
            </td>
            <td>@if($reservation->contact_time != ""){{$contactTimeArray[$reservation->contact_time]}} @endif</td>
			<td>{{$reservation->email}}</td>
            <td>{{$reservation->service_date}}</td>
			<td>{{$reservation->created_at}}<br/>{{$reservation->updated_at}}</td>
			<td>
				<a href="#" type="button" class="btn btn-sm btn-warning btn-details">詳細資料</a>
                <a href="{{$actionURL}}/{{$reservation->id}}" type="button" class="btn btn-sm btn-primary">修改</a>
                <a href="#" type="button" class="btn btn-sm btn-danger btn-delete">刪除</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@include('spa_admin._partials.widget_pager', array('wp'=>$pagerParam))

<script type="text/x-tmpl" id="tmpl-details">
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">{%=o.data.name%}-基本資料</h3>
    </div>
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>姓名</dt>
            <dd>{%=o.data.name%}</dd>

            <dt>國家</dt>
            <dd>{%=o.data.country%}</dd>

            <dt>聯絡方式</dt>
            <dd>
                {% for (var i=0; i<o.data.contact.count; i++) { %}
                    <div>{%=o.data.styleArray[i]%}{%=o.data.contact.data[i]%}</div>
                {% } %}
            </dd>

            <dt>方便聯繫的時間</dt>
            <dd>{%=o.data.contact_time%}</dd>

            <dt>生日</dt>
            <dd>{%=o.data.birthday%}</dd>

            <dt>E-Mail</dt>
            <dd>{%=o.data.email%}</dd>

            <dt>在台停留時間</dt>
            <dd>
                {%=o.data.stay_start_date%}<br/>
                {%=o.data.stay_exit_date%}
            </dd>

            <dt>諮詢／療程時間</dt>
            <dd>{%=o.data.service_date%}</dd>
        </dl>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">諮詢資料</h3>
    </div>
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>想改善的項目</dt>
            <dd>{%=o.data.improve_item%}</dd>

            <dt>其他補充</dt>
            <dd>{%=o.data.other_notes%}</dd>
        </dl>
    </div>
</div>
</script>
@stop
@section('head')
{{ HTML::style(asset('spa_admin/js/plugins/featherlight/featherlight.min.css'))}}
<style type="text/css">
.result{
    display: none;
}
</style>
@stop
@section('bottom')
{{ HTML::script(asset('spa_admin/js/plugins/featherlight/jquery-latest.js'))}}
{{ HTML::script(asset('spa_admin/js/plugins/featherlight/featherlight.min.js'))}}
{{ HTML::script(asset('spa_admin/js/plugins/templates/tmpl.min.js'))}}
{{ HTML::script(asset('spa_admin/js/reservation/js_reservation_list.js'))}}
@stop