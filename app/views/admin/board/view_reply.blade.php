@extends('admin._layouts.default')

@section('title')
<i class="glyphicon glyphicon-pencil"></i> 美麗留言 - 回覆
 <span class="btn btn-default" onclick="window.history.back();">回上一頁</span>
@stop

@section('main')
<div class="col-lg-12">
<form action="<?=URL::route('admin.board.reply.store')?>" method="post">
	<div class="form-group">
		<label class="control-label" for="message"><strong>詢問內容</strong></label>
		<div style="min-height: 150px; padding: 10px 0px;">
			<?=$board['content'];?>
		</div>
	</div>
	<!-- message -->

	<div class="form-group">
		<label class="control-label" for="content"><strong>回覆內容</strong></label>
		<div>
			<textarea class="form-control" id="content" name="content" style="width: 650px; min-height: 150px;"><?=Arr::get($reply, 'content', '')?></textarea>
		</div>
	</div>
	<!-- title -->

	<br/>
	@include('spa_admin._partials.widget_labels', array('label'=>$lblWidgetOptions))
	<!-- tags fields: labels-->

	<div class="form-group">
		<div style="margin-top: 10px;">
			<button class="btn btn-danger" type="button" onclick="history.back();">取消</button>
			<button class="btn btn-primary btn-submit">回覆</button>
		</div>
	</div>

	<?=Form::token()?>
	<input type="hidden" name="board_id" value="<?=$board['id']?>" />
</form>
</div>
@stop


@section('bottom')
    {{ HTML::script(asset('js/admin/widgets/labels/js_widget_labels.js')) }}
	{{ HTML::script(asset('js/admin/board/js_reply.js')) }}
@stop
