<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\pre_class\index.html";i:1608687529;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>标题</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="">
</head>

<body class="container">
    <?php use app\common\view\MenuViewTeacher; $MenuViewTeacher = new MenuViewTeacher; ?>
    <?php echo $MenuViewTeacher->render(); ?>
    <form class="form-horizontal" action="<?php echo url('SeatMapTeacher/index'); ?>" method="post">
        <table class="table table-hover table-bordered" style="200px;">
            <td><select class="form-control" name="aodnum" id="gradeaod">
                    <?php if(is_array($courses) || $courses instanceof \think\Collection): $i = 0; $__LIST__ = $courses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$course): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $course->id; ?>" <?php if($course->id == $course->id): ?> selected="selected" <?php endif; ?>><?php echo $course->name; ?> </option> <?php endforeach; endif; else: echo "" ;endif; ?> </select> </td> </table> 签到时间设置:  <input type="datetime-local" name="">----<input type="datetime-local" name="">
                        <div class="form-group">
                            <div style="margin-left: 200px;margin-top: 50px;">
                                <button type="submit" onclick="开始上课" class="btn btn-default">开始上课</button>
                            </div>
                        </div>
    </form>
</body>
<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

</html>