<div id="pageTitle">
	<div id="titleWrapper">
		<div id="titleImg">
			<img id="about_logo" src="../spa/img/sign/icon_spa/{{$titleType}}.png"/>
		</div>
		<div id="title">
			@if($titleType=='about')
			<h1 id="about_title">關於煥麗111</h1>
			@elseif($titleType=='service')
			<h1 id="service_title">服務項目</h1>
			@elseif($titleType=='product')
			<h1 id="service_title">專業產品</h1>
			@elseif($titleType=='news')
			<h1 id="service_title">最新消息</h1>
			@elseif($titleType=='case')
			<h1 id="service_title">美麗分享</h1>
			@endif
		</div>
	</div>
	<div id="dateAndViews">
		<div id="date">
			<img class="arrow_g" src="../spa/img/sign/arrow_g.png">
			<div class="date_wrapper">
				<label class="date_label">發表日期：</label>
				<span class="date_detail">{{$publish}}</span>
			</div>
		</div>
		<div id="views">
			<img class="arrow_g" src="../spa/img/sign/arrow_g.png">
			<div class="views_wrapper">
				<label class="views_label">瀏覽：</label>
				<span class="views_detail">{{$views}}</span>
			</div>
		</div>
	</div>
</div>



