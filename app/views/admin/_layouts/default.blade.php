<!DOCTYPE html>
<html lang="en" ng-app="adminApp">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>rebeauty</title>

        <style type="text/css">
            #clearTop {
                margin-top: 10px;
            }
        </style>

        <!-- Bootstrap Core CSS -->

        {{ HTML::style(asset('spa_admin/css/admin/bootstrap.min.css'))}}
        <!-- Custom CSS -->
        {{ HTML::style(asset('spa_admin/css/admin/sb-admin.css'))}}
        <!-- Morris Charts CSS -->
        {{ HTML::style(asset('spa_admin/css/admin/plugins/morris.css'))}}
        <!-- Custom Fonts -->
        {{ HTML::style(asset('spa_admin/font-awesome-4.1.0/css/font-awesome.min.css'))}}
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>   
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        {{ HTML::style(asset('css/admin/css_global.css'))}}
        {{ HTML::style('aesthetics/css/ckeditor.css'); }}
        {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'); }}
        @yield('head')
    </head>

    <body id="admin">

        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{URL::route('switch.to.admin.spa')}}">Rebeauty後台</a>
                </div>

                <!-- Top Menu Items -->
                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Sentry::getUser()->email }} <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{URL::route('admin.user.modify')}}"><i class="fa fa-fw fa-gear"></i> 設定密碼</a>
                            </li>
                            <li>
                                <a href="{{URL::route('switch.to.admin.spa')}}"><i class="glyphicon glyphicon-home"></i>&nbsp; Spa</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ URL::route('admin.logout') }}"><i class="fa fa-fw fa-power-off"></i> 登出</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <ul class="nav navbar-nav side-nav">
                <!-- 系統管理 -->
                @if(Sentry::getUser()->hasAccess(array('system')))
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#system"><i class="glyphicon glyphicon-dashboard"></i> 系統管理 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="system" class="collapse">
                        <li>
                            <a href="{{URL::route('admin.users.index')}}">使用者</a>
                        </li>
                        
                        <li>
                            <a href="{{URL::route('admin.groups.index')}}">群組</a>
                        </li>
                        <li>
                            <a href="{{URL::route('admin.permissions.index')}}">權限</a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (helper::nav_show('ranks'))
                    <li {{ (Request::is( 'admin/ranks*') ? ' class="active"' : '') }}><a href="{{ URL::route('admin.ranks.index') }}"><i class="glyphicon glyphicon-signal"></i> 美麗排行榜</a></li>
                @endif

                <!-- 文章管理 -->
                @if (helper::nav_show('articles'))
                    <li {{ (Request::is( 'admin/articles*') ? ' class="active"' : '') }}><a href="{{ URL::route('admin.articles.index') }}?category=1&lang=all"><i class="glyphicon glyphicon-pencil"></i> 文章管理</a></li>
                @endif

                @if (helper::nav_show('technologies'))
                    <li {{ (Request::is( 'admin/technologies*') ? ' class="active"' : '') }}><a href="{{ URL::route('admin.technologies.index') }}"><i class="glyphicon glyphicon-certificate"></i>  美麗新技術</a></li>
                @endif

                <!-- Banner管理 -->
                @if (helper::nav_show('banners'))       
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#banner"><i class="glyphicon glyphicon-picture"></i> Banner管理 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="banner" class="collapse">
                        <li>
                            <a href="{{ URL::route('admin.banners.list', array('large'))}}">尺寸 960x430</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('admin.banners.list', array('medium'))}}">尺寸 960x250</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('admin.banners.list', array('small'))}}">尺寸 700x300</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (helper::nav_show('reservations'))
                    <li {{ (Request::is( 'admin/reservations*') ? ' class="active"' : '') }}><a href="{{ URL::route('admin.reservations.index') }}"><i class="glyphicon glyphicon-list-alt"></i> 預約管理</a></li>
                @endif

                @if (helper::nav_show('services'))
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#service"><i class="glyphicon glyphicon-exclamation-sign"></i> 服務項目 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="service" class="collapse">
                        <li>
                            <a href="{{ URL::route('admin.service_faq.category.list', array('type'=>'service'))}}">分類列表</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('admin.service_faq.article.list', array('type'=>'service'))}}">文章列表</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (helper::nav_show('faqs'))
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#faq"><i class="glyphicon glyphicon-exclamation-sign"></i> 常見問題 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="faq" class="collapse">
                        <li>
                            <a href="{{ URL::route('admin.service_faq.category.list', array('type'=>'faq'))}}">分類列表</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('admin.service_faq.article.list', array('type'=>'faq'))}}">文章列表</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (helper::nav_show('board'))
                    <li class="{{ (Request::is('admin/board*') ? 'active' : '') }}"> <a href="<?=URL::route('admin.board.list')?>"><i class="glyphicon glyphicon-pencil"></i> 美麗留言</a></li>
                @endif

                <!-- 會員管理 -->
                @if (helper::nav_show('member'))
                <li>
                    <a href="{{URL::route('admin.member.list')}}"><i class="glyphicon glyphicon-user"></i> 會員管理</a>
                </li>
                @endif

                @if (helper::nav_show('wintness'))
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#wintness"><i class="glyphicon glyphicon-camera"></i> 美麗見證 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="wintness" class="collapse">
                        <li>
                            <a href="{{ URL::route('admin.wintness.gallery')}}">圖片集</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('admin.wintness.article.list', array('page'=>1, 'lang'=>'all'))}}">文章列表</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (helper::nav_show('beautyNews'))
                    <li class="{{ (Request::is('admin/beautynews*') ? 'active' : '') }}"> <a href="<?=URL::route('admin.beautynews.list')?>"><i class="glyphicon glyphicon-leaf"></i> 美麗新知</a></li>
                @endif


            </ul>
            <!-- /.navbar-collapse -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div>
                        <div class="col-lg-12">
                            <h1 class="page-header">
                                @yield('title')
                            </h1>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div>
                        @yield('main')
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->

        <!-- jQuery Version 1.11.0 -->
        {{ HTML::script(asset('spa_admin/js/jquery-1.11.0.js'))}}
        <!-- Bootstrap Core JavaScript -->
        {{ HTML::script(asset('spa_admin/js/bootstrap.min.js'))}}
        <!-- Morris Charts JavaScript -->
        {{ HTML::script('packages/angularjs/angular.min.js'); }}
        {{ HTML::script('js/admin/app.js'); }}
        {{ HTML::script('spa_admin/js/plugins/morris/raphael.min.js')}}
        <!-- {{ HTML::script(asset('spa_admin/js/plugins/morris/morris.min.js'))}}
        {{ HTML::script(asset('spa_admin/js/plugins/morris/morris-data.js'))}} -->
        @yield('bottom')
    </body>

    </html>
