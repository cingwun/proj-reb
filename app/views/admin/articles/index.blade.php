@extends('admin._layouts.default')

@section('title')
文章管理
@stop


@section('main')

<?php 

$cc = $category;

?>

<div class="col-lg-12">
  <ul class="nav nav-pills" style="float: left">
    @foreach ( helper::article_category() as $key=>$category )
    <li @if (Input::get('category')==$key)class="active" @endif>
      <a href="{{ URL::to('admin/articles?category='.$key.'&lang=all') }}">{{ $category }}</a>
    </li>
    @endforeach
  </ul>
  <div class="col-md-2" style="float: left">
      <select class="form-control" name="forma" onchange="location = this.options[this.selectedIndex].value;">
        <option value="{{ 'articles?category='.$cc.'&lang=tw' }}" <?php echo (Input::get('lang')=='tw')?'selected':''; ?>>顯示繁體</a></option>
        <option value="{{ 'articles?category='.$cc.'&lang=cn' }}" <?php echo (Input::get('lang')=='cn')?'selected':''; ?>>顯示簡體</a></option>
        <option value="{{ 'articles?category='.$cc.'&lang=all' }}" <?php echo (Input::get('lang')=='all')?'selected':''; ?>>顯示全部</a></option>
      </select>
    </div>
  <div class="pull-right"><a href="{{ URL::to('admin/articles/create?category='.Input::get('category').'&lang='.Input::get('lang')) }}" class="btn btn-success">新增</a></div>
</div>
<table class="table table-bordered" ng-controller="articlesCtrl" id="clearTop">
<thead>
                <tr>
                  <th>標題</th>
                  <th>分類</th>
                  <th>上架日期</th>
                  <th>狀態</th>
                  <th>瀏覽數</th>
                  <th>語言</th>
                  <th>功能</th>
                </tr>
              </thead>
<tbody id="sortable">
@foreach ($articles as $article)
                <tr id="{{ $article->id }}">
                  <td>{{ $article->title }}</td>
                  <td>{{ helper::article_category($article->category) }}</td>
                  <td>{{ $article->open_at }}</td>
                  <td>{{ ($article->status=='1')?'<span style="color: #00AA00">顯示</span>':'隱藏' }}</td>
                  <td>{{ $article->views }}</td>
                  <td>{{ ($article->lang=='tw') ? '繁體' : '簡體' }}</td>
                  <td><a href="{{ URL::to('admin/articles/'.$article->id.'/edit?category='.Input::get('category').'&lang='.Input::get('lang')) }}" class="btn btn-primary">修改</a> <a href ng-click="deleteArticle({{ $article->id }})" class="btn btn-danger">刪除</a></td>
                </tr>
@endforeach
</tbody>
</table>
<?php
//Here is the acient method to set pager.
// if(!Input::get('category') || Input::get('category')==3)
//   echo $articles->appends(array('category' => Input::get('category')))->links() ;
?>
@include('spa_admin._partials.widget_Rarticle_pager', array('wp'=>$wp))
@stop

@section('bottom')
@if(Input::get('category')==1 || Input::get('category')==2)
<style>
.table tbody tr
{
  cursor:move;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
  $(function() {
    $( "#sortable" ).sortable({
      helper: function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index)
        {
          // Set helper cell sizes to match the original sizes
          $(this).width($originals.eq(index).width());
        });
        return $helper;
      },
      update: function( event, ui ) {
        var sort = $(this).sortable("toArray").toString();

        $.ajax({
          type: "POST",
          url: "/admin/articles/sort",
          data: { sort:sort }
        }).done(function( msg ) {
        });
      }
    });
    $( "#sortable" ).disableSelection();
  });
</script>
@endif
@stop
