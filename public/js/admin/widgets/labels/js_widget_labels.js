'use strict';

/*
 * prototype of labels
 * @params (object) o, {el}
 */
var _labels = function(o){
	// initialize
	o.init = function(){
		var self = this;
		self.list = [];
		self.hasAlert = false;

		self.$el = $(self.el);
		self.$lblContainer = self.$el.find('.labels-container');
		self.$select = self.$el.find('select');

		self.$select.change(function(){
			self.fetchLabels();
		});

		self.fetchLabels();
		return self;
	}

	/*
	 * fetch and display label to container
	 */
	o.fetchLabels = function(){
		var self = this,
			html = '';
		self.list = [];
		self.$select.find('option:selected').each(function(){
			self.list.push(this.value);
			html += '<span class="label label-primary">' + this.text + '</span>&nbsp;&nbsp;';
		});

		if (html.length===0)
			self.$lblContainer.html(html).hide();
		else{
			self.$lblContainer.html(html).show();
			self.toggleError(false);
		}
	}

	/*
	 * toggle error message
	 * @params (bool) bool
	 */
	o.toggleError = function(bool){
		if (!bool){
			this.$el.find('.alert').remove();
			this.hasAlert = false
		}else{
			if (!this.hasAlert){
				this.$lblContainer.after('<div class="alert alert-danger">未選擇標籤</div>');
				this.hasAlert = true;
			}
		}
	}

	/*
	 * validating is user selected.
	 * @return (bool) bool
	 */
	o.validate = function(){
		var bool = (this.list.length>0);
		this.toggleError(!bool);
		return bool;
	}

	return o.init();
}