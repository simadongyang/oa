<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title><?php echo ($meta_title); ?>-内部办公系统</title>
		<link href="/oa/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
		<link rel="stylesheet" type="text/css" href="/oa/Public/static/semantic-ui/semantic.min.css" media="all">
		<link rel="stylesheet" type="text/css" href="/oa/Build/Admin/Style/style.css" media="all">
		<script type="text/javascript" src="/oa/Public/static/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="/oa/Public/static/semantic-ui/semantic.min.js"></script>
		<script type="text/javascript" src="/oa/Public/static/jquery.mousewheel.js"></script>
		
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
				

				
    
    <h1>基本信息</h1><hr>
    <form action="<?php echo U();?>" method="post" class="form-horizontal">    
        <div class="form-item">
        	<div>
	        	<label>姓名：</label><input type="text" class="text" name="username" value="<?php echo ($find["realname"]); ?>" >
	        	<input type="hidden" class="text input-large" name="password" value="123456">
	            <input type="hidden" class="text input-large" name="repassword" value="123456">
	      　 　           <label>工号：</label> <input type="text" class="text" name="gonghao" value="<?php echo ($find["uid"]); ?>" readonly>
            </div>
            <div>
	            <label>性别：</label>　　<input type="radio"  name="sex" value="1" <?php echo ($find["nan"]); ?>>男 　　<input type="radio"  name="sex" value="0" <?php echo ($find["nv"]); ?>>女　　　
	 　　　　　　  <label>联系电话：</label><input type="text" class="text" name="phone" value="<?php echo ($find["phone"]); ?>">
            </div>
            <div>
	            <label>QQ号：</label><input type="text" class="text" name="qq" value="<?php echo ($find["qq"]); ?>">
	            <label>紧急联系人姓名：</label><input type="text" class="text" name="criticalname" value="<?php echo ($find["criticalname"]); ?>">
            </div>
            <div>
	            <label>紧急联系人电话：</label><input type="text" class="text" name="criticalphone" value="<?php echo ($find["criticalphone"]); ?>">
	            <label>出生日期：</label><input type="text" class="text" name="birthday" value="<?php echo ($find["birthday"]); ?>">
            </div>
            <div>
	            <label>年龄：</label><input type="text" class="text" name="age" value="<?php echo ($find["age"]); ?>">
	            <label>政治面貌：</label><input type="text" class="text" name="political" value="<?php echo ($find["political"]); ?>">
            </div>
            <div>
	            <label>身份证号：</label><input type="text" class="text" name="IDnumber" value="<?php echo ($find["IDnumber"]); ?>">
	            <label>专业：</label><input type="text" class="text" name="major" value="<?php echo ($find["major"]); ?>">
            </div>
            <div>
	            <label>最高学历：</label><input type="text" class="text" name="topeducation" value="<?php echo ($find["topeducation"]); ?>">
	            <label>民族：</label><input type="text" class="text" name="nation" value="<?php echo ($find["nation"]); ?>">
            </div>
            <div>
	            <label>婚姻状况：</label>
	            	<select name="matrimonial">
	            		<option value="未婚" <?php if($find["matrimonial"] == '未婚'): ?>selected<?php endif; ?>>未婚</option>
	            		<option value="已婚" <?php if($find["matrimonial"] == '已婚'): ?>selected<?php endif; ?>>已婚</option>
	            		<option value="离异" <?php if($find["matrimonial"] == '离异'): ?>selected<?php endif; ?>>离异</option>
	            		<option value="丧偶" <?php if($find["matrimonial"] == '丧偶'): ?>selected<?php endif; ?>>丧偶</option>
	            	</select>
	            <label>籍贯：</label><input type="text" class="text" name="nativeplace" value="<?php echo ($find["nativeplace"]); ?>">
            </div>
            <div>
	            <label>现居地址：</label><input type="text" class="text" name="nowliveplace" value="<?php echo ($find["nowliveplace"]); ?>">
	            <label>爱好、特长：</label><input type="text" class="text" name="hobbies" value="<?php echo ($find["hobbies"]); ?>">
            </div>
            <div>
	            <label>入职时间：</label><input type="text" class="text" name="entrytime" value="<?php echo ($find["entrytime"]); ?>">
	            <label>试用期：</label><input type="text" class="text" name="trydate" value="<?php echo ($find["trydate"]); ?>">
            </div>
            <div>
	            <label>状态：</label>　　<input type="radio"  name="iscompletion" value="是" <?php echo ($find["shi"]); ?>>正式 　　<input type="radio"  name="iscompletion" value="否" <?php echo ($find["fou"]); ?>>试用　　　
	            <label>转正日期：</label><input type="text" class="text" name="completiontime" value="<?php echo ($find["completiontime"]); ?>">
                <input type="hidden" name="leixing1" value="" class='jibenxinxi'>
            </div>
        </div>
        <div class="form-item">            
            <button class="btn submit-btn ajax-post jiben" id="submit"  target-form="form-horizontal">确 定 编 辑</button>
            <button class="btn submit-btn ajax-post jiben1" id="submit" style='display:none'  type="submit" target-form="form-horizontal">确 定 编 辑</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
           
        </div>
    </form>

    <h1>岗位信息</h1><hr>

    <form action="<?php echo U();?>" method="post" class="form-horizontal">   
        <div class="form-item">
             <label>所属部门：</label>
            	<select name="did">
            		<?php if(is_array($department)): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["did"]); ?>" <?php if($vo["did"] == $sel[0]['did']): ?>selected<?php endif; ?>><?php echo ($vo["dname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            	</select>
             <label>所属岗位：</label>
            	<select name="sid" >
	            	<?php if(is_array($station)): $i = 0; $__LIST__ = $station;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option  value="<?php echo ($vo["sid"]); ?>" isstaff='<?php echo ($vo["isstaff"]); ?>' <?php if($vo["sid"] == $sel[0]['sid']): ?>selected<?php endif; ?>><?php echo ($vo["stationname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            	</select>
            <div class="project hidd" >
                <div val='1'>
                    <input type="hidden" name="prid1" value="<?php echo ($sel[0]['dssid']); ?>">
                    <label>所属项目：</label>
                        <?php if($sel[0]['projectid'] != ''): ?><input type="text" name="p1" value="<?php echo ($sel[0]['projectname']); ?>" readonly>
                            <label>分摊工资：</label><input type="text" class="text" name="ps1" readonly value="<?php echo ($sel[0]['projectsalary']); ?>"/>
                        <?php else: ?>
                        <select name="p1">
                        <?php if(is_array($project)): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"  ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        
                     <label>分摊工资：</label><input type="text" class="text" name="ps1" value=""/><?php endif; ?>
                     <a class='jia' val='+'>＋</a> <a class='jia' val='-'>－</a>                    

                 </div>       
                <?php if(is_array($sel)): $k = 0; $__LIST__ = $sel;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($k % 2 );++$k; if($k > 1): ?><div>
                    <input type="hidden" name="prid<?php echo ($k); ?>" value="<?php echo ($vos["dssid"]); ?>" >
		            <label>所属项目：</label>
		                <input type="text" name="p<?php echo ($k); ?>" value="<?php echo ($vos["projectname"]); ?>" readonly >
		            <label>分摊工资：</label><input type="text" class="text" name="ps<?php echo ($k); ?>" value="<?php echo ($vos["projectsalary"]); ?>" readonly/>                    
	             </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>	             	             
            </div>
        </div>        
        <div class="hidd" >
            <h1>薪资信息</h1><hr>
            <div class="form-item">             
                <div>
    	            <label>试用薪资：</label><input type="text" class="text" name="trysalary" value="<?php echo ($salarychange["trysalary"]); ?>">	            	
    	            <label>正式薪资：</label><input type="text" class="text" name="completionsalary" value="<?php echo ($salarychange["completionsalary"]); ?>"/>
                </div>
                <div>
                	<label>绩效考核：</label><textarea name="jixiao"><?php echo ($salarychange["jixiao"]); ?></textarea>
                </div>
            </div>
        </div>
        <div class="hidd" >
            <h1>审批流程</h1><hr>
            <div class="form-item">             
                
                    <p style="margin-top:30px;">
                        <?php echo ($appro["username"]); ?>[发起]
                                <?php if(is_array($appro["info"])): $i = 0; $__LIST__ = $appro["info"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; endforeach; endif; else: echo "" ;endif; ?> 
                    </p>
               
            </div>
        </div>
        <br />
        <input type="hidden" name="leixing2" value="" class="gangwei">
        <input type="hidden" name="uid" value="<?php echo ($sel[0]['uid']); ?>">
		  <input type="hidden" name="approid" value="<?php echo ($find['jid']); ?>">
        <!--  <input type="submit" value="提交">-->
        <div class="form-item">

            <button class="btn submit-btn ajax-post gang " id="submit"  target-form="form-horizontal">同意</button>
            <button class="btn submit-btn ajax-post gang1 " id="submit" type="submit" style="display:none;" target-form="form-horizontal">确 定</button>
           <?php
 echo "<a href='".U("Perappro/forbid?id=$find[uid]")."'>拒绝</a>"; ?>
        </div>
    </form>

			</div>
		</div>
		<!-- /内容区 -->
		<script type="text/javascript" src="/Build/Admin/Js/all.js"></script>
		<script type="text/javascript">
			(function() {
				var ThinkPHP = window.Think = {
					"ROOT": "/oa", //当前网站地址
					"APP": "/oa/index.php?s=", //当前项目地址
					"PUBLIC": "/oa/Public", //项目公共目录地址
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
        //导航高亮
        highlight_subnav('<?php echo U('User/index');?>');  


                $(function(){
                    //增减项目
                    var a;
                    var html;
                    var clicknum;
                    var first;
                    $('.jia').click(function(){
                        clicknum=$(this).parents('div').find('.project').children('div').length;
                        a=$(this).attr('val');
                        if(a=='+'){
                            clicknum += 1;                          
                            html="<div><input type='hidden' name='pridnew"+clicknum+"' value='-1'><label>所属项目：</label> <select name='p"+clicknum+"'><?php if(is_array($project)): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>"+
                                "<option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>"+
                             " <label>分摊工资：</label><input type='text' class='text' name='ps"+clicknum+"'/>";
                          
                            $(this).parents('div').find('.project').append(html);
                        }
                        if(a=='-'){
                            
                            first=$(this).parents('div').find('.project').children('div:last-child').attr('val');
                           
                            if(first!=1){
                                $(this).parents('div').find('.project').children('div:last-child').remove();    
                            }                   
                            
                        }
                    })
                    //如果是非普通员工岗位，人事将无权查看                   
                   var isst;
                    $('select[name=sid]').change(function(){
                        isst=$(this).find("option:selected").attr('isstaff');
                        if(isst==1){
                            $('.hidd').hide();
                        }else{
                            $('.hidd').show();
                        }
                    });
                   
                    //点击基本信息时                    
                    $('.jiben').click(function(){
                        
                        $('.jibenxinxi').attr("value",'基本信息');
                        
                        $('.jiben1').click();
                       
                    })
                    //点击岗位下面的确认时。
                    $('.gang').click(function(){

                        $('.gangwei').attr("value",'岗位');
                        $('.gang1').click();

                    });
                })
  </script>

	</body>

</html>