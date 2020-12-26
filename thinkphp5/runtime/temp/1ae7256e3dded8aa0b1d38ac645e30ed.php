<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:88:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\grade\index.html";i:1608687882;s:81:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\menu.html";i:1608604718;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <title>
课程管理
</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/static/bootstrap-3.3.7-dist/css/bootstrap.min.css">
</head>

<body class="container">
    <!--菜单导航-->
    <?php use app\common\view\MenuViewTeacher; $MenuViewTeacher = new MenuViewTeacher; ?>
    <?php echo $MenuViewTeacher->render(); ?>

    <!--/菜单导航-->
    <div class="row">
        <div class="col-md-12">
            
            <hr />
            <div class="row">
                <div class="col-md-8">
                    <form class="form-inline">
                        <div class="form-group">
             
                            <input name="name" type="text" class="form-control" placeholder="学号..." value=<?php echo input('get.name'); ?>>
                        </div>
                        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>&nbsp;查询</button>
                    </form>
                </div>
                
            </div>
            <hr />
            
            
<table class="table table-hover table-bordered">
    <tr class="info">
        <th>序号</th>
        <th>课程名称</th>
        <th>学生人数</th>
        <th>签到分数占比</th>
        <th>上课表现</th>
        <th>成绩列表</th>
        <th>操作</th>
    </tr>
    <?php if(is_array($courses) || $courses instanceof \think\Collection): $key = 0; $__LIST__ = $courses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_course): $mod = ($key % 2 );++$key;?>
    <tr>
        <td><?php echo $key; ?></td>
        <td><?php echo $_course->getData('name'); ?></td>
        <td><?php echo $_course->student_num = sizeof($_course->Students); ?></td>
        <td><?php echo $_course->getData('usmix'); ?>%</td>
        <td><a class="btn btn-default" href="<?php echo url('Coursegrade/index?id=' . $_course->id); ?>">增减分数</a> &nbsp;&nbsp;</td>
        <td><a class="btn btn-default" href="<?php echo url('Gradelook/index?id=' . $_course->id); ?>">成绩查看</a> &nbsp;&nbsp;</td>
        <td><a class="btn btn-sm btn-primary" href="<?php echo url('Grade/edit?id=' . $_course->id); ?>">分数设置</a> &nbsp;&nbsp;</td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</table>

            

        </div>
    </div>
</body>

</html>