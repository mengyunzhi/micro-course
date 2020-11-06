<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\admin_student\edit.html";i:1603113893;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>编辑</title>
</head>
<body>
    <form action="<?php echo url('update'); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $Student->getData('id'); ?>" />
        <label for="name">name:</label><input type="text" name="name" id="name" value="<?php echo $Student->name; ?>"/>
        <select name="course_id" id="course">
            <?php if(is_array($Student->Courses()->select()) || $Student->Courses()->select() instanceof \think\Collection): $i = 0; $__LIST__ = $Student->Courses()->select();if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_Course): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $_Course->getData('id'); ?>"><?php echo $_Course->getData('name'); ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
        <button type="submit">submit</button>
    </form>
</body>
</html>