@extends('admin._layouts.default')

@section('main')
    <h2>美麗留言&nbsp;管理&nbsp;</h2>
    <div class="pull-left">
        <form action="<?=route('admin.board.list')?>" method="get" class="form-inline" role="form" id="search-form">
            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail2">搜尋主題</label>
                <input type="text" name="keyword" class="form-control" placeholder="輸入主題關鍵字，至少兩個字" value="<?=Input::get('keyword', '')?>">
                <button type="submit" class="btn btn-default btn-search">搜尋</button>
            </div>
        </form>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>主題/詢問人(性別)</th>
                <!--<th>性別</th>-->
                <th>E-Mail</th>
                <th>留言狀態</th>
                <th>回覆狀態</th>
                <th>建立/編輯時間</th>
                <th>功能</th>
            </tr>
        </thead>
        <tbody id="sortable">
            <?php if (sizeof($data)==0):?>
            <tr><td colspan="6">目前暫無留言</td></tr>
            <?php else:
                   foreach($data as $r):?>
            <tr>
                <td><?='<strong>'.$r->topic.'</strong><br />'.sprintf('%s(&nbsp;%s&nbsp;)', $r->name, ($r->gender=='m')?'男':'女')?></td>
                <td><?=$r->email?></td>
                <td><?=sprintf('%s&nbsp;/&nbsp;<a href="#" data-id="%d" style="text-decoration: none;" class="status">%s</a>', ($r->isPrivate=='0')?'私密':'公開', $r->id, ($r->status=='0')?'隱藏':'顯示')?></td>
                <td><?=($r->isReply=='0')?'未回覆':'已回覆';?></td>
                <td><?=$r->created_at.'<br />'.$r->updated_at?></td>
                <td><a href="<?=URL::route('admin.board.reply', array($r->id))?>" class="btn btn-primary">回覆</a></td>
            </tr>
            <?php   endforeach;
                endif;?>
        </tbody>
    </table>

    @include('admin._partials.widget_pager', array('wp'=>$wp))
@stop

@section('bottom')
    {{ HTML::script(asset('js/admin/board/js_list.js')) }}
    <script type="text/javascript">
        var JSVAR = <?=json_encode(array('ajaxURL'=>URL::route('admin.board.status'), '_token'=>csrf_token()))?>;
    </script>
@stop
