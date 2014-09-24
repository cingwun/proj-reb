<div id="pageTitle">
	<div id="titleWrapper">
		<div id="titleImg">
			<img id="about_logo" src="../img/sign/icon_spa/about.png"/>
			{{@yield('bodyId')}}
			<!--@if() == 'spa_about' %>
			<img id="about_logo" src="../img/sign/icon_spa/about.png"/>
			<% elsif @tmpID == 'spa_service_detail' %>
			<img id="service_logo" src="../img/sign/icon_spa/service.png"/>
			<% elsif @tmpID == 'spa_products_detail' %>
			<img id="product_logo" src="../img/sign/icon_spa/product.png"/>
			<% elsif @tmpID == 'spa_newsPost_detail' %>
			<img id="newsPost_logo" src="../img/sign/icon_spa/news.png"/>
			<% elsif @tmpID == 'spa_shareCase' %>
			<img id="shareCase_logo" src="../img/sign/icon_spa/case.png"/>
			<% end %>-->
		</div>
		<div id="title">
			<h1 id="about_title">關於煥麗</h1>
			<!--@if(@yield()) == 'spa_about' %>
			<h1 id="about_title">關於煥麗</h1>
			<% elsif @tmpID == 'spa_service_detail' %>
			<h1 id="service_title">服務項目</h1>
			<% elsif @tmpID == 'spa_products_detail' %>
			<h1 id="products_title">專業產品</h1>
			<% elsif @tmpID == 'spa_newsPost_detail' %>
			<h1 id="newsPost_title">最新消息</h1>
			<% elsif @tmpID == 'spa_shareCase' %>
			<h1 id="shareCase_title">美麗分享</h1>
			<% end %>-->
		</div>
	</div>
	<div id="dateAndViews">
		<div id="date">
			<img class="arrow_g" src="../img/sign/arrow_g.png">
			<div class="date_wrapper">
				<label class="date_label">發表日期：</label>
				<span class="date_detail">2014/08/01</span>
			</div>
		</div>
		<div id="views">
			<img class="arrow_g" src="../img/sign/arrow_g.png">
			<div class="views_wrapper">
				<label class="views_label">瀏覽：</label>
				<span class="views_detail">150</span>
			</div>
		</div>
	</div>
</div>