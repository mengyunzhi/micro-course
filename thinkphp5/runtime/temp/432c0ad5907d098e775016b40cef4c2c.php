<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:96:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\admin_student\index.html";i:1608536612;s:82:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\index.html";i:1608533016;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
学生管理
</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="">
</head>

<body class="container">
    <?php use app\common\view\MenuView; $MenuView = new MenuView; ?>
    <?php echo $MenuView->render(); ?>
    <div class="row">
        <div class="col-md-12">
            
            <hr />
            
            <div class="row">
                <div class="col-md-8">
                    <form class="form-inline">
                        <div class="form-group">
                            
<input name="num" type="text" class="form-control" placeholder="学号..." value="<?php echo input('get.num'); ?>">

                        </div>
                        
                        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>&nbsp;查询</button>
                        
                    </form>
                </div>
                
                <div class="col-md-4 text-right">
                    <a href="<?php echo url('add'); ?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;增加</a>
                </div>
                
            </div>
            
            <hr />
            
            <!-- 定义表格目录模板 -->
            
<table class="table table-hover table-bordered">
    <tr class="info">
        <th>序号</th>
        <th>姓名</th>
        <th>学号</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    <?php if(is_array($coursestudents) || $coursestudents instanceof \think\Collection): $key = 0; $__LIST__ = $coursestudents;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$coursestudent): $mod = ($key % 2 );++$key;?>
    <tr>
        <td><?php echo $key; ?></td>
        <td><?php echo $coursestudent->Student->getData('name'); ?></td>
        <td><?php echo $coursestudent->Student->getData('num'); ?></td>
        <td><?php echo $coursestudent->Student->create_time; ?></td>
        <td><?php echo $coursestudent->Student->update_time; ?></td>
        <td>
            <a class="btn btn-sm btn-primary" href="<?php echo url('edit?id=' . $coursestudent->Student->getData('id')); ?>"><i class="glyphicon glyphicon-pencil"></i>&nbsp;编辑</a>
            <a class="btn btn-danger btn-sm" href="<?php echo url('delete?id=' . $coursestudent->Student->getData('id')); ?>"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</a>&nbsp;
        </td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</table>

            <!-- 定义页数模板 -->
            
<?php echo $coursestudents->render(); ?>

        </div>
    </div>
</body>
<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

</html>