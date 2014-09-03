'use strict';

var $btnRefresh;

var _msgBox = function(o){
	o.init = function(){
		var self = this;
		self.$el = $(self.el);
		self.$el.find('.close').click(function(e){
			e.preventDefault();
			self.close();
		});
		return self;
	}

	o.open = function(){
		this.$el.fadeIn();
	}

	o.close = function(){
		this.$el.fadeOut();
	}

	return o.init();
}

$(function(e){
	var $codeImage = $('#codeImage'),
		msgBox = _msgBox({el: '.popBox'});

	$btnRefresh = $('#btn-refresh');
	$btnRefresh.click(function(e){
		e.preventDefault();
		var ts = Math.round((new Date()).getTime() / 1000),
			URL = $(this).attr('data-url') + '?key=' + ts,
			img = new Image();
			img.src = URL;
			img.onload = function(){
				$codeImage.attr('src', this.src);
			}
	}).trigger('click');

	$('#btn-enter').click(function(e){
		e.preventDefault();
		e.stopPropagation();

		var name = $.trim($('input[name=name]').val()),
			email = $.trim($('input[name=email]').val()),
			ask = $.trim($('input[name=ask]').val()),
			content = $.trim($('textarea').val()),
			code = $.trim($('input[name=code]').val()),
			text = "提醒您:\n\n\t",
			params = {};

		if (name.length==0){
			alert(text + '尚未填入您的大名!');
			return false;
		}else
			params.name = name;

		if (email.length==0){
			alert(text + '尚未填入電子郵件!');
			return false;
		}else{
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    		if (!re.test(email)){
    			alert(text + '電子郵件格式錯誤!');
				return false;
    		}else
    			params.email = email;
		}

		if (ask.length==0){
			alert(text + '尚未填入發問主題!');
			return false;
		}else
			params.ask = ask;

		if (content.length==0){
			alert(text + '尚未填入發問內容!');
			return false;
		}else
			params.content = content;

		if (code.length==0){
			alert(text + '尚未填入驗証碼!');
			return false;
		}else
			params.code = code;

		$('input[name=sex]').each(function(idx, obj){
			if (this.checked)
				params.sex = this.value;
		});

		$('input[name=isPrivate]').each(function(idx, obj){
			if (this.checked)
				params.isPrivate = this.value;
		});

		params.user_id = $('input[name=user_id]').val();

		$.ajax({
			url: $(this).attr('data-url'),
			data: params,
			dataType: 'json',
			type: 'POST',
			success: function(d, s, xhr){
				if (d.status=='ok'){
					msgBox.open();
					$('input[type=text], textarea').val('');
					$('input[name=name]').focus();
				}else
					alert(d.message);

				$btnRefresh.trigger('click');
			},
			error: function(xhr, s, e){
				alert("很抱歉，網路發生問題，我們將儘快修復，敬請原諒!");
			}
		});
	});

})