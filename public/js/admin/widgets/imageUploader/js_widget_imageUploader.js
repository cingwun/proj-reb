'use strict';
/*
 * prototype of uploader
 *
 * @params (object) o, {$el, }
 */
var _uploader = function(o){

    var _progressBar = function(obj){
        // initialize
        obj.init = function(){
            var self = this;
            self.$bar = self.$el.find('.bar');
            return self;
        }

        /*
         * toggle progress bar
         *
         * @params (bool) bool(optional)
         */
        obj.toggle = function(bool){
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
        obj.setValue = function(percent){
            this.$bar.css('width', percent+'%');
            return this;
        }

        /*
         * set filename
         *
         * @params (string) filename
         */
        obj.setFilename = function(filename){
            this.$bar.html(filename);
            return this;
        }

        return obj.init();
    }

    // initialize
    o.init = function(){
        var self = this,
            url = '';
        self.$fu = self.$el.find('input:file');

        url = self.$fu.attr('data-url');

        if (self.isMultiple)
            self.$fu.attr('multiple', 'multiple');

        self.$el.find('.btn-select').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            self.$el.find('input:file').click();
        });

        self.progress = _progressBar({$el: self.$el.find('.progress-box')});

        self.xhr = self.$fu.fileupload({
            dataType: 'json',
            add: function (e, data) {
                self.progress.setFilename(data.files[0].name)
                             .toggle(true);
                data.submit();
            },
            change: function(e, data){
                self.progress.setFilename('');
            },
            done: function (e, data) {
                if (data.result.status=='ok'){
                    if (self.afterUpload)
                        self.afterUpload(data.result);
                    else
                        alert('上傳完成!');
                }else
                    alert('上傳錯誤，請再試一次!');
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                self.progress.setValue(progress);
                if (progress>=100)
                    self.progress.toggle(false);
            }
        });

        return self;

    }

    /*
     * hide uploader
     */
    o.hide = function(){
        this.$el.hide();
    }

    /*
     * display uploader
     */
    o.show = function(){
        this.$el.show();
    }

    return o.init();
}

/*
 * prototype of image uploader
 * @params (object) o, {
        (string) el,
        (bool) isMultiple
        (object) imageBoxMeta: {
            photoFieldName,
            descFieldName(optional),
            delFieldName
        },
        (array) files
    }
 */
var _imageUploader = function(o){
    // initialize
    o.init = function(){
        var self = this;
        self.$el = $(self.el);
        self.$photoList = self.$el.find('.photo-list');
        self.$delPhotoList = self.$el.find('.delete-photo-list');

        self.$photoList.on('click', '.btn-remove', function(e){
            e.preventDefault();
            e.stopPropagation();
            self.onClick_remove(e);
        });

        self.uploader = _uploader({
            $el: self.$el.find('.uploader'),
            isMultiple: self.isMultiple,
            afterUpload: function(res){
                if (res.status=='ok'){
                    if (!this.isMultiple)
                        this.hide();
                    var item = {id: null, image: res.files[0], text: ''};
                    self.addImageBox(item, true);
                    console.log(this.$fu);
                    return ;
                }

                alert(res.message);
            }
        });

        self.deleteURL = self.$el.attr('data-deleteURL');

        if (typeof(self.files)=='undefined')
            self.files = [];
        else{
            var len = self.files.length,
                i = 0;
            for(; i<len; i++)
                self.addImageBox(self.files[i]);
        }

        if (self.files.length>0 && !self.isMultiple)
            self.uploader.hide();
        return self;
    }

    /*
     * add an image box
     * @params (object) item, {image, text}
     * @params (bool) force
     */
    o.addImageBox = function(item, force){
        if (typeof(item)=='undefined')                                  
            return false;

        if (typeof(force)=='undefined')
            force = false;

        var id = (typeof(item.id)=='undefined' || item.id==null) ? 'null' : item.id,
            desc = (this.imageBoxMeta.descFieldName) ?
                        '<input class="desc" type="text" value="' + item.text + '" name="' + this.imageBoxMeta.descFieldName + '" placeholder="請輸入描述…"/>' :
                        '',
            html = '<li data-id="' + id + '">' +
                   '<span class="btn-remove" title="刪除圖片"><i class="icon-trash icon-white glyphicon glyphicon-trash"></i></span>' +
                   '<img src="' + item.image + '?w=120&h=90&ar=i" />' + desc +
                   '<input class="photoField" type="hidden" value="' + item.image + '" name="' + this.imageBoxMeta.photoFieldName + '" />' +
                   '</li>';

        this.$photoList.append(html);

        if (force)
            this.files.push(item);
    }

    /*
     * delete image box
     * @params (event) e
     */
    o.onClick_remove = function(e){
        var self = this,
            $parent = $(e.currentTarget).parent(),
            filename = $parent.find('.photoField').val(),
            id = $parent.attr('data-id');
        /*    len = self.files.length,
            i = 0,
            list = [],
            item;

        for(i; i<len; i++){
            if (self.files[i].image!=filename)
                list.push(self.files[i]);
            else
                item = self.files[i];
        }
        self.files = list;


        $parent.remove();*/

        if (id=='null'){
            $.ajax({
                url: self.deleteURL,
                type: 'post',
                data: {file: filename},
                dataType: 'json',
                success: function(res, s, xhr){
                    if (res.status=='ok'){
                        $parent.remove();
                        /*
                        var len = self.files.length,
                            i = 0,
                            list = [];
                        for(i; i<len; i++){
                            if (self.files[i].image!=filename)
                                list.push(self.files[i]);
                        }
                        self.files = list;
*/
                        self.afterRemoveImage(filename, null);
                    }else
                        alert(res.message);
                },
                error: function(){
                    alert('刪除錯誤 [404]');
                }
            });
        }else{
            $parent.remove();
            self.afterRemoveImage(filename, id);
        }
    }

    o.validate = function(){
        return (this.files.length==0) ? false : true;
    }

    /*
     * after remove image
     * @params (string) image
     * @params (mixed) id
     * @return (bool) bool
     */
    o.afterRemoveImage = function(image, id){
        if (typeof(image)=='undefined')
            return false;

        var len = this.files.length,
            i = 0,
            list = [],
            item;

        for(i; i<len; i++){
            if (this.files[i].image!=image)
                list.push(this.files[i]);
        }
        this.files = list;

        if (!this.isMultiple && this.files.length==0)
            this.uploader.show();

        /*
         * prepare for change mechanism of delete
         */
        if (id!=null)
            this.$delPhotoList.append('<input type="hidden" name="' + this.imageBoxMeta.deleteFieldName + '" value="' + id + '"/>');

        return true;
    }

    return o.init();
}