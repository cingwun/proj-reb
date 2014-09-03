'use strict';

/*
 * prototype of uploader
 *
 * @params (object) o, {el}
 */
var _uploader = function(o){

    var _progressBar = function(o){
        // initialize
        o.init = function(){
            var self = this;
            self.$bar = self.$el.find('.bar');
            return self;
        }

        /*
         * toggle progress bar
         *
         * @params (bool) bool(optional)
         */
        o.toggle = function(bool){
            if (bool=='undefined')
                this.$el.toggle();
            else
                this.$el.toggle(bool);
            return this;
        }

        /*
         * set value for bar
         *
         * @params (int) percent
         */
        o.setValue = function(percent){
            this.$bar.css('width', percent+'%');
            return this;
        }

        /*
         * set filename
         *
         * @params (string) filename
         */
        o.setFilename = function(filename){
            this.$bar.html(filename);
            return this;
        }

        return o.init();
    }

    // initialize
    o.init = function(){
        var self = this;

        self.$el = $(self.el);
        self.$fu = self.$el.find('#fileupload');
        self.$image = self.$el.find('input[name='+self.fieldName+']');
        self.$thumb = self.$el.find('#thumb');


        self.$el.find('.btn-select').click(function(e){
            self.$fu.trigger('click');
        });

        self.progress = _progressBar({$el: self.$el.find('#progress')});

        self.xhr = self.$fu.fileupload({
            autoUpload: false,
            dataType: 'json',
            replaceFileInput: false,
            add: function (e, data) {
                self.progress.setFilename(data.files[0].name)
                             .toggle(true);
                data.submit();
            },
            change: function(e, data){
                self.progress.setFilename('');
            },
            done: function (e, data) {
                if (data.result.status=='ok')
                    self.addImage(data.result.files[0]);
                else
                    alert('上傳錯誤，請再試一次!');
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                self.progress.setValue(progress);
            }
        });

        // init image
        self.addImage(self.$image.val());

        return self;

    }

    /*
     * add image to thumbnail and file list
     *
     * @params (string) img
     */
    o.addImage = function(img){
        if (img.length==0){
            this.$thumb.hide();
            return ;
        }

        var self = this,
            image = new Image();

        image.src = img + '?w=120';
        image.onload = function(){
            self.$thumb.attr('src', this.src)
                       .show();
        }

        self.files.push(img);
        self.$image.val(img);
        setTimeout(function(){
            self.progress.setValue(0)
                         .toggle(false);
        }, 3000);
    }

    /*
     * get image list
     */
    o.getImageList = function(){
        return this.files.join('=sep=');
    }

    /*
     * validate image uploader
     *
     * @return (bool) bool
     */
    o.validate = function(){
        return (this.$image.val()!='');
    }

    return o.init();
}


$(function(){
    // create uploader instance
    var uploader = _uploader({
        el: '.uploader',
        fieldName: 'imageURL',
        files: []
    });

    $('.btn-submit').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        if (!uploader.validate()){
            alert("提醒您:\n\n    尚未上傳圖片!");
            return false;
        }

        var bool = true,
            title = $.trim($('input[name=title]').val());

        bool = bool && (title.length>0);

        if (bool){
            $('form').submit();
            return true;
        }

        alert("提醒您:\n\n    未填入標題!");
        return false;
    });
});