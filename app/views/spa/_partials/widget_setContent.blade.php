<aside id="setContent" role="complementary">

	@if($titleType=='about')
	<h3 class="titleRp about">關於煥麗</h3>
	<ul class="spaAbout">
		@foreach($articleList as $list)
		<li class="aboutSub">
			<a class="aboutSubTitle" href="{{ URL::route('spa.about', array($list->id))}}">{{array_get($list, 'title')}}</a>
		</li>
		@endforeach
	</ul>

	@elseif($titleType=='service')
	<h3 class="titleRp service">服務項目
	</h3>
	<ul class="spaService">
		@foreach($categorys as $category)
		<li class="servSub">
			<a class="servSubTitle" href="javascript:void(0)">{{\Text::preEllipsize(strip_tags($category['cat']['title']), 10)}}</a>
			<img class="side_arrow active" src="<?=asset('spa/img/sign/arrow_yellow.png');?>"/>
			<img class="side_arrow" src="<?=asset('spa/img/sign/arrow_white.png');?>"/>
			<ul class="servDetail">
				@foreach($category['serv'] as $service)
				<li class="servDetailInner"><a class="servDetailLink" href="{{$serviceDetailURL}}/{{$service['id']}}">{{\Text::preEllipsize(strip_tags($service['title']), 10)}}</a></li>
				@endforeach
			</ul>
		</li>
		@endforeach
	</ul>

	@elseif($titleType=='product')
	<h3 class="titleRp products">專業產品</h3>
	<ul class="spaProducts">
		@foreach($categorys as $category)
		<li class="productsSub">
			<a class="productsSubTitle" href="{{$productListURL}}/{{$category['id']}}">{{\Text::preEllipsize(strip_tags($category['title']), 10)}}</a>
		</li>
		@endforeach
	</ul>

	@elseif($titleType=='news')
	<div class="setListWrap">
		<h3 class="titleRp h3_hot hotClass">熱門課程推薦</h3> <!-- 熱門課程=熱門服務項目 -->
		<ul class="setList hotList classList">
			<?php $i = 0; ?>
			@foreach($hotService as $s)
			<?php $i++; ?>
			<li class="classSub">
				<i>{{$i}}</i>
				<a class="classSubTitle" href="#">{{$s->title}}</a>
			</li>
			@endforeach
		</ul>
	</div>


	@elseif($titleType=='oversea')
	<h3 class="titleRp overSea">海外專區</h3>
	<ul class="spaOverSea">
		<li class="overSeaSub">
			<a class="overSeaSubTitle" href="{{$ovewSeaURL}}">海外預約流程</a>
		</li>
		<li class="overSeaSub">
			<a class="overSeaSubTitle" href="{{$formURL}}">海外貴賓來檯預約表</a>
		</li>
	</ul>
	@endif
	<!-- ======================== setListWrap end ======================== -->
</aside>