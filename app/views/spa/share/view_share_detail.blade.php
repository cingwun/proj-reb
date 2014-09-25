<% @tmpID = 'spa_shareCase_detail' %>
<div id="mainContent" class="fullWidth" role="main">
	<div class="breadcrumb">
		<a href="#">首頁</a><span class="arrow"></span>
		<a href="#">美麗分享</a><span class="arrow"></span>
		<a href="#">3D</a>
	</div><!-- ======================== breadcrumb end ======================== -->
	<div class="allTop_con">
		<div class="shareTop_pic"><img src="http://placehold.it/500x300"></div>
		<div class="shareTop_con">
			<div class="classList">
				<div><p>課程項目：</p></div>
				<!-- @text for shareTag -->
				<div class="shareTag">
					<a href="#"><div class="shareTag_btn"><%= zh_lorem_words 4 %></div></a>
					<a href="#"><div class="shareTag_btn"><%= zh_lorem_words 2 %></div></a>
					<a href="#"><div class="shareTag_btn"><%= zh_lorem_words 4 %></div></a>
					<a href="#"><div class="shareTag_btn"><%= zh_lorem_words 2 %></div></a>
					<a href="#"><div class="shareTag_btn"><%= zh_lorem_words 4 %></div></a>
					<a href="#"><div class="shareTag_btn"><%= zh_lorem_words 2 %></div></a>
				</div>
			</div>
			<div class="classPro">
				<div><p>專業產品：</p></div>
				<!-- @text for shareTag -->
				<div class="shareTag">
					<a href="#"><div class="shareTag_btn"><%= zh_lorem_words 4 %></div></a>
				</div>
			</div>
			<div class="classDes">
				<!-- @text for productDescription -->
				<%= zh_lorem_words 100 %>
			</div>
		</div>
	</div>
	<!-- sliderBox -->
	<div class="slider" id="sliderBox">
		<span class="btn-close" alt="關閉">關閉</span>
		<div class="main-title">小護士靠立塑終結萬年小腹</div>
		<div class="photo-num">第<span class="no number">1</span>張&nbsp;/&nbsp;共<span class="number">8</span>張</div>
		<div class="clear"></div>
		<div class="wrapper">
			<ul class="container-images">
				<% for @e in 1..10 %>
				<!--@images for colorbox
				    @text for the lightbox title fetch from following <p>
			    -->
				<li><a rel="gallery1" href="../img/demo/shareDemo.jpg" title="yo rock">
					<!--@images for shareDemo slider-->
					<img src="http://placehold.it/130x130">
					<!-- @text for the list title-->
					<p>1電波雷射後的皮膚狀況</p></a>
				</li><% end %>
			</ul>
		</div><!-- wrapper end -->
		<span class="nex prevCcontrol" id="prev"></span>
		<span class="pre nextCcontrol" id="next"></span>
		<div class="image-block">
			<img src="" class="hidden"/>
			<div class="title hidden"></div>
			<div class="loader hidden"></div>
		</div>
	</div>
	<!-- slider end -->

	<div class="shareCon">
		<div class="exempleTag">課程案例分享</div>
		<div class="shareWord"><!-- @text for sharePost --></div>
	</div>

	<div class="postNav">
		<div>
			上一篇
			<span class="arrow"></span>
			<a href="#">美容網站FashionGuide母親節講座花絮分享</a>
		</div>
		<div>
			下一篇
			<span class="arrow"></span>
			<a href="#">大美人世代嚮往女性美大調查</a>
		</div>
	</div>
</div>