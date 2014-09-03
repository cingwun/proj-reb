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
		self.$image = self.$el.find('input[name=image]');
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
		files: []
	});

	var isDate = function (txtDate){
		var currVal = txtDate;
		if(currVal == '')
			return false;

		//Declare Regex
		var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
		var dtArray = currVal.match(rxDatePattern); // is format OK?

		if (dtArray == null)
			return false;

		//Checks for mm/dd/yyyy format.
		dtMonth = dtArray[1];
		dtDay= dtArray[3];
		dtYear = dtArray[5];

		if (dtMonth < 1 || dtMonth > 12)
			return false;
		else if (dtDay < 1 || dtDay> 31)
			return false;
		else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
			return false;
		else if (dtMonth == 2){
			var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
			if (dtDay> 29 || (dtDay ==29 && !isleap))
				return false;
		}

		return true;
	}

	$('.btn-submit').click(function(e){
		var $title = $('#title'),
			$start = $('input[name=start]'),
			$end = $('input[name=end]'),
			title = $.trim($title.val()),
			start = $.trim($start.val()),
			end = $.trim($end.val()),
			bool = true;

		$('.alert').remove();

		if (title==''){
			$title.after('<div class="alert alert-danger" style="margin-top: 5px">未輸入標題</div>');
			bool = false;
		}

		if (!uploader.validate()){
			uploader.$el.after('<div class="alert alert-danger" style="margin-top: 5px">未上傳Banner圖片</div>');
			bool = false;
		}else{
			$('input[name=imglist]').val(uploader.getImageList());
		}

		if (start.length>0){
			if (isDate(start))
				start = parseInt(start.replace('-', ''));
			else{
				$start.after('<div class="alert alert-danger" style="margin-top: 5px">日期格式錯誤(ex: 2014-01-01)</div>');
			}
		}

		if (end.length>0){
			if (isDate(end))
				end = parseInt(end.replace('-', ''));
			else{
				$end.after('<div class="alert alert-danger" style="margin-top: 5px">日期格式錯誤(ex: 2014-01-01)</div>');
			}
		}

		if ((start>0 && end>0) && start<end){
			bool = false;
			$start.after('<div class="alert alert-danger" style="margin-top: 5px">上線時間晚於下線時間</div>');
		}

		if (!bool){
			alert("提醒您:\n\n\t您有部分欄位輸入錯誤");
			return false;
		}

		$('form').submit();
	});

	$('.input-daterange').datepicker({
	    format: "yyyy-mm-dd",
	    todayBtn: true,
	    language: "zh-TW"
	});
});