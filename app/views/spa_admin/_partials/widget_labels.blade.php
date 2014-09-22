<?php
	/*
	 * this widget is used to handle label selecting
	 * @params (array) $label, value: array((value|null)selected, (array)items)
	 * format:
     *      elementId: string
     *      fieldName: string
     *      formTitle: string
	 * 		items: array(value=>text, value=>text)
	 * 		selected: int|string|null
	 * @requirement
	 * 		js: jquery, js_widget_labels.js
	 * @usage
	 * 		var labels = _labels({el: '.label-box'});
     * @data
     *      (mixed) fieldName
	 */
?>

<div class="form-group" id="<?=$label['elementId']?>">
<<<<<<< HEAD
	<label class="control-label" for="link"><?=$label['formTitle']?>&nbsp;(Ctrl+左鍵複選)</label>
	<div class="labels-container" style="padding: 0px 0px 10px;"></div>
	<div class="">
=======
	<label class="col-sm-3 control-label" for="link"><?=$label['formTitle']?>&nbsp;(Ctrl+左鍵複選)</label>
	<div class="labels-container" style="padding: 0px 0px 10px;"></div>
	<div class="col-sm-5">
>>>>>>> b290acbf75ca76338e3e8dbf3467d6f8a54983e1
		<?=Form::select($label['fieldName'], $label['items'], $label['selected'], array('multiple'=>'multiple', 'size'=>'5', 'class'=>'form-control'))?>
	</div>
</div>
<!-- labels -->
