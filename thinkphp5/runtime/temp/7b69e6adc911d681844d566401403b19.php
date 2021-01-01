<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:99:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\classroom\seating_plan.html";i:1609497666;}*/ ?>
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
            <?php $x = 0; for($i = 0; $i < $SeatMap->x_map; $i++): ?>
            <tr>
                <?php $y = 0; for($j = 0; $j < $SeatMap->y_map; $j++): if($seat[$i][$j]->getData("is_seat") == '0'): ?>
                <td class="success" style="height: 50px;width: 50px;">
                    <p style="font-size: 25px;">
                        <?php echo($x);echo($y); $y = $y + 1;?>
                    </p>
                </td>
                <?php else: ?>
                <td class="default" style="height: 50px;width: 50px;">
                    <p style="font-size: 25px;">
                    </p>
                </td>
                <?php endif; endfor; ?>
            </tr>
            <?php if($y === 0): $x--; endif; $x++; endfor; ?>
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