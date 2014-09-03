'use strict';

$(function(){

    // instance of labels
    var labels = _labels({
        el: '#label-box'
    });

    // instace of tabs
    var tabs = _tabs({
        el: '#tab-box'
    });

	$('.btn-submit').click(function(e){
		$('form').submit();
		return false;
	});
});