<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\course\index.html";i:1602767181;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

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
                            <a class="navbar-brand" href="#">梦云智</a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo url('Term/index'); ?>">学期管理</a></li>
                                <li><a href="<?php echo url('Teacher/index'); ?>">用户管理</a></li>
                                <li><a href="<?php echo url('Classroom/index'); ?>">教室管理</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="#">注销</a></li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </div>
        </div>
    </div>

    <body class="container">
        <div class="row">
            <div class="col-md-12">
                <hr />
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-inline">
                            <div class="form-group">
                                <label class="sr-only" for="name">姓名</label>
                                <input name="name" type="text" class="form-control" placeholder="课程名..." value="<?php echo input('get.name'); ?>">
                            </div>
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>&nbsp;查询</button>
                        </form>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="<?php echo url('add'); ?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;增加</a>
                    </div>
                </div>
                <hr />
                <table class="table table-hover table-bordered">
                    <tr class="info">
                        <th>序号</th>
                        <th>课程名称</th>
                        <th>教师姓名</th>
                        <th>操作</th>
                    </tr>
                    <?php if(is_array($courses) || $courses instanceof \think\Collection): $key = 0; $__LIST__ = $courses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$course): $mod = ($key % 2 );++$key;?>
                    <tr>
                        <td><?php echo $key; ?></td>
                        <td><?php echo $course->getData('name'); ?></td>
                        <td>
                            <?php echo $course->Teacher->name; ?>
                            
                        </td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="<?php echo url('edit?id=' . $course->getData('id')); ?>"><i class="glyphicon glyphicon-pencil"></i>&nbsp;编辑</a>
                            <a class="btn btn-success btn-sm" href="<?php echo url('Student/index?id=' . $course->getData('id')); ?>">&nbsp;对应学生名单</a>
                            <a class="btn btn-danger btn-sm" href="<?php echo url('delete?id=' . $course->getData('id')); ?>"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</a></td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
                <?php echo $courses->render();; ?>
            </div>
        </div>
    </body>

</html>
<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</body>

</html>