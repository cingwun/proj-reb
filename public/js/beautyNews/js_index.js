'use strict';

/*
 * prototype of share
 */
var _share = function(o){
    // initialize
    o.init = function(){
        var self = this;

        self.url = self.$el.attr('data-shareURL');
        self.title = self.$el.attr('data-shareTitle');
        self.image = self.$el.attr('data-shareImage');

        self.$el.find('.btn-facebook').click(function(e){
            e.preventDefault();
            self.toFacebook();
        });

        self.$el.find('.btn-google').click(function(e){
            e.preventDefault();
            self.toGoogle();
        });

        self.$el.find('.btn-line').click(function(e){
            e.preventDefault();
            self.toLine();
        });

        self.$el.find('.btn-plurk').click(function(e){
            e.preventDefault();
            self.toPlurk();
        });

        self.$el.find('.btn-twitter').click(function(e){
            e.preventDefault();
            self.toTwitter();
        });

        self.$el.find('.btn-weibo').click(function(e){
            e.preventDefault();
            self.toWeibo();
        });

        return self;
    }

    /*
     * share to facebook
     */
    o.toFacebook = function(){
        var href = 'https://www.facebook.com/sharer/sharer.php?u=' + this.url;
        //window.open(href);
        this.openDialog(href);
        return false;
    }

    /*
     * share to google
     */
    o.toGoogle = function(){
        var href = 'http://plus.google.com/share?url=' + this.url;
        //window.open(href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes');
        this.openDialog(href);
        return false;
    }

    /*
     * share to line
     */
    o.toLine = function(){
        var href = 'http://line.me/R/msg/text/?' + encodeURIComponent(this.title+"\r\n"+this.url);
        this.openDialog(href);
        return false;
    }

    /*
     * share to plurk
     */
    o.toPlurk = function(){
        var href = 'http://www.plurk.com/?qualifier=shares&status=' + this.url,
            title = encodeURIComponent(this.title);
        href += '(' + title + ')';
        this.openDialog(href);
        return true;
    }

    /*
     * share to twitter
     */
    o.toTwitter = function(){
        var href = 'https://twitter.com/home?status=' + this.url;
        this.openDialog(href);
        return true;
    }

    /*
     * share to weibo
     */
    o.toWeibo = function(){
        var href = 'http://v.t.sina.com.cn/share/share.php?appkey=' +
                   '&url=' + encodeURIComponent(this.url) +
                   '&title=' + encodeURIComponent(this.title) +
                   '&source=' + '&sourceURL=' +
                   '&content=utf-8' +
                   '&pic=' + encodeURIComponent(this.image);

        this.openDialog(href);
        return true;
    }

    /*
     * open share dialog
     */
    o.openDialog = function(href){
        var s = screen,
            meta = ['toolbar=0,status=0,resizable=1,width=440,height=430,left=',(s.width-440)/2,',top=',(s.height-430)/2].join('');
        window.open(href, 'mb', meta);
    }

    return o.init();
}