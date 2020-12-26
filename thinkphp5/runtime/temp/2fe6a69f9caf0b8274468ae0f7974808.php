<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\admin_teacher\edit.html";i:1604134260;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>管理员系统</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="shouye.css">
    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="http://www.mengyunzhi.com">梦云智</a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo url('Term/index'); ?>">学期管理</a></li>
                                <li><a href="<?php echo url('AdminTeacher/index'); ?>">用户管理</a></li>
                                <li><a href="<?php echo url('Classroom/index'); ?>">教室管理</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="<?php echo url('Login/logout'); ?>">注销</a></li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 40px;">
        <div class="row">
            <div class="col-md-12">
                <?php $action = request()->action() === 'add' ? 'save' : 'update'; ?>
                <form action="<?php echo url($action); ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $Teacher->getData('id'); ?>" />
                    <div class="form-group">
                        <label for="name" style="font-size: 20px;margin-top: 20px;">姓名:</label>
                        <input type="text" name="name" id="name" value="<?php echo $Teacher->name; ?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="teacher_id" style="font-size: 20px;margin-top: 20px;">工号:</label>
                    <input type="text" name="teacher_id" id="teacher_id" value="<?php echo $Teacher->teacher_id; ?>" class="form-control" />
                    </div>
                    
                    <button type="submit" class="btn btn-default" style="margin-top: 20px">submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

</html>