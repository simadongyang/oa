<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;
use Admin\Api\ApproApi;
use Think\Controller;
/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class UserController extends AdminController {

  
    //用于显示转化信息
    public function memberlist($list){
        //根据获得的信息查询相关的信息
        foreach($list as &$v){
            //将数字转为文字
            if($v['sex']==1){
                $v['sex']='男';
            }else{
                $v['sex']='女';
            }
            //将是否转正数字转为文字
            if($v['iscompletion']==1){
                $v['iscompletion']='正式';
            }else{
                $v['iscompletion']='试用';
            }
            //显示年龄
            $v['age']=date('Y-m-d')-$v['birthday'];
            //查询员工所属部门
            $dss=M('dss')->where('uid='.$v['uid'])->order('dssid desc')->find();            
            $department=M('department')->where('did='.$dss['did'])->find();            
            $v['dname']=$department['dname'];
            //查询员工所属岗位
            $station=M('station')->where('sid='.$dss['sid'])->find(); 
            $v['stationname']=$station['stationname'];
        }

        return $list;
    }
     //递归得到pid
    private function partment($did)
    {
        $data  = array();
        function sima($pid)
        {
            $res = M('Department')
            ->alias('d')
            ->field('d.did did ,p.did pid , d.dperson dp , p.dperson pd')
            ->join('ganen_department p on p.dpid = d.did ')
            ->where("d.did = $pid")
            ->find();
            $data =$res['']; 
            if(!empty($res['pid'])  )
            {
                $data=$data.','.sima($res['did']);
            }  
           return trim(trim($data,','),'0');
        }
         
        return sima($did);
    }
    //显示人事列表页
    public function index(){   
       
       
        //查询审批通过且未被删除的员工
        $field='uid,realname,sex,birthday,phone,iscompletion,entrytime';
        $map['status']  =  array('egt',0);        
        $map['isadopt'] =array('eq',1);
        if($denguid != 1){
            $map['uid'] = array('in',$uids);
        }
       // echo '<pre>';
        //分类显示
        //部门
        //
        //
        //$ids = $this->partment('2');
        //echo '<pre>';
       //$did = 2;
        function sima($did)
        {
             $res = M('Department')
                ->alias('d')
                ->field('d.did d_did ,p.did p_did,p.dpid p_dpid ')
                ->join('ganen_department p on p.dpid = d.did ')
                ->where("d.did = $did")
                ->select();
               
            foreach($res as $k=>$v)
            {
                $ids .= $v['p_did'].','.sima($v['p_did']);
                
               
            }
            return $ids;
         }
        // var_dump(sima(2));
        //echo $ids;
        //var_dump($res);die;
        $did = I('get.depar');
        if(!empty($did))
        {
            $this->assign('depar',$did);
            $ids = trim($did.','.sima($did),',');
            $uids= "p.did in ($ids)";

        }else{
            $uids = 1;
        }
        //var_dump($uids);die;
       // $uids = 1;
        //echo $did;
        //var_dump($uids);die;
        $pro = I('get.pro');
        //项目
        if(!empty($pro))
        {
            $this->assign('pro',$pro);
            $pro = 'd.projectid = '.$pro;
        }else{
            $pro = 1;
        }
        //岗位
        $sid = I('get.stat');
        if(!empty($sid))
        {
            $this->assign('stat',$sid);
            $sid = 'd.sid = ' .$sid;
        }else{
            $sid = 1;
        }
        $where = $sid.' and '.$pro.' and '.$uids;
       /* $res=M('Dss')->alias('d')
                    ->field('d.uid,d.realname,d.sex,d.birthday,d.phone,d.iscompletion,d.entrytime,d.status')
                    ->join('ganen_member m on d.uid = m.uid')
                    ->where(" $sid and $pro $uids and m.status = 0 and m.isadopt = 1")
                    ->select();*/
       // $list   = $this->lists('Member', $map,'','',$field);
       //var_dump($where);die;
       $res=M('Dss')->alias('d')
                    ->field('m.*,s.*,d.*,p.*')
                    ->join('ganen_member m on d.uid = m.uid')
                    ->join('ganen_department p on p.did = d.did')
                    ->join('ganen_station s on s.sid = d.sid')
                    ->group('d.uid')
                    ->where($where)->select();
       // echo '<pre>';
        //var_dump($res);die;
        //查询审批通过且未被删除的员工
        $field='uid,realname,sex,birthday,phone,iscompletion,entrytime,status';
        $map['status']  =   array('egt',0); 
        $map['isadopt']=array('eq',1);
            

        $list   = $this->lists('Member', $map,'','',$field);
        
        
        $this->assign('_list',$res);
        //根据获得的信息查询相关的信息
        $list=$this->memberlist($list);

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
        
        //构造新数组       
        $station=stationtrees($station);
        

        $this->assign('station',$station);

        //$this->assign('_list', $list);
        
        $this->display();
    }

    /**
     * 修改昵称初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updateNickname(){
        $nickname = M('Member')->getFieldByUid(UID, 'nickname');
        $this->assign('nickname', $nickname);
        $this->meta_title = '修改昵称';
        $this->display();
    }

    /**
     * 修改昵称提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitNickname(){
        //获取参数
        $nickname = I('post.nickname');
        $password = I('post.password');
        empty($nickname) && $this->error('请输入昵称');
        empty($password) && $this->error('请输入密码');

        //密码验证
        $User   =   new UserApi();
        $uid    =   $User->login(UID, $password, 4);
        ($uid == -2) && $this->error('密码不正确');

        $Member =   D('Member');
        $data   =   $Member->create(array('nickname'=>$nickname));
        if(!$data){
            $this->error($Member->getError());
        }

        $res = $Member->where(array('uid'=>$uid))->save($data);

        if($res){
            $user               =   session('user_auth');
            $user['username']   =   $data['nickname'];
            session('user_auth', $user);
            session('user_auth_sign', data_auth_sign($user));
            $this->success('修改昵称成功！');
        }else{
            $this->error('修改昵称失败！');
        }
    }

    /**
     * 修改密码初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updatePassword(){
        $this->meta_title = '修改密码';
        $this->display();
    }

    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitPassword(){
        //获取参数
        $password   =   I('post.old');
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if($data['password'] !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }

        $Api    =   new UserApi();
        $res    =   $Api->updateInfo(UID, $password, $data);
        if($res['status']){
            $this->success('修改密码成功！');
        }else{
            $this->error($res['info']);
        }
    }

    /**
     * 用户行为列表
     * @author huajie <banhuajie@163.com>
     */
    public function action(){
        //获取列表数据
        $Action =   M('Action')->where(array('status'=>array('gt',-1)));
        $list   =   $this->lists($Action);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = '用户行为';
        $this->display();
    }

    /**
     * 新增行为
     * @author huajie <banhuajie@163.com>
     */
    public function addAction(){
        $this->meta_title = '新增行为';
        $this->assign('data',null);
        $this->display('editaction');
    }

    /**
     * 编辑行为
     * @author huajie <banhuajie@163.com>
     */
    public function editAction(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Action')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑行为';
        $this->display();
    }

    /**
     * 更新行为
     * @author huajie <banhuajie@163.com>
     */
    public function saveAction(){
        $res = D('Action')->update();
        if(!$res){
            $this->error(D('Action')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['uid'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', $map );
                break;
            case 'resumeuser':
                $this->resume('Member', $map );
                break;
            case 'deleteuser':
                $this->delete('Member', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function add($username = '', $password = '', $repassword = '',$criticalname='', $email = ''){
    	

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
        
        //构造新数组       
        $station=stationtrees($station);
        

    	$this->assign('station',$station);


        //该部分是作用修改(开始1)

        //查询登录用户能否他人查看薪资
        
        $denguid=UID;
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

                    //用于查询数据对比是否进行了更改
                     $findd=M('Member')->where('uid='.$arr['uid'])->find();
                    if($findd){
                        $ew=congruent($findd,$arr);
                        if($ew==21){
                            $this->success('您未作出任何编辑！',U('index'));
                        }
                    }


                    $updat=M('Member')->where('uid='.$arr['uid'])->save($arr); 
                    if($updat){
                        $this->success('用户编辑成功！',U('index'));                    
                    } else {
                        $this->error('用户编辑失败！',U('add?id='.$arr['gonghao']));
                    } 

                    //作用修改(结束2)
                }else{

                    //注册信息

                    /* 检测密码 */
                    if($password != $repassword){
                        $this->error('密码和重复密码不一致！');
                    }

                        if(!$arr['IDnumber']){
                            $this->error('请输入身份证号码！');
                        }
                        if(strlen($arr['IDnumber'])!=18 || !preg_match('/^([\d]{17}[xX\d])$/',$arr['IDnumber'])){
                            $this->error('请输入18位身份证号码！');
                        }
                        if(!preg_match('/^(0|1)$/',$arr['iscompletion'])){
                            $this->error('请选择状态！');
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


                                                          
                       
                      /* $member=D('Member')->create();

                       if(!$member){
                            echo $member->getError();exit;

                           $this->error('用户添加失败！'.'8888');

                       }   */



                       
                        if(!M('Member')->add($arr)){
                            $this->error('用户添加失败！');
                        } else {
                            $this->success('用户基本信息添加成功请点击部门与薪资继续编写！',U('add?id='.$arr['uid']),5);
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
            $where=array('uid'=>$id,'status'=>0);
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
            if($arr['savestatus']=='岗位'){
                
                //如果没有填写基本信息而进行岗位信息的提交时，提示
                if(!$arr['uid']){
                     $this->error('请先填写基本信息，然后通过查看详情信息进行添加岗位等信息');
                }

                if($arr['did']==1){
                        $this->error('请选择部门！');
                }
                if($arr['sid']<=1){
                        $this->error('请选择岗位！');
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

                    if(!$arr['trysalary'] || !$arr['completionsalary']){
                            $this->error('请输入薪资！');
                    }
                    if(!preg_match('/^(\d+(.)?\d+)$/',$arr['trysalary']) || !preg_match('/^(\d+(.)?\d+)$/',$arr['completionsalary'])){
                        $this->error('薪资部分请输入数字！');
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
                   //查询员工现有的岗位项目薪资信息等
                   $dssnow=M('dss')->where('uid='.$arr['uid'].' and status=0')->select();

                   //判断岗位或着部门发生变化时。
                   if($num==0){//说明没有进行新增，然后判断部门或岗位是否发生变化
                        if($dssnow[0]['sid']!=$arr['sid'] || $dssnow[0]['did']!=$arr['did']){
                            M('dss')->where('uid='.$arr['uid'].' and status=0')->setField('status',1);
                            if($dssnow){
                                foreach($dssnow as $va){

                                    $va['sid']=$arr['sid'];
                                    $va['did']=$arr['did'];
                                    $va['caozuorenid']=$denguid;//登陆者的id，也就是操作人的id  
                                    unset($va['dssid']);
                                    M('dss')->add($va);                                
                                }
                            }else{
                                M('dss')->add($arr);
                            }
                            
                           
                        }
                   }
                   

                   //修改薪资部分
                  
                   $where=array('uid'=>$arr['uid']);
                   $salaryone=M('salarychange')->where($where)->order('uid desc')->find();

                   if($salaryone['trysalary']==$arr['trysalary'] && $salaryone['completionsalary']==$arr['completionsalary'] && $salaryone['jixiao']==$arr['jixiao']){

                     

                   }else{
                    $arr['caozuorenid']=$denguid;
                     $result=M('salarychange')->add($arr);
                   }
                  
                   //如果$count的值等于项目的个数，说明操作成功
                   if($num==$newnum || $result){

                         $Appro = new ApproApi;
                            $res = json_decode($Appro->appr_arr($arr['uid'],$arr['dperson'])) ;
                            if($res !=1)
                            {
                              
                                $this->error($res);die;
                            }

                        $this->success('用户编辑成功！',U('index'));                    
                    } else {
                        $this->error('用户编辑失败',U('add?id='.$arr['uid']));
                    } 
                }                
                
            }
        }

        $this->display();
    }

    /*
        显示我的员工信息
    */
    public function mestafflist(){
       
        //获得登录人的id
        
        $denguid=UID;
        $uids=mydown($denguid);

       $this->assign('list',$uids);
        //查询审批通过且未被删除的员工
        $field='uid,realname,sex,birthday,phone,iscompletion,entrytime';
        $map['status']  =  array('egt',0);        
        $map['isadopt'] =array('eq',1);
        if($denguid != 1){
            $map['uid'] = array('in',$uids);
        }
        

        $list   = $this->lists('Member', $map,'','',$field);

        $list=$this->memberlist($list);       

        $this->assign('_list',$list);

        $this->display();
    }

    /*
        显示我的员工信息的个人信息详情
    */
    public function mestaffinfo(){

        
        $uid=I('get.id');
        //查询审批通过且未被删除的员工
        //$field='uid,realname,sex,phone,qq,criticalname,criticalphone,birthday,nation,political,IDnumber,major,school,topeducation,matrimonial,nowliveplace,iscompletion,entrytime,completiontime';
        $map['status']  =   array('egt',0);
        $map['isadopt']=array('eq',1);
        $map['uid']=$uid;

        $findone=M('Member')->where($map)->find();
        if($findone['sex']==1){
            $findone['sex']='男';
        }else{
             $findone['sex']='女';
        }
        if($findone['iscompletion']==1){
            $findone['iscompletion']='正式';
        }else{
            $findone['iscompletion']='试用';
        }
        
        $dssone=M('dss')->where('uid='.$uid)->order('dssid desc')->find();
        $department=M('department')->where('did='.$dssone['did'])->find();            
        $findone['dname']=$department['dname'];
        //查询员工所属岗位
        $station=M('station')->where('sid='.$dssone['sid'])->find(); 
        $findone['stationname']=$station['stationname'];
            
            //查询员工薪资情况
        $salarychange=M('salarychange')->where('uid='.$uid)->order('said desc')->find();

        $findone['trysalary']=$salarychange['trysalary'];
        $findone['completionsalary']=$salarychange['completionsalary'];
        $findone['jixiao']=$salarychange['jixiao'];
        


        $this->assign('find',$findone);

        $this->display();
    }
    


    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }

}
