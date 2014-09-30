<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>rebeauty</title>
    {{ HTML::style('packages/bootstrap/css/bootstrap.min.css'); }}

    <style type="text/css">
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
    }

    .form-signian {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
        box-shadow: 0 1px 2px rgba(0,0,0,.05);
    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin input[type="text"],
    .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
    }

    </style>
</head>
<body>
    <div class="container">
        <div class="form-signian">
            {{ Form::open() }}
            <h2 class="form-signin-heading">Please sign in</h2>
            @if ($errors->has('login'))
            <div class="alert alert-error">{{ $errors->first('login', ':message') }}</div>
            @endif
            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                <div class="form-control">
                    {{ Form::text('email') }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('password', 'Password') }}
                <div class="form-control">
                    {{ Form::password('password') }}
                </div>
            </div>


            <select name="where" class="form-control">
                <option>rebeauty</option>
                <option>spa</option>
            </select>
            <br>

            {{ Form::submit('Login', array('class' => 'btn btn-inverse btn-login')) }}

            {{ Form::close() }}
        </div>
    </div>
</body>
</html>
