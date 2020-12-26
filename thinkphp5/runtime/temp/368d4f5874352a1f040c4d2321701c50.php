<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:91:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\classroom\edit.html";i:1608815180;s:82:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\index.html";i:1608540289;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
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
            
<div class="container" style="margin-top: 40px">
    <div class="row">
        <div class="col-md-12">
            <form>
                <div class="form-group">
                    <label for="name">教室编号</label>
                    <input type="text" class="form-control" id="name" placeholder="教室编号">
                </div>
                <div class="form-group">
                    <label for="name">对应模板编号</label>
                    <select class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <a class="btn btn-primary" href="<?php echo url('SeatMap/index'); ?>" style="margin-top: 20px;"><i class="glyphicon glyphicon-hand-right"></i>&nbsp;查看模板</a>
                <hr>
                <a class="btn btn-primary" href="<?php echo url('seatmap_change?id='.$classroom->id); ?>" style="margin-top: 20px;"><i class="glyphicon glyphicon-hand-right"></i>&nbsp;更改本教室座位图</a>
                <hr>
                <a href="<?php echo url('index'); ?>" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>&nbsp;submit</a>
            </form>
            </form>
        </div>
    </div>
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