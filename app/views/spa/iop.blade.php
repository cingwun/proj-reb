<!doctype html>
<html lang="en" ng-app="adminApp">
    <head>
        <meta charset="utf-8">
        <title>rebeauty</title>
        {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'); }}
        {{ HTML::style('packages/bootstrap/css/bootstrap.min.css'); }}
        {{ HTML::script('packages/bootstrap/js/bootstrap.min.js'); }}
        {{ HTML::script('packages/angularjs/angular.min.js'); }}
        {{ HTML::script('js/admin/app.js'); }}
        {{ HTML::style(asset('css/admin/css_global.css'))}}
        {{ HTML::style('aesthetics/css/ckeditor.css'); }}
        @yield('head')
        <style>
            body {
                padding-top: 60px;
            }
        </style>
    </head>
    <body id="admin">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid"> <a class="brand" href="/admin">rebeauty</a>
                    <div class="nav-collapse collapse">
                      
                        </ul>
                    </div>
                    <!--/.nav-collapse-->
                </div>
            </div>
        </div>
        <div class="container">@yield('main')</div>
        @yield('bottom')
    </body>
</html>