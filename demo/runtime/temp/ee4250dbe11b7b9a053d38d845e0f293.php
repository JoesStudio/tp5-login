<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:112:"/Users/cenglingwen/Projects/Tp5Projects/extends/tp5-login/demo/public/../application/index/view/login/index.html";i:1569674529;}*/ ?>
<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:23 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Wstudio个人商城后台 - 登录</title>
    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/static/hplus/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/static/hplus/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">

    <link href="/static/hplus/css/animate.min.css" rel="stylesheet">
    <link href="/static/hplus/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">W+</h1>

        </div>
        <h3>欢迎使用 Wstudio</h3>

        <form class="m-t" role="form" method="post" action="<?php echo url('loginAction'); ?>">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="用户名">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="密码">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>


            <!--            <p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a> | <a href="register.html">注册一个新账号</a>-->
            <!--            </p>-->

        </form>
    </div>
</div>
<script src="/static/hplus/js/jquery.min.js?v=2.1.4"></script>
<script src="/static/hplus/js/bootstrap.min.js?v=3.3.6"></script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
</body>


<!-- Mirrored from www.zi-han.net/theme/hplus/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:23 GMT -->
</html>
