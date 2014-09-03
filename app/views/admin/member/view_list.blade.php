@extends('admin._layouts.default')

@section('main')
    <h2>會員&nbsp;管理&nbsp;</h2>
    <div class="pull-left" style="margin: 0px 0px 10px;">
        <form action="<?=route('admin.member.list')?>" method="get" class="form-inline" role="form" id="search-form">
            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail2">搜尋會員</label>
                <input type="text" name="keyword" class="form-control" placeholder="輸入姓名關鍵字，至少兩個字" value="<?=Input::get('keyword', '')?>">
                <button type="submit" class="btn btn-default btn-search">搜尋</button>
            </div>
        </form>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>帳號/來源</th>
                <th>姓名/E-Mail</th>
                <th>生日/電話</th>
                <th>建立/編輯時間</th>
                <th>功能</th>
            </tr>
        </thead>
        <tbody>
            <?php if (sizeof($data)==0):?>
            <tr><td colspan="5">目前暫無會員</td></tr>
            <?php else:
                   foreach($data as $r):
            ?>
            <tr>
                <td><?=$r->uid?><br /><strong><?=$r->social?></strong></td>
                <td><?=$r->name . '<br /><strong>' . $r->email . '</strong>'?> </td>
                <td><?=$r->birthday . '<br /><strong>' . $r->phone . '</strong>'?></td>
                <td><?=$r->created_at . '<br /><strong>' . $r->updated_at . '</strong>'?></td>
                <td><a href="<?=URL::route('admin.member.action', array($r->id))?>" class="btn btn-primary">編輯</a></td>
            </tr>
            <?php   endforeach;
                endif;?>
        </tbody>
    </table>

    @include('admin._partials.widget_pager', array('wp'=>$wp))
@stop

@section('bottom')
@stop