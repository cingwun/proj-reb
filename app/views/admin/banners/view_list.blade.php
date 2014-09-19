@extends('admin._layouts.default')

@section('main')
    <h2>Banner&nbsp;管理&nbsp;(&nbsp;<?=$size['text']?>&nbsp;)</h2>
    <div class="pull-right" style="margin: 0px 0px 10px;"><a href="<?=URL::route('admin.banners.action', array($size['value'], 0, 'where'=>Input::get('where')))?>" class="btn">新增</a></div>
    <table class="table table-bordered" ng-controller="articlesCtrl">
        <thead>
            <tr>
                <th>Banner</th>
                <th>標題</th>
                <th>[&nbsp;視窗&nbsp;]&nbsp;連結</th>
                <th>上線日期</th>
                <th>下線日期</th>
                <th>狀態</th>
                <th>編輯時間</th>
                <th>功能</th>
            </tr>
        </thead>
        <tbody id="sortable">
            <?php if (sizeof($data)==0):?>
            <tr><td colspan="5">目前暫無Banner</td></tr>
            <?php else:?>
            <?php   foreach($data as $r):?>
            <tr>
                <td width="120" align="center"><img src="<?=$r->image?>?w=100" class="img-rounded"/></td>
                <td><?=$r->title?></td>
                <td>[&nbsp;<strong><?=($r->target=='_self')?'原視窗':'另開';?></strong>&nbsp;]&nbsp;<a href="<?=$r->link?>" target="_blank"><?=(mb_strlen($r->link)>30)?mb_substr($r->link, 0, 30):$r->link;?></a></td>
                <td><?=($r->on_time==0) ? '不指定' : date('Y-m-d', (int) $r->on_time)?></td>
                <td><?=($r->off_time==0) ? '不指定' : date('Y-m-d', (int) $r->off_time)?></td>
                <td><?=($r->status==1) ? '顯示' : '隱藏'?></td>
                <td><?=$r->updated_at?></td>
                <td><a href="<?=URL::route('admin.banners.action', array($size['value'], $r->bid, 'where'=>Input::get('where')))?>" class="btn btn-primary">修改</a> <a href="<?=URL::route('admin.banners.delete', array($size['value'], $r->bid, 'where'=>Input::get('where')))?>" class="btn btn-danger btn-delete">刪除</a></td>
            </tr>
            <?php   endforeach;
                endif;
            ?>
        </tbody>
    </table>
    @include('admin._partials.widget_pager', array('wp'=>$wp))
@stop
@section('bottom')
    <script type="text/javascript">
        $(function(){
            $('.btn-delete').on('click', function(e){
                return (confirm("提醒您:\n\n\t確定要刪除該Banner資料嗎?"));
            });
        });
    </script>
@stop
