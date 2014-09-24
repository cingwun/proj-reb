/*
 * slide box function of prototype
 * @params (object) o, {el, viewSize}
 */
var _slidebox = function(o){
	o.init = function(){
		var self = this;
		self.$el = $(o.el);
		self.$container = self.$el.find('.container-images');
        self.$container.find('li a').each(function(idx, obj){
                            			if (!self.imgsLength)
                            				self.imgsLength = 0;
                            			self.imgsLength++;
                            		})
                                    .colorbox({rel:'imgGroup', transition:"fade"});;

		self.$next = self.$el.find('#next');
		self.$next.click(function(e){self.onClick_control(e)});
		self.$prev = self.$el.find('#prev');
		self.$prev.click(function(e){self.onClick_control(e)});

		self.$no = self.$el.find('.no');

		self.current = 0;
		self.last = self.imgsLength - self.viewSize;

		return self;
	}

	/*
	 * handle click control button
	 */
	o.onClick_control = function(e){
		if (this.imgsLength<=this.viewSize)
			return;
		var direction = e.currentTarget.id,
			next, left;
		if (direction=='next')
			next = this.current + 1;
		else
			next = this.current - 1;

		if (next>=0 && next<=this.last){
			left = this.itemWidth * next * -1;
			this.$container.stop().animate({'margin-left': left+'px'}, 500);
			this.current = next;
		}
		this.toggleControl();
	}

	/*
	 * toggleControl
	 */
	o.toggleControl = function(){
		if ((this.current+1)>this.last)
			this.$next.addClass('disabled');
		else
			this.$next.removeClass('disabled');

		if ((this.current-1)<0)
			this.$prev.addClass('disabled');
		else
			this.$prev.removeClass('disabled');
	}

	return o.init();
}