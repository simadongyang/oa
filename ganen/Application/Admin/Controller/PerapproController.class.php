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
          ->field('m.realname,a.*')
          ->join('ganen_member m on a.aid = m.uid')
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
            ->field('m.realname,a.*')
            ->join('ganen_member m on a.uid = m.uid')
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
    //审批历史
     public function his(){
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
         $res=M('Appro')
         ->alias('a')
         ->field('m.realname,a.*')
         ->join('ganen_member m on m.uid = a.uid')
         ->where("a.aid = $uid")
         ->where('a.status = 1 or a.status=0')
         ->select();
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
 //得到用户id
      $id = I('id');
       $this->assign('id',$id);
      $res=M('Appro')->field('uid')->where("id = $id")->find();
      $id = $res['uid'];
     
      //var_dump($id);die;
            //显示所属部门信息
      //显示所属部门信息
      $department=M('department')->where('status>-1')->select();
        //$department=tree($department);
        $department=getTrees($department);
      $this->assign('department',$department);
      
      //显示项目信息
      $project=M('project')->where('status>-1')->select();
      $this->assign('project',$project);
      
      //显示岗位信息
      $station=M('station')->where('status>-1')->select();
      $this->assign('station',$station);


        //该部分是作用修改(开始1)

        //查询登录用户能否他人查看薪资
        $user = session('user_auth');
        $denguid=$user['uid'];
        $deng=M('Member')->where('uid='.$denguid)->find();
        $this->assign('look',$deng['looksalary']);

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
            if($find['iscompletion']=='1'){
                $find['shi']='checked';
            }else{
                $find['fou']='checked';
            }

            $this->assign('find',$find);  

        } 
      //作用修改(结束1)

        //作用修改(开始3)。部门岗位信息显示

        //显示员工的岗位及薪资信息
           //查询员工岗位信息等
            $where=array('uid'=>$id);
            $sel=M('dss')->where($where)->order('dssid desc')->select();

            //判断员工现在是否有所属项目
            if($sel[0]['projectid']){
                $where=array('uid'=>$id,'status'=>0);
                $sel=M('dss')->where($where)->order('dssid desc')->select();

                foreach($sel as &$v){
                    //获取员工所在项目名称
                    $find=M('project')->where('id='.$v['projectid'])->find();
                    $v['projectname']=$find['name'];            
                }
            }

            //根据员工的岗位等信息获取该岗位是否为普通岗
            $isstaff=M('station')->where('sid='.$sel[0]['sid'])->find();
            $this->assign('isstaff',$isstaff['isstaff']);        
            
            //查询员工薪资
            $salarychange=M('salarychange')->where('uid='.$id)->order('said desc')->find();
            $this->assign('salarychange',$salarychange);

            $this->assign('sel',$sel);
            //作用修改(结束3)。             
        
      


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
        $data = $this->appro_list($id);
        $this ->assign('appro',$data);
        $this->display();
    }
    public function appro_list($id)
    {
      //审批流程数据
        //审批信息id

        //查询到被审批人id
         $uidres=M('Appro')
          ->field('uid')
         ->where("id = $id")->find();
         //var_dump($uidres);die;
         $uid = $uidres['uid'];
        // 审批人的信息
         $ares=M('Appro')
          ->alias('a')
          ->field('m.realname,a.status,a.person')
          ->join('ganen_member m on a.aid = m.uid')
         ->where("a.uid = $uid")->select();
         
     
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

        return $data;
    }



     public function base_info()
     {

     }
     //待我审批详细信息
     public function info(){
      //得到用户id
      $id = I('id');
       $this->assign('id',$id);
      $res=M('Appro')->field('uid')->where("id = $id")->find();
      $id = $res['uid'];
      // 是否为普通岗位
    
      $res = M('Dss')->alias('d')
      ->field('s.isstaff')
      ->join('ganen_station s on s.sid = d.sid')
      ->where("d.uid = $id")
      ->find();
      // 如果没有默认为普通岗位
      $is_staff = $res['isstaff']?$res['isstaff']:0;
     
            //显示所属部门信息
      //显示所属部门信息
      $department=M('department')->where('status>-1')->select();
        //$department=tree($department);
        $department=getTrees($department);
      $this->assign('department',$department);
      
      //显示项目信息
      $project=M('project')->where('status>-1')->select();
      $this->assign('project',$project);
      
      //显示岗位信息
      $station=M('station')->where('status>-1')->select();
      $this->assign('station',$station);


        //该部分是作用修改(开始1)

        //查询登录用户能否他人查看薪资
        $user = session('user_auth');
        $denguid=$user['uid'];
        $deng=M('Member')->where('uid='.$denguid)->find();
        $this->assign('look',$deng['looksalary']);

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
            if($find['iscompletion']=='1'){
                $find['shi']='checked';
            }else{
                $find['fou']='checked';
            }

            $this->assign('find',$find);  

        } 
      //作用修改(结束1)


        //获取基本信息新增
        if(IS_POST){
          $arr=I('post.');

            
            if($arr['savestatus']=='基本信息'){                 

                    //作用修改(开始2).修改基本信息
                
                if($arr['uid']){//如果存在工号，说明是修改
                    $updat=M('Member')->where('uid='.$arr['uid'])->save($arr); 
                    if($updat){
                        $this->success('用户编辑成功！',U('index'));                    
                    } else {
                        $this->error('用户编辑失败！',U('update?id='.$arr['gonghao']));
                    } 

                    //作用修改(结束2)
                }else{

                    //注册信息

                    /* 检测密码 */
                    if($password != $repassword){
                        $this->error('密码和重复密码不一致！');
                    }

                    /* 调用注册接口注册用户 */
                    $User   =   new UserApi;
                    $email=rand(0,100000).rand(a,z).'@qq.com';
                    //获取身份证号码后4位
                    $hou4=substr($arr['IDnumber'],-4);
                    $username=$arr['username'].$hou4;
                    $password='123456';
                    $uid    =   $User->register($username, $password, $email);
                    if(0 < $uid){ //注册成功
                      $arr['realname']=$arr['username'];
                      $arr['uid']=$uid;
                      $arr['nickname']=$username;
                      $arr['status']=1;
                        $user = $arr;




                       
                        if(!M('Member')->add($user)){
                            $this->error('用户添加失败！');
                        } else {
                            $this->success('用户添加成功！',U('add?id='.$arr['uid']));
                        }
                    
                    } else { //注册失败，显示错误信息
                        $this->error($this->showRegError($uid));
                    }
                }
            }
        }


        //作用修改(开始3)。部门岗位信息显示

        //显示员工的岗位及薪资信息
           //查询员工岗位信息等
            $where=array('uid'=>$id);
            $sel=M('dss')->where($where)->order('dssid desc')->select();

            //判断员工现在是否有所属项目
            if($sel[0]['projectid']){
                $where=array('uid'=>$id,'status'=>0);
                $sel=M('dss')->where($where)->order('dssid desc')->select();

                foreach($sel as &$v){
                    //获取员工所在项目名称
                    $find=M('project')->where('id='.$v['projectid'])->find();
                    $v['projectname']=$find['name'];            
                }
            }

            //根据员工的岗位等信息获取该岗位是否为普通岗
            $isstaff=M('station')->where('sid='.$sel[0]['sid'])->find();
            $this->assign('isstaff',$isstaff['isstaff']);        
            
            //查询员工薪资
            $salarychange=M('salarychange')->where('uid='.$id)->order('said desc')->find();
            $this->assign('salarychange',$salarychange);

            $this->assign('sel',$sel);
            //作用修改(结束3)。             

        

        
        if(IS_POST){

            $arr=I('post.');
             if(empty($arr['is_first']))
             {
                //直接提交
                if(!empty($arr['id']))
                {
                  if($this->resume($arr['id']))
                  {
                     $this->success('您已同意审批！',U('wait'));   
                  }
                }
                $this->error('审批失败');
             }
            if($arr['savestatus']=='岗位'){
                
                //如果没有填写基本信息而进行岗位信息的提交时，提示
                if(!$arr['uid']){
                     $this->error('请先填写基本信息，然后通过查看详情信息进行添加岗位等信息');
                }
                //如果获得了uid说明是编辑修改信息
                if($arr['uid']){
                    //判断是否为普通岗
                    $findst=M('station')->where('sid='.$arr['sid'])->find();
                    if($findst['isstaff']==1 && $arr['panduan']==1){
                        $arr['caozuorenid']=$denguid;
                        $resul=M('dss')->add($arr);
                        if($resul){
                            $this->success('用户编辑成功！',U('add?id='.$arr['uid']));                    
                        } else {
                            $this->error('用户编辑失败',U('add?id='.$arr['uid']));
                        } 

                    }
                    //根据获的数组prid的中对应的值去查询是否进行了删除，值就是dssid

                    //获得员工项目原有的id是否还存在，
                   $where=array('uid'=>$arr['uid'],'status'=>0);
                   $sele=M('dss')->where($where)->select();
                   $count=0;
                   foreach($sele as $val){
                        //如果不存在了说明该项目已经结束
                        if(!in_array($val['dssid'],$arr['prid'])){
                           $sta= M('dss')->where('dssid='.$val['dssid'])->setField('status',1);
                           if($sta){
                                $count += 1;
                           }
                        }                    
                   }
                   
                    //根据获得的数组newdid的数量确定新增项目数量
                   $num=count($arr['newdid']);
                   $newarr=array();
                   for($i=1;$i<=$num;$i++){
                        $k=$i-1;
                        $newarr[$k]['status']=0;
                        $newarr[$k]['uid']=$arr['uid'];
                        $newarr[$k]['did']=$arr['did'];
                        $newarr[$k]['sid']=$arr['sid'];                        
                        $newarr[$k]['projectid']= $arr['newdid'][$k];//获取新增对应项目的id
                        $newarr[$k]['projectsalary']=$arr['newps1'][$k];//获取分摊的金额
                        $newarr[$k]['caozuorenid']=$denguid;//登陆者的id，也就是操作人的id                      
                   }
                   $newnum=0;
                   foreach($newarr as $v){
                        $ra=M('dss')->add($v);//进行添加操作
                        if($ra){
                            $newnum += 1;//每天加成功一次记录一次，用于后面的判断
                        }
                   }

   

                   //修改薪资部分
                  
                   $where=array('uid'=>$arr['uid']);
                   $salaryone=M('salarychange')->where($where)->order('uid desc')->find();

                   if(empty($arr['trysalary']) || empty($arr['completionsalary']) ){

                      $this->error('请正确填写薪资');die;

                   }else{
                    $arr['caozuorenid']=$denguid;
                     $result=M('salarychange')->add($arr);
                    // $this->resume(I('git.id'));
                     if(!empty($result) && !empty($arr['id']))
                        {
                          if($this->resume($arr['id']))
                          {
                             $this->success('您已同意审批！',U('wait'));   
                          }
                        }else{
                           $this->error('审批失败');die;

                        } 
                   }
                  
                   //如果$count的值等于项目的个数，说明操作成功
                   if($num==$newnum || $result){

                         $Appro = new ApproApi;
                            if($Appro->appr($arr['uid']) == -1)
                            {
                                $this->error('审批新增失败');die;
                            }

                        $this->success('用户编辑成功！',U('index'));                    
                    } else {
                        $this->error('用户编辑失败',U('add?id='.$arr['uid']));
                    } 
                }                
                
            }
        }
///////////////////////////////////////

        //审批流程数据
        //审批信息id
        $id = I('id');
        $data = $this->appro_list($id);
        $this ->assign('appro',$data);
        $id = 11;
        //判断是否为直接主管
         $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
        // echo $id;die;
        $res = D('Dss')
               ->alias('d')
               ->field('p.dperson')
               ->join('ganen_department p on d.did = p.did')
               ->where("d.uid =$id")
               ->find();
        // 是否可更改薪资
        //满足条件  1 非普通岗位 2 审批人为直属上级时 
        if(!empty($res['deperson']) && $res['deperson'] == $uid && $is_staff == 1)
        {
          $this->assign('is_first',1);
        }
        $this->display();
    }
    //同意审批  
    //$id 记录id
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
