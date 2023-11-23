<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BrightDrop</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{config('url')}}/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
          folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{config('url')}}/css/skins/skin-blue.min.css">
    <link rel="stylesheet" type="text/css" href="{{config('url')}}/css/style.css">
    <style type="text/css">
        .content-wrapper, .right-side, .main-footer{
            margin-left: 0;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue">

<!-- Site wrapper -->
<div class="wrapper" id="login">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 0px !important">
        <!-- Display content header -->
        <section class="content">
            <div class="login-box">
                <div class="login-logo">
                    <a href="http://brightdrop.com/"><b>BrightDrop </b><br>Virtual Assistants</a>
                </div>
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    <form role="form" action="/login" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <!-- E-mail Address -->
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="form-group has-feedback">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" autofocus>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" name="password" placeholder="Password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="row">
                            <div class="form-group" >
                                <div class="col-xs-8">
                                    <div class="checkbox icheck">
                                        <input type="checkbox" name="remember" style="margin-left: 0px;">
                                        <label style="padding-left: 20px;">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Login Button -->
                            <div class="form-group">
                                <div class="col-xs-4">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                                        Login
                                    </button>


                                </div>
                            </div>
                        </div>
                    </form>
                    <a href="{{ url('/password/reset') }}">I forgot my password</a> &nbsp;&nbsp;&nbsp; <!-- <a href="{{ url('/register') }}"><b>Sign Up</a></b></a> --><br>
                </div>
                <!-- /.login-box-body -->
            </div>
            <!-- /.login-box -->
        </section>
    </div>
    <footer class="main-footer">
        <strong>Copyright © 2019 <a href="http://www.brightdrop.com" target="_blank">BrightDrop Virtual Assistants</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->
<!-- jQuery 2.2.3 -->
<script src="{{config('url')}}/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="{{config('url')}}/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{config('url')}}/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{config('url')}}/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{config('url')}}/js/demo.js"></script>
</body>
</html>