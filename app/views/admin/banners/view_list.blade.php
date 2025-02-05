@extends($layout)

@section('title')
<i class="glyphicon glyphicon-picture"></i> Banner管理 -&nbsp;(&nbsp;<?=$size['text']?>&nbsp;)
@stop

@section('main')
<div class="col-lg-12" >
    <input type="hidden" value="{{URL::route('admin.banners.many.delete')}}" name="manyDeleteURL">
    <button onclick="manyDelete()" class="btn btn-danger pull-left" >多項刪除</button>
    <a href="<?=URL::route('admin.banners.action', array($size['value'], 0))?>" class="btn btn-success pull-right">新增</a>
</div>
<div class="col-lg-12" id="clearTop">
    <table class="table table-bordered" ng-controller="articlesCtrl">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll">全選</th>
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
                <td><input type="checkbox" name="waitForDelete" value="{{$r->bid}}" ajaxDeleteURL="{{URL::route('admin.banners.many.delete')}}"></td>
                <td width="120" align="center"><img src="<?=$r->image?>?w=100" class="img-rounded"/></td>
                <td><?=$r->title?></td>
                <td>[&nbsp;<strong><?=($r->target=='_self')?'原視窗':'另開';?></strong>&nbsp;]&nbsp;<a href="<?=$r->link?>" target="_blank"><?=(mb_strlen($r->link)>30)?mb_substr($r->link, 0, 30):$r->link;?></a></td>
                <td><?=($r->on_time==0) ? '不指定' : date('Y-m-d', (int) $r->on_time)?></td>
                <td><?=($r->off_time==0) ? '不指定' : date('Y-m-d', (int) $r->off_time)?></td>
                <td><?=($r->status==1) ? '顯示' : '隱藏'?></td>
                <td><?=$r->updated_at?></td>
                <td><a href="<?=URL::route('admin.banners.action', array($size['value'], $r->bid))?>" class="btn btn-primary">修改</a> <a href="<?=URL::route('admin.banners.delete', array($size['value'], $r->bid))?>" class="btn btn-danger btn-delete">刪除</a></td>
            </tr>
            <?php   endforeach;
                endif;
            ?>
        </tbody>
    </table>
</div>
@include('spa_admin._partials.widget_pager', array('wp'=>$wp))
@stop
@section('bottom')
    <script type="text/javascript">
        $(function(){
            $('.btn-delete').on('click', function(e){
                return (confirm("提醒您:\n\n\t確定要刪除該Banner資料嗎?"));
            });
        });


        function manyDelete() {
            var url = $('input[name=manyDeleteURL]').val();
            var ids = [];
            $('input:checkbox:checked[name="waitForDelete"]').each(function(i) {
                ids[i] = (this.value);
            });
            if(ids.length==0) {
                alert('請勾選欲刪除之項目');
                exit;
            }
            if(confirm("提醒您:\n\n\t確定要刪除選取之資料嗎?")) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        ids: ids
                    },
                    dataType: 'json',
                    success: function(res, s, xhr){
                        alert(res.message);
                        if (res.status=='ok')
                            window.location.reload();
                        return;
                    },
                    error: function(){
                        alert('提醒您:\n\n    系統刪除錯誤，請通知工程師');
                    }
                });
            }
        }

        var checkbox = function(tick){
            $('input[type=checkbox]').each(function(idx, obj){
                if(obj.checked!=tick)
                    obj.checked = tick;
            })
        }
        $('#checkAll').click(function(){
            var tick = this.checked;
            checkbox(tick);
        });
        var allCmount = $('input[name=waitForDelete]').size();
        $('input[name=waitForDelete]').click(function(e){
            var cal = 0;
            $('input[name=waitForDelete]').each(function(idx, obj){
                if(obj.checked)
                    cal++;
            });
            $('#checkAll')[0].checked = (cal==allCmount);
        });

    </script>
@stop
