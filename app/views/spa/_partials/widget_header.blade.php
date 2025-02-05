<?php

//article
$aboutArticle = \SpaArticles::where('category', 'about')
							->where('lang', App::getlocale())
							->where('status', '1')
							->orderBy('sort', 'desc')
							->get();

//service
$servCatCmd = \SpaService::where('_parent', 'N')
						 ->where('display', 'yes')
						 ->where('lang', App::getLocale())
						 ->orderBy('sort', 'desc')
						 ->get(array('id', 'title'))
						 ->toArray();
$servCats = array();
if($servCatCmd) {
	foreach ($servCatCmd as $cat) {
		$servCmd = \SpaService::where('_parent', $cat['id'])
							  ->where('display', 'yes')
						 	  ->orderBy('sort', 'desc')
							  ->get(array('id', 'title'))
  	 						  ->toArray();
  	 	$servCats[] = array(
  	 		'cat' => $cat,
  	 		'serv' => $servCmd
  	 	);
	}
}

//product
$prodCats = array();
$prodCatsCmd = \SpaProduct::where('_parent', 'N')
						  ->where('display', 'yes')
						  ->where('lang', App::getLocale())
						  ->orderBy('sort', 'desc')
						  ->get(array('id', 'title'))
						  ->toArray();
if(!empty($prodCatsCmd))
	$prodCats = $prodCatsCmd;

$www = 'http://' . Host::get('rebeauty');
?>

<header id="header" role="banner">
	<div class="innerWrap">
		<h1><a href="/">煥儷美顏SPA</a></h1>
		<div class="languageSwitch">
			<a href="{{Host::getLangURL('tw')}}">繁體中文</a>
			<a class="last" href="{{Host::getLangURL('cn')}}">简体中文</a>
			<form class="searchBox" action="{{URL::route('spa.search')}}" method="get"><input type="text" name="q" placeholder="請輸入關鍵字" /><button type="submit"><span>搜尋</span></button></form>
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
						<a class="lv1_link" href="{{URL::route('spa.about', array('id'=>$list->id, 'title'=>Urlhandler::encode_url($list->title)))}}">{{$list->title}}</a>
					</li>
					@endforeach
				</ul>
			</li>
			<li class="navs">
				<a class="navsTitle" href="{{URL::route('spa.service')}}">服務項目</a>
				<ul class="subNav lv1">
					@foreach($servCats as $servCat)
					<li class="lv1_list">
						<a class="lv1_link" href="{{URL::route('spa.service')}}">{{\Text::preEllipsize(strip_tags($servCat['cat']['title']), 8)}}</a>
						<ul class="subNav lv2">
							@foreach($servCat['serv'] as $serv)
							<li class="lv2_list"><a class="lv2_link" href="{{URL::route('spa.service.detail', array('id'=>$serv['id'], 'title'=>Urlhandler::encode_url($serv['title'])))}}">{{\Text::preEllipsize(strip_tags($serv['title']), 8)}}</a></li>
							@endforeach
						</ul>
					</li>
					@endforeach
				</ul>
			</li>
			<li class="navs">
				<a class="navsTitle" href="{{URL::route('spa.product')}}">專業產品</a>
				<ul class="subNav lv1">
					@foreach($prodCats as $prodCat)
					<li class="lv1_list">
						<a class="lv1_link" href="{{URL::route('spa.product.list', array('cat'=>$prodCat['id']))}}">{{\Text::preEllipsize(strip_tags($prodCat['title']), 8)}}</a>
					</li>
					@endforeach
				</ul>
			</li>
			<li class="navs"><a class="navsTitle" href="{{URL::route('spa.news')}}">最新消息</a></li>
			<li class="navs"><a class="navsTitle" href="{{URL::route('spa.share')}}">美麗分享</a></li>
			<li class="navs">
				<a class="navsTitle" href="{{URL::route('spa.reservation.overSea')}}">海外專區</a>
				<ul class="subNav lv1">
					<li class="lv1_list"><a class="lv1_link" href="{{URL::route('spa.reservation.overSea')}}">海外客戶預約流程</a></li>
					<li class="lv1_list"><a class="lv1_link" href="{{URL::route('spa.reservation.form')}}">海外貴賓來檯預約表</a></li>
				</ul>
			</li>
			<li class="navs"><a class="navsTitle goAes" class="goAes" href="{{ $www }}">煥麗醫美診所</a></li>
			</ul>

		</nav><!-- ======================== mainNav end ======================== -->
</header><!-- ======================== header end ======================== -->
