<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\admin_student\add.html";i:1603113864;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加</title>
</head>
<body>
	<form action="<?php echo url('save'); ?>" method="post">
		<label for="name">姓名</label>
		<input type="text" name="name" id="name"/>
		<label for="num">学号</label>
		<input type="text" name="num" id="num"/>
		<select name="course_id" id="course">
            <?php if(is_array($Student->Courses()->select()) || $Student->Courses()->select() instanceof \think\Collection): $i = 0; $__LIST__ = $Student->Courses()->select();if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_Course): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $_Course->getData('id'); ?>"><?php echo $_Course->getData('name'); ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
		<button type="submit">submit</button>
	</form>
</body>
</html>