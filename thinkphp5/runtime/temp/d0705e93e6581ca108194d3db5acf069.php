<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\course\edit.html";i:1602077295;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>编辑</title>
</head>
<body>
    <form action="<?php echo url('update'); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $course->getData('id'); ?>" />
        <label for="name">name:</label><input type="text" name="name" id="name" value="<?php echo $course->getData('name'); ?>" />
        <label for="teacher">teacher:</label>
         <select name="teacher_id" id="teacher">
           <?php if(is_array($teachers) || $teachers instanceof \think\Collection): $i = 0; $__LIST__ = $teachers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_Teacher): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $_Teacher->getData('id'); ?>" <?php if($_Teacher->getData('id') == $course->getData('teacher_id')): ?> selected="selected" <?php endif; ?>><?php echo $_Teacher->getData('name'); ?></option>
           <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
        <button type="submit">submit</button>
    </form>
</body>
</html>