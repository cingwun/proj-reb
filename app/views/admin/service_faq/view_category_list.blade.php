@extends('admin._layouts.default')

@section('main')
<h2><?php echo ($type=='service') ? '服務項目' : '常見問題'?> - 分類列表</h2>
<div class="row">
    <div class="span8">
        <table class="table table-bordered" id="sortable" data-sortAction="<?php echo URL::route('admin.service_faq.sort.update', array('type'=>$type))?>" data-deleteAction="<?php echo URL::route('admin.service_faq.delete', array('type'=>$type))?>">
            <thead>
                <tr>
                    <th>分類標題</th>
                    <th>排序</th>
                    <th width="200">功能</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cats as $cat):
                        $link = URL::route('admin.service_faq.article.list', array('type'=>$type, 'category'=>$cat->id));
                ?>
                <tr id="<?php echo $cat->id?>">
                    <td><?php echo $cat->title?></td>
                    <td><?php echo $cat->sort?></td>
                    <td>
                        <a href="<?php echo $link?>" title="<?php echo $cat->title?>相關文章" class="btn btn-success">文章</a>
                        <span class="btn btn-primary btn-modify">修改</span>
                        <span href="#" class="btn btn-danger btn-delete">刪除</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span4" id="form-panel">
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
    </div>
</div>
@stop

@section('head')
{{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
@stop

@section('bottom')
{{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
{{ HTML::script(asset('js/admin/service_faq/js_category_list.js'))}}
@stop
