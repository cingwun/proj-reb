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
		<li class="servSub">
			<a class="servSubTitle" href="javascript:void(0)">臉部保養</a>
			<img class="side_arrow active" src="../img/sign/arrow_yellow"/>
			<img class="side_arrow" src="../img/sign/arrow_white"/>
			<ul class="servDetail">
				<li class="servDetailInner"><a class="servDetailLink" href="#">活養嫩膚護理</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">養皮術</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">基因更生療法</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">保濕水嫩美肌保養</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">抗老提拉回春理療</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">活氧特效嫩膚護理</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">敏若修護強肌保養</a></li>
			</ul>
		</li>
		<li class="servSub">
			<a class="servSubTitle" href="javascript:void(0)">美體保養</a>
			<img class="side_arrow active" src="../img/sign/arrow_yellow"/>
			<img class="side_arrow" src="../img/sign/arrow_white"/>
			<ul class="servDetail">
				<li class="servDetailInner"><a class="servDetailLink" href="#">盆腔活力泉源課程</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">美胸保健塑形療程</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">胸腔深層釋放課程</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">嗅覺香氛能量按摩</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">曲線纖體調塑療程</a></li>
				<li class="servDetailInner"><a class="servDetailLink" href="#">熱帶雨林體膚療程</a></li>
			</ul>

		</li>
		<li class="servSub noSub">
			<a class="servSubTitle" href="#">術後保養</a>
		</li>
		<li class="servSub noSub">
			<a class="servSubTitle" href="#">其他保養</a>
		</li>
		<!-- servList -->
	</ul>

	@elseif($titleType=='product')
	<h3 class="titleRp products">專業產品</h3>
	<ul class="spaProducts">
		<li class="productsSub">
			<a class="productsSubTitle" href="#">欣娜可臉部系列</a>
		</li>
		<li class="productsSub">
			<a class="productsSubTitle" href="#">席薇臉部系列</a>
		</li>
		<li class="productsSub">
			<a class="productsSubTitle" href="#">真尼蒂身體系列</a>
		</li>
		<li class="productsSub">
			<a class="productsSubTitle" href="#">席薇純質精油系列</a>
		</li>
		<li class="productsSub">
			<a class="productsSubTitle" href="#">舒莉泉臉部/美體系列</a>
		</li>
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

	@elseif($titleType=='overser')
	<h3 class="titleRp overSea">海外專區</h3>
	<ul class="spaOverSea">
		<li class="overSeaSub">
			<a class="overSeaSubTitle" href="#">海外預約流程</a>
		</li>
		<li class="overSeaSub">
			<a class="overSeaSubTitle" href="#">海外貴賓來檯預約表</a>
		</li>
	</ul>

	@endif
	<!-- ======================== setListWrap end ======================== -->
</aside>