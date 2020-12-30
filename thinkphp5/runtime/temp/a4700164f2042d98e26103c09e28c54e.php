<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:93:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\classroom\qrcode.html";i:1609335450;}*/ ?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>jquery-qrco生成 QR CODE</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../../../../static/jquery-qrcode-0.17.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="">
    <style>
    .block>div {
        float: left;
        margin: 15px;
    }
    </style>
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
            <?php for($i = 0; $i < $SeatMap->x_map; $i++): ?>
            <tr>
                <?php for($j = 0; $j < $SeatMap->y_map; $j++): if($seats[$i][$j]->getData("is_seat") == '0'): ?>
                <td>
                    <div id="<?php echo $i; ?><?php echo $j; ?>"></div>
                </td>

                <script>
                $("#<?php echo $i; ?><?php echo $j; ?>").qrcode({
                    render: 'div',
                    size: 250,
                    text: '<?php echo($url . url('isSeated?x=' . $seats[$i][$j], '&y=' . $seats[$i][$j])); ?>'
                });
                </script>
                <?php else: ?>
                <td class="default" style="height: 50px;width: 50px;">
                    <p style="font-size: 25px;">
                        <?php echo($seats[$i][$j]->x);echo($seats[$i][$j]->y); ?>
                    </p>
                </td>
                <?php endif; endfor ?>
            </tr>
            <?php endfor ?>
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