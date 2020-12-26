<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:89:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\course\index.html";i:1608604718;s:81:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\menu.html";i:1608604718;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <title>
教师管理
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
            
            
<div class="col-md-4 text-right" style="float: right;">
                    <a href="<?php echo url('course/add?id=' . $Teacher->id); ?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;增加</a>
                </div>
<table class="table table-hover table-bordered">
    <tr class="info">
        <th>序号</th>
        <th>课程名称</th>
        <th>学生人数</th>
        <th>学期</th>
        <th>学生列表</th>
        <th>操作</th>
    </tr>
    <?php if(is_array($courses) || $courses instanceof \think\Collection): $key = 0; $__LIST__ = $courses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_course): $mod = ($key % 2 );++$key;?>
    <tr>
        <td><?php echo $key; ?></td>
        <td><?php echo $_course->getData('name'); ?></td>
        <td><?php echo $_course->student_num = sizeof($_course->Students); ?></td>
        <td><?php echo $_course->Term->name; ?></td>
        <td><a class="btn btn-default" href="<?php echo url('student/index?id=' . $_course->id); ?>"><i class="glyphicon glyphicon-pencil"></i>&nbsp;点击查看</a>&nbsp;</td><td>
            <a class="btn btn-sm btn-primary" href="<?php echo url('course/courseedit?id=' . $_course->id); ?>"><i class="glyphicon glyphicon-pencil"></i>&nbsp;编辑</a><a class=" btn btn-danger btn-sm" href="<?php echo url('course/delete?id=' . $_course->id); ?>"><i class="glyphicon glyphicon-pencil"></i>&nbsp;删除</a></td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<?php echo $courses->render(); ?> 

            

        </div>
    </div>
</body>

</html>