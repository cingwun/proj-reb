@extends('admin._layouts.default')

@section('main')

<h2>回覆留言</h2>
<form action="<?=URL::route('admin.board.reply.store')?>" method="post">
	<div class="form-group">
		<label class="col-sm-3 control-label" for="message"><strong>詢問內容</strong></label>
		<div class="col-sm-5" style="min-height: 150px; padding: 10px 0px;">
			<?=$board['content'];?>
		</div>
	</div>
	<!-- message -->

	@include('admin._partials.widget_labels', array('label'=>$lblWidgetOptions))
	<!-- tags fields: labels-->

	<div class="form-group">
		<label class="col-sm-3 control-label" for="content"><strong>回覆內容</strong></label>
		<div class="col-sm-5">
			<textarea class="form-control" id="content" name="content" style="width: 650px; min-height: 150px;"><?=Arr::get($reply, 'content', '')?></textarea>
		</div>
	</div>
	<!-- title -->

	<div class="form-group">
		<div style="margin-top: 10px;">
			<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse btn-submit">回覆</button>
		</div>
	</div>

	<?=Form::token()?>
	<input type="hidden" name="board_id" value="<?=$board['id']?>" />
</form>
@stop


@section('bottom')
    {{ HTML::script(asset('js/admin/widgets/labels/js_widget_labels.js')) }}
	{{ HTML::script(asset('js/admin/board/js_reply.js')) }}
@stop