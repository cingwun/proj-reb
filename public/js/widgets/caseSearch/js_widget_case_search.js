'use strict';

var _searchBox = function(o){
    o.init = function(){
        var self = this;
        self.$el = $(self.el);
        self.$searchPanel = self.$el.find('.funTool');
        self.$itemList = self.$searchPanel.find('#item-list');
        self.$keyword = self.$searchPanel.find('input[name=keyword]');

        self.$el.find('.btn-caseSearch').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            self.open();
        });

        self.$searchPanel.find('.btn-close').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            self.close();
        });

        self.$searchPanel.find('.tab-service, .tab-faq').click(function(e){
            e.preventDefault();
            e.stopPropagation();

            var type = this.className.replace('tab-', '');
            self.toggleItems(type);
        });

        self.$searchPanel.find('.btn-search').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            return self.onClick_search();
        });

        self.$itemList.on('click', 'a.items', function(e){
            self.close();
            return true;
        });

        $(document).keyup(function(e){
            if (e.keyCode==27 && self.isOpenPanel)
                self.close();
        });

        self.current = 'service';
        self.isOpenPanel = false;

        return self;
    }

    // close
    o.close = function(){
        this.$searchPanel.fadeOut();
        this.isOpenPanel = false;
    }

    // on click search
    o.onClick_search = function(){
        var keyword = $.trim(this.$keyword.val()),
            URI = '';
        if (keyword.length<2){
            alert("提醒您:\n\n    尚未輸入關鍵字或字數不足(最少兩個字)");
            return false;
        }

        URI = "/wintness#/keyword/" + keyword.replace('/', '#');
        window.location.href = URI;
        this.close();
        return false;
    }

    // open
    o.open = function(){
        var self = this;
        self.toggleItems();
        self.$searchPanel.fadeIn();
        self.isOpenPanel = true;
    }

    /*
     * toggle items
     * @params (string) type
     */
    o.toggleItems = function(type){
        var self = this,
            models;

        if (typeof(type)=='undefined')
            type = self.current;
        else{
            if (type==self.current) return;
            self.current = type;
        }
        var models = self.models[type],
            html = '';

        $.each(models, function(idx, model){
            html += tmpl('tmpl-tabItem', model);
        });

        self.$itemList.empty()
                      .html(html);

        return ;
    }

    return o.init();
};