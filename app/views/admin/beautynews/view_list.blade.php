@extends('admin._layouts.default')

@section('main')
<h2>美麗新知</h2>
<div class="row" style="margin-bottom: 5px;">
    <div class="span12">
        <a href="javascript:window.history.back()" class="btn btn-inverse pull-right" style="float: left;">回上一頁</a>
        <a href="<?php echo URL::route('admin.beautynews.action')?>" class="btn" style="float: right;">新增</a>
    </div>
</div>
<div class="row">
    <div class="span12">
        <table class="table table-bordered" id="sortable" data-sortAction="<?php echo URL::route('admin.beautynews.sort.update')?>" data-deleteAction="<?php echo URL::route('admin.beautynews.delete')?>">
            <thead>
                <tr>
                    <th>封面/facebook圖片</th>
                    <th>[樣式] 標題</th>
                    <th>[視窗] 連結</th>
                    <th>狀態</th>
                    <th>排序</th>
                    <th>更新時間</th>
                    <th>功能</th>
                </tr>
            </thead>
            <tbody>
                <?php if (sizeof($articles)==0):?>
                <tr><td colspan="8">目前暫無任何新知文章</td></tr>
                <?php else:
                        foreach($articles as $article):
                            $cover = json_decode($article->cover);
                            $fb = json_decode($article->fb);
                ?>
                <tr id="<?php echo $article->id?>">
                    <td>
                        <?php if (is_array($cover)):?>
                        <img src="<?php echo  $cover[0]->image?>?w=90" alt="" class="img-rounded">
                        <?php endif;?>
                        <?php if (is_array($fb)):?>
                        <img src="<?php echo  $fb[0]->image?>?w=90" alt="" class="img-rounded">
                        <?php endif;?>
                    </td>
                    <td>[<?php echo $article->style?>]&nbsp;<?php echo $article->title?></td>
                    <td>[<?php echo ($article->target=='_self')?'原視窗':'另開視窗'?>]&nbsp;<?php echo (empty($article->link)) ? 'none' : sprintf('<a href="%s" target="_blank">%s</a>', $article->link, substr($article->link, 0, 30))?></td>
                    <td><?php echo ($article->status=='1') ? '<span style="color: #00AA00">顯示</span>' : '隱藏'?></td>
                    <td><?php echo $article->sort?></td>
                    <td><?php echo $article->updated_at?></td>
                    <td>
                        <a href="<?php echo URL::route('admin.beautynews.action', array('id'=>$article->id))?>" class="btn btn-primary btn-modify">修改</a>
                        <a href="#" class="btn btn-danger btn-delete">刪除</a>
                    </td>
                </tr>
                <?php   endforeach;
                      endif;?>
            </tbody>
        </table>
        @include('admin._partials.widget_pager', array('wp'=>$pagerParam))
    </div>
</div>
@stop

@section('bottom')
    {{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js')) }}
    {{ HTML::script(asset('js/admin/wintness/js_article_list.js')) }}
    <script type="text/javascript">
        var sortTable = _sortTable({el: '#sortable', role: 'article', sortColumn: 5});
    </script>
@stop
