@extends('aesthetics._layouts.default')

@section('bodyId'){{$bodyId}}@stop


@section('mainContent')
<div id="mainContent" class="postBox" role="main">
    <div class="breadcrumb">
        <a href="/">首頁</a><span class="arrow"></span>
        <a href="/wintness#/list">美麗見證</a><span class="arrow"></span>
        <a href="#"><?php echo $model->title?></a>
    </div>
    <!-- breadcrumb end -->

    @section('mainBanner')
        @include('aesthetics._partials.banner', array('size'=>'medium'))
    @stop
    <!-- mainBanner end -->

    <section class="serviceInfo">
        <h1><img src="<?php echo asset('aesthetics/img/sign/icon/service.png')?>" alt="service"><?php echo $model->title?></h1>

        <img src="<?php echo $list['before']['items'][0]['image'] . '?w=270&h=270&ar=i'?>" alt="services1" width="270" height="270" />
        <img src="<?php echo $list['after']['items'][0]['image'] . '?w=270&h=270&ar=i'?>" alt="services2" width="270" height="270" />

        <div class="infoText">
            <h4>改善問題</h4>
            <div class="tags">
                <?php foreach($labelList['service'] as $label):?>
                <a href="<?php echo URL::route('frontend.service_faq.article', array('type'=>'service', 'id'=>$label['id']))?>"><?php echo $label['title']?></a>
                <?php endforeach?>
            </div>
            <!-- tags end -->

            <h4>適用項目</h4>
            <div class="tags">
                <?php foreach($labelList['faq'] as $label):?>
                <a href="<?php echo URL::route('frontend.service_faq.article', array('type'=>'faq', 'id'=>$label['id']))?>"><?php echo $label['title']?></a>
                <?php endforeach?>
            </div>
            <!-- tags end -->
            <p><?php echo $model->description;?></p>
        </div>

        @include('aesthetics._partials.widget_rebeauty_slider', array('title'=>$model->title, 'images'=>$list['gallery']['items']))

        <!-- slider end -->
    </section>
    <!-- serviceInfo end -->

    <article id="servBox">
        <ul class="tabNav">
            <?php foreach($tabs as $idx=>$tab):?>
            <li><a href="#tab<?php echo $idx?>" class="<?php echo ($idx===0)?'curr':''?>"><?php echo $tab->title?></a></li>
            <?php endforeach;?>
        </ul>

        <?php foreach($tabs as $idx=>$tab):?>
            <div class="tabBox <?php echo ($idx===0)?'curr':''?>" id="tab<?php echo $idx?>"><?php echo $tab->content?></div>
        <?php endforeach;?>
        <!-- tabBox end -->

        @include('aesthetics._partials.widget_case_search')
        <!-- caseSearch end -->

    </article>
    <!-- serviceBox end -->
    <div class="postNav">
        <?php if (!empty($prev)):?>
        <div>上一篇<span class="arrow"></span><a href="<?php echo URL::route('frontend.wintness.article', array('id'=>$prev->id))?>"><?php echo $prev->title?></a></div>
        <?php endif;
              if (!empty($next)):
        ?>
        <div>下一篇<span class="arrow"></span><a href="<?php echo URL::route('frontend.wintness.article', array('id'=>$next->id))?>"><?php echo $next->title?></a></div>
        <?php endif;?>
    </div>
    <!-- postNav end -->
</div>
<!-- mainContent end -->

@stop



@section('aside')

@stop

@section('bottomContent')
    {{ HTML::script('packages/template/js/tmpl.min.js'); }}

    <script type="text/javascript">
        var slidebox;
        $(function(){
            slidebox = _slidebox({
                el: '#sliderBox',
                viewSize: 6,
                itemWidth: 130
            });
        });

        var searchBox = _searchBox({el: '.caseSearch', models: <?php echo json_encode($servicesFaqs)?>});
    </script>

    @parent
@stop

@section('head')
<meta name="keywords" content="<?php echo $model->meta_name?>">
<meta name="description" content="<?php echo $model->meta_content?>">
@stop

@if($article->meta_title!="")
    @section('title')
    <title>{{$model->meta_title}}</title>
    @stop
@endif

@section('h1')
<h1 style="display:none">{{$model->h1}}</h1>
@stop
