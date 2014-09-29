<!DOCTYPE html>
<html lang="zh-TW">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>快速預約</title>
	{{ HTML::style('spa/css/layout_spa.css'); }}
</head>
<body>
	<div class="quickForm">
		<form action="{{URL::route('spa.reservation.form.write')}}" method="post" id="quickReservationForm">
			<p><label>姓名</label><input type="text" name="name" /></p>
			<p><label>性別</label><label class="radioWrap"><input type="radio" name="sex" value="women" checked />女<span>(限女性)</span></label></p>
			<p><label>電話</label><input type="text" name="phone" /></p>
			<p><label>Email</label><input type="text" name="email" /></p>
			<p><label>預約項目</label><input type="text" name="improve_item" /></p>
			<button class="btn sent" type="submit"></button>
		</form>
	</div>
</body>
{{ HTML::script(asset('spa_admin/js/jquery-1.11.0.js'))}}
{{ HTML::script('spa/js/reservation/js_form.js'); }}
<script type="text/javascript">
	var form = _reservation({el: '.quickForm', mode: 'iframe'});
</script>
</html>