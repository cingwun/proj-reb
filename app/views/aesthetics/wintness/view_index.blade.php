@extends('aesthetics._layouts.default')

@section('bodyId'){{$bodyId}}@stop

@section('mainBanner')
    @include('aesthetics._partials.banner', array('size'=>'large'))
@stop

@section('mainContent')
<div id="mainContent" class="postBox" role="main">
    <div class="breadcrumb">
        <a href="/">首頁</a><span class="arrow"></span>
        <a >美麗見證</a>
    </div>
    <!-- breadcrumb end -->
<div>
</div>
<!-- mainBanner end -->
<!--
<div class="slider">
    <div class="wrapper">
        <ul class="cycle-slideshow"
            data-cycle-fx=carousel
            data-cycle-timeout=0
            data-cycle-carousel-visible=4
            data-cycle-slides="li"
            data-cycle-prev=".prevControl"
            data-cycle-next=".nextControl">
        <?php
            if (sizeof($gallery)>0):
                foreach($gallery as $img):
        ?>
            <li><a href="<?php echo $img->link?>" target="<?php echo $img->target?>">
                <img src="<?php echo $img->imageURL . '?w=228&h=150&ar=i'?>" width="228" height="150"><p><?php echo $img->title?></p>
                </a>
            </li>
        <?php   endforeach;
            endif;
        ?>
        </ul>
    </div>

    <span class="nex prevControl" id="prev"></span>
    <span class="pre nextControl" id="next"></span>
</div>
-->
<div id="search-info" style="display: none; text-align: center; padding: 10px 0px;"></div>

<ul class="caseList" id="container" data-loadURL="<?php echo URL::route('frontend.wintness.ajax.load.articles')?>"></ul>

@include('aesthetics._partials.widget_case_search')
<!-- caseSearch end -->

</div>
<!-- mainContent end -->

@stop

@section('aside')

@stop

@section('bottomContent')
    <script type="text/x-tmpl" id="template">
        <li class="item" id="item-{%=o.id%}"><div class="caseWrapper" style="background-color: {%=o.background_color%};"><a href="<?php echo URL::route('frontend.wintness.article', array('id'=>'', 'title'=>''))?>/{%=o.id%}/{%=o.title%}" class="cases">
        <h3>{%=o.title%}</h3>
        <img alt="h3"/>
        <p>{%=o.description%}</p>
        </a><p class="tags">
            {% for(var i=0; i<o.label_faq.length; i++){ %}
                {% if (i>=1) { %} / {% } %}<a href="{%=o.label_faq[i].link %}">{%=o.label_faq[i].title %}</a>
            {% } %}
        </p></div></li>
    </script>

    {{ HTML::script('packages/masonry/isotope.pkgd.min.js'); }}
    {{ HTML::script('packages/template/js/tmpl.min.js'); }}
    {{ HTML::script('packages/pathjs/path.min.js'); }}
    {{ HTML::script('js/wintness/js_index.js'); }}

    <script type="text/javascript">
        var searchBox = _searchBox({el: '.caseSearch', models: <?php echo json_encode($servicesFaqs)?>});
    </script>
    @parent
@stop
