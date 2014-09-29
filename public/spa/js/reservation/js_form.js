'use strict';

/*
 * prototype of reservation
 */
var _reservation = function(o){
    // initialize
    o.init = function(){
        var self = this;
        self.$el = $(self.el);
        self.$form = self.$el.find('form');
        self.$form.find('.sent').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            self.onClick_submit();
        });

        self.$input = self.$form.find('input[type=text]');
        self.ajaxURL = self.$form.attr('action');
        return self;
    }

    o.onClick_submit = function(){
    	var self = this,
            isValid = true;

        self.$input.each(function(idx, input){
            var $input = $(this),
            	value = $.trim($input.val()),
            	bool = (value.length>0);
            if($input.attr('name')=='line' || $input.attr('name')=='wechat' || $input.attr('name')=='qq' || $input.attr('name')=='improve_item' || $input.attr('name')=='other_notes')
            	bool = true;
            if ($input.attr('name')=='email'){
                var reg = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
                bool &= (reg.test(value));
            }

            if (!bool)
                $input.css('border', '1px solid #C00');

            isValid &= bool;
        });

        if (!isValid){
            alert("提醒您:\n\n    部分欄位未填寫或格式錯誤!!");
            return false;
        }

        $.ajax({
            url: self.ajaxURL,
            type: 'post',
            data: self.$form.serialize(),
            dataType: 'json',
            success: function(res, s, xhr){
                if (res.status=='ok'){
                    alert("預約成功，我們將儘快與您聯絡，謝謝!");
                    switch(self.mode) {
                        case 'page':
                            location.reload();
                            break;
                        case 'iframe':
                            self.$input.val('');
                            self.$el.hide();
                            break;
                    }
                    return true;
                }

                alert(res.message);
                return false;
            },
            error: function(){
                alert("提醒您:\n\n    系統連線發生問題，請通知本院!!");
            }
        });
    }
    return o.init();
}