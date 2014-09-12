@extends('spa_admin._layouts.default')

@section('title')
<?php if($action == "create"){echo "新增文章";}else{echo "編輯文章";} ?>
@stop

@section('main')
<div class="col-lg-6">
	<form action="<? if($action=="create"){echo URL::Route('spa.admin.articles.store');}?>" method="post" role="form">

		
		<div class="form-group">
			<label for="category">分類</label>
				<select name="category" class="form-control">
					<option value="1" @if($action=="create" && $createCategory == 1 || $action=="update" && $specArticle->category==1) selected @endif>關於煥麗</option>
					<option value="2" @if($action=="create" && $createCategory == 2 || $action=="update" && $specArticle->category==2) selected @endif>最新消息</option>
					<option value="3" @if($action=="create" && $createCategory == 3 || $action=="update" && $specArticle->category==3) selected @endif>美麗分享</option>
				</select>
		</div>

		<div class="form-group">
			<label for="title">標題</label>
			<div>
				<input type="text" class="form-control" id="title" name="title" size="12" <?php if($action == "update") echo 'value="'.$specArticle->title.'"'; ?>>
			</div>
		</div>

		<div class="form-group">
			<label for="content">內文</label>
			<div>
				<textarea class="form-control ckeditor" id="content" name="content"><?php if($action == "update") echo $specArticle->content; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<label for="open_at">上架日期</label>
			<div><input type="text" class="form-control" id="open_at" name="open_at" size="12" <?php if($action == "update"){echo 'value="'.$specArticle->open_at.'"';}else{echo 'value="'.date("Y-m-d").'"';} ?>
			</div>
		</div>

		<div class="form-group">
			<label for="status">狀態</label>
			<label class="radio-inline">
				<input type="radio" name="status" value="1" id="optionsRadiosInline" <?php if($action=="create" || $specArticle->status==1) echo "checked"; ?> >顯示
			</label>
			<label class="radio-inline">
				<input type="radio" name="status" value="0" id="optionsRadiosInline" <?php if($action=="update"){if($specArticle->status==0) echo "checked";} ?> >隱藏
			</label>
		</div>

		<div class="form-group">
			<label for="lan">語言</label>
			@if($action=="create")
			<label class="radio-inline">
				<input type="radio" name="lan" value="zh" id="optionsRadiosInline" checked> 繁體
			</label>
			<label class="radio-inline">
				<input type="radio" name="lan" value="cn" id="optionsRadiosInline"> 簡體
			</label>
			@elseif($action=="update" && $changeLan==null)
			<label class="radio-inline">
				<input type="radio" name="lan" value="zh" id="optionsRadiosInline" <?php if($specArticle->lan=="zh") echo "checked"; ?> > 繁體
			</label>
			<label class="radio-inline">
				<input type="radio" name="lan" value="cn" id="optionsRadiosInline" <?php if($specArticle->lan=="cn") echo "checked"; ?> > 簡體
			</label>
			@elseif($specArticle->lan=="zh")
			<label class="radio-inline">
				<input type="radio" name="lan" value="cn" id="optionsRadiosInline" checked> 簡體
			</label>
			@else
			<label class="radio-inline">
				<input type="radio" name="lan" value="zh" id="optionsRadiosInline" checked> 繁體
			</label>
			@endif
		</div>


		<!--<input type="hidden" name="_method" value="POST" />-->
		<input type="hidden" name="id" value="{{ $id}}" method="post"/>
		<button class="btn btn-danger" type="button" onclick="history.back();">取消</button>
		@if($action=="create") <button class="btn btn-primary">新增</button> @endif
		@if($action=="update") <button class="btn btn-primary">修改</button> @endif
	</form>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
$(function() {
  $( "#open_at" ).datepicker({ dateFormat: "yy-mm-dd" });
});
</script>
{{ HTML::script('packages/ckeditor/ckeditor.js'); }}
@stop


