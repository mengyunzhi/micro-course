<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:99:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\classroom\seating_plan.html";i:1609141452;}*/ ?>
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
    <?php use app\common\view\MenuView; $MenuView = new MenuView; ?>
    <?php echo $MenuView->render(); ?>
        <h3 style="margin-left: 545px;margin-bottom: 50px;">
            <?php echo($Classroom->name) ?>
            <hr>
            <p style="color: green;">讲台</p>
        </h3>
        <div class="container">
            <table class="table table-bordered table-condensed ">
                <?php if(is_array($Seat) || $Seat instanceof \think\Collection): $key = 0; $__LIST__ = $Seat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$seat): $mod = ($key % 2 );++$key;if(($seat->y === 0)): ?>
                <tr>
                    <?php endif; ?>
                    <!-- 空一行 -->
                <tr></tr>
                    <?php if($seat->getData("is_seat") == '0'): ?>
                    <td class="btn btn-success" style="height: 50px;width: 50px;"><p style="font-size: 25px;"><?php echo $key; ?></p></td><?php else: ?>
                    <td class="btn btn-default" style="height: 50px;width: 50px;><p font-size: 30px; ><?php echo $code; ?></p></td>
                    <?php endif; if(($seat->y === $SeatMap[0]->y_map)): ?>
                </tr>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </div>
        <hr>
    </div>
</body>
<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

</html>