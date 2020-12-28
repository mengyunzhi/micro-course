<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:92:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\classroom\index.html";i:1609141846;s:82:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\index.html";i:1608540289;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
教师管理
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
                            
<input name="name" type="text" class="form-control" placeholder="教室编号..." value="<?php echo input('get.name'); ?>">

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
        <th>教室编号</th>
        <th>操作</th>
    </tr>
    <?php if(is_array($classrooms) || $classrooms instanceof \think\Collection): $key = 0; $__LIST__ = $classrooms;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$classroom): $mod = ($key % 2 );++$key;?>
    <tr>
        <td><?php echo $key; ?></td>
        <td><?php echo $classroom->getData('name'); ?></td>
        <td>
            <a class="btn btn-success btn-sm" href="<?php echo url('seating_plan?id=' . $classroom->getData('id') . '&course_id=' . $Course->id); ?>"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;查看座位图</a>
            <a class="btn btn-danger btn-sm" href="<?php echo url('delete?id=' . $classroom->getData('id')); ?>"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</a>
            <a class="btn btn-sm btn-primary" href="<?php echo url('edit?id=' . $classroom->getData('id')); ?>"><i class="glyphicon glyphicon-pencil"></i>&nbsp;编辑</a></td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</table>

            <!-- 定义页数模板 -->
            
<?php echo $classrooms->render(); ?>

        </div>
    </div>
</body>
<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

</html>