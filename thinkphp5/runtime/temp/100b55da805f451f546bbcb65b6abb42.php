<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\login\index.html";i:1608639460;}*/ ?>
<!DOCTYPE HTML>
<html>

<head>
    <title>微课堂Login</title>
    <!-- Custom Theme files -->
    <link href="__PUBLIC__/static/bootstrap-3.3.7-dist/css/menu.css" rel="stylesheet" type="text/css" media="all" />
    <!-- Custom Theme files -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Login form web template, Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
    <!--Google Fonts-->
    <link href='http://fonts.useso.com/css?family=Roboto:500,900italic,900,400italic,100,700italic,300,700,500italic,100italic,300italic,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.useso.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <!--Google Fonts-->
</head>

<body style="background-color: grey">
    <form action="<?php echo url('Login/login'); ?>" method="post">
        <div class="login">
            <h2>Micro-course</h2>
            <div class="login-top">
                <h1>LOGIN FORM</h1>
                <form>
                    <label class="col-sm-2 control-label" style="font-size: 20px;">username:</label><input type="text" name="username" id="username" />
                    <label class="col-sm-2 control-label" style="font-size: 20px;">password:</label><input type="password" name="password" id="password" />
                </form>
                <div class="forgot">
                    <input type="submit" value="Login">
                </div>
            </div>
            <div class="login-bottom">
                <h3>梦云智欢迎你 &nbsp;<a href="#">welcome</a>&nbsp You</h3>
            </div>
        </div>
        <div class="copyright">
            <p>Create &copy; in 2020 By 梦云智</a></p>
        </div>
    </form>
</body>

</html>