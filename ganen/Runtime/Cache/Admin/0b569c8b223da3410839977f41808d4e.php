<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title><?php echo ($meta_title); ?>-内部办公系统</title>
		<link href="/oa/oa/ganen/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
		<link rel="stylesheet" type="text/css" href="/oa/oa/ganen/Public/static/semantic-ui/semantic.min.css" media="all">
		<link rel="stylesheet" type="text/css" href="/oa/oa/ganen/Build/Admin/Style/style.css" media="all">
		<script type="text/javascript" src="/oa/oa/ganen/Public/static/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="/oa/oa/ganen/Public/static/semantic-ui/semantic.min.js"></script>
		<script type="text/javascript" src="/oa/oa/ganen/Public/static/jquery.mousewheel.js"></script>
		
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
				

				
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>行为日志</h2>
	</div>

    <div>
        <button class="btn ajax-get confirm" url="<?php echo U('clear');?>">清 空</button>
		<button class="btn ajax-post confirm" target-form="ids" url="<?php echo U('remove');?>">删 除</button>
    </div>
	<!-- 数据列表 -->
	<div class="data-table">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">行为名称</th>
		<th class="">执行者</th>
		<th class="">执行时间</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<?php if(!empty($_list)): if(is_array($_list)): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="<?php echo ($vo["id"]); ?>" /></td>
			<td><?php echo ($vo["id"]); ?> </td>
			<td><?php echo get_action($vo['action_id'],'title');?></td>
			<td><?php echo get_nickname($vo['user_id']);?></td>
			<td><span><?php echo (time_format($vo["create_time"])); ?></span></td>
			<td><a href="<?php echo U('Action/edit?id='.$vo['id']);?>">详细</a>
				<a class="confirm ajax-get" href="<?php echo U('Action/remove?ids='.$vo['id']);?>">删除</a>
                </td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<?php else: ?>
		<td colspan="6" class="text-center"> aOh! 暂时还没有内容! </td><?php endif; ?>
	</tbody>
    </table>
	</div>
	<!-- 分页 -->
	<div class="page"><?php echo ($_page); ?></div>
	<!-- /分页 -->


			</div>
		</div>
		<!-- /内容区 -->
		<script type="text/javascript" src="/Build/Admin/Js/all.js"></script>
		<script type="text/javascript">
			(function() {
				var ThinkPHP = window.Think = {
					"ROOT": "/oa/oa/ganen", //当前网站地址
					"APP": "/oa/oa/ganen/index.php?s=", //当前项目地址
					"PUBLIC": "/oa/oa/ganen/Public", //项目公共目录地址
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
$(function(){
	$("#action_add").click(function(){
		window.location.href = $(this).attr('url');
	})
})
</script>

	</body>

</html>