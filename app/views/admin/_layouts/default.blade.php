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
                        <p class="navbar-text pull-right">{{ Sentry::getUser()->email }} | <a href="{{ URL::route('admin.logout') }}" class="navbar-link">Logout</a>
                        </p>
                        <ul class="nav">@if (Sentry::getUser()->hasAnyAccess(array('system')))
                            <li class="dropdown @if(Request::is('admin/users*') || Request::is('admin/permissions*') || Request::is('admin/groups*')) active @endif"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">系統管理</a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                    <li><a tabindex="-1" href="{{ URL::route('admin.users.index') }}">使用者</a>
                                    </li>
                                    <li><a tabindex="-1" href="{{ URL::route('admin.groups.index') }}">群組</a>
                                    </li>
                                    <li><a tabindex="-1" href="{{ URL::route('admin.permissions.index') }}">權限</a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if (helper::nav_show('ranks'))
                            <li {{ (Request::is( 'admin/ranks*') ? ' class="active"' : '') }}><a href="{{ URL::route('admin.ranks.index') }}">美麗排行榜</a></li>
                            @endif

                            @if (helper::nav_show('articles'))
                            <li {{ (Request::is( 'admin/articles*') ? ' class="active"' : '') }}><a href="{{ URL::route('admin.articles.index') }}">文章管理</a></li>
                            @endif

                            @if (helper::nav_show('technologies'))
                            <li {{ (Request::is( 'admin/technologies*') ? ' class="active"' : '') }}><a href="{{ URL::route('admin.technologies.index') }}">美麗新技術</a></li>
                            @endif

                            @if (helper::nav_show('banners'))
                            <li class="dropdown {{ (Request::is('admin/banners*') ? 'active' : '') }}"> <a href="#" data-toggle="dropdown">Banner管理</a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.banners.list', array('large'))}}">尺寸 960x430</a>
                                    </li>
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.banners.list', array('medium'))}}">尺寸 960x250</a>
                                    </li>
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.banners.list', array('small'))}}">尺寸 700x300</a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if (helper::nav_show('reservations'))
                            <li {{ (Request::is( 'admin/reservations*') ? ' class="active"' : '') }}><a href="{{ URL::route('admin.reservations.index') }}">預約管理</a></li>
                            @endif

                            @if (helper::nav_show('services'))
                            <li class="dropdown {{ (Request::is('admin/service*') ? 'active' : '') }}"> <a href="#" data-toggle="dropdown">服務項目</a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.service_faq.category.list', array('type'=>'service'))}}">分類列表</a>
                                    </li>
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.service_faq.article.list', array('type'=>'service'))}}">文章列表</a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if (helper::nav_show('faqs'))
                            <li class="dropdown {{ (Request::is('admin/faq*') ? 'active' : '') }}"> <a href="#" data-toggle="dropdown">常見問題</a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.service_faq.category.list', array('type'=>'faq'))}}">分類列表</a>
                                    </li>
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.service_faq.article.list', array('type'=>'faq'))}}">文章列表</a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if (helper::nav_show('board'))
                            <li class="{{ (Request::is('admin/board*') ? 'active' : '') }}"> <a href="<?=URL::route('admin.board.list')?>">美麗留言</a></li>
                            @endif

                            @if (helper::nav_show('member'))
                            <li class="{{ (Request::is('admin/member*') ? 'active' : '') }}"> <a href="<?=URL::route('admin.member.list')?>">會員管理</a></li>
                            @endif

                            @if (helper::nav_show('wintness'))
                            <li class="dropdown {{ (Request::is('admin/wintness*') ? 'active' : '') }}"> <a href="#" data-toggle="dropdown">美麗見證</a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.wintness.gallery')}}">圖片集</a></li>
                                    <li role="presentation"><a role="menuitem" href="{{ URL::route('admin.wintness.article.list')}}">文章列表</a></li>
                                </ul>
                            </li>
                            @endif

                            @if (helper::nav_show('beautyNews'))
                            <li class="{{ (Request::is('admin/beautynews*') ? 'active' : '') }}"> <a href="<?=URL::route('admin.beautynews.list')?>">美麗新知</a></li>
                            @endif
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