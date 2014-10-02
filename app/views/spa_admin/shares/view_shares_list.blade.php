@extends('spa_admin._layouts.default')

@section('title')
美麗分享
@stop

@section('main')
<div class="col-lg-12">
    <div>
        <a href="javascript:window.history.back()" class="btn btn-default pull-left">回上一頁</a>
        <div class="col-md-2">
            <select class="form-control" name="forma" onchange="location = this.options[this.selectedIndex].value;">
                <option value="{{URL::route('spa.admin.share.article.list', array('1', 'tw'))}}" <?php echo($lang=='tw')?'selected':''?>>顯示繁體</a></option>
                <option value="{{URL::route('spa.admin.share.article.list', array('1', 'cn'))}}" <?php echo($lang=='cn')?'selected':''?>>顯示簡體</a></option>
                <option value="{{URL::route('spa.admin.share.article.list', array('1'))}}" <?php echo($lang=='all')?'selected':''?>>顯示全部</a></option>
            </select>
        </div>
        <a href="{{ URL::route('spa.admin.share.article.action')}}" class="btn btn-md btn-success pull-right">新增</a>
    </div>
</div>
<br>
<div class="col-lg-12" id="clearTop">
    <div>
        <table class="table table-bordered" id="sortable" data-sortAction="{{ URL::route('spa.admin.share.sort.update', array('type'=>'article'))}}" data-deleteAction="{{ URL::route('spa.admin.share.article.delete')}}">
            <thead>
                <tr>
                  <th>封面圖片</th>
                  <th>標題/背景顏色</th>
                  <th>狀態</th>
                  <th>時間</th>
                  <th>瀏覽數</th>
                  <th hidden="hidden">排序</th>
                  <th>語言</th>
                  <th>功能</th>
                </tr>
            </thead>
            <tbody>
                <?php if (sizeof($articles)==0):?>
                <tr><td colspan="8">目前暫無任何文章</td></tr>
                <?php else:
                        foreach($articles as $article):
                            $cover = json_decode($article->cover);
                ?>
                <tr id="{{ $article->id}}">
                    <td>
                        @if(is_array($cover))  
                        <img src="{{ $cover[0]->image}}?w=90" alt="" class="img-rounded">
                        @endif
                    </td>
                    <td>{{ $article->title}}<br /><div style="width: 20px; height: 20px; background-color: {{ $article->background_color}}"></div></td>
                    <td>{{ ($article->status=='1') ? '<span style="color: #00AA00">顯示</span>' : '隱藏'}}</td>
                    <td>{{ "建立: ".$article->created_at."<br>更新: ".$article->updated_at}}</td>
                    <td>{{ $article->views}}</td>
                    <td hidden="hidden"><?php echo $article->sort?></td>
                    <td>{{($article->language=='tw') ? '繁體' : '簡體'}}</td>
                    <td>
                        <a href="<?php echo URL::route('spa.admin.share.article.action', array('id'=>$article->id))?>" class="btn btn-sm btn-primary btn-modify">修改</a>
                        <a href="#" class="btn btn-sm btn-danger btn-delete">刪除</a>
                        @if($article->reference==0)
                            <a type="button" href="{{ URL::route('spa.admin.share.article.action', array('id'=>$article->id, 'lang'=>$article->language))}}" class="btn btn-sm btn-warning">新增{{($article->language=='tw') ? '簡體' : '繁體'}}語系</a>
                        @endif
                    </td>
                </tr>
                <?php   endforeach;
                      endif;?>
            </tbody>
        </table>
        @include('spa_admin._partials.widget_pager', array('wp'=>$pagerParam))
    </div>
</div>
@stop

@section('head')
{{ HTML::style(asset('css/admin/service_faq/css_category_list.css'))}}
@stop

@section('bottom')
    {{ HTML::script(asset('packages/tableDnD/js/jquery.tablednd.0.8.min.js')) }}
    {{ HTML::script(asset('js/admin/wintness/js_article_list.js')) }}
    <script type="text/javascript">
        var sortTable = _sortTable({el: '#sortable', role: 'article', sortColumn: 6});
    </script>
@stop
