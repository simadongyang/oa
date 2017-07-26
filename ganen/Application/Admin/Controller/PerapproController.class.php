<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;


/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PerapproController extends AdminController {

     public function index(){
       
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
         $res=M('Appro')
          ->alias('a')
          ->group('update_time')
          ->field('m.username,a.*')
          ->join('ganen_ucenter_member m on a.aid = m.id')
         ->where("a.uid = $uid")->select();    

        $this->assign('_list', $res);
        $this->display();
    }
  
    public function waitbak(){
        die;
             //  xxx发起->第一个（通过-备注）->第二个（正在审批）->第三个
           $pids = $this -> partment($did);
            //当前用户的uid
            $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
            $res=M('Department')
            ->field('dperson')
             ->where("did = $did")
             ->find();
            // 判断是否为本部门的负责人
            $dp = $res['dperson'];
            if($uid != $dp)
            {
                $pids = $dp . ',' .$pids;
            }
            //生成 审批
            $arr_pids = explode(',',$pids);
            //发起人id
            $uid = 110;
            //审批名称
            $name = '入职';
            // 申请原因
            $reason = '新人入职';
            foreach($arr_pids as $k => $v)
            {
                $Appro = M("Appro"); // 实例化User对象
                $data['uid'] = $uid;
                $data['aids'] = $pids;
                $data['aid'] = $v;
                $data['name'] = $name;
                $data['reason'] = $reason;
                $data['time'] = time();
                $data['status'] = -2;
                $Appro->add($data);
            }
           
        //  var_dump($pids);die;
         // var_dump($res);die;

       // $find = [0,1];
    // $replace = ['q','e'];
      //  $arr = [3,2,4,1,0];
   // print_r(str_replace($find,$replace,$arr));die;

        //当前用户的uid
        //echo '<pre>';
        $map['id']  = array(' in',array('1','4','8'));
         $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //删除后把 aids 改为 -1
                    $str = -1;
                     //更改数据
                     //得到uid
                     $uid = $val['uid'];
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                
                }
                die;

        //判断是否为当前审批人
        foreach($res as $key=>$val)
        {

           $arr = explode(',',$val['aids']);
           if($arr[0] == $uid)
           {
            $nres[] = $res[$key];
           }
        }
        $this->assign('_list', $nres);
        $this->display();
    }
    public function wait(){
        //当前用户的uid
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
        //var_dump($uid);
        $res=M('Appro')
            ->alias('a')
            ->field('m.username,a.*')
            ->join('ganen_ucenter_member m on a.uid = m.id')
            ->where("a.aid=$uid and a.status = -2")
            ->select();
        //判断是否为当前审批人
        foreach($res as $key=>$val)
        {

           $arr = explode(',',$val['aids']);
           if($arr[0] == $uid)
           {
            $nres[] = $res[$key];
           }
        }
        $this->assign('_list', $nres);

        

        $this->display();
    }
     public function his(){
       // echo '<pre>';
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
         $res=M('Appro')
         ->alias('a')
         ->field('m.username,a.*')
         ->join('ganen_ucenter_member m on m.id = a.uid')
         ->where("a.aid = $uid")
         ->where('a.status = 1 or a.status=0')
         ->select();
         //var_dump($res);die;
        $this->assign('_list', $res);
        $this->display();
    }
    public function appro()
    {
        $this->display('User/update');
    }
    public function add(){
       if(IS_POST){
            $Menu = D('Project');
            $data = $Menu->create();
            if($data){
                $id = $Menu->add();
                if($id){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_menu', 'Menu', $id, UID);
                    $this->success('新增成功', Cookie('__forward__'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Menu->getError());
            }
        } else {
            
            $this->display();
        }
    }

    public function edit($id = 0)
    {
        if(IS_POST){
            $Pro = D('Project');
            $data = $Pro->create();
            if($data ){
                if($data['id']) // 编辑
                {
                    if($Pro->save()!== false){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_pro', 'Pro', $data['id'], UID);
                    $this->success('更新成功',U('Team/index'));
                    } else {
                        $this->error(json_encode($data));
                    }
                }else{ // 新增
                     $id = $Pro->add();
                    if($id){
                        // S('DB_CONFIG_DATA',null);
                        //记录行为
                        action_log('add_pro', 'Pro', $id, UID);
                        $this->success('新增成功', U('Team/index'));
                    } else {
                        $this->error('新增失败');
                    }
                }
                
            } else {
                $this->error($Pro->getError());
            }
        } else { //编辑页
            $info = array();
            /* 获取数据 */
            $info = M('Project')->field(true)->find($id);
            //$menus = M('Project')->field(true)->select();
           // $menus = D('Common/Tree')->toFormatTree($menus);

            //$menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            //$this->assign('Menus', $menus);
            if(false === $info){
                $this->error('获取后台菜单信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑';
            $this->display();
        }
    }
    /**
     * 删除项目
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Appro')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_appro', 'Appro', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
     /*
    * 查看员工信息或修改信息


    */
    public function info_mine()
    {
		 
        // $this->redirect('User/update', array('id' => 9));die;
        //查看员工基本信息
        $id=I('get.id');
        if(empty($id))  $this->error('查询失败！');
            //查询信息
            $find=M('Member')->where('uid='.$id)->find();
            //显示性别
            if($find['sex']==1){
                $find['nan']='checked';
            }else{
                $find['nv']='checked';
            }
            //计算年龄
            $find['age']=date('Y-m-d')-$find['birthday'];
            //显示是否转正
            if($find['iscompletion']=='是'){
                $find['shi']='checked';
            }else{
                $find['fou']='checked';
            }

            $this->assign('find',$find);           
      

        
      


        //显示员工的岗位及薪资信息
        
        
        //显示项目信息
        $project=M('project')->select();
        $this->assign('project',$project);
        
        //显示岗位信息
        $station=M('station')->select();
        $this->assign('station',$station);

        //查询员工岗位信息等
        $where=array('uid'=>$id,'status'=>1);
        $sel=M('dss')->where($where)->select();
        foreach($sel as &$v){
            //获取员工所在项目名称
            $find=M('project')->where('id='.$v['projectid'])->find();
            $v['projectname']=$find['name'];            
        }

        //显示所属部门信息及员工所属部门
        $department=M('department')->select();
        //$department=tree($department,$sel[0]['did']);
        $department=getTrees($department);
        $this->assign('department',$department);
        //查询员工薪资
        $salarychange=M('salarychange')->where('uid='.$id)->order('said desc')->find();
        $this->assign('salarychange',$salarychange);

        $this->assign('sel',$sel);
       

        //审批流程数据
        //审批信息id
        $id = I('id');
        //查询到被审批人id
         $uidres=M('Appro')
          ->field('uid')
         ->where("id = $id")->find();
         //var_dump($uidres);die;
         $uid = $uidres['uid'];
        // 审批人的信息
         $ares=M('Appro')
          ->alias('a')
          ->field('m.username,a.status')
          ->join('ganen_ucenter_member m on a.aid = m.id')
         ->where("a.uid = $uid")->select();
         // 被审批人的信息
         $res=M('Ucenter_member')
         ->field('username')
         ->where("id = $uid")->find();
        //被审批人的名字
        $data['username'] = $res['username'];
        //审批人的信息
        $data['info'] = $ares;
        // 去掉拒绝之后的
        foreach($data['info'] as $k => $v)
        {
            if($v['status'] == 0)
            {
              $data['info']=array_slice($data['info'],0,$k+1);
            }
        }
        $this ->assign('appro',$data);
        $this->display();
    }
     public function info(){
       // $this->redirect('User/update', array('id' => 9));die;
        //查看员工基本信息
		// 得到的是记录id
        $id=I('get.id');
		$res=M('Appro')->field('uid')->where('id='.$id)->find();
			//入职id
		$id = $res['uid'];
        if($id){
            //查询信息
            
			 $find=M('Member')->where('uid='.$id)->find();
            //显示性别
            if($find['sex']==1){
                $find['nan']='checked';
            }else{
                $find['nv']='checked';
            }
            //计算年龄
            $find['age']=date('Y-m-d')-$find['birthday'];
            //显示是否转正
            if($find['iscompletion']=='是'){
                $find['shi']='checked';
            }else{
                $find['fou']='checked';
            }
             //审批流程数据
            $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
            // 审批人的信息
             $ares=M('Appro')
              ->alias('a')
              ->field('m.username,a.status')
              ->join('ganen_ucenter_member m on a.aid = m.id')
             ->where("a.uid = $uid")->select();
             // 被审批人的信息
             $res=M('Ucenter_member')
             ->field('username')
             ->where("id = $uid")->find();
            //被审批人的名字
            $data['username'] = $res['username'];
            //审批人的信息
            $data['info'] = $ares;
            // 去掉拒绝之后的
            foreach($data['info'] as $k => $v)
            {
                if($v['status'] == 0)
                {
                  $data['info']=array_slice($data['info'],0,$k+1);
                }
            }
                $this ->assign('appro',$data);
				//保存 记录id
				$find['jid'] = I('id');
                $this->assign('find',$find);           
            } 
			//echo '<pre>';
        
        if(IS_POST){
            $arr=I('post.');
			//die('sdfsdfsd');
			
            //编辑员工基本信息
            if($arr['leixing1']=='基本信息'){ 
                            
                $updat=M('Member')->where('uid='.$arr['gonghao'])->save($arr); 
                if($updat){
                    $this->success('用户编辑成功！',U('index'));                    
                } else {
                    $this->error('用户编辑失败！',U('update?id='.$arr['gonghao']));
                } 
            }           

        } 


        
        //显示项目信息
        $project=M('project')->select();
        $this->assign('project',$project);
        
        //显示岗位信息
        $station=M('station')->select();
        $this->assign('station',$station);

        //查询员工岗位信息等
        $where=array('uid'=>$id,'status'=>1);
        $sel=M('dss')->where($where)->select();
        foreach($sel as &$v){
            //获取员工所在项目名称
            $find=M('project')->where('id='.$v['projectid'])->find();
            $v['projectname']=$find['name'];            
        }

        //显示所属部门信息及员工所属部门
        $department=M('department')->select();
        //$department=tree($department,$sel[0]['did']);
        $department=getTrees($department);
        $this->assign('department',$department);
        //查询员工薪资
        $salarychange=M('salarychange')->where('uid='.$id)->order('said desc')->find();
        $this->assign('salarychange',$salarychange);

        $this->assign('sel',$sel);
       


        if(IS_POST){
            $arr=I('post.');
				 
              $res = $this->resume($arr['approid']);
			  if($res) $this->success('操作成功！',U('Perappro/wait'),1); die;  
            //编辑员工的岗位、薪资等信息
            if($arr['leixing2']=='岗位'){
                //获取提交数组的个数并判断有几个项目
               $num=(count($arr)-31)/3;
               $newarr=array();
               for($i=1;$i<=$num;$i++){
                    $k=$i-1;
                    $newarr[$k]['status']=1;
                    $newarr[$k]['uid']=$arr['uid'];
                    $newarr[$k]['did']=$arr['did'];
                    $newarr[$k]['sid']=$arr['sid'];
                    $newarr[$k]['dssid']=$arr['prid'.$i]?$arr['prid'.$i]:$arr['pridnew'.$i];
                    $newarr[$k]['projectid']= $arr['p'.$i];
                    $newarr[$k]['projectsalary']=$arr['ps'.$i];
                    $idarr[$k]=$arr['prid'.$i];//用于判断
               }
                //获得员工项目原有的id是否还存在，
               $where=array('uid'=>$arr['uid'],'status'=>1);
               $sel=M('dss')->where($where)->select();
               foreach($sel as $val){
                    //如果不存在了说明该项目已经结束
                    if(!in_array($val['dssid'],$idarr)){
                        M('dss')->where('dssid='.$val['dssid'])->setField('status',0);
                    }                    
               }
               $countnum=0;
               foreach($newarr as $v){
                //如果dssid不为-1说明原有记录没有变动
                   if($v['dssid']!='-1'){                         
                        $countnum += 1;
                         
                   }else{//如果dissid为-1时表示添加
                        $find=M('dss')->order('dssid desc')->find();
                        $v['dssid']=$find['dssid']+1;
                         $ra=M('dss')->add($v);
                         if($ra){
                            $countnum += 1;
                         }
                   } 
               }
               //修改薪资部分
              
               $where=array('uid'=>$arr['uid']);
               $salaryone=M('salarychange')->where($where)->order('uid deac')->find();

               if($salaryone['trysalary']==$arr['trysalary'] && $salaryone['completionsalary']==$arr['completionsalary'] && $salaryone['jixiao']==$arr['jixiao']){

               }else{//如果有任何变动都会按新增处理
                 $result=M('salarychange')->add($arr);
               }
              //处理 审批状态
              //$this->success(I($arr['uid']),U('index'));
		
               //如果$count的值等于项目的个数，说明操作成功
               if($countnum==$num || $result){
                    $this->success('用户编辑成功！',U('index'));                    
                } else {
                    $this->error('用户编辑失败',U('update?id='.$arr['gonghao']));
                } 

               
            }
        }

        //审批流程数据
        //审批信息id
        $id = I('id');
        //查询到被审批人id
         $uidres=M('Appro')
          ->field('uid')
         ->where("id = $id")->find();
         //var_dump($uidres);die;
         $uid = $uidres['uid'];
        // 审批人的信息
         $ares=M('Appro')
          ->alias('a')
          ->field('m.username,a.status')
          ->join('ganen_ucenter_member m on a.aid = m.id')
         ->where("a.uid = $uid")->select();
         // 被审批人的信息
         $res=M('Ucenter_member')
         ->field('username')
         ->where("id = $uid")->find();
        //被审批人的名字
        $data['username'] = $res['username'];
        //审批人的信息
        $data['info'] = $ares;
        //echo '<pre>';
        //var_dump($data);die;
        $this ->assign('appro',$data);
        $this->display();
    }
    //同意审批  
    public function resume ($id)
    {
         $id = array_unique((array)$id);
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);

         // 删除 aids 里面的id
                $suid = $_SESSION['onethink_admin']['user_auth']['uid'];
                $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //查到要改的数据
                    $uid = $val['uid'];
                     $nres=M('Appro')->where("uid = $uid")->select();
                     $arr = explode(',',$nres[0]['aids']);
                     //删除第一个元素
                     if($arr[0] == $suid)
                     {
                        array_shift($arr);
                     }else{
                        return false;
                     }
                     
                     $str=implode(',',$arr);
                     //判断是否为空
                     if(empty($str)) $str = 0;
                     //更改数据
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                    //更新状态
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['status'] = 1;
                    $data['atime'] = time();
                    $Ures->where($map)->save($data); // 根据条件更新记录
                }
				return 1;
				//$this->success('用户编辑成功！',U('Perappro/wait'));   
               // $this->resume('Appro', $map );
    }
    public function info_(){
       // $this->redirect('User/update', array('id' => 9));die;
        //查看员工基本信息
        $id=I('get.id');
        if($id){
            //查询信息
            $find=M('Member')->where('uid='.$id)->find();
            //显示性别
            if($find['sex']==1){
                $find['nan']='checked';
            }else{
                $find['nv']='checked';
            }
            //计算年龄
            $find['age']=date('Y-m-d')-$find['birthday'];
            //显示是否转正
            if($find['iscompletion']=='是'){
                $find['shi']='checked';
            }else{
                $find['fou']='checked';
            }
             //审批流程数据
            $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
            // 审批人的信息
             $ares=M('Appro')
              ->alias('a')
              ->field('m.username,a.status')
              ->join('ganen_ucenter_member m on a.aid = m.id')
             ->where("a.uid = $uid")->select();
             // 被审批人的信息
             $res=M('Ucenter_member')
             ->field('username')
             ->where("id = $uid")->find();
            //被审批人的名字
            $data['username'] = $res['username'];
            //审批人的信息
            $data['info'] = $ares;
            // 去掉拒绝之后的
            foreach($data['info'] as $k => $v)
            {
                if($v['status'] == 0)
                {
                  $data['info']=array_slice($data['info'],0,$k+1);
                }
            }
                $this ->assign('appro',$data);

                $this->assign('find',$find);           
            } 

        
        if(IS_POST){
            $arr=I('post.');

            //编辑员工基本信息
            if($arr['leixing1']=='基本信息'){ 
                            
                $updat=M('Member')->where('uid='.$arr['gonghao'])->save($arr); 
                if($updat){
                    $this->success('用户编辑成功！',U('index'));                    
                } else {
                    $this->error('用户编辑失败！',U('update?id='.$arr['gonghao']));
                } 
            }           

        } 



        //显示员工的岗位及薪资信息
        $id=I('get.id');
        
        
        //显示项目信息
        $project=M('project')->select();
        $this->assign('project',$project);
        
        //显示岗位信息
        $station=M('station')->select();
        $this->assign('station',$station);

        //查询员工岗位信息等
        $where=array('uid'=>$id,'status'=>1);
        $sel=M('dss')->where($where)->select();
        foreach($sel as &$v){
            //获取员工所在项目名称
            $find=M('project')->where('id='.$v['projectid'])->find();
            $v['projectname']=$find['name'];            
        }

        //显示所属部门信息及员工所属部门
        $department=M('department')->select();
        //$department=tree($department,$sel[0]['did']);
        $department=getTrees($department);
        $this->assign('department',$department);
        //查询员工薪资
        $salarychange=M('salarychange')->where('uid='.$id)->order('said desc')->find();
        $this->assign('salarychange',$salarychange);

        $this->assign('sel',$sel);
       


        if(IS_POST){
            $arr=I('post.');
            //编辑员工的岗位、薪资等信息
            if($arr['leixing2']=='岗位'){
                //获取提交数组的个数并判断有几个项目
               $num=(count($arr)-31)/3;
               $newarr=array();
               for($i=1;$i<=$num;$i++){
                    $k=$i-1;
                    $newarr[$k]['status']=1;
                    $newarr[$k]['uid']=$arr['uid'];
                    $newarr[$k]['did']=$arr['did'];
                    $newarr[$k]['sid']=$arr['sid'];
                    $newarr[$k]['dssid']=$arr['prid'.$i]?$arr['prid'.$i]:$arr['pridnew'.$i];
                    $newarr[$k]['projectid']= $arr['p'.$i];
                    $newarr[$k]['projectsalary']=$arr['ps'.$i];
                    $idarr[$k]=$arr['prid'.$i];//用于判断
               }
                //获得员工项目原有的id是否还存在，
               $where=array('uid'=>$arr['uid'],'status'=>1);
               $sel=M('dss')->where($where)->select();
               foreach($sel as $val){
                    //如果不存在了说明该项目已经结束
                    if(!in_array($val['dssid'],$idarr)){
                        M('dss')->where('dssid='.$val['dssid'])->setField('status',0);
                    }                    
               }
               $countnum=0;
               foreach($newarr as $v){
                //如果dssid不为-1说明原有记录没有变动
                   if($v['dssid']!='-1'){                         
                        $countnum += 1;
                         
                   }else{//如果dissid为-1时表示添加
                        $find=M('dss')->order('dssid desc')->find();
                        $v['dssid']=$find['dssid']+1;
                         $ra=M('dss')->add($v);
                         if($ra){
                            $countnum += 1;
                         }
                   } 
               }
               //修改薪资部分
              
               $where=array('uid'=>$arr['uid']);
               $salaryone=M('salarychange')->where($where)->order('uid deac')->find();

               if($salaryone['trysalary']==$arr['trysalary'] && $salaryone['completionsalary']==$arr['completionsalary'] && $salaryone['jixiao']==$arr['jixiao']){

               }else{//如果有任何变动都会按新增处理
                 $result=M('salarychange')->add($arr);
               }
              //处理 审批状态
              //$this->success(I($arr['uid']),U('index'));
              $this->resume(I('post.'));
               //如果$count的值等于项目的个数，说明操作成功
               if($countnum==$num || $result){
                    $this->success('用户编辑成功！',U('index'));                    
                } else {
                    $this->error('用户编辑失败',U('update?id='.$arr['gonghao']));
                } 

               
            }
        }

        //审批流程数据
        //审批信息id
        $id = I('id');
        //查询到被审批人id
         $uidres=M('Appro')
          ->field('uid')
         ->where("id = $id")->find();
         //var_dump($uidres);die;
         $uid = $uidres['uid'];
        // 审批人的信息
         $ares=M('Appro')
          ->alias('a')
          ->field('m.username,a.status')
          ->join('ganen_ucenter_member m on a.aid = m.id')
         ->where("a.uid = $uid")->select();
         // 被审批人的信息
         $res=M('Ucenter_member')
         ->field('username')
         ->where("id = $uid")->find();
        //被审批人的名字
        $data['username'] = $res['username'];
        //审批人的信息
        $data['info'] = $ares;
        //echo '<pre>';
        //var_dump($data);die;
        $this ->assign('appro',$data);
        $this->display();
    }
    //同意审批
    public function resume_ ($id)
    {
         $id = array_unique((array)$id);
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
		$this->error($map['id']);
         // 删除 aids 里面的id
                $suid = $_SESSION['onethink_admin']['user_auth']['uid'];
                $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //查到要改的数据
                    $uid = $val['uid'];
                     $nres=M('Appro')->where("uid = $uid")->select();
                     $arr = explode(',',$nres[0]['aids']);
                     //删除第一个元素
                     if($arr[0] == $suid)
                     {
                        array_shift($arr);
                     }else{
                        return false;
                     }
                     
                     $str=implode(',',$arr);
                     //判断是否为空
                     if(empty($str)) $str = 0;
                     //更改数据
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                    //更新状态
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['status'] = 1;
                    $data['atime'] = time();
                    $Ures->where($map)->save($data); // 根据条件更新记录
                }
               // $this->resume('Appro', $map );
    }
    //拒绝审批
    public function forbid()
    {
            $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
         $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //删除后把 aids 改为 -1
                    $str = -1;
                     //更改数据
                     //得到uid
                    $uid = $val['uid'];
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                
                }
                 //更新状态
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['status'] = 0;
                    $data['atime'] = time();
                    $res = $Ures->where($map)->save($data); // 根据条件更新记录

                        if($res)
                        {
                             $this->success('已经拒绝审批',U('index')); 
                        }
                    
                    
    }
    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':

                 $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //删除后把 aids 改为 -1
                    $str = -1;
                     //更改数据
                     //得到uid
                    $uid = $val['uid'];
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                
                }
                $this->forbid('Appro', $map );
                break;
            case 'resumeuser':
               // 删除 aids 里面的id
                $suid = $_SESSION['onethink_admin']['user_auth']['uid'];
                $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //查到要改的数据
                    $uid = $val['uid'];
                     $nres=M('Appro')->where("uid = $uid")->select();
                     $arr = explode(',',$nres[0]['aids']);
                     //删除第一个元素
                     if($arr[0] == $suid)
                     {
                        array_shift($arr);
                     }else{
                        return false;
                     }
                     
                     $str=implode(',',$arr);
                     //判断是否为空
                     if(empty($str)) $str = 0;
                     //更改数据
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                
                }
                $this->resume('Appro', $map );
                break;
            case 'deleteuser':
                $this->delete("Appro", $map );
                break;
            default:
                $this->error('参数非法');
        }
    }
   


}
