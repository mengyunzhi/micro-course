<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:90:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\seat_map\edit.html";i:1609499890;s:82:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\index.html";i:1608540289;}*/ ?>
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
    <a class="btn btn-warning" href="<?php echo url('add'); ?>" style="float: right;"><p style="text-align: center;margin: auto;">重设模板</p></a>
    <hr>
    <table style="margin: auto;">
        <label class="container"><h3 style="text-align: center;">讲台</h3></label>
        <?php if(is_array($seatAisles) || $seatAisles instanceof \think\Collection): $key = 0; $__LIST__ = $seatAisles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$seatAisle): $mod = ($key % 2 );++$key;if(($seatAisle->y === 0)): ?>
        <tr>
            <?php endif; ?>
            <th>
                <a href="<?php echo url('isSeat?id=' . $seatAisle->getData('id')); ?>">
                    <?php if($seatAisle->getData("state") == '0'): ?>
                    <button class="btn btn-info">座位<?php echo($seatAisle->x); echo($seatAisle->y) ?></button><?php else: ?>
                    <button class="btn btn-default">过道<?php echo($seatAisle->x); echo($seatAisle->y) ?></button><?php endif; ?>
                </a>
            </th>
            <?php if(($seatAisle->y === ($SeatMap->y_map-1))): ?>
        </tr>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </table>
    <div style="margin-left: auto;margin-right: auto;">
        <a href="<?php echo url('index'); ?>"><button type="submit" class="btn btn-primary" style="margin-top: 20px" style="padding-right: auto;padding-left: auto;">submit</button></a></div>
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