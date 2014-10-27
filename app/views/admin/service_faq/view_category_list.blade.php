@extends('admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-exclamation-sign"></i>
<?php echo ($type=='service') ? '服務項目' : '常見問題'?> - 分類列表
@stop

@section('main')
<div class='col-lg-12'>
    <a href='<?php echo URL::route('admin.service_faq.category.action', array('type'=>$type))?>' type="button" class="btn btn-success pull-right">新增</a>
</div>
<div class="col-lg-6">
    <div><label>繁體列表</label></div>
    <table class="table table-bordered" id="sortableTW" data-sortAction="<?php echo URL::route('admin.service_faq.sort.update', array('type'=>$type))?>" data-deleteAction="<?php echo URL::route('admin.service_faq.delete', array('type'=>$type))?>">
        <thead>
            <tr>
                <th>分類標題</th>
                <th>排序</th>
                <th>狀態</th>
                <th width="200">功能</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cats['twList'] as $cat):
                    $link = URL::route('admin.service_faq.article.list', array('type'=>$type, 'category'=>$cat->id, 'langList'=>'tw'));
            ?>
            <tr id="<?php echo $cat->id?>">
                <td><?php echo $cat->title?></td>
                <td><?php echo $cat->sort?></td>
                <td>
                    @if($cat->status == 'Y')
                    <span style="color: #00AA00">顯示</span>
                    @else
                    隱藏
                    @endif
                </td>
                <td>
                    <a href="<?php echo $link?>" title="<?php echo $cat->title?>相關文章" class="btn btn-success">文章</a>
                    <a href="<?php echo URL::route('admin.service_faq.category.action', array('type'=>$type, 'id'=>$cat->id))?>" class="btn btn-primary">編輯</a>
                    <span href="#" class="btn btn-danger btn-delete">刪除</span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="col-lg-6">
    <div><label>簡體列表</label></div>
    <table class="table table-bordered" id="sortableCN" data-sortAction="<?php echo URL::route('admin.service_faq.sort.update', array('type'=>$type))?>" data-deleteAction="<?php echo URL::route('admin.service_faq.delete', array('type'=>$type))?>">
        <thead>
            <tr>
                <th>分類標題</th>
                <th>排序</th>
                <th>狀態</th>
                <th width="200">功能</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cats['cnList'] as $cat):
                    $link = URL::route('admin.service_faq.article.list', array('type'=>$type, 'category'=>$cat->ref, 'langList'=>'cn'));
            ?>
            <tr id="<?php echo $cat->id?>">
                <td><?php echo $cat->title?></td>
                <td><?php echo $cat->sort?></td>
                <td>
                    @if($cat->status == 'Y')
                    <span style="color: #00AA00">顯示</span>
                    @else
                    隱藏
                    @endif
                </td>
                <td>
                    <a href="<?php echo $link?>" title="<?php echo $cat->title?>相關文章" class="btn btn-success">文章</a>
                    <a href="<?php echo URL::route('admin.service_faq.category.action', array('type'=>$type, 'id'=>$cat->id))?>" class="btn btn-primary">編輯</a>
                    <span href="#" class="btn btn-danger btn-delete">刪除</span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!--
<div class="col-lg-4" id="form-panel">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="panel-title">新增分類</th>
            </tr>
        </thead>
        <tbody><tr><td>
            <form name="form-category" action="<?php echo URL::route('admin.service_faq.category.update', array('type'=>$type))?>" method="post" >
                <div class="form-group">
                    <label class="control-label" for="title">類別標題</label>
                    <input type="text" class="form-control" name="title" size="12" value="" />
                    <label class="control-label" for="title">排序 (輸入數字)</label>
                    <input type="text" class="form-control" name="sort" size="12" value="1" />
                    <input type="hidden" name="id" value="null" />
                </div>
                <button class="btn-reset btn" type="button">重設</button> <button class="btn btn-inverse btn-submit">新增</button>
            </form>
        </td></tr></tbody>
    </table>
</div>-->
@stop

@section('head')
{{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
@stop

@section('bottom')
{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
{{ HTML::script(asset('spa_admin/js/service/js_category_list.js'))}}
<script type="text/javascript">
    var tableTW = _sortTable({el: '#sortableTW', role: 'category', sortColumn: 2});
    var tableCN = _sortTable({el: '#sortableCN', role: 'category', sortColumn: 2});
</script>
@stop
