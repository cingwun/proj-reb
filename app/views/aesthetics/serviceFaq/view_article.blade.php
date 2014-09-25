@extends('aesthetics._layouts.default')


<?php
    if ($type=='service'){
        $bodyId = 'services';
        $icon = 'service.png';
        $pageTitle = '服務項目';
        $secTitleClass = 'h3_serv';
        $labelType = 'faq';
    }else{
        $bodyId = 'faq';
        $icon = 'faqs.png';
        $pageTitle = '常見問題';
        $secTitleClass = 'h2_faq';
        $labelType = 'service';
    }
?>
@section('bodyId'){{$bodyId}}@stop


@section('mainContent')
<div id="mainContent" class="postBox" role="main">
    <div class="breadcrumb">
        <a href="/">首頁</a><span class="arrow"></span>
        <span><?php echo $pageTitle?></span><span class="arrow"></span>
        <span><?php echo $parent->title?></span><span class="arrow"></span>
        <a href="#"><?php echo $model->title?></a>
    </div>
    <!-- breadcrumb end -->

    <section class="serviceInfo">

        <h2><img src="<?php echo asset('aesthetics/img/sign/icon/'.$icon)?>" alt="service" width="120" height="56"><?php echo $model->title?></h2>
        <img src="<?php echo $model->image . '?w=300&h=300&ar=i'?>" alt="<?php echo $model->title;?>" width="300"/>
        <div class="infoText">
            <div class="contentInfo">
                <time datetime="<?php echo $model->date?>">發表日期：<?php echo $model->date?></time>
                <span>瀏覽：<?php echo (int)$model->views?></span>
            </div>
            <!-- contentInfo end -->

            <p>{{ $model->content }}</p>
            <div class="tags">
                <h4>相關標籤</h4>
                <?php foreach($labels as $label):?>
                <a href="<?php echo URL::route('frontend.service_faq.article', array('type'=>$labelType, 'id'=>$label->id))?>"><?php echo $label->title?></a>
                <?php endforeach;?>
            </div>
            <!-- tags end -->

        </div>
        @include('aesthetics._partials.widget_rebeauty_slider', array('title'=>$model->title, 'images'=>$images))

    </section>
    <!-- serviceInfo end -->
    <article id="servBox">
        <ul class="tabNav">
            <?php foreach($tabs as $idx=>$tab):?>
            <li><a href="#tab<?php echo $idx?>" class="<?php echo ($idx===0)?'curr':''?>"><?php echo $tab->title?></a></li>
            <?php endforeach;?>
        </ul>
        <?php foreach($tabs as $idx=>$tab):?>
            <div class="tabBox ckeditor-style <?php echo ($idx===0)?'curr':''?>" id="tab<?php echo $idx?>"><?php echo $tab->content?></div>
        <?php endforeach;?>
        <!-- tabBox end -->
        <ul class="tabNav">
                    <li><a href="#tab1" class="a">a_title</a></li>
                    <li><a href="#tab2" class="b">b_title</a></li>
                </ul>
                <div class="tabBox ckeditor-style a" id="tab1">a_content</div>
                <div class="tabBox ckeditor-style b" id="tab2">b_content</div>
                <!-- tabBox end -->
    </article>
    <!-- serviceBox end -->

    <div class="funcBar"><a href="#" class="goTop">回上方</a></div>
</div>
<!-- mainContent end -->

@stop

@section('aside')
    <h3 class="titleRp <?php echo $secTitleClass?>"><?php echo $pageTitle?></h3>
    <ul class="servList">
        <?php foreach($navs as &$n):?>
        <li>
            <h4><?php echo $n['title']?></h4>
            <ul class="sub">
                <?php foreach($n['childs'] as $c):?>
                <li><a href="<?php echo URL::route('frontend.service_faq.article', array('type'=>$type, 'id'=>$c['id']))?>"><?php echo $c['title']?></a></li>
                <?php endforeach;?>
            </ul>
            <!-- servList ul.sub -->

        </li>
        <!-- servList -->

        <?php endforeach;?>
    </ul>

    {{-- 美麗排行 --}}
    @include('aesthetics._partials.sidebar_rank')
@stop

@section('bottomContent')
    <script type="text/javascript">
        $(function(){
            var slidebox = _slidebox({
                el: '#sliderBox',
                viewSize: 4,
                itemWidth: 155
            });
        });
    </script>
    @parent
@stop