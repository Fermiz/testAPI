<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>抽奖小程序</title>
    
    <!-- logo -->
    <link rel=”icon” href=”/favicon.ico” mce_href=”/favicon.ico” type=”image/x-icon”>
    <link rel=”shortcut icon” href=”/favicon.ico” mce_href=”/favicon.ico” type=”image/x-icon”>

    <!-- Fonts -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrapValidator.min.css">
    <link rel="stylesheet" href="css/bootstrap-select.min.css">
    <!-- {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}} -->

    <link href="css/theme.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">切换</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                   金数据
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <!-- @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">登录</a></li>
                        <li><a href="{{ url('/register') }}">注册</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>退出</a></li>
                            </ul>
                        </li>
                    @endif -->
                    <li><a href="/">{{$me}}</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrapValidator.min.js"></script>
    <script type="text/javascript" src="js/welcome.blade.js"></script> 
    <script type="text/javascript" src="js/prizes.blade.js"></script> 
    <script type="text/javascript" src="js/setting.blade.js"></script> 
    <script type="text/javascript" src="js/bootstrap-select.min.js"></script>
    <!-- {{-- <script src="{{ elixir('js/app.js') }}"></script> --}} -->
</body>
</html>
