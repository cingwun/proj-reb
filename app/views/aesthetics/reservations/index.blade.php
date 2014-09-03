<!DOCTYPE html>
<html lang="zh-TW">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<!-- <meta name="viewport" content="width=device-width, initial-scale=0.7, maximum-scale=3.0, user-scalable=1" /> -->
	<title>煥儷美形診所</title>
        {{ HTML::style('aesthetics/css/layout.css'); }}
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body id="index">
<form action="/reservation" method="post" id="quickReservationForm">
    <p><label>姓名</label><input type="text" /></p>
    <p><label>性別</label><label class="radioWrap"><input type="radio" name="sex" />男</label><label class="radioWrap"><input type="radio" name="sex" />女</label></p>
    <p><label>電話</label><input type="text" /></p>
    <p><label>Email</label><input type="text" /></p>
    <p><label>預約項目</label><input type="text" /></p>
    <button class="btn" type="submit"><span>我要預約</span></button>
</form>
<!--[if IE]>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <![endif]-->
        <!--[if !IE]>-->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <!--<![endif]-->
        {{ HTML::script('aesthetics/js/jq_plugin.js'); }}
        {{ HTML::script('aesthetics/js/jq_index.js'); }}
        {{ HTML::script('aesthetics/js/app.js'); }}

{{-- Google Tag Manager --}}
@include('aesthetics._partials.googletagmanager')
</body>
</html>
