
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

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
        @yield('head')
    </head>

    <body>

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
                    <a class="navbar-brand" href="{{URL::route('spa.admin.index')}}">Rebeauty SPA</a>
                </div>
                
                <!-- Top Menu Items -->
                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <ul class="nav navbar-nav side-nav">
                <li class="active">
                    <a href="{{URL::route('spa.admin.articles.list')}}"><i class="fa fa-fw fa-dashboard"></i> 文章管理</a>
                </li>
                <li>
                    <a href="{{URL::route('spa.admin.articles.list')}}"><i class="fa fa-fw fa-edit"></i> 預約管理</a>
                </li>
                <li>
                    <a href="{{URL::route('spa.admin.share.article.list')}}"><i class="fa fa-fw fa-desktop"></i> 美麗分享</a>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#service"><i class="fa fa-fw fa-arrows-v"></i> 美麗服務 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="service" class="collapse">
                        <li>
                            <a href="#">類型列表</a>
                        </li>
                        <li>
                            <a href="#">文章列表</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#product"><i class="fa fa-fw fa-arrows-v"></i> 美麗產品 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="product" class="collapse">
                        <li>
                            <a href="#">類型列表</a>
                        </li>
                        <li>
                            <a href="#">文章列表</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
                </li>
            </ul>
            <!-- /.navbar-collapse -->
            <div id="page-wrapper">
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">
                                @yield('title')
                            </h1>
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class='container'>
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
