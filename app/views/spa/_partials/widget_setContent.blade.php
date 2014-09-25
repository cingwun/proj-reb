<aside id="setContent" role="complementary">

	@if($titleType=='about')
	<h3 class="titleRp about">關於煥麗</h3>
	<ul class="spaAbout">
		<li class="aboutSub">
			<a class="aboutSubTitle" href="#">經營理念</a>
		</li>
		<li class="aboutSub">
			<a class="aboutSubTitle" href="#">會館資訊</a>
		</li>
		<li class="aboutSub">
			<a class="aboutSubTitle" href="#">環境設備</a>
		</li>
		<li class="aboutSub">
			<a class="aboutSubTitle" href="#">專業團隊</a>
		</li>
	</ul>

	@elseif($titleType=='service')
	<h3 class="titleRp service">服務項目
	</h3>
	<ul class="spaService">
		@foreach($categorys as $category)
		<li class="servSub">
			<a class="servSubTitle" href="javascript:void(0)">{{$category['cat']['title']}}</a>
			<img class="side_arrow active" src="<?=asset('spa/img/sign/arrow_yellow.png');?>"/>
			<img class="side_arrow" src="<?=asset('spa/img/sign/arrow_white.png');?>"/>
			<ul class="servDetail">
				@foreach($category['serv'] as $service)
				<li class="servDetailInner"><a class="servDetailLink" href="{{$serviceDetailURL}}/{{$service['id']}}">{{$service['title']}}</a></li>
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
			<a class="productsSubTitle" href="{{$productListURL}}/{{$category['id']}}">{{$category['title']}}</a>
		</li>
		@endforeach
	</ul>

	@elseif($titleType=='news')
	<div class="setListWrap">
		<h3 class="titleRp h3_hot hotClass">熱門課程推薦</h3>
		<ul class="setList hotList classList">
			<li class="classSub">
				<i>1</i>
				<a class="classSubTitle" href="#">養皮術</a>
			</li>
			<li class="classSub">
				<i>2</i>
				<a class="classSubTitle" href="#">基因更生療法</a>
			</li>
			<li class="classSub">
				<i>3</i>
				<a class="classSubTitle" href="#">熱帶雨林體膚療程</a>
			</li>
			<li class="classSub">
				<i>4</i>
				<a class="classSubTitle" href="#">美胸保健塑形課程</a>
			</li>
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