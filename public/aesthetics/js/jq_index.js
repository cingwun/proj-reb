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

$newTechBox.find('.tabNav li').click(function(e) {
	e.stopPropagation();
	e.preventDefault();
	$newTechBox.find('.curr').removeClass('curr').end().find('.tabBox').eq($(this).index()).addClass('curr');
	$(this).find('a').addClass('curr');
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

/* 美麗留言 */
/*var $popBox =  $(".popBox");
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
*/
/*
$('a').not('#quickReservation').not('.tabNav a').on('click', function(e){
	e.stopPropagation();
	e.preventDefault();
	$.notify( '網站內容建置中!', { globalPosition:"top center", className: 'info', style: 'bootstrap', autoHide: true, autoHideDelay: 2000 });
	});
*/


});