@extends('admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-exclamation-sign"></i>
<?php echo ($type=='service') ? '服務項目' : '常見問題'?><?php echo (!empty($category)) ? sprintf(' ( %s ) ', $category) : ' '?>- 文章列表
@stop

@section('main')
<div class="col-lg-12">
    <?php
        $backLink = 'javascript:window.history.back()';
        $backLinkTitle = '回上一頁';
        if (isset($_GET['afterAction'])){
            $params = array('type'=>$type);
            if (isset($_GET['category']))
                 $params['category'] = (int) $_GET['category'];
            $backLink = URL::route('admin.service_faq.article.list', $params);
            $backLinkTitle = '回分類列表';
        }
    ?>
    <a href="<?php echo $backLink?>" class="btn btn-default pull-left"><?php echo $backLinkTitle?></a>
    <div class="col-md-2">
        <select class="form-control" onchange="langList(this)">
            <option value="tw" @if($articleLang == 'tw') selected @endif>繁體</option>
            <option value="cn" @if($articleLang == 'cn') selected @endif>簡體</option>
        </select>
    </div>
    <a href="<?php echo URL::route('admin.service_faq.article.action', array('type'=>$type))?>?langList=<?php echo $articleLang?>" class="btn btn-success pull-right">新增</a>
</div>
<div class="col-lg-12" id="clearTop">
    <table class="table table-bordered" id="sortable" data-sortAction="<?php echo URL::route('admin.service_faq.sort.update', array('type'=>$type))?>" data-deleteAction="<?php echo URL::route('admin.service_faq.delete', array('type'=>$type))?>">
        <thead>
            <tr>
              <th>服務項目標題</th>
              <th>分類</th>
              <th width="60">瀏覽數</th>
              <th width="50">狀態</th>
              <th width="140">發表/更新日期</th>
              <th width="50">排序</th>
              <th width="230">功能</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($articles as $article):?>
            <tr id="<?php echo $article->id?>">
                <td><?php echo $article->title?></td>
                <td><a href="<?php echo URL::route('admin.service_faq.article.list', array('type'=>$type, 'category'=>$article->_parent))?>"><?php echo $categories[$article->_parent]?></a></td>
                <td><?php echo $article->views?></td>
                <td><?php echo ($article->status=='Y') ? '<span style="color: #00AA00">顯示</span>' : '隱藏'?></td>
                <td><?php echo $article->created_at . '<br />' . $article->updated_at?></td>
                <td><?php echo $article->sort?></td>
                <td>
                    <a href="<?php echo URL::route('admin.service_faq.article.action', array('type'=>$type, 'id'=>$article->id))?>?langList=<?php echo $articleLang?>" class="btn btn-primary btn-modify">修改</a>
                    <span href="#" class="btn btn-danger btn-delete">刪除</span>
                    @if($article->ref_display == 'Y')
                    <a href='<?php echo URL::route('admin.service_faq.article.action', array('type'=>$type, 'id'=>$article->ref))?>?langList=<?php echo $langControlGroup[$articleLang]?>' type="button" class="btn btn-sm btn-warning">編輯@if($article->lang == 'tw')簡體@else繁體@endif</a>
                    @endif
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    @include('spa_admin._partials.widget_pager', array('wp'=>$pagerParam))
</div>
@stop

@section('head')
    {{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
@stop

@section('bottom')
    {{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js'))}}
    {{ HTML::script(asset('js/admin/service_faq/js_article_list.js'))}}
    <script type="text/javascript">
        var sortTable = _sortTable({el: '#sortable', role: 'article', sortColumn: 6, hasCategory: <?php echo (!empty($category))?'true':'false'?>});
        function langList(e) {
            if(e.value == "tw")
                document.location.href = '{{$twListUrl}}';
            else
                document.location.href = '{{$cnListUrl}}';
        }
    </script>
@stop
