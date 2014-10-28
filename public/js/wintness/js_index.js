'use strict';

var app = function(o){
    // initialize
    o.init = function(){
        var self = this;
        self.isotopeOptions = {
            layoutMode: 'masonry',
            masonry: {
                columnWidth:219,
                gutter: 28,
                animationEngine : 'best-available',
            },
            selectItem: '.caseWrapper'
        };

        self.$info = $('#search-info');

        self.$container = $('#container');
        self.$container.isotope(self.isotopeOptions);

        var midHeight = $(window).height() - 331;
        $('#midWrap').css('min-height', midHeight+'px');

        self.list = [];
        self.totalImages = 0;

        return self;
    }

    // before load
    o.beforeLoad = function(){
        this.toggleInfo(false);
    }

    // load data
    o.load = function(params){
        this.beforeLoad();

        if (typeof(params)=='undefined')
            params = {page: 1};

        var self = this;

        if (params.page==1){
            self.$container.empty()
                           .isotope('destroy')
                           .isotope(self.isotopeOptions);
            self.totalImages = 0;
        }
        $.ajax({
            url: self.$container.attr('data-loadURL'),
            type: 'GET',
            data: params,
            dataType: 'json',
            success: function(res, status, xhr){
                if (res.status=='ok'){
                    var i = 0,
                        len = res.data.length;

                    if (len!=0){
                        for(i; i<len; i++){
                            var html = tmpl('template', res.data[i]),
                                $html = $(html),
                                img = new Image(),
                                key = '#item-' + res.data[i].id;

                            img.src = res.data[i].cover + '?w=280';
                            img.onload = function(){
                                self.totalImages--;
                                if (self.totalImages==0){
                                    for(var j=0; j<self.list.length; j++)
                                        self.list[j].$el.find('img').attr('src', self.list[j].img.src);
                                    setTimeout(function(){
                                        self.$container.isotope('layout');
                                    }, 1000);

                                    console.log(self.$container.isotope('getItemElements'));
                                    self.list = [];
                                }
                            }

                            self.$container.isotope('insert', $html);
                            self.totalImages++;

                            self.list.push({key: key, img: img, $el: $html});
                        }

                        params.page++;
                        self.load(params);
                    }else{
                        if (params.page==1){
                            if (params.title)
                                self.setNullInfoText(params.title);

                            if (params.keyword)
                                self.setNullInfoText(params.keyword);
                        }
                    }
                }
            },
            error: function(){
                alert("提醒您:\n\n    系統發生錯誤。");
            }

        });
    }

    // set info text of null status
    o.setNullInfoText = function(text){
        var html = "很抱歉，目前尚未有與「" + text + "」相關的案例。   <a href='/wintness#/list'>顯示全部案例</a>";
        this.$info.html(html);
        this.toggleInfo(true);
    }

    // toggle info
    o.toggleInfo = function(bool){
        if (typeof(bool)=='undefined')
            bool = true;
        if (bool)
            this.$info.fadeIn();
        else
            this.$info.fadeOut();
    }

    return o.init();
}({});


// route by specific item
Path.map("#/item/:id/:title").to(function(){
    var id = parseInt(this.params['id']),
        title = this.params['title'].replace('#', '/'),
        params = {page:1, item: id, 'title': title};
    if (id<=0){
        alert("提醒您:\n\n    您輸入的網址有誤，請重新輸入");
        return;
    }

    app.load(params);
});

// route by specific keyword
Path.map("#/keyword/:keyword").to(function(){
    var keyword = $.trim(this.params['keyword']),
        params = {page:1, keyword: keyword.replace('#', '/')};
    if (keyword.length<2){
        alert("提醒您:\n\n    您輸入的網址有誤，請重新輸入");
        return;
    }

    app.load(params);
});

Path.map('#/list').to(function(){
    app.load({page: 1});
});

Path.listen();
