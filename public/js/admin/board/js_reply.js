$(function(){
	var label = _labels({el: '#label-panel'});
	$('.btn-submit').click(function(e){
		e.preventDefault();
		var content = $.trim($('#content').val()),
			bool = true;
		if (content.length==0){
			alert("提醒您，\n\n\t尚未填入回覆內容!");
			bool = false;
		}

		if (bool)
			$('form').submit();

	});
})