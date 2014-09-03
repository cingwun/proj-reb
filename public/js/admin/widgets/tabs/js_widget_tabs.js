'use strict';

/*
 * controller prototype of tabs
 */
var _tabs = function(o){
    //initiaiize
    o.init = function(){
        var self = this;
        self.tabs = [];
        self.isEditMode = false;
        self.keyNum = 0;
        self.current = 0;

        self.$el = $(self.el);
        self.$inputItem = self.$el.find('input[name=tabName]');
        self.$inputOrd = self.$el.find('input[name=tabOrder]');
        self.$tabs = self.$el.find('ul.nav-tabs');
        self.$tabs.on('click', 'li', function(e){
            self.onClick_tab(e);
        });

        self.$tabContents = self.$el.find('.tab-content');
        self.$btnAdd = self.$el.find('.btn-add');
        self.$btnAdd.click(function(){
            self.onClick_add();
        });

        self.$storeBar = self.$el.find('.btn-storebar');
        self.$storeBar.find('.btn-store').click(function(){
            self.onClick_store();
        });

        self.$editBar = self.$el.find('.btn-editbar');
        self.$editBar.find('.btn-edit').click(function(){
            self.onClick_edit();
        });

        self.$editBar.find('.btn-remove').click(function(){
            self.onClick_remove();
        })

        // fetch initialize data
        self.$tabs.find('li').each(function(idx, obj){
            var $obj = $(obj),
                tab = {name: $obj.find('a').text(), order: idx+1, key: $obj.attr('data-key')};
            self.tabs.push(tab);
        });

        self.keyNum = self.tabs.length;
        self.renderTabs(false);

        self.$inputOrd.val(self.tabs.length+1);

        return self;
    }

    /*
     * on click button of action
     */
    o.onClick_add = function(){
        var self = this;
        if (!self.isEditMode){
            var tab = self.collectItem(),
                key = 'tab' + self.keyNum;

            if (tab===false)
                return;

            tab.key = key;
            self.tabs.push(tab);
            self.current = self.tabs.length-1;
            self.renderTabs(true);
            self.keyNum++;
            self.$inputItem.val('');
            self.$inputOrd.val(self.tabs.length+1);
        }
    }

    /*
     * handle click event for button of edit
     */
    o.onClick_edit = function(){
        var self = this,
            curr = self.tabs[self.current];
        self.$editBar.hide();
        self.$storeBar.show();
        self.$btnAdd.hide();
        self.$inputItem.val(curr.name);
        self.$inputOrd.val(curr.order);
        self.isEditMode = true;
        // replace editor
        self.toggleEditor(true);
    }

    /*
     * handle click event for button of remove
     */
    o.onClick_remove = function(){
        var self = this,
            curr = self.tabs[self.current];

        self.$tabs.find('li.active').remove();
        self.$tabContents.find('div.active').remove().end()
                                 .find('.' + curr.key).remove();
        self.tabs.splice(self.current, 1);
        if (self.tabs.length===0)
            self.$editBar.hide();

        self.current = 0;
        self.renderTabs(false);
    }

    /*
     * handle click event for button of store
     */
    o.onClick_store = function(){
        var self = this,
            origTab = self.tabs[self.current],
            tab = self.collectItem();

        self.$storeBar.hide();
        self.$editBar.show();
        self.$btnAdd.show();

        self.isEditMode = false;

        if (tab===false)
            return;

        // destroy editor
        self.toggleEditor(false);

        if (origTab.order!=tab.order || origTab.name!=tab.name){
            origTab.name = tab.name;
            origTab.order = tab.order;
            self.tabs[self.current] = origTab;
            self.renderTabs(false);
        }
    }

    /*
     * handle click event for tab
     * @params (object) e
     */
    o.onClick_tab = function(e){
        e.preventDefault();
        if (this.isEditMode){
            alert("提醒您:\n\n\t請點選右方「離開」按紐，離開編輯模式");
            return ;
        }

        var self = this,
            $this = $(e.currentTarget),
            key = $this.attr('data-key'),
            len = self.tabs.length,
            i = 0;

        self.$tabs.find('li').removeClass('active');

        $this.addClass('active');

        self.$tabContents.find('div.active')
                            .removeClass('active')
                            .end()
                         .find('#'+key)
                            .addClass('active');
        self.$editBar.show();
        for(; i<len; i++){
            if(self.tabs[i].key==key)
                self.current = i;
        }
    }

    /*
     * add content pane
     * @params (object) tab, {name, order, key}
     */
    o.addContent = function(tab){
        this.$tabContents.find('.active').removeClass('active');
        this.$tabContents.append('<div class="tab-pane active" id="' + tab.key + '"></div><textarea name="tabContents[' + tab.key + ']" class="' + tab.key + ' editor"></textarea>');
    }

    /*
     * collect item value
     * @return (mixed) res
     */
    o.collectItem = function(){
        var name = $.trim(this.$inputItem.val()),
            order = parseInt($.trim(this.$inputOrd.val()));
        if (name.length===0 || order.length===0){
            alert("提醒您:\n\n\t請輸入tab名稱或順序");
            return false;
        }else{
            return {name: name, order: order};
        }
    }

    /*
     * render tab and content
     */
    o.renderTabs = function(isNew){
        var self = this,
            len = self.tabs.length,
            i=0, html='', isActive, curr;

        if (len===0){
            self.resetOrder();
            return;
        }

        curr = self.tabs[self.current];

        // remove all tab
        self.$tabs.find('li').remove();
        self.$tabs.html('');

        self.tabs.sort(function(a,b){
            return (a.order>b.order);
        });

        for(; i<len; i++){
            isActive = (self.tabs[i].key==curr.key) ? 'active' : '';
            html += '<li class="' + isActive + '" data-key="' + self.tabs[i].key + '">' +
                    '<a href="#">' + self.tabs[i].name + '&nbsp;(' + self.tabs[i].order + ')<input type="hidden" name="tabName[' + self.tabs[i].key + ']" value="' + self.tabs[i].name + '"/></a>' +
                    '</li>';
        }
        self.$tabs.html(html);

        if (isNew)
            self.addContent(curr);
        else{
            if (self.tabs.length===0)
                self.$editBar.hide();
            else
                self.$tabs.find('li:first').trigger('click');
            self.resetOrder();
        }
    }

    /*
     * reset order
     */
    o.resetOrder = function(){
        this.$inputOrd.val(this.tabs.length + 1);
    }

    /*
     * toggle editor
     */
    o.toggleEditor = function(bool){
        var self = this,
            curr = self.tabs[self.current];

        if (bool)
            self.ckeditor = CKEDITOR.replace(curr.key);
        else{
            if (self.ckeditor!='undefined'){
                var data = self.ckeditor.getData();
                self.$tabContents.find('#'+curr.key)
                                    .html(data)
                                    .end()
                                 .find('.'+curr.key)
                                    .val($('<div />').html(data).html());
                self.ckeditor.destroy();
            }
            self.ckeditor = null;
        }
    }

    /*
     * validate
     * @return (bool)
     */
    o.validate = function(){
        if (this.isEditMode)
            alert("提醒您:\n\n\t尚未離開編輯模式，請點選右方「離開」按紐");

        return (this.tabs.length!==0 && !this.isEditMode);
    }

    return o.init();
}