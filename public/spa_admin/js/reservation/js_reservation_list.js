var reservationTable = function(o){
    o.init = function(){
        var self = this;
        self.$el = $(self.el);

        self.resetTrCollection();
        self.ajaxdetailsURL = self.$el.attr('data-detailsAction');
        self.ajaxdeleteURL = self.$el.attr('data-deleteAction');

        return self;
    }
    o.resetTrCollection = function(){
        var self = this, tr;

        self.$el.find('tbody tr').each(function(idx, tr){
            var $el = $(this);

            $el.find('.btn-details').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                $.ajax({
                    url: self.ajaxdetailsURL,
                    type: 'POST',
                    data: {id: tr.id},
                    dataType: 'json',
                    async: true,
                    success: function(res, s, xhr){
                        if (res.status=='ok'){
                            var content = tmpl('tmpl-details', res.dataStr);
                            $.featherlight(content, {closeOnClick: false});
                        }
                    },
                    error: function(){
                        alert('提醒您:\n\n    系統顯示詳細資料錯誤，請通知工程師');
                    }
                });
            });
            $el.find('.btn-delete').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                var msg = "請問您是否刪除?";
                if (!confirm(msg))
                    return ;
                $.ajax({
                    url: self.ajaxdeleteURL,
                    type: 'POST',
                    data: {id: tr.id},
                    dataType: 'json',
                    async: true,
                    success: function(res, s, xhr){
                        alert(res.message);
                        if (res.status=='ok')
                            window.location.reload();
                    },
                    error: function(){
                        alert('提醒您:\n\n    系統刪除錯誤，請通知工程師');
                    }
                });
            });
        });
    }
    return o.init();
}({el: '#reservationTable'});