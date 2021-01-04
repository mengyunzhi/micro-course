<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\test\file.html";i:1609726979;}*/ ?>
<!DOCTYPE html>
<html>
<body>

<form enctype="multipart/form-data" action="<?php echo url('file1'); ?>" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>

</body>
</html>