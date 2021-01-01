<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"D:\xampp\htdocs\micro-course\thinkphp5\public/../application/index\view\index\menu.html";i:1608687529;}*/ ?>
<!--菜单导航-->
<div class="row">
    <div class="col-md-12">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"></a>
                </div>
                <!--Collect the nav links,forms,and other contend for toggling-->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="http://www.mengyunzhi.com">梦云智</a>
                        </div>
                        <?php if(is_array($menus) || $menus instanceof \think\Collection): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_menu): $mod = ($i % 2 );++$i;?>
                        <li class="<?php echo $_menu->getClass(); ?>"><a href="<?php echo $_menu->getHref(); ?>"><?php echo $_menu->title; ?></a></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo url('Login/logout'); ?>">注销</a></li>
                    </ul>
                </div>
                <!--/.navbar-collapse-->
            </div>
            <!--/.container-fluid-->
        </nav>
    </div>
</div>