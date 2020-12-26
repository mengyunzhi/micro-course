<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\student\edit.html";i:1602162842;}*/ ?>
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
        <?php if(is_array($Student->Courses()->select()) || $Student->Courses()->select() instanceof \think\Collection): $i = 0; $__LIST__ = $Student->Courses()->select();if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$course): $mod = ($i % 2 );++$i;?>
            <input type="checkbox" name="course_id[]" id="course_id_<?php echo $course->id; ?>"<?php if($Student->getIsChecked($course) == 'true'): ?>checked="checked"<?php endif; ?> />
            <label for="course_id_<?php echo $course->id; ?>"><?php echo $course->name; ?></label>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <button type="submit">submit</button>
    </form>
</body>
</html>