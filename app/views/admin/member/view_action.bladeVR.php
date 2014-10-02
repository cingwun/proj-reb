@extends('admin._layouts.default')

@section('main')
<h2>編輯會員</h2>
<form action="<?=URL::route('admin.member.action.post')?>" method="post">
	<div class="form-group">
		<label class="col-sm-3 control-label">帳號</label>
		<div class="col-sm-5">
			<?=$m->uid?>
		</div>
	</div>
	<!-- account -->

	<div class="form-group">
		<label class="col-sm-3 control-label">來源</label>
		<div class="col-sm-5">
			<?=$m->social?>
		</div>
	</div>
	<!-- social -->

	<?php if ($m->social=='rebeauty'):?>
	<div class="form-group">
		<label class="col-sm-3 control-label">密碼</label>
		<div class="col-sm-5">
			<input type="text" name="password" value="" />
		</div>
	</div>
	<!-- password -->
	<?php endif;?>

	<div class="form-group">
		<label class="col-sm-3 control-label">E-Mail</label>
		<div class="col-sm-5">
			<input type="text" name="email" value="<?=$m->email?>" />
		</div>
	</div>
	<!-- email -->

	<div class="form-group">
		<label class="col-sm-3 control-label">姓名</label>
		<div class="col-sm-5">
			<input type="text" name="name" value="<?=$m->name?>" />
		</div>
	</div>
	<!-- name -->

	<div class="form-group">
		<label class="col-sm-3 control-label">生日</label>
		<div class="col-sm-5">
			<input type="text" name="birthday" value="<?=$m->birthday?>" />
		</div>
	</div>
	<!-- name -->

	<div class="form-group">
		<label class="col-sm-3 control-label">電話</label>
		<div class="col-sm-5">
			<input type="text" name="phone" value="<?=$m->phone?>" />
		</div>
	</div>
	<!-- phone -->

	<div class="form-group">
		<label class="col-sm-3 control-label">地址</label>
		<div class="col-sm-5">
			<input type="text" name="address" value="<?=$m->address?>" />
		</div>
	</div>
	<!-- address -->

	<div class="form-group">
		<button class="btn" type="button" onclick="history.back();">取消</button> <button class="btn btn-inverse btn-submit">編輯完成</button>
	</div>

	<input type="hidden" name="id" value="<?=$m->id?>" />
</form>
@stop

@section('head')
	{{ HTML::style(asset('css/admin/banners/css_action.css')) }}
@stop

@section('bottom')
	{{ HTML::script(asset('js/admin/banners/js_action.js')) }}
@stop