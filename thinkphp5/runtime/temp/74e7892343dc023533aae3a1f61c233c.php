<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\student\add.html";i:1602856057;}*/ ?>
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
            <?php if(is_array($Course_id) || $Course_id instanceof \think\Collection): $i = 0; $__LIST__ = $Course_id;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_Course): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $_Course->getData('id'); ?>"><?php echo $_Course->getData('name'); ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
		<button type="submit">submit</button>
	</form>
</body>
</html>