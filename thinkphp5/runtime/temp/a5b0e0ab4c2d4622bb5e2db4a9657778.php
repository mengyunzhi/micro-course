<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\teacher\edit.html";i:1601690376;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <title>新增数据</title>
</head>

<body class="contanier">
    <div class="row">
        <div class="col-md-12">
            <?php $action = request()->action() === 'add' ? 'save' : 'update'; ?>
            <form action="<?php echo url($action); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $Teacher->getData('id'); ?>" />
                <label for="name">姓名:</label><input type="text" name="name" id="name" value="<?php echo $Teacher->name; ?>" />
                <label for="teacher_id">工号:</label><input type="text" name="teacher_id" id="teacher_id" value="<?php echo $Teacher->teacher_id; ?>" />
                <button type="submit">submit</button>
            </form>
        </div>
    </div>
</body>

</html>