<?php
$servicefaq = ServiceFaq::where('status', '=', 'Y')
                        ->orderBy('type', 'asc')
                        ->orderBy('_parent', 'desc')
                        ->orderBy('sort', 'desc')
                        ->orderBy('updated_at', 'desc')
                        ->get();
$servicesFaqs = array('service'=>array(), 'faq'=>array());
foreach($servicefaq as $item){
    $key = $item->id;
    $parent = $item->_parent;
    $list = $servicesFaqs[$item->type];
    if ($parent=='N'){
        if (!isset($list[$key]))
            $list[$key] = array('title'=>$item->title, 'subItems'=>array());
    }else{
        $list[$parent]['subItems'][] = $item;
    }
    $servicesFaqs[$item->type] = $list;
}
?>
<nav id="mainNav" role="navigation">
    <ul class="lv0">
        <li>
            <a href="#">關於煥儷</a>
            <ul class="subNav lv1">
                @foreach (ArticlesController::getNav(1) as $article)
                <li><a href="{{ URL::to('articles/'.$article->id) }}">{{ $article->title }}</a></li>
                @endforeach
            </ul>
        </li>
        <li>
            <a href="#">服務項目</a>
            <ul class="subNav lv1">
                @foreach ($servicesFaqs['service'] as $category)
                <li>
                    <a href="#">{{ $category['title'] }}</a>
                    <ul class="subNav lv2">
                        @foreach ($category['subItems'] as $service)
                        <li><a href="{{ URL::route('frontend.service_faq.article', array('type'=>'service', 'id'=>$service->id)) }}">{{$service->title}}</a></li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </li>
        <li><a href="/wintness#/list">美麗見證</a></li>
        <li><a href="/news">最新消息</a></li>
        <li>
            <a href="#">常見問題</a>
            <ul class="subNav lv1">
                @foreach ($servicesFaqs['faq'] as $category)
                <li>
                    <a href="#">{{$category['title']}}</a>
                    <ul class="subNav lv2">
                        @foreach ($category['subItems'] as $faq)
                        <li><a href="{{ URL::route('frontend.service_faq.article', array('type'=>'faq', 'id'=>$faq->id)) }}">{{$faq->title}}</a></li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </li>
        <li><a href="/beautynews">美麗新知</a></li>
        <li>
            <a href="#">海外專區</a>
            <ul class="subNav lv1">
                @foreach (ArticlesController::getNav(2) as $article)
                <li><a href="{{ URL::to('articles/'.$article->id) }}">{{ $article->title }}</a></li>
                @endforeach
            </ul>
        </li>
    </ul>
    <a class="goSpa" href="#">關於煥儷SPA</a><!-- ../sap/ -->
</nav>
<!-- ======================== mainNav end ======================== -->