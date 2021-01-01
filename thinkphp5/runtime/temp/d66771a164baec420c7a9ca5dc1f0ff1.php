<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:100:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\classroom\seatmapchange.html";i:1609500037;s:82:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\index.html";i:1608540289;}*/ ?>
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
    <label style="font-size: 15px;">请根据需要选择过道或者座位：</label>
    <hr>
    <table>
        <table class="table table-bordered table-condensed ">
            <?php $x = 0; for($i = 0; $i < $SeatMap->x_map; $i++): ?>
            <tr>
                <?php $y = 0; for($j = 0; $j < $SeatMap->y_map; $j++): ?>
                <td>
                    <a href="<?php echo url('isSeat?id=' . $seat[$i][$j]->getData('id') . '&classroomId=' . $Classroom->id); ?>">
                        <?php if($seat[$i][$j]->getData("is_seat") == '0'): ?>
                        <button class="btn btn-success" style="height: 50px;width: 80px;">座位 <?php echo $x; ?><?php echo $y; $y++;?>
                        </button>
                        <?php else: ?>
                        <button class="btn btn-default" style="height: 50px;width: 80px;">过道
                        </button>
                        <?php endif; ?>
                    </a>
                </td>
                <?php endfor; ?>
            </tr>
            <?php if($y === 0): $x--; endif; $x++; endfor; ?>
        </table>
        <div style="margin-left: auto;margin-right: auto;">
            <a href="<?php echo url('edit?id=' . $Classroom->id); ?>"><button type="submit" class="btn btn-default" style="margin-top: 20px" style="padding-right: auto;padding-left: auto;">submit</button></a></div>
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