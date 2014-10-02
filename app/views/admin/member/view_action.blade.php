@extends('spa_admin._layouts.default')

@section('title')
編輯會員
@stop

@section('main')
<form action="<?=URL::route('admin.member.action.post')?>" method="post" class="col-lg-4">
	<div class="form-group">
		<label>帳號</label>
		<div>
			<? //echo $m->uid; ?>
			<h4>{{ $m->uid;}}</h4>
		</div>
	</div>
	<!-- account -->

	<div class="form-group">
		<label>來源</label>
		<div>
			<h4>{{ $m->social;}}</h4>
		</div>
	</div>
	<!-- social -->

	<?php if ($m->social=='rebeauty'):?>
	<div class="form-group">
		<label>密碼</label>
		<div>
			<input type="text" class="form-control" name="password" value="" />
		</div>
	</div>
	<!-- password -->
	<?php endif;?>

	<div class="form-group">
		<label>E-Mail</label>
		<div>
			<input type="text" class="form-control" name="email" value="<?=$m->email?>" />
		</div>
	</div>
	<!-- email -->

	<div class="form-group">
		<label>姓名</label>
		<div>
			<input type="text" class="form-control" name="name" value="<?=$m->name?>" />
		</div>
	</div>
	<!-- name -->

	<div class="form-group">
		<label>生日</label>
		<div>
			<input type="text" class="form-control" name="birthday" value="<?=$m->birthday?>" />
		</div>
	</div>
	<!-- name -->

	<div class="form-group">
		<label>電話</label>
		<div>
			<input type="text" class="form-control" name="phone" value="<?=$m->phone?>" />
		</div>
	</div>
	<!-- phone -->

	<div class="form-group">
		<label>地址</label>
		<div>
			<input type="text" class="form-control" name="address" value="<?=$m->address?>" />
		</div>
	</div>
	<!-- address -->

	<div class="form-group">
		<button class="btn btn-danger" type="button" onclick="history.back();">取消</button> <button class="btn btn-primary btn-submit">編輯完成</button>
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