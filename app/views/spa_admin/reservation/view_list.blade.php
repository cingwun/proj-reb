@extends('spa_admin._layouts.default')

@section('title')
預約管理
@stop

@section('main')
<table class="table table-bordered table-hover" id="reservationTable" data-detailsAction="{{$details_url}}">
	<thead>
		<tr>
			<th>姓名</th>
			<th>國家</th>
			<th>聯絡方式(時段)</th>
			<th>生日</th>
			<th>E-Mail</th>
			<th>服務項目</th>
			<th>新增時間</th>
			<th>功能</th>
		</tr>
	</thead>
	<tbody>
		@foreach($reservation_list as $reservation)
		<tr id="{{$reservation->id}}">
			<td>{{$reservation->name}}</td>
			<td>{{$reservation->country}}</td>
			<td>{{$contact_array[$reservation->contact_style]}}&nbsp:&nbsp{{$reservation->contact_content}}&nbsp({{$contact_time_array[$reservation->contact_time]}})</td>
			<td>{{$reservation->birthday}}</td>
			<td>{{$reservation->email}}</td>
			<td>{{$reservation->improve_item}}</td>
			<td>{{$reservation->created_at}}</td>
			<td>
				<a href="#" type="button" class="btn btn-sm btn-primary btn-details">詳細資料</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@include('spa_admin._partials.widget_pager', array('wp'=>$pagerParam))

<script type="text/x-tmpl" id="tmpl-details">
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">{%=o.data.name%}-預約資料</h3>
    </div>
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>姓名</dt>
            <dd>{%=o.data.name%}</dd>

            <dt>國家</dt>
            <dd>{%=o.data.country%}</dd>

            <dt>聯絡方式</dt>
            <dd>{%=o.data.contact_style%}&nbsp:&nbsp{%=o.data.contact_content%}</dd>

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
<script type="text/javascript">
var reservationTable = function(o){
    o.init = function(){
    	var self = this;
    	self.$el = $(self.el);

    	self.resetTrCollection();
        self.ajaxdetailsURL = self.$el.attr('data-detailsAction');

        return self;
    }
    o.resetTrCollection = function(){
        var self = this, tr;

        self.$el.find('tbody tr').each(function(idx, tr){
            var $el = $(this);

            $el.find('.btn-details').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                $.ajax({
                    url: self.ajaxdetailsURL,
                    type: 'POST',
                    data: {id: tr.id},
                    dataType: 'json',
                    async: true,
                    success: function(res, s, xhr){
                        if (res.status=='ok'){
                            var content = tmpl('tmpl-details', res.data_str);
                            $.featherlight(content, {closeOnClick: false});
                        }
                    },
                    error: function(){
                        alert('提醒您:\n\n    系統刪除錯誤，請通知工程師');
                    }
                });
            });
        });
    }
    return o.init();
}({el: '#reservationTable'});
</script>
@stop