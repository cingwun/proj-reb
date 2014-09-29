<?php

$aboutArticle = \SpaArticles::where('category', 'about')
							->orderBy('sort', 'desc')
							->get();

$serviceParent = \SpaService::where('_parent', 'N') 
						    ->get(array('id', 'title'))
						    ->toArray();
$service = array();
if($serviceParent) {
	foreach ($serviceParent as $category) {
		$servCmd = \SpaService::where('_parent', $category['id'])
							  ->where('display', 'yes')
							  ->get(array('id', 'title'))
  	 						  ->toArray();
  	 	$service[] = array(
  	 		'cat' => $category,
  	 		'serv' => $servCmd
  	 	);
	}
}
$productParent = \SpaProduct::where('_parent', 'N')
							->orderBy('sort', 'desc')
							->get(array('id', 'title'))
							->toArray();
?>

<header id="header" role="banner">
	<div class="innerWrap">
		<h1><a href="/">煥儷美顏SPA</a></h1>
		<div class="languageSwitch">
			<a href="#">繁體中文</a>
			<a class="last" href="#">简体中文</a>
			<form class="searchBox" action="/" method="post"><input type="text" placeholder="請輸入關鍵字" /><button type="submit"><span>搜尋</span></button></form>
		</div>
		<div class="memberFunc funcBar">
			<span>會員獨享</span>
			<a href="#">登入</a>
			<a href="#">註冊</a>
			<a class="fb" href="https://www.facebook.com/RebeautyClinic">facebook</a>
		</div>
	</div>
	<nav id="mainNav" role="navigation">
		<ul class="lv0">
			<li class="navs">
				<a class="navsTitle" href="{{URL::route('spa.about')}}">關於煥儷</a>
				<ul class="subNav lv1">
					@foreach($aboutArticle as $list)
					<li class="lv1_list">
						<a href="{{URL::route('spa.about', array($list->id))}}">{{$list->title}}</a>
					</li>
					@endforeach
				</ul>
			</li>
			<li class="navs">
				<a class="navsTitle" href="/spa/service.html">服務項目</a>
				<ul class="subNav lv1">
					@foreach($service as $service)
					<li class="lv1_list">
						<a class="lv1_link" href="/spa/service.html">{{$service['cat']['title']}}</a>
						<ul class="subNav lv2">
							@foreach($service['serv'] as $serv)
							<li class="lv2_list"><a class="lv2_link" href="/spa/service_detail.html">{{$serv['title']}}</a></li>
							@endforeach
						</ul>
					</li>
					@endforeach
				</ul>
			</li>
			<li class="navs">
				<a class="navsTitle" href="/spa/products.html">專業產品</a>
				<ul class="subNav lv1">
					@foreach($productParent as $list)
					<li class="lv1_list">
						<a class="lv1_link" href="/spa/service.html">{{$list['title']}}</a>
					</li>
					@endforeach
				</ul>
			</li>
			<li class="navs"><a class="navsTitle" href="{{URL::route('spa.news')}}">最新消息</a></li>
			<li class="navs"><a class="navsTitle" href="{{URL::route('spa.share')}}">美麗分享</a></li>
			<li class="navs">
				<a class="navsTitle" href="/spa/overSea.html">海外專區</a>
				<ul class="subNav lv1">
					<li class="lv1_list"><a class="lv1_link" href="/spa/overSea.html">海外客戶預約流程</a></li>
					<li class="lv1_list"><a class="lv1_link" href="#">觀光醫療特惠活動</a></li>
				</ul>
			</li>
			<li class="navs"><a class="navsTitle goAes" class="goAes" href="/aesthetics">煥麗醫美診所</a></li>
			</ul>

		</nav><!-- ======================== mainNav end ======================== -->
</header><!-- ======================== header end ======================== -->