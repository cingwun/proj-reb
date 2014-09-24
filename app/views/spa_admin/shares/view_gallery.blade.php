@extends('spa_admin._layouts.default')

@section('title')
美麗見證&nbsp;-&nbsp;圖片集
@stop

@section('main')
<div>
    <div class="btn-group pull-left" style="margin: 0px 0px 10px;">
        <a href="{{ URL::route('spa.admin.share.gallery', array('1', 'lang'=>'tw'))}}" class="btn btn-info">顯示繁體圖片</a>
        <a href="{{ URL::route('spa.admin.share.gallery', array('1', 'lang'=>'cn'))}}" class="btn btn-warning">顯示簡體圖片</a>
        <a href="{{ URL::route('spa.admin.share.gallery', array('1', 'lang'=>''))}}" class="btn btn-default">顯示全部圖片</a>
    </div>
    <a href="{{ URL::route('spa.admin.share.gallery.action')}}" class="btn btn-success pull-right" style="margin: 0px 0px 10px;">新增</a>
</div>
<table class="table table-bordered" {{($lang=='tw'||$lang=='cn')?"id=\"sortable\"":''}} data-sortAction="{{ URL::route('spa.admin.share.sort.update', array('type'=>'gallery'))}}">
    <thead>
        <tr>
            <th>圖片</th>
            <th>標題</th>
            <th>[&nbsp;視窗&nbsp;]&nbsp;連結</th>
            <th>狀態</th>
            <th>排序</th>
            <th>編輯時間</th>
            <th>功能</th>
        </tr>
    </thead>
    <tbody id="sortable">
        @if(sizeof($photos)==0)   
        <tr><td colspan="7">目前暫無圖片</td></tr>
        @else                     
        @foreach($photos as $p)  
        <tr id="{{$p->id}}">
            <td width="120" align="center"><img src="{{ $p->imageURL}}?w=100" class="img-rounded"/></td>
            <td>{{ $p->title}}</td>
            <td>[&nbsp;<strong>{{ ($p->target=='_self')?'原視窗':'另開'}}</strong>&nbsp;]&nbsp;<a href="{{$p->link}}" target="_blank">{{ (mb_strlen($p->link)>30)?mb_substr($p->link, 0, 30):$p->link}}</a></td>
            <td>{{ ($p->status=='1') ? '<span style="color: #00AA00">顯示</span>' : '隱藏'}}</td>
            <td>{{ $p->sort}}</td>
            <td>{{ $p->updated_at}}</td>
            <td><a href="{{ URL::route('spa.admin.share.gallery.action', array('id'=>$p->id))}}" class="btn btn-primary">修改</a> <a href="{{ URL::route('spa.admin.share.gallery.delete', array('id'=>$p->id))}}" class="btn btn-danger btn-delete">刪除</a></td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@include('spa_admin._partials.widget_pager', array('wp'=>$wp))
@stop


@section('bottom')
{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
{{ HTML::script('/js/admin/wintness/js_gallery_list.js')}}
@stop
