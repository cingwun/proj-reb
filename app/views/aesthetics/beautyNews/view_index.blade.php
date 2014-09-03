@extends('aesthetics._layouts.default')

@section('bodyId'){{$bodyId}}@stop

@section('headContent')
<?php
    if (!isset($pagerParams)):
        list($fb) = json_decode($articles[0]->fb);
        echo sprintf('<meta property="og:url" content="%s" />', \URL::route('frontend.beautynews.article', array('id'=>$articles[0]->id)));
        echo sprintf('<meta property="og:title" content="%s" />', $articles[0]->title);
        echo sprintf('<meta property="og:description" content="%s" />', strip_tags($fb->text));
        echo sprintf('<meta property="og:image" content="%s" />', 'http://' . $_SERVER['HTTP_HOST'] . $fb->image);
    endif;
?>
@stop

@section('mainContent')
<div id="mainContent" class="postBox" role="main">
    <div class="breadcrumb">
        <a href="/">首頁</a><span class="arrow"></span>
        <a>美麗新知</a>
    </div>
    <!-- breadcrumb end -->
    <?php if (sizeof($articles)==0):?>
    <div clsss="" style="text-align: center;">目前暫無任何新知文章唷!敬請期待...</div>
    <?php else:?>
    <ul class="bnList">
        <?php foreach($articles as $article):
                $cover = json_decode($article->cover);
                $fb = json_decode($article->fb);
                $link = $article->link;
                $shareLink = $article->link;
                $articleLink = sprintf('%s/beautynews/article/%d', 'http://'.$_SERVER['HTTP_HOST'], $article->id);
                if (empty($article->link)){
                    $link = '/beautynews';
                    $shareLink = $articleLink;
                }
                if ($article->style=='1'):
        ?>
        <li><a href="<?php echo $link?>" target="<?php echo $article->target?>" title="<?php echo $article->title?>">
            <h3><?php echo $article->title?></h3>
            <img src="<?php echo $cover[0]->image . '?w=650&h=150'?>" width="650" height="150" alt="<?php echo $cover[0]->text?>" class="cover"/>
            <p class="context"><?php echo strip_tags($article->description)?></p></a>
            <div class="funBar" data-shareURL="<?php echo $shareLink?>" data-shareTitle="<?php echo $fb[0]->text?>" data-shareImage="<?php echo 'http://'.$_SERVER['HTTP_HOST'] . $fb[0]->image?>">
                <iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode($articleLink)?>&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=435336489944403" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width: 75px; height:21px;" allowTransparency="true"></iframe>
                <a href="#" class="btn-facebook"><i class="fa fa-facebook"></i></a>
                <a href="#" class="btn-google"><i class="fa fa-google-plus-square"></i></a>
                <a href="#" class="btn-weibo"><i class="fa fa-weibo"></i></a>
            </div>
        </li>
        <?php   endif;
               endforeach;?>
    </ul>
    <?php endif;?>

    <?php if (isset($pagerParams)): ?>
        @include('aesthetics._partials.widget_pager', $pagerParams)
    <?php endif;?>
</div>
<!-- mainContent end -->

@stop

@section('bottomContent')
    {{ HTML::script('js/beautyNews/js_index.js'); }}
    <script type="text/javascript">
        var shares = [];
        $('ul.bnList li').each(function(idx, el){

            var share = _share({$el: $(el).find('.funBar')});
            shares.push(share);
        });
    </script>
    @parent
@stop
