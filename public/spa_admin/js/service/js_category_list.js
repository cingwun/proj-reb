'use strict';

/*
 * form object
 * @params (object) o, {el}
 */
var formCategory = function(o){
    // initialize
    o.init = function(){
        var self = this;
        self.$el = $(self.el);
        self.$input = self.$el.find('input');
        self.$panelTitle = self.$el.find('.panel-title');
        self.$form = self.$el.find('form');

        self.$el.find('.btn-reset').click(function(e){
            e.stopPropagation();
            e.preventDefault();

            if (self.mode=='create')
                self.reset();
            if (self.mode=='update')
                self.setValue();
            return false;
        });

        self.$submit = self.$el.find('.btn-submit');
        self.$submit.click(function(e){
            e.stopPropagation();
            e.preventDefault();
            var params = self.$form.serialize();
            $.ajax({
                url: self.ajaxURL,
                type: 'POST',
                data: params,
                dataType: 'json',
                success: function(res, s, xhr){
                    if (res.status=='ok'){
                        alert('更新成功，頁面將立即重新整理!');
                        window.location.reload();
                    }
                    alert("提醒您:\n\n    "+res.message);
                    return;
                },
                error: function(){
                    alert("系統更新錯誤，請通知工程師!");
                    return;
                }
            })
        });

        self.ajaxURL = self.$form.attr('action');
        self.isNew = true;
        self.mode = 'create'; // create, update
        self.model = {title: '', sort: 1, id: 'null', lang: 'tw', ref: 'null'};
        self.reset();
        return self;
    }

    /*
     * input value when update
     * @params (object) model
     */
    o.update = function(model){
        this.isNew = false;
        this.mode = 'update';
        this.setValue(model);
        this.toggleStatus('update');
    }

    /*
     * reset all input field and property
     */
    o.reset = function(){

        $("label[id=lang_tw]").show();
        $("label[id=lang_cn]").show();
        this.mode = 'create';
        this.model = {title: '', sort: 1, id: 'null', lang: 'tw', ref: 'null'};
        this.isNew = true;
        this.setValue();
        this.toggleStatus('create');
    }

    /*
     * create other lang
     */
    o.create_other_lang = function(model){
        if(model.lang=='tw'){
            $("label[id=lang_cn]").hide();
        }else if(model.lang=='cn'){
            $("label[id=lang_tw]").hide();
        }
        this.isNew = true;
        this.mode = 'create';
        this.setValue(model);
        this.toggleStatus('create_lang', model.lang);
    }

    /*
     * setValue
     * @params (object) model,
     */
    o.setValue = function(model){
        if (typeof(model)=='undefined')
            model = this.model;
        else
            this.model = model;
        this.$input.each(function(idx, input){
            var value = "null";
            if (input.name=='title')
                value = model.title;
            if (input.name=='sort')
                value = model.sort;
            if (input.name=='id')
                value = model.id;
            if (input.name=='ref')
                value = model.ref;
            if (input.name=='lang'){
                if (input.value==model.lang)
                    $(input).prop('checked', 'checked');
                return;
            }
            $(this).val(value);
        });
    }

    /*
     * toggle status
     * @params (string) action
     */
    o.toggleStatus = function(action,lang){
        if (action=='update'){
            this.$panelTitle.html('編輯分類');
            this.$submit.html('編輯完成');
        }else if(action=='create'){
            this.$panelTitle.html('新增分類');
            this.$submit.html('新增');
        }else if(action=='create_lang'){
            if(lang=='tw')
                lang = "繁體";
            else
                lang = "簡體";
            this.$panelTitle.html('新增分類-'+lang);
            this.$submit.html('新增');
        }
    }

    return o.init();
}({el: '#form-panel'});

/*
 * sort table object
 */
