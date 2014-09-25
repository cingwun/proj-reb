'use strict';

/*
 * prototype of reservation
 */
var reservation = function(o){
    // initialize
    o.init = function(){
        var self = this;
        self.$el = $(self.el);
        self.$form = self.$el.find('form');
        self.$form.find('.btn-submit').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            self.onClick_submit();
        });

        self.$input = self.$form.find('input[type=text]');

        self.ajaxURL = self.$form.attr('action');
        return self;
    }

    return o.init();
}({el: '.infoList'});