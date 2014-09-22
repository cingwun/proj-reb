@extends('aesthetics._layouts.default')

@section('bodyId'){{'index'}}@stop

@section('mainBanner')
    @include('aesthetics._partials.banner', array('size'=>'large'))
@stop

@section('mainContent')
<div id="mainContent" role="main">
    <article class="newsList">
        <h2 class="titleRp h2_news">最新消息</h2>
        <div class="funcBar"><a href="/news" class="more">more</a></div>
        <ul class="infoList">
            <?php foreach($newses as $idx=>$news):
                    $class = ($idx!=0) ? '' : 'curr';
            ?>
                <li><a href="<?php echo URL::route('frontend.article', array('id'=>$news->id))?>"><time datetime="<?php echo $news->open_at?>"><?php echo $news->open_at?></time><span><?php echo $news->title?></span></a></li>
            <?php endforeach; ?>
        </ul>
    </article>
    <!-- newsList end -->

    <article id="newTechBox">
            <h2 class="titleRp h2_tech">美麗新技術</h2>
            <ul class="tabNav">
            @foreach ($techs as $key=>$technology)
                <li><a @if($key==0) {{'class="curr"'}} @endif href="#">{{$technology->title}}</a></li>
            @endforeach
            </ul>

            <?php foreach($techs as $key=>$technology):
                    $class = ($key!=0) ? '' : 'curr';
            ?>
            <a class="tabBox <?php echo $class?>" href="<?php echo $technology->link?>" target="<?php echo $technology->target?>">
                <img src="<?php echo $technology->image.'?w=700'?>" alt="<?php echo $technology->title?>" width="700"/>
            </a>
            <?php endforeach;?>
    </article>
    <!-- newTech end -->

    <article class="commentList">
        <h2 class="titleRp h2_comment">美麗留言</h2>
        <div class="funcBar"><a href="<?php echo route('frontend.board.ask')?>">我要發問</a><a href="<?php echo route('frontend.board.list')?>">所有留言</a></div>
        <table class="infoList">
            <thead>
                <tr>
                    <th class="postTime">發表時間</th>
                    <th>問題</th>
                    <th class="postUser">發表人</th>
                    <th>瀏覽人數</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($boards as $b): ?>
                <tr>
                    <td><time datetime="<?php echo $b->d?>"><?php echo $b->d?></time></td>
                    <td><a href="<?php echo route('frontend.board.post', array($b->id))?>"><span><?php echo HTML::decode($b->topic)?></span></a></td>
                    <td class="userName"><?php echo HTML::decode($b->name)?></td>
                    <td class="viewCount"><?php echo $b->count_num?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </article>
    <!-- commentList end -->

</div>
<!-- mainContent end -->

@stop


@section('bottomContent')
@stop