var sortTable = function(o){
    // initialize
    o.init = function(){
        var self = this;
        self.trs = [];
        self.updateId = null;
        self.$el = $(self.el);

        self.$el.tableDnD({
            onDragClass: 'onDraging',
            onDragStart: function(table, row){
                if (self.updateId!=null)
                    self.resetStatus();
            },
            onDrop: function(table, row){
                self.resetTrCollection();
                self.updateSort(row.id);
            }
        });

        self.ajaxSortURL = self.$el.attr('data-sortAction');
        self.ajaxDeleteURL = self.$el.attr('data-deleteAction');
        self.resetTrCollection();
        return self;
    }

    /*
     * get specific row by id
     * @params (int) id,
     * @params (object) model
     */
    o.getTr = function(id){
        var i = 0,
            len = this.trs.length,
            index;
        for(; i<len; i++){
            if (id==this.trs[i].id){
                index = i;
                break;
            }
        }
        return {index: index, tr: this.trs[index]};
    }

    /*
     * get html by specific tr object
     * @params (object) tr
     * @params (int) index
     * @return (mixed) value
     */
    o.getTd = function(tr, tdIndex){
        if (typeof(tr)=='undefined' || typeof(tdIndex)=='undefined')
            return false;
        return tr.$el.find('td:nth-child('+tdIndex+')').html();
    }

    /*
     * handle click event of modify button
     * @params (int) id, mean: row id
     */
    o.onClick_modify = function(id){
        var r = this.getTr(id),
            model = {};
            model.id = id;
            model.ref = "null";
        this.resetStatus();
        this.updateId = id;

        r.tr.$el.find('td').each(function(idx, td){
            if (idx==0)
                model.title = td.innerHTML;
            if (idx==1)
                model.sort = parseInt(td.innerHTML);
            if (idx==2)
                model.lang = td.id;
        }).end()
          .toggleClass('onDraging');
        formCategory.update(model);
    }

    o.onClick_lang = function(id){
        var r = this.getTr(id),
            model = {};
            model.id = "null";
            model.ref = id;
        this.resetStatus();
        this.updateId = id;

        r.tr.$el.find('td').each(function(idx, td){
            if (idx==0)
                model.title = td.innerHTML;
            if (idx==1)
                model.sort = parseInt(td.innerHTML);
            if (idx==2){
                if(td.id=='tw')
                    model.lang = 'cn';
                if(td.id=='cn')
                    model.lang = 'tw';
            }
        }).end()
          .toggleClass('onDraging');
        formCategory.create_other_lang(model);
    }

    /*
     * reset tr collection
     */
    o.resetTrCollection = function(){
        var self = this,
            tr;
        while(tr=self.trs.pop()){
            tr.$el.find('.btn-modify').unbind();
        }

        self.$el.find('tbody tr').each(function(idx, tr){
            var $el = $(this);
            $el.find('.btn-modify').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                self.onClick_modify(tr.id);
            });

            $el.find('.btn-delete').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                var msg = "提醒您:\n\n    刪除該分類時，將連同刪除該分類相關之文章、圖片等資料\n\n    請問您是否刪除?";
                if (!confirm(msg))
                    return ;
                $.ajax({
                    url: self.ajaxDeleteURL,
                    type: 'POST',
                    data: {id: tr.id},
                    dataType: 'json',
                    success: function(res, s, xhr){
                        alert(res.message);
                        if (res.status=='ok')
                            window.location.reload();
                        return;
                    },
                    error: function(){
                        alert('提醒您:\n\n    系統刪除錯誤，請通知工程師');
                    }
                });
            });

            $el.find('.btn-lang').click(function(e){
                e.stopPropagation();
                e.preventDefault();
                self.onClick_lang(tr.id);
            });

            self.trs.push({
                id: tr.id,
                $el: $el
            });
        });
    }

    /*
     * reset status
     */
    o.resetStatus = function(){
        if (this.updateId!=null){
            var r = this.getTr(this.updateId);
            r.tr.$el.removeClass('onDraging');
            formCategory.reset();
        }
        this.updateId==null;
    }

    /*
     * update specific row by model
     * @params (object) model, {key, data}
     */
    o.updateRow = function(model){
        if (typeof(model)=='undefined')
            alert('提醒您:\n\n    系統更新錯誤 [10]');
        var r = this.getTr(model.id);
        r.tr.$el.find('td').each(function(idx, td){
            if (idx==0)
                td.innerHTML = model.title;
            if (idx==1)
                td.innerHTML = parseInt(model.sort);
        });
        this.resetStatus();
    }

    /*
     * update sort
     * @params (int) updatedId
     */
    o.updateSort = function(updatedId){
        var self = this,
            len = this.trs.length,
            r = this.getTr(updatedId),
            params = {id: updatedId};

        if (len==1)
            return;

        if (r.index==0){
            var sort = this.getTd(this.trs[1], self.sortColumn);
            params.sort = parseInt(sort) + 2 ;
        }else if(r.index==(len-1)){
            var idx = r.index-1,
                sort = this.getTd(this.trs[idx], self.sortColumn);
            params.sort = parseInt(sort) - 1;
            if (params.sort<=0){
                params.sort = 1;
                params.isUpdatedTime = true;
                params.lastUpdatedId = this.trs[idx].id;
            }
        }else{
            var beforeSort = parseInt(this.getTd(this.trs[r.index-1], self.sortColumn)),
                afterSort = parseInt(this.getTd(this.trs[r.index+1], self.sortColumn));

            if (beforeSort==afterSort){
                params.sort = beforeSort;
                params.isUpdatedTime = true;
                params.lastUpdatedId = this.trs[r.index-1].id;
            }else{
                if ((beforeSort-afterSort)==2)
                    params.sort = Math.round((beforeSort+afterSort)/2);
                else{
                    params.sort = beforeSort;
                    params.isUpdatedTime = true;
                    params.lastUpdatedId = this.trs[r.index-1].id;
                }
            }
        }

        if (this.role)
            params.role = this.role;

        $.ajax({
            url: self.ajaxSortURL,
            type: 'POST',
            data: params,
            dataType: 'json',
            success: function(res, s, xhr){
                alert(res.message);
                if (res.status=='ok')
                    r.tr.$el.find('td:nth-child('+self.sortColumn+')').html(params.sort);
                return;
            },
            error: function(){
                alert("系統更新錯誤，請通知工程師 [11]");
                return;
            }
        });
    }

    return o.init();

}({el: '#sortable', role: 'category', sortColumn: 2});
