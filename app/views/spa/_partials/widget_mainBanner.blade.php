<div id="mainBanner" class="topBanner bannerSlider">
	@for ($i = 1 ; $i < 6 ; $i++)
	<a class="slide" href="#">
		<img src="<?=asset('../spa/img/demo/banner0'.$i.'.jpg')?>" alt="demo_banner" />
	</a>
	@endfor
	<div class="bannerNext npNav">Next</div><div class="bannerPrev npNav">Prev</div>
</div><!-- ======================== mainBanner end ======================== -->