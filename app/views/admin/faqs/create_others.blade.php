<?php
	$labels = array();
	foreach($label['items'] as $item)
		$labels[$item->id] = $item->title;
?>

<div class="form-group label-box">
	<label class="col-sm-3 control-label" for="link">標籤 (Ctrl+左鍵複選)</label>
	<div class="labels-container" style="padding: 0px 0px 10px;"></div>
	<div class="col-sm-5">
		<?=Form::select('labels[]', $labels, $label['selected'], array('multiple'=>'multiple', 'size'=>'5', 'class'=>'form-control'))?>
	</div>
</div>
<!-- labels -->

<div class="form-group" id="tabs-box">
	<label class="col-sm-3 control-label" for="link">Tab項目&nbsp;:&nbsp;(若要編輯Tab，請點頁籤->編輯->變更內容及標題->離開->編輯完成)</label>
	<div class="col-sm-5 form-inline">
		<input type="text" name="tabName" value="" placeholder="請輸入tab名稱" />
		<input type="text" name="tabOrder" value="" placeholder="順序" class="order"/>
		<button type="button" href="#" class="btn btn-primary btn-add">新增</button>
		<div class="btn-bar">
			<div class="btn-group btn-group-sm btn-editbar">
				<button type="button" class="btn btn-primary btn-edit">編輯</button>
				<button type="button" class="btn btn-danger btn-remove">移除</button>
			</div>
			<div class="btn-storebar">
				<button type="button" class="btn btn-info btn-store">離開</button>
			</div>
		</div>
	</div>
	<br />
	<div class="col-sm-5 form-inline tabs-container">
		<ul class="nav nav-tabs">
			<?php if (sizeof($tab['items'])>0):?>
				<?php foreach($tab['items'] as $idx=> &$item):?>
				<li class="<?=($idx===0)?'active':''?>" data-key="tab<?=$idx?>">
					<a href="#"><?=$item['title']?></a><input type="hidden" name="tabName[tab<?=$idx?>]" value="<?=$item['title']?>"/>
				</li>
				<?php endforeach;?>
			<?php endif;?>
		</ul>
		<!-- tab items -->

		<div class="tab-content">
			<?php if (sizeof($tab['items'])>0):?>
				<?php foreach($tab['items'] as $idx=> &$item):?>
				<div class="tab-pane <?=($idx===0)?'active':''?>" id="tab<?=$idx?>"><?=$item['content']?></div>
				<textarea name="tabContents[tab<?=$idx?>]" class="tab<?=$idx?>"><?=$item['content']?></textarea>
				<?php endforeach;?>
			<?php endif;?>
		</div>
		<!-- Tab panes -->
	</div>
</div>
<!-- tabs -->

@section('head')
	{{ HTML::style(asset('css/admin/services/css_others.css')) }}
@stop

@section('bottom')
	{{ HTML::script(asset('packages/ckeditor/ckeditor.js')) }}
	{{ HTML::script(asset('js/admin/services/js_others.js')) }}
	@parent
@stop