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
	<label class="control-label" for="link"><?=$label['formTitle']?>&nbsp;(Ctrl+左鍵複選，請選擇對應語系的標籤)</label>
	<div class="labels-container" style="padding: 0px 0px 10px;"></div>
	<div class="">
		<?=Form::select($label['fieldName'], $label['items'], $label['selected'], array('multiple'=>'multiple', 'size'=>'5', 'class'=>'form-control'))?>
	</div>
</div>
<!-- labels -->
