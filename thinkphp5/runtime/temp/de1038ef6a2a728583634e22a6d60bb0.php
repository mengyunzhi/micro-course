<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\xampp\htdocs\thinkphp5\public/../application/index\view\teacher\index.html";i:1602075691;}*/ ?>
<!DOCTYPE html>
<html lang="zh-hans">

<head>
    <meta charset="UTF-8">
    <title>教师管理</title>
    <link rel="stylesheet" type="text/css" href="/thinkphp5/public/static/bootstrap-3.3.7/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="http://www.mengyunzhi.com">梦云智</a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo url('Term/index'); ?>">学期管理</a></li>
                                <li><a href="<?php echo url('Teacher/index'); ?>">用户管理</a></li>
                                <li><a href="<?php echo url('Classroom/index'); ?>">教室管理</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="<?php echo url('Login/logout'); ?>">注销</a></li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <hr />
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-inline" method="post">
                                <div class="form-group">
                                    <label class="sr-only" for="name">姓名</label>
                                    <input name="name" type="text" class="form-control" placeholder="工号..." value="<?php echo input('get.teacher_id'); ?>">
                                </div>
                                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>&nbsp;查询</button>
                            </form>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo url('add'); ?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;增加</a>
                        </div>
                    </div>
                    <hr />
                    <table class="table table-hover table-bordered">
                        <tr class="info">
                            <th>序号</th>
                            <th>姓名</th>
                            <th>工号</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        <?php if(is_array($teachers) || $teachers instanceof \think\Collection): $key = 0; $__LIST__ = $teachers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$teacher): $mod = ($key % 2 );++$key;?>
                        <tr>
                            <td><?php echo $key; ?></td>
                            <td><?php echo $teacher->getData('name'); ?></td>
                            <td><?php echo $teacher->getData('teacher_id'); ?></td>
                            <td><?php echo $teacher->create_time; ?></td>
                            <td><?php echo $teacher->update_time; ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="<?php echo url('edit?id=' . $teacher->getData('id')); ?>"><i class="glyphicon glyphicon-pencil"></i>&nbsp;编辑</a>
                                <a class="btn btn-success btn-sm" href="<?php echo url('Course/index?id=' . $teacher->getData('id')); ?>">&nbsp;所教课程</a>
                                <a class="btn btn-danger btn-sm" href="<?php echo url('course/index?id=' . $teacher->getData('id')); ?>"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</a>&nbsp;
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                    <?php echo $teachers->render(); ?>
                </div>
            </div>
        </div>
</body>
</div>
</div>
</div>
</body>

</html>