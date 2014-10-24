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
function getSelectBox() {
    var url = $('#manyDeleteURL').val();

    var deleteRes = new Array();
    $('input:checkbox:checked[name="selectBox"]').each(function(i) {
        deleteRes[i] = this.value;
    });
    if(deleteRes.length == 0) {
        alert('請勾選需要刪除的預約資料');
        return;
    }
    $.ajax({
        url: url,
        type: 'POST',
        data:{
            deleteRes: deleteRes
        },
        dataType: 'json',
        async: true,
        success: function(res, s, xhr) {
            alert(res.message);
            if(res.status == 'ok')
                window.location.reload();
        },
        error: function() {
            alert('提醒您:\n\n    系統刪除錯誤，請通知工程師');
        }
    });
}
function selectAll(allBox) {
    $('input[name="selectBox"]').each(function(i, obj) {
        obj.checked = allBox.checked;
    });
}
function checkSelect() {
    var checkBox = $('input[name="selectBox"]');
    var checkSum = checkBox.size();
    var checkAmount = 0;
    checkBox.each(function(i,obj) {
        if(obj.checked)
            checkAmount++;
    });
    $('#allCheckBox')[0].checked = (checkSum == checkAmount);
}
