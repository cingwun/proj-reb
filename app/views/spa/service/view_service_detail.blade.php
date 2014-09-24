<% @tmpID = 'spa_service_detail' %>
<%= render :partial => "setContent" %>
<div id="mainContent" class="postBox" role="main">
	<div class="breadcrumb">
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="#">服務項目</a><span class="arrow"></span>
		<a href="#">臉部保養</a><span class="arrow"></span>
		<a href="#">活氧特效嫩膚護理</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<%= render :partial => "pageTitle" %>
	<%= render :partial => "socialIcons" %>
	<!-- pagedetails -->
	<div id="contentInner">
		<!-- @image, for the Post Image -->
		<img src="http://placehold.it/680x430">
		<div class="contentArticle">
			<!-- @text, for Service Content -->
			<p><%= zh_lorem_paragraphs 8 %></p>
		</div>
	</div>
	<div id="hotClasses">
		<div class="bar">
			<img src="../img/sign/hotClass.jpg" height="34" width="700">
		</div>
		<div class="class">
			<% for @i in 1..4 %>
			<div class="hotClass">
				<!-- @image for Service Hotclass Image -->
				<div class="pic"><img src="http://placehold.it/160x104"></div>
				<!-- @text, for Service Hotclass Title -->
				<div class="title"><a href="#"><%= zh_lorem_words 10 %></a></div>
				<!-- @text, for Service Hotclass Content -->
				<div class="content"><%= zh_lorem_paragraph%><a href="#">(more)</a></div>
			</div>
			<% end %>
		</div>
	</div>
	<!-- pagedetails end -->
</div>
</div>