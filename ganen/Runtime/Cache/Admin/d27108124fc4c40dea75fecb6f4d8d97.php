<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title><?php echo ($meta_title); ?>-内部办公系统</title>
		<link href="/ganen/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
		<link rel="stylesheet" type="text/css" href="/ganen/Public/static/semantic-ui/semantic.min.css" media="all">
		<link rel="stylesheet" type="text/css" href="/ganen/Build/Admin/Style/style.css" media="all">
		<script type="text/javascript" src="/ganen/Public/static/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="/ganen/Public/static/semantic-ui/semantic.min.js"></script>
		<script type="text/javascript" src="/ganen/Public/static/jquery.mousewheel.js"></script>
		
	</head>

	<body>
		<!-- 头部 -->
		<div class="header">
			<!-- Logo -->
			<span class="logo"></span>
			<!-- /Logo -->

			<!-- 主导航 -->
			<ul class="main-nav">
				<?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>">
						<a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
			<!-- /主导航 -->

			<!-- 用户栏 -->
			<div class="user-bar">
				<a href="javascript:;" class="user-entrance">
					<i class="icf">&#xe64e;</i>
					<span><?php echo session('user_auth.username');?></span>
				</a>
				<ul class="user-menu hidden">
					<li>
						<a href="<?php echo U('User/updatePassword');?>">修改密码</a>
					</li>
					<li>
						<a href="<?php echo U('User/updateNickname');?>">修改昵称</a>
					</li>
					<li>
						<a href="<?php echo U('Public/logout');?>">退出</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- /头部 -->

		<!-- 边栏 -->
		<div class="sidebar">
			<!-- 子导航 -->
			
				<div class="ui styled accordion">
					<?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
						<?php if(!empty($sub_menu)): if(!empty($key)): ?><div class="title"><i class="dropdown icon"></i><?php echo ($key); ?></div><?php endif; ?>
							<div class="content">
								<ul class="side-sub-menu">
									<?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
											<a class="item" href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div><?php endif; ?>
						<!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<!--<div id="subnav" class="subnav">
					<?php if(!empty($_extra_menu)): ?> <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
					
				</div>-->
			
			<!-- /子导航 -->
		</div>
		<!-- /边栏 -->

		<!-- 内容区 -->
		<div id="main-content">
			<div id="top-alert" class="fixed alert alert-error" style="display: none;">
				<button class="close fixed" style="margin-top: 4px;">&times;</button>
				<div class="alert-content">这是内容</div>
			</div>
			<div id="main" class="main">
				
					<!-- nav -->
					<?php if(!empty($_show_nav)): ?><div class="breadcrumb">
							<span>您的位置:</span>
							<?php $i = '1'; ?>
							<?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
									<?php else: ?>
									<span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
								<?php $i = $i+1; endforeach; endif; ?>
						</div><?php endif; ?>
					<!-- nav -->
				

				
    <div id="menuEdit">
        <div class="main-title">
            <h2><?php echo isset($info['id'])?'编辑':'新增';?>项目</h2>
        </div>
        <form action="<?php echo U();?>" method="post" class="form-horizontal ui form">
            <div class="field">
                <label>项目名称</label>
                <input type="text" class="text input-large" name="name" value="<?php echo ((isset($info["name"]) && ($info["name"] !== ""))?($info["name"]):''); ?>">
            </div>
            <div class="form-item">
                <label class="item-label">所属地区</label>
                <div class="controls">
                    <input type="text" class="text input-small" name="area" value="<?php echo ((isset($info["area"]) && ($info["area"] !== ""))?($info["area"]):''); ?>">
                </div>
            </div>
            <div class="form-item">
                <label class="item-label">医院名称</label>
                <div class="controls">
                    <input type="text" class="text input-large" name="h_name" value="<?php echo ((isset($info["h_name"]) && ($info["h_name"] !== ""))?($info["h_name"]):''); ?>">
                </div>
            </div>
             <div class="form-item">
                <label class="item-label">科室</label>
                <div class="controls">
                    <input type="text" class="text input-large" name="depar" value="<?php echo ((isset($info["depar"]) && ($info["depar"] !== ""))?($info["depar"]):''); ?>">
                </div>
            </div>
             <div class="form-item">
                <label class="item-label">项目主管</label>
                <div class="controls">
                    <input type="text" class="text input-large" name="charge" value="<?php echo ((isset($info["charge"]) && ($info["charge"] !== ""))?($info["charge"]):''); ?>">
                </div>
            </div>
            <div class="form-item">
                <input type="hidden" name="id" value="<?php echo ((isset($info["id"]) && ($info["id"] !== ""))?($info["id"]):''); ?>">
                <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
                <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
            </div>
        </form>
    </div>

			</div>
		</div>
		<!-- /内容区 -->
		<script type="text/javascript" src="/Build/Admin/Js/all.js"></script>
		<script type="text/javascript">
			(function() {
				var ThinkPHP = window.Think = {
					"ROOT": "/ganen", //当前网站地址
					"APP": "/ganen/index.php?s=", //当前项目地址
					"PUBLIC": "/ganen/Public", //项目公共目录地址
					"DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
					"MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
					"VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
				}
			})();
		</script>
		<script type="text/javascript">
			$(function(){
				/* 左边菜单高亮 */
	            var url = window.location.pathname + window.location.search;
	            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
	            $(".sidebar").find("a[href='" + url + "']").parent().addClass("current");
	            var openNum = $('.sidebar .content').index($(".sidebar").find("a[href='" + url + "']").closest(".content"));
	            //侧边栏
				$('.sidebar .accordion')
				  .accordion()
				;
				if(openNum >= 0){
					$('.sidebar .accordion').accordion('open',Number(openNum));
				}else{
					$(".sidebar .content").each(function(i){
						if($(this).find(".current").length > 0){
							$('.sidebar .accordion').accordion('open',$(this).index()-1);
						}
					})
				}
				
            
				/* 头部管理员菜单 */
	            $(".user-bar").mouseenter(function(){
	                var userMenu = $(this).children(".user-menu ");
	                userMenu.removeClass("hidden");
	                clearTimeout(userMenu.data("timeout"));
	            }).mouseleave(function(){
	                var userMenu = $(this).children(".user-menu");
	                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
	                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
	            });

			})
		</script>
		
    <script type="text/javascript">
        Think.setValue("pid", {
            $info.pid |
            default = 0
        });
        Think.setValue("hide", {
            $info.hide |
            default = 0
        });
        Think.setValue("is_dev", {
            $info.is_dev |
            default = 0
        });
        //导航高亮
        highlight_subnav('<?php echo U('index');?>');
    </script>

	</body>

</html>