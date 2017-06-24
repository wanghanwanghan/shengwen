@extends('layouts.plane')
@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>ZBXL</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">
                    <img onclick="loginout();" height="100%" width="100%" src="{{asset('public/img/zbxl_logo.png')}}"/>
                </span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">
                            <!-- Menu toggle button -->
                            <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success">4</span>
                            </a>-->
                            <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    <!-- inner menu: contains the messages -->
                                    <ul class="menu">
                                        <li><!-- start message -->
                                            <a href="#">
                                                <div class="pull-left">
                                                    <!-- User Image -->
                                                    <img src="{{asset('public/bower_components/AdminLTE/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
                                                </div>
                                                <!-- Message title and timestamp -->
                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
                                                <!-- The message -->
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                        <!-- end message -->
                                    </ul>
                                    <!-- /.menu -->
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>
                        <!-- /.messages-menu -->

                        <!-- Notifications Menu -->
                        <li class="dropdown notifications-menu">
                            <!-- Menu toggle button -->
                            <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">10</span>
                            </a>-->
                            <ul class="dropdown-menu">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    <!-- Inner Menu: contains the notifications -->
                                    <ul class="menu">
                                        <li><!-- start notification -->
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                            </a>
                                        </li>
                                        <!-- end notification -->
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <!-- Tasks Menu -->
                        <li class="dropdown tasks-menu">
                            <!-- Menu Toggle Button -->
                            <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-flag-o"></i>
                                <span class="label label-danger">9</span>
                            </a>-->
                            <ul class="dropdown-menu">
                                <li class="header">You have 9 tasks</li>
                                <li>
                                    <!-- Inner menu: contains the tasks -->
                                    <ul class="menu">
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <!-- Task title and progress text -->
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <!-- The progress bar -->
                                                <div class="progress xs">
                                                    <!-- Change the css width attribute to simulate progress -->
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li>
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="{{asset('public/bower_components/AdminLTE/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                                    <p>
                                        Alexander Pierce - Web Developer
                                        <small>Member since Nov. 2012</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <a href="#" onclick="refresh_mail();" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                {{--<!-- Sidebar user panel (optional) -->--}}
                {{--<div class="user-panel">--}}
                    {{--<div class="pull-left image">--}}
                        {{--<img src="{{asset('public/bower_components/AdminLTE/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">--}}
                    {{--</div>--}}
                    {{--<div class="pull-left info">--}}
                        {{--<p name="username">loading...</p>--}}
                        {{--<!-- Status -->--}}
                        {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    <li class="header">功能菜单</li>
                    <!-- Optionally, you can add icons to the links -->
                    <!--<li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>-->
                    <!--<li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>-->
                    <li class="active treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>用户登记</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{url('add/cust')}}"><i class="fa fa-fw fa-hand-o-right"></i>登记用户信息（A类）</a></li>
                            <li><a href="{{url('add/cust/b')}}"><i class="fa fa-fw fa-hand-o-right"></i>登记用户信息（B类）</a></li>
                        </ul>
                    </li>

                    <li class="active treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>客服功能</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{url('service/care')}}"><i class="fa fa-fw fa-hand-o-right"></i>维护现有客户</a></li>
                        </ul>
                    </li>

                    <li class="active treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>客户管理</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{url('modify/cust/info')}}"><i class="fa fa-fw fa-hand-o-right"></i>修改客户信息</a></li>
                            <li><a href="{{url('ivr/return/msg')}}"><i class="fa fa-fw fa-hand-o-right"></i>录音返回信息</a></li>
                        </ul>
                    </li>

                    <li class="active treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>声纹管理</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                        <!--<li><a href="{{url('select/info')}}"><i class="fa fa-fw fa-hand-o-right"></i>查询用户声纹信息</a></li>-->
                            <li><a href="{{url('loop/call')}}"><i class="fa fa-fw fa-hand-o-right"></i>循环拨打用户认证</a></li>
                            <li><a href="{{url('ivr/return/loop/msg')}}"><i class="fa fa-fw fa-hand-o-right"></i>轮播返回信息</a></li>
                            <li><a href="{{url('statistics')}}"><i class="fa fa-fw fa-hand-o-right"></i>统计</a></li>
                            <li><a href="{{url('analysis')}}"><i class="fa fa-fw fa-hand-o-right"></i>分析</a></li>
                        </ul>
                    </li>

                    <li class="active treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>系统设置</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{url('set/staff')}}"><i class="fa fa-fw fa-hand-o-right"></i>添加新的员工</a></li>
                            <li><a href="{{url('set/project')}}"><i class="fa fa-fw fa-hand-o-right"></i>添加属地信息</a></li>
                            <li><a href="{{url('set/si')}}"><i class="fa fa-fw fa-hand-o-right"></i>添加参保类型</a></li>
                            <li><a href="{{url('set/confirm')}}"><i class="fa fa-fw fa-hand-o-right"></i>添加认证类型</a></li>
                            <li><a href="{{url('set/level')}}"><i class="fa fa-fw fa-hand-o-right"></i>添加权限信息</a></li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>超级管理员功能</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{url('source/cust/data')}}"><i class="fa fa-fw fa-hand-o-right"></i>导入待采集客户信息</a></li>
                            <li><a href="{{url('edit/config')}}"><i class="fa fa-fw fa-hand-o-right"></i>修改认证配置</a></li>
                            <li><a href="{{url('edit/staff')}}"><i class="fa fa-fw fa-hand-o-right"></i>修改员工信息</a></li>
                            <li><a href="{{url('send/staffmail')}}"><i class="fa fa-fw fa-hand-o-right"></i>给员工发邮件</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <B>@yield('page_heading')</B>
                    <small>@yield('page_heading_small')</small>
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('section')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active">
                    <a href="#control-sidebar-home-tab" data-toggle="tab">
                        收件箱
                    </a>
                </li>
                <li>
                    <a href="#control-sidebar-settings-tab" data-toggle="tab">
                        <i class="fa fa-gears">
                        </i>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane active" id="control-sidebar-home-tab">
                    <ul id="mail_ul" class="control-sidebar-menu">
                        <li>
                            <a href="javascript:;">
                                <h5 class="control-sidebar-subheading">
                                    <div>年月日时分秒</div>
                                    <div>消息内容---消息内容</div>
                                </h5>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 100%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                        <h3 class="control-sidebar-heading">wanghan</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                wanghan
                            </label>

                            <p>
                                wanghan
                            </p>
                        </div>
                        <!-- /.form-group -->
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>

    <script>
//        $(function () {
//            $.get('get/user/name', function(result)
//            {
//                $("p[name=username]").html(result);
//            });
//        })
    </script>
@stop