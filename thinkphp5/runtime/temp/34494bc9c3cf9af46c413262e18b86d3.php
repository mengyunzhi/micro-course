<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\seat_map\index.html";i:1609166029;}*/ ?>
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
    <div class="row">
        <div class="col-md-8">
            <form class="form-inline">
                <div class="form-group">
                    
                    <label class="sr-only" for="name">姓名</label>
                    <input name="name" type="text" class="form-control" placeholder="学期名..." value="<?php echo input('get.name'); ?>">
                    
                </div>
                
                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>&nbsp;查询</button>
                
            </form>
        </div>
        <div class="row">
            <hr style="margin-top: 50px;">
            <br>
            <a href="<?php echo url('add'); ?>" class="btn btn-primary" style="float: left;"><i class="glyphicon glyphicon-plus"></i>&nbsp;新建模板</a>
            <a href="<?php echo url('delete?id='. $SeatMap->id); ?>" class="btn btn-danger" style="float: right;"><i class="glyphicon glyphicon-plus"></i>&nbsp;删除</a>
        </div>
        <h3 style="margin-left: 545px;margin-bottom: 50px;">
            <?php echo('模板' . $SeatMap->getData('id')) ?>
            <hr>
            <p style="color: green;">讲台</p>
        </h3>
        <div class="container">
            <table class="table table-bordered table-condensed ">
                <?php if(is_array($seatAisles) || $seatAisles instanceof \think\Collection): $key = 0; $__LIST__ = $seatAisles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$seatAisle): $mod = ($key % 2 );++$key;if(($seatAisle->y === ($SeatMap->y_map-1))): ?>
                    <tr>
                <?php endif; if($seatAisle->getData("state") == '0'): ?>
                    <td class="success" style="padding-left: 10px;padding-right: 10px;"><?php echo $key; ?></td><?php else: ?>
                    <td style="padding-left: 10px;padding-right: 10px;"><?php echo $key; ?></td><?php endif; if(($seatAisle->y === 0)): ?>
                </tr>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </div>
        <hr>
        <?php $i = 0; for(; $seatMap1[$i]->id != $SeatMap->id; $i++); ?>

        <div class="row">
            <!-- 既不是第一个也不是最后一个模板 -->
            <?php if(($SeatMap->is_last != 1 && $SeatMap->is_first != 1)): $id1 = $seatMap1[$i-1]->id;
            $id2 = $seatMap1[$i+1]->id; endif; ?>
            <!-- 最后一个模板 -->
            <?php if(($SeatMap->is_last ===  1 && $SeatMap->is_first != 1)): $id1 = $seatMap1[$i-1]->id;
            $id2 = -2; endif; ?>
            <!-- 第一个模板 -->
            <?php if(($SeatMap->is_first ===  1 && $SeatMap->is_last != 1)): $id1 = -1;
            $id2 = $seatMap1[$i+1]->id; endif; if(($SeatMap->is_first ===  1 && $SeatMap->is_last ===  1)): $id1 = $SeatMap->id;
            $id2 = $SeatMap->id; endif; ?>
            <a href="<?php echo url('index?id=' . $id1); ?>" class="btn btn-primary " style="float: left;">
                <i class="glyphicon glyphicon-hand-right"></i>
                &nbsp;上一个模板
            </a>
            <a href="<?php echo url('index?id=' . $id2); ?>" class="btn btn-primary " style="float: right;">
                <i class="glyphicon glyphicon-hand-right "></i>
                &nbsp;下一个模板
            </a>
        </div>
    </div>
</body>
<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

</html>