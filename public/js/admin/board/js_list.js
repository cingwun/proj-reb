$(function(){
	// check search
	$('#search-form').submit(function(){
		var $keyword = $('input[name=keyword]'),
			keyword = $.trim($keyword.val());
		if (keyword.length==0 || keyword.length<2){
			alert("提醒您:\n\n\t未輸入關鍵字或少於兩個字");
			$keyword.focus();
			return false;
		}
		return true;
	});

	// bind click event for
	$('.status').click(function(e){
		e.preventDefault();
		var $this = $(this),
			params = {};
		params._token = JSVAR._token;
		params.board_id = $this.attr('data-id');
		$.ajax({
			url: JSVAR.ajaxURL,
			data: params,
			dataType: 'json',
			type: 'POST',
			success: function(d, s, x){
				if (d.status=='ok'){
					var text = (d.value=='1') ? '顯示' : '隱藏';
					JSVAR._token = d._token;
					$this.text(text);
				}
				alert(d.message);
			},
			error: function(x, s, e){
				alert('系統錯誤，請通知工程師!')
			}
		});
	});
})