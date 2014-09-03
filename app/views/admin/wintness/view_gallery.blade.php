@extends('admin._layouts.default')

@section('main')
    <h2>美麗見證&nbsp;-&nbsp;圖片集</h2>
    <div class="pull-right" style="margin: 0px 0px 10px;"><a href="<?=URL::route('admin.wintness.gallery.action')?>" class="btn">新增</a></div>
    <table class="table table-bordered" id="sortable" data-sortAction="<?php echo URL::route('admin.wintness.sort.update', array('type'=>'gallery'))?>">
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
            <?php if (sizeof($photos)==0):?>
            <tr><td colspan="7">目前暫無圖片</td></tr>
            <?php else:?>
            <?php   foreach($photos as $p):?>
            <tr id="<?=$p->id?>">
                <td width="120" align="center"><img src="<?=$p->imageURL?>?w=100" class="img-rounded"/></td>
                <td><?=$p->title?></td>
                <td>[&nbsp;<strong><?=($p->target=='_self')?'原視窗':'另開';?></strong>&nbsp;]&nbsp;<a href="<?=$p->link?>" target="_blank"><?=(mb_strlen($p->link)>30)?mb_substr($p->link, 0, 30):$p->link;?></a></td>
                <td><?=($p->status=='1') ? '顯示' : '隱藏'?></td>
                <td><?=$p->sort?></td>
                <td><?=$p->updated_at?></td>
                <td><a href="<?=URL::route('admin.wintness.gallery.action', array('id'=>$p->id))?>" class="btn btn-primary">修改</a> <a href="<?=URL::route('admin.wintness.gallery.delete', array('id'=>$p->id))?>" class="btn btn-danger btn-delete">刪除</a></td>
            </tr>
            <?php   endforeach;
                endif;
            ?>
        </tbody>
    </table>
    @include('admin._partials.widget_pager', array('wp'=>$wp))
@stop


@section('bottom')
    {{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
    {{ HTML::script('/js/admin/wintness/js_gallery_list.js')}}
@stop
