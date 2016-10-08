<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
<title><?php echo C('WEB_SITE_TITLE');?></title>
<link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
<link href="/Public/static/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="/Public/static/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/Public/static/bootstrap/css/docs.css" rel="stylesheet">
  
<link href="/Public/static/bootstrap/css/onethink.css" rel="stylesheet">
<link href="/Public/static/flexslider/Flexslider.css" rel="stylesheet">
<link href="/Public/home/css/module.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/Public/home/css/<?php echo (C("COLOR_STYLE")); ?>.css?222" media="all">
<link href="/Public/home/css/enhwa.css" rel="stylesheet">


<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="/Public/static/bootstrap/js/html5shiv.js"></script>
<![endif]-->

<!--[if lt IE 9]>
<script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/Public/static/bootstrap/js/bootstrap.min.js"></script>
<!--<![endif]-->

<script type="text/javascript" src="/Public/static/flexslider/jquery.flexslider-min.js"></script>


<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<?php echo hook('pageHeader');?>

</head>
<body>
	<!-- 头部 -->
	<!-- 导航条
================================================== -->
<div class="navbar navbar-fixed-top" style="backgroud:#ccc">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" style="width:15%;height:45px" href="<?php echo U('index/index');?>">
             <img style="height:40px;" src="/Public/logo.png" />
            </a>
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="nav-collapse collapse">
                <ul class="nav" style="font-size:18px;padding-top:14px;">
                    <?php $__NAV__ = M('Channel')->field(true)->where("status=1")->order("sort")->select(); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i; if(($nav["pid"]) == "0"): ?><li>
                            <a href="<?php echo (get_nav_url($nav["url"])); ?>" target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>"><?php echo ($nav["title"]); ?></a>
                        </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <div class="nav-collapse collapse pull-right" style="display:none">
                <?php if(is_login()): ?><ul class="nav" style="margin-right:0">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left:0;padding-right:0"><?php echo get_username();?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo U('User/profile');?>">修改密码</a></li>
                                <li><a href="<?php echo U('User/logout');?>">退出</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="nav" style="margin-right:0;padding-top:14px;font-size:18px">
                        <li>
                            <a href="<?php echo U('/admin/index');?>">管理</a>
                        </li>
                    </ul><?php endif; ?>
            </div>
        </div>
    </div>
</div>

	<!-- /头部 -->
	
	<!-- 主体 -->
	
    <header class="subhead" id="overview">
		<div class="container">
			
		</div>
	</header>

<div id="main-container" class="container">
    <div class="row">
        

    <div class="span9 main-content" style="padding-top:45px;">
         
        <!-- Contents
        ================================================== -->
        <div class="" style="margin-top:30px;text-align:center;" >
		 <h3 style="text-align:center;">
		 <a  href="<?php echo U('Article/detail?id='.$data['id']);?>"><?php echo ($info["title"]); ?></a>
		</h3>	
		        <span style="margin-top:30px" class="" >
					<span class="author"><?php echo (get_username($info["uid"])); ?></span>
					<span> 发表于 <?php echo (date('Y-m-d H:i',$info["create_time"])); ?></span>
				</span>
			
		</div>
				
        <section id="contents"><?php echo ($info["content"]); ?></section>
     
        <hr/>   
      	<p>
				
				<div class="" style="text-align:center;">
					<?php $prev = D('Document')->prev($info); if(!empty($prev)): ?><a href="<?php echo U('?id='.$prev['id']);?>" style="padding:10px;">上一篇</a><?php endif; ?>
                    <?php $next = D('Document')->next($info); if(!empty($next)): ?><a href="<?php echo U('?id='.$next['id']);?>"  style="padding:10px;">下一篇</a><?php endif; ?>
				</div>
	  </p>
   
     
       
    </div>

        
        <!-- 左侧 nav
        ================================================== -->
            <div class="span3" style="padding-top:45px">
                
                <ul class="nav" style="display:none">
                   <?php echo W('Category/lists', array($category['id'], ACTION_NAME == 'index'));?>
                </ul>
            </div>
        
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $(window).resize(function(){
            $("#main-container").css("min-height", $(window).height() - 343);
        }).resize();
    })
</script>
	<!-- /主体 -->

	<!-- 底部 -->
	
    <!-- 底部
    ================================================== -->
    <footer class="footer">
      <div class="container">
      <a href="./uploads/picture/HLWJYXKZ.jpg" target="_blank">江苏省食品药品监督管理局批准经营性网站：苏-经营性-2015-0006
			</a>
			<br>
        <a href="http://www.miibeian.gov.cn" target="_blank">工业和信息化部网站备案：苏ICP备10217328号-1</a>
          <p> &copy 2016 <?php if(date('Y') > 2016): echo '-'.date(Y); endif; ?>  <strong><a href="http://www.enhwa.com" target="_blank">江苏恩华和润医药有限公司</a></strong> </p>
      </div>
    </footer>

<script type="text/javascript">
(function(){
	
	 $(".flexslider").flexslider({
		 directionNav:true,
		 pauseOnAction:false,
		 pauseOnHover:true,
		 controlNav:true,
		 directionNav:false,
		 }); 
	
	var ThinkPHP = window.Think = {
		"ROOT"   : "", //当前网站地址
		"APP"    : "/index.php?s=", //当前项目地址
		"PUBLIC" : "/Public", //项目公共目录地址
		"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
		"MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
		"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
	}
})();


</script>
 <!-- 用于加载js代码 -->
<!-- 页面footer钩子，一般用于加载插件JS文件和JS代码 -->
<?php echo hook('pageFooter', 'widget');?>
<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
	
</div>

	<!-- /底部 -->
</body>
</html>