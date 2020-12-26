<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\course\add.html";i:1602035168;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加</title>
</head>

<body>
    <form action="<?php echo url('save'); ?>" method="post">
        <label for="name">课程名</label>
        <input type="text" name="name" id="name">
        <select name="teacher_id" id="teacher">
            <?php if(is_array($teachers) || $teachers instanceof \think\Collection): $i = 0; $__LIST__ = $teachers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_Teacher): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $_Teacher->getData('id'); ?>"><?php echo $_Teacher->getData('name'); ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
        <button type="submit">submit</button>
    </form>
</body>

</html>