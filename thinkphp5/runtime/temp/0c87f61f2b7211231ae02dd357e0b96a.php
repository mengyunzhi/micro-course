<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:89:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\seat_map\add.html";i:1608861029;s:82:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\index.html";i:1608540289;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
模板添加
</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="">
</head>

<body class="container">
    <?php use app\common\view\MenuView; $MenuView = new MenuView; ?>
    <?php echo $MenuView->render(); ?>
    <div class="row">
        <div class="col-md-12">
            

            <div class="row">
                <div class="col-md-8">
                    <form class="form-inline">
                        <div class="form-group">
                            

                        </div>
                        

                    </form>
                </div>
                

            </div>
            

            <!-- 定义表格目录模板 -->
            
<div class="container">
    <form action="<?php echo url('save'); ?>" method="post">
        <div class="form-group">
            <label for="name" style="font-size: 20px;margin-top: 20px;">输入教室总行数:</label>
            <input name="xMap" id="xMap" class="form-control" />
        </div>
        <div class="form-group">
            <label for="name" style="font-size: 20px;margin-top: 20px;">输入教室总列数:</label>
            <input name="yMap" id="yMap" class="form-control" />
        </div>
        <a href="<?php echo url('save'); ?>"><button type="submit" class="btn btn-default" style="margin-top: 20px">submit</button></a>
    </form>
</div>

            <!-- 定义页数模板 -->
            
            
        </div>
    </div>
</body>
<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

</html>