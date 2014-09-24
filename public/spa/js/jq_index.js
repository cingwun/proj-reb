//========================================================
// asamoon @ 2012/05/23
//========================================================

var $newTechBox = $('#newTechBox'),
$servBox = $('#servBox');



$(function(){

	/* 首頁 Banner slider */
	$('.bannerSlider').cycle({ 
		fx:   				'fade', 
		slides:				'> .slide', 
    //pager:  			'#mainBannerNav', 
    next:   			'> .bannerNext', 
    prev:   			'> .bannerPrev',
    pauseOnHover:		true,
    speed:				750, 
    timeout:			6000
});


	/* 美麗新技術 */
/*
$newTechBox.cycle({ 
    timeout: 		0, 
    slide:			'> .tabBox', 
    speed:   		300, 
    startingSlide: 	0
	});
*/

// for the Face book SDK
// Need the appid
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$newTechBox.find('.tabNav li').click(function(e) { 
	e.stopPropagation();
	e.preventDefault();
	$newTechBox.find('.curr').removeClass('curr').end().find('.tabBox').eq($(this).index()).addClass('curr');
	$(this).find('a').addClass('curr');
});

// for the mainNav fixd top when screen height is more than 135px
$(window).scroll(function () {
	var scrollVal = $(this).scrollTop();
	if(scrollVal > 135){
		$('#header #mainNav').addClass('active');
		$('#scrollToTop').addClass('active');
	} else {
		$('#header #mainNav').removeClass('active');
		$('#scrollToTop').removeClass('active');
	}
});
$(function(){
	var scrollVal = $(this).scrollTop();
	if(scrollVal > 135){
		$('#header #mainNav').addClass('active');
		$('#scrollToTop').addClass('active');
	} else {
		$('#header #mainNav').removeClass('active');
		$('#scrollToTop').removeClass('active');
	}
});

// for the scroll top function
$('#scrollToTop').click(function() {
	var scrollVal = $(this).scrollTop();
	$('html, body').animate({
		scrollTop: scrollVal
	}, 1000);
});

// $('element:nth-child(n)').addClass('elementnth');
//to pick up the third element to change its css style
$("#spa_products a.products_categories").each(function(){
	var index = $(this).index();
	if(index %3 ==2){
		$(this).addClass("element3n");
	}
});
//to pick up the fourth element to change its css style
$("#spa_products_list .proList").each(function(){
	var index = $(this).index();
	if(index %4 ==3){
		$(this).addClass("element4n");
	}
});

//isotope
// $(document).ready(function() {
// 	var $container = $('#isoCon');
// 	$container.isotope({
// 		itemSelector: '.isoItem',
// 		layoutMode: 'masonry'
// 	});
// });

//isotope
$( function() {
	
	$('#isoCon').isotope({
		itemSelector: '.isoItem',
		layoutMode: 'masonry',
		masonry: {
			gutter: 14
		},
		sortBy: 'original-order'
	});

});


/* 快速預約 */
$('#quickReservation').click(function(e){
	e.stopPropagation();
	e.preventDefault();

	$('#quickReservationForm').toggle();
});

/* 服務項目 */
$servBox.find('.tabNav a').click(function(e) { 
	e.stopPropagation();
	e.preventDefault();

	boxID = $(this).attr('href');
	$servBox.find('.curr').removeClass('curr').end().find(boxID).addClass('curr');
	$(this).addClass('curr');
});

/* Change the services page's servList menu open/collapse event from hover to click */
$("ul.spaService > li").each(function() {
	$(this).click(function() {
		if($(this).hasClass("noSub")) {
			$(this).siblings().removeClass("active");
		} 
		else {
			$(this).siblings().removeClass("active");
			$(this).toggleClass("active");
		}
	});
});
// service landing page
var $lastBc = $('#last-bc');
$(".serList").each(function() {
	$(this).find('.serList_btn1').click(function() {
		var $parent = $(this).parents('.serList');
		if($parent.hasClass('active')) {
			$parent.removeClass('active');
			return;
		}
		$(this).parents('#serList_con').find('.serList').removeClass('active');
		$parent.addClass("active");
		$lastBc.html($(this).text());
	});
	// change the top image when hover on the sub list
	$(this).find('.serList_btn2 a').each(function() {
		var $serList_pic = $(this).parents(".serList").find(".serList_pic img");
		var defaultImg = $serList_pic.attr("src");
		$(this).hover(
			// mouse in
			function() {
				var thisImg = $(this).attr("data-src");
				$serList_pic.attr("src", thisImg);
				return;
			},
			// mouse out
			function() {
				$serList_pic.attr("src", defaultImg);
			});
	});
});

/* 美麗留言 */
var $popBox =  $(".popBox");
$("#messageAsk").find(".sent").click(function(e){
	e.stopPropagation();
	e.preventDefault();
	$popBox.show();
});

$popBox.find(".close").click(function(e){
	e.stopPropagation();
	e.preventDefault();
	$popBox.hide();
});
/*
$('a').not('#quickReservation').not('.tabNav a').on('click', function(e){
	e.stopPropagation();
	e.preventDefault();
	$.notify( '網站內容建置中!', { globalPosition:"top center", className: 'info', style: 'bootstrap', autoHide: true, autoHideDelay: 2000 });
	});
*/


});

// for colorbox
var slidebox;
$(function(){
	slidebox = _slidebox({
		el: '#sliderBox',
		viewSize: 6,
		itemWidth: 150
	});
});