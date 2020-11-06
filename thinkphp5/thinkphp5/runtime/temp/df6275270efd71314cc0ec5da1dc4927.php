<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\term\edit.html";i:1602075535;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>管理员系统</title>
</head>
<body class="container">
    <div class="row">
        <div class="col-md-12">
            <?php $action=request()->action()==='add'?'save':'update'; ?>
            <form action="<?php echo url($action); ?>" method="post">
                <label>学期名:</label>
                <input type="hidden" name="id" value="<?php echo $Term->getData('id'); ?>" />
                <input type="text" name="name" value="<?php echo $Term->getData('name'); ?>" />
                <label>起始时间:</label>
                <input type="date" name="ptime" value="<?php echo $Term->getData('ptime'); ?>" />
                <button type="submit">保存</button>
            </form>
        </div>
    </div>

</body>
</html>