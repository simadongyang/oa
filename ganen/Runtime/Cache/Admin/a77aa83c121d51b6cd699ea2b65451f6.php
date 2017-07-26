<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title><?php echo ($meta_title); ?>-内部办公系统</title>
		<link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
		<link rel="stylesheet" type="text/css" href="/Public/static/semantic-ui/semantic.min.css" media="all">
		<link rel="stylesheet" type="text/css" href="/Build/Admin/Style/style.css" media="all">
		<script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="/Public/static/semantic-ui/semantic.min.js"></script>
		<script type="text/javascript" src="/Public/static/jquery.mousewheel.js"></script>
		
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
			<h2><?php echo isset($info['id'])?'编辑':'新增';?>后台菜单</h2>
		</div>
		<form action="<?php echo U();?>" method="post" class="form-horizontal ui form">
			<div class="fields">
				<div class="field">
					<label>标题<span class="check-tips">（用于后台显示的配置标题）</span></label>
					<input type="text" name="title" value="<?php echo ((isset($info["title"]) && ($info["title"] !== ""))?($info["title"]):''); ?>">
				</div>
			</div>
			<div class="fields">
				<div class="field">
					<label>排序<span class="check-tips">（用于分组显示的顺序）</span></label>
					<input type="text" name="sort" value="<?php echo ((isset($info["sort"]) && ($info["sort"] !== ""))?($info["sort"]):0); ?>">
				</div>
			</div>
			<div class="fields">
				<div class="field">
					<label>链接<span class="check-tips">（U函数解析的URL或者外链）</span></label>
					<input type="text" name="url" value="<?php echo ((isset($info["url"]) && ($info["url"] !== ""))?($info["url"]):''); ?>">
				</div>
			</div>
			<div class="fields">
				<div class="field">
					<label>上级菜单<span class="check-tips">（所属的上级菜单）</span></label>
					<select class="ui dropdown w204" name="pid">
						<?php if(is_array($Menus)): $i = 0; $__LIST__ = $Menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><option value="<?php echo ($menu["id"]); ?>" <?php if($info["pid"] == $menu.id): ?>selected="selected"<?php endif; ?>><?php echo ($menu["title_show"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
			</div>
			<div class="fields">
				<div class="field">
					<label>分组<span class="check-tips">（用于左侧分组二级菜单）</span></label>
					<input type="text" name="group" value="<?php echo ((isset($info["group"]) && ($info["group"] !== ""))?($info["group"]):''); ?>">
				</div>
			</div>
			<div class="fields">
				<div class="field">
					<label>是否隐藏<span class="check-tips"></span></label>
					<div class="ui radio checkbox mr15">
						<input type="radio" name="hide" value="1" tabindex="0" class="hidden" <?php if($info["hide"] == 1): ?>checked=""<?php endif; ?>>
						<label>是</label>
					</div>
					<div class="ui radio checkbox">
						<input type="radio" name="hide" value="0" tabindex="0" class="hidden" <?php if($info["hide"] != 1): ?>checked=""<?php endif; ?>>
						<label>否</label>
					</div>
				</div>
			</div>
			<div class="fields">
				<div class="field">
					<label>仅开发者模式可见<span class="check-tips"></span></label>
					<div class="ui radio checkbox mr15">
						<input type="radio" name="is_dev" value="1" tabindex="0" class="hidden" <?php if($info["is_dev"] == 1): ?>checked=""<?php endif; ?>>
						<label>是</label>
					</div>
					<div class="ui radio checkbox">
						<input type="radio" name="is_dev" value="0" tabindex="0" class="hidden" <?php if($info["is_dev"] != 1): ?>checked=""<?php endif; ?>>
						<label>否</label>
					</div>
				</div>
			</div>
			<div class="fields">
				<div class="field">
					<label>说明<span class="check-tips">（菜单详细说明）</span></label>
					<input type="text" name="tip" value="<?php echo ((isset($info["tip"]) && ($info["tip"] !== ""))?($info["tip"]):''); ?>">
				</div>
			</div>
			<div class="form-item">
				<input type="hidden" name="id" value="<?php echo ((isset($info["id"]) && ($info["id"] !== ""))?($info["id"]):''); ?>">
				<button class="ui blue button submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
				<button class="ui blue button btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
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
					"ROOT": "", //当前网站地址
					"APP": "/index.php?s=", //当前项目地址
					"PUBLIC": "/Public", //项目公共目录地址
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
		$(function() {
			$('.ui.radio.checkbox').checkbox();
			$('.ui.dropdown').dropdown();
		})
		//		Think.setValue("pid", <?php echo ((isset($info["pid "]) && ($info["pid "] !== ""))?($info["pid "]): 0); ?>);
		//		Think.setValue("hide", <?php echo ((isset($info["hide "]) && ($info["hide "] !== ""))?($info["hide "]): 0); ?>);
		//		Think.setValue("is_dev", <?php echo ((isset($info["is_dev "]) && ($info["is_dev "] !== ""))?($info["is_dev "]): 0); ?>);
		//导航高亮
		highlight_subnav('<?php echo U('index');?>');
	</script>

	</body>

</html>