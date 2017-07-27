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
					<!--<li>
						<a href="<?php echo U('User/updateNickname');?>">修改昵称</a>
					</li>-->
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
				

				
	<div id="userAdd">
		<div class="ui top attached tabular menu">
			<div class="item active" data-tab="tab-name">基本信息</div>
			<div class="item" data-tab="tab-name2">部门与薪资</div>
		</div>
		<div class="ui bottom attached segment tab active" data-tab="tab-name">
			<form action="<?php echo U();?>" method="post" class="form-horizontal ui form">
				<div class="fields">
					<div class="field">
						<label>工号</label>
						<input type="text" name="uid" value="<?php echo ($find["uid"]); ?>" readonly>
					</div>
					<div class="field">
						<label>姓名</label>
						<input type="text" name="username" value="<?php echo ($find["realname"]); ?>">
						<input type="hidden" class="text input-large" name="password" value="123456">
						<input type="hidden" class="text input-large" name="repassword" value="123456">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>性别</label>
						<div class="ui radio checkbox mr15">
							<input type="radio" name="sex" value="1" tabindex="0" class="hidden" <?php echo ($find["nan"]); ?> <?php if($sone["isstaff"] == 0): ?>checked=""<?php endif; ?>>
							<label>男</label>
						</div>
						<div class="ui radio checkbox mr15">
							<input type="radio" name="sex" value="0" tabindex="0" class="hidden" <?php echo ($find["nv"]); ?> <?php if($sone["isstaff"] != 0): ?>checked=""<?php endif; ?>>
							<label>女</label>
						</div>
					</div>
					<div class="field">
						<label>联系电话</label>
						<input type="text" name="phone" value="<?php echo ($find["phone"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>QQ号</label>
						<input type="text" name="qq" value="<?php echo ($find["qq"]); ?>">
					</div>
					<div class="field">
						<label>紧急联系人姓名</label>
						<input type="text" name="criticalname" value="<?php echo ($find["criticalname"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>紧急联系人电话</label>
						<input type="text" name="criticalphone" value="<?php echo ($find["criticalphone"]); ?>">
					</div>
					<div class="field">
						<label>出生日期</label>
						<input type="text" name="birthday" value="<?php echo ($find["birthday"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>民族</label>
						<input type="text" name="nation" value="<?php echo ($find["nation"]); ?>">
					</div>
					<div class="field">
						<label>政治面貌</label>
						<input type="text" name="political" value="<?php echo ($find["political"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>身份证号</label>
						<input type="text" name="IDnumber" value="<?php echo ($find["IDnumber"]); ?>">
					</div>
					<div class="field">
						<label>专业</label>
						<input type="text" name="major" value="<?php echo ($find["major"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>毕业学校</label>
						<input type="text" name="topeducation" value="<?php echo ($find["school"]); ?>">
					</div>
					<div class="field">
						<label>最高学历</label>
						<input type="text" name="topeducation" value="<?php echo ($find["topeducation"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>婚姻状况</label>
						<select class="ui dropdown dropdown-init" name="matrimonial">
							<option value="未婚" <?php if($find["matrimonial"] == '未婚'): ?>selected<?php endif; ?>>未婚</option>
							<option value="已婚" <?php if($find["matrimonial"] == '已婚'): ?>selected<?php endif; ?>>已婚</option>
							<option value="离异" <?php if($find["matrimonial"] == '离异'): ?>selected<?php endif; ?>>离异</option>
							<option value="丧偶" <?php if($find["matrimonial"] == '丧偶'): ?>selected<?php endif; ?>>丧偶</option>
						</select>
					</div>
					<div class="field">
						<label>籍贯</label>
						<input type="text" name="nativeplace" value="<?php echo ($find["nativeplace"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>现居地址</label>
						<input type="text" name="nowliveplace" value="<?php echo ($find["nowliveplace"]); ?>">
					</div>
					<div class="field">
						<label>爱好、特长</label>
						<input type="text" name="hobbies" value="<?php echo ($find["hobbies"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>入职时间</label>
						<input type="text" name="entrytime" value="<?php echo ($find["entrytime"]); ?>">
					</div>
					<div class="field">
						<label>试用期</label>
						<input type="text" name="trydate" value="<?php echo ($find["trydate"]); ?>">
					</div>
				</div>
				<div class="fields">
					<div class="field">
						<label>状态</label>
						<div class="ui radio checkbox mr15">
							<input type="radio" name="iscompletion" value="1" tabindex="0" class="hidden" <?php echo ($find["shi"]); ?>>
							<label>正式</label>
						</div>
						<div class="ui radio checkbox mr15">
							<input type="radio" name="iscompletion" value="0" tabindex="0" class="hidden" <?php echo ($find["fou"]); ?>>
							<label>试用</label>
						</div>
					</div>
					<div class="field">
						<label>转正日期</label>
						<input type="text" name="completiontime" value="<?php echo ($find["completiontime"]); ?>">
					</div>
				</div>
				<input type="hidden" name="savestatus" value="基本信息">
				<div class="form-item">
					<button class="ui blue button submit-btn ajax-post mr20" type="submit" id="submit" target-form="form-horizontal">确 定</button>
					<button class="ui blue button btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
				</div>
			</form>
		</div>
		<div class="ui bottom attached segment tab" data-tab="tab-name2">
			<form action="<?php echo U();?>" method="post" class="form-horizontal2 ui form">
				<div class="fields">
					<div class="field">
						<label>所属部门</label>
						<select class="ui dropdown dropdown-init" name="did">
							<?php if(is_array($department)): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["did"]); ?>" <?php if(($vo["did"]) == $sel[0]['did']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["dname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</div>
					<div class="field">
						<label>所属岗位</label>
						<!--
							isstaff = 1 则是非普通员工，隐藏薪资内容
						-->
						<!--<select class="ui dropdown" name="sid" id="station">
							<?php if(is_array($station)): $i = 0; $__LIST__ = $station;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["sid"]); ?>" isstaff='<?php echo ($vo["isstaff"]); ?>' <?php if($vo["sid"] == $sel[0]['sid']): ?>selected<?php endif; ?>><?php echo ($vo["stationname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>-->
						<div class="ui selection dropdown" id="station">
							<input type="hidden" name="sid" value="<?php echo ($sel[0]['sid']); ?>">
							<i class="dropdown icon"></i>
							<div class="default text"></div>
							<div class="menu">
								<?php if(is_array($station)): $i = 0; $__LIST__ = $station;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item" data-value="<?php echo ($vo["sid"]); ?>" isstaff='<?php echo ($vo["isstaff"]); ?>'><?php echo ($vo["stationname"]); ?></div><?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php if(($look == 0 and $isstaff == 0) or ($look == 1)): if(empty($sel)): ?><div class="fields fields-xm fields-hide">
							<div class="field">
								<label>所属项目</label>
								<select class="ui dropdown dropdown-init" name="newdid[]">
									<?php if(is_array($project)): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</div>
							<div class="field">
								<label>分摊工资</label>
								<input type="text" name="newps1[]" value="" />
							</div>
							<div class="field field-icon">
								<i class="minus square icon"></i>
							</div>
						</div>
					<?php else: ?>
						<?php if(is_array($sel)): $k = 0; $__LIST__ = $sel;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($k % 2 );++$k;?><div class="fields fields-xm fields-hide">
								<div class="field">
									<label>所属项目</label>
									<input type="text" name="p<?php echo ($k); ?>" value="<?php echo ($vos["projectname"]); ?>" readonly>
									<input type="hidden" name="prid[]" value="<?php echo ($vos["dssid"]); ?>">
								</div>
								<div class="field">
									<label>分摊工资</label>
									<input type="text" name="ps<?php echo ($k); ?>" value="<?php echo ($vos["projectsalary"]); ?>" readonly/>
								</div>
								<div class="field field-icon">
									<i class="minus square icon"></i>
								</div>
							</div><?php endforeach; endif; else: echo "" ;endif; endif; ?> 
					<input type="button" class="ui blue button add mb20 fields-hide" value="添加所属项目" />
					<div class="fields fields-xm fields-hide">
						<div class="field">
							<label>试用薪资</label>
							<input type="text" name="trysalary" value="<?php echo ($salarychange["trysalary"]); ?>">
						</div>
						<div class="field">
							<label>正式薪资</label>
							<input type="text" name="completionsalary" value="<?php echo ($salarychange["completionsalary"]); ?>" />
						</div>
					</div>
					<div class="fields fields-xm fields-hide">
						<div class="field field-textarea">
							<label>绩效考核</label>
							<textarea name="jixiao" rows="3"><?php echo ($salarychange["jixiao"]); ?></textarea>
						</div>
					</div><?php endif; ?>
				<input type="hidden" name="savestatus" value="岗位">
				<input type="hidden" name="uid" value="<?php echo ($find["uid"]); ?>">
				<div class="form-item">
					<button class="ui blue button submit-btn ajax-post mr20" type="submit" id="submit" target-form="form-horizontal2">确 定</button>
					<button class="ui blue button btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
				</div>
			</form>
		</div>
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
			$('.menu .item').tab();
			$('.ui.radio.checkbox').checkbox();
			$('.dropdown-init').dropdown();
			$("#station").dropdown({
				onChange: function(value, text, $selectedItem) {
					if($selectedItem.attr("isstaff") == 1 && !<?php echo ($look); ?>){
						$(".fields-hide").hide();
					}else{
						$(".fields-hide").show();
					}
				}
			});
			//添加项目
			$(".button.add").click(function(){
				var html = '<div class="fields fields-xm fields-hide">' + 
								'<div class="field">' + 
									'<label>所属项目</label>' + 
									'<select class="ui dropdown dropdown-init" name="newdid[]">' + 
					                    '<?php if(is_array($project)): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>' + 
					                    	'<option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option>' + 
					                    '<?php endforeach; endif; else: echo "" ;endif; ?>' + 
					                '</select>' +
								'</div>' + 
								'<div class="field">' + 
									'<label>分摊工资</label>' + 
									'<input type="text" name="newps1[]" value="" />' + 
								'</div>' + 
								'<div class="field field-icon">' + 
									'<i class="minus square icon"></i>' + 
								'</div>' + 
							'</div>';
				$(this).before(html);
				$('.dropdown-init').dropdown();
			})
			//删除项目
			$(".form-horizontal2").delegate(".minus.icon","click",function(){
				if($(".fields-xm").length > 1){
					$(this).closest(".fields").remove();
				}else{
					alert("至少保留一个所属项目");
				}
			})
		})
		//导航高亮
		highlight_subnav('<?php echo U('User/index');?>');
	</script>

	</body>

</html>