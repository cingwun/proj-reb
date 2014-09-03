@extends('aesthetics._layouts.default')

@section('bodyId'){{'messageAsk'}}@stop

@section('mainBanner')
    @include('aesthetics._partials.banner', array('size'=>'medium'))
@stop

@section('mainContent')
<article id="mainContent" role="main" class="commentList">
	<div class="breadcrumb">
		<a href="/">首頁</a><span class="arrow"></span>
		<a href="<?php echo route('frontend.board.list')?>">美麗留言</a><span class="arrow"></span>
		<a href="#">我要發問</a>
	</div>
	<!-- breadcrumb end -->
	<div class="infoList">
		<h2>我要發問</h2>
		<p>此留言版僅供作為醫美整型保養等相關話題之討論專用，嚴禁刑登任何廣告以及使用飆罵、人身攻擊或不文雅的用詞。若氏緊急重大問題，請直接電話聯繫我我&nbsp;02-2562-3631。</p>
		<p class="br">請在下方欄位填寫</p>
		<form>
			<label for="name">您的大名:</label><input type="text" name="name" id="" value="<?php echo Arr::get($user, 'name', '')?>"><br/>
			<label>性別:</label>
				<label for="sex">男</label><input type="radio" name="sex" value="m" />
				<label for="sex">女</label><input type="radio" name="sex" value="f" checked="checked" />
				<br/>
			<label for="email">電子郵件：</label><input type="text" name="email" id="" class="email" value="<?php echo Arr::get($user, 'email', '')?>"><br/>
			<label for="ask">發問主題：</label><input type="text" name="ask" id="" class="askC"><br/>
			<label for="message">發問內容：</label><textarea name="content"></textarea><br/>
			<label>私密留言:</label>
				<label for="priv">否</label><input type="radio" name="isPrivate" id="nPriv" checked="checked" value="n">
				<?php if (isset($user['id'])):?>
				<label for="priv">是</label><input type="radio" name="isPrivate" id="yPriv"><span>（限會員）</span>
				<?php endif;?>
				<br/>
			<label for="checkBox">驗證碼:</label><input type="text" name="code" id="" />
			<img src="" alt="" id="codeImage"/>
			<p>看不到驗證碼？ 請</p><a href="#" id="btn-refresh" data-url="<?php echo route('captcha.get')?>">重新整理</a>
			<input type="hidden" name="user_id" value="<?php echo Arr::get($user, 'id', '')?>" />
			<div class="funcBar">
				<a href="#" class="sent" id="btn-enter" data-url="<?php echo route('frontend.board.post.ask')?>">送出文章</a>
				<a href="<?php echo route('frontend.board.list')?>">返回美麗留言列表</a>
			</div>
		</form>
	</div>
	<div class="popBox">
		<div class="wp infoList funcBar">
		<p>文章己成功送出，請等待一下喔！</br>我們會盡快給您回覆</p>
		<a href="#" class="close">確定</a></div>
	</div>
</article>
<!-- mainContent end -->
@stop

@section('aside')
	{{-- 美麗排行 --}}
	@include('aesthetics._partials.sidebar_rank')
@stop

@section('bottomContent')
	{{ HTML::script(asset('js/board/js_ask.js')) }}
@stop