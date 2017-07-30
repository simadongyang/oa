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
   /* public function memberlist($list){
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
    }*/
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
       
       
       /* //查询审批通过且未被删除的员工
        $field='uid,realname,sex,birthday,phone,iscompletion,entrytime';
        $map['status']  =  array('egt',0);        
        $map['isadopt'] =array('eq',1);
        if($denguid != 1){
            $map['uid'] = array('in',$uids);//$uids ?
        }
       */
       //将部门id重组
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
         //var_dump(sima(2));
        //echo $ids;
        //var_dump($res);die;
         //搜索部门
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
        //搜索项目
        $pro = I('get.pro');
        //项目
        if(!empty($pro))
        {
            $this->assign('pro',$pro);
            $pro = 'd.projectid = '.$pro;
        }else{
            $pro = 1;
        }
        //搜索岗位
        $sid = I('get.stat');
        if(!empty($sid))
        {
            $this->assign('stat',$sid);
            $sid = 'd.sid = ' .$sid;
        }else{
            $sid = 1;
        }
        //查到所有的有效dss id 
        $dss = M('Dss')->field('max(dssid) m_dssid')->group('uid')->order('uid desc')->select();
        //echo '<pre>';
        //得到id数组
        $ids = array_column($dss, 'm_dssid');
        //将数组变为字符串
        $ids = implode($ids,',');
        if(!empty($ids))
        {
            $dssid = "( $ids )";
        }else{
            $dssid = 1;
        }        
        //组装where 语句
        $where = $sid.' and '.$pro.' and '.$uids.' and m.isadopt = 1 and s.status > 0 and m.status > -1 '.' and d.dssid in '.$dssid;



        $dss = D('Dss');
        $res=$dss->alias('d')
                    ->field('m.uid')
                    ->join('left join ganen_member m on d.uid = m.uid')
                    ->join('left join ganen_department p on p.did = d.did')
                    ->join('left join ganen_station s on s.sid = d.sid')
                    ->order('d.dssid desc')
                    ->where($where);
        //得到总数
        $data = $res;
        $count = count($data->select());       
        $page = new \Think\Page($count,15);  
            
        $page->setConfig('first','首页');
        $page->setConfig('last','末页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        $show = $page->show();

       // 查找到匹配的信息
       $res=$dss->alias('d')
                    ->field('m.uid,m.status as m_status,m.realname,m.sex,m.birthday,m.phone,m.iscompletion,m.entrytime,s.stationname,d.*,p.*')
                    ->join('left join ganen_member m on d.uid = m.uid')
                    ->join('left join ganen_department p on p.did = d.did')
                    ->join('left join ganen_station s on s.sid = d.sid')
                    ->order('d.dssid desc')
                    ->where($where)
                    ->limit($page->firstRow.','.$page->listRows)
                    ->select();
//echo $dss->getLastSql();
        //查询审批通过且未被删除的员工
      /*  $field='uid,realname,sex,birthday,phone,iscompletion,entrytime,status';
        $map['status']  =   array('egt',0); 
        $map['isadopt']=array('eq',1);
            

        $list   = $this->lists('Member', $map,'','',$field);*/
      /*  echo '<pre>';
        var_dump($res);*/
        $this->assign('_page',$show);
        $this->assign('_list',$res);
        //根据获得的信息查询相关的信息
       // $list=$this->memberlist($list);

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

//基本信息验证
public function jibenyanzheng($arr){

        if(!$arr['username']){
            $this->error('请输入员工姓名！');
        }
        if(mb_strlen($arr['username'],"utf-8")>12){
            $this->error('员工姓名长度须在12字以内！');
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
        if($arr['did']==1){
            $this->error('请选择部门！');
        }
        if($arr['sid']<=1){
            $this->error('请选择岗位！');
        }
    }

    //薪资信息验证
    public function xinziyanzheng($arr){

        if($arr['trysalary']==0 || $arr['completionsalary']==0){
            $this->error('请输入薪资！');
        }
        if(!preg_match('/^(\d+(.)?\d+)$/',$arr['trysalary']) || !preg_match('/^(\d+(.)?\d+)$/',$arr['completionsalary'])){
            $this->error('薪资部分请输入数字！');
        }

    }
    //显示部门岗位项目选择项
    public function dps(){
    //显示所属部门信息
        $department=M('department')->where('status>-1')->select();
        //$department=tree($department);
        $department=getTrees($department);

        $depar = $this->get_stat($department);
//      $depar = json_encode($depar);
        $this->assign('department',$depar);

        
        
        //显示岗位信息
        $station=M('station')->where('status>-1')->select();        
        //构造新数组       
        $station=stationtrees($station);  
  //      echo '<pre>';
//var_dump($depar);
        $this->assign('station',$station);


    }


    //传入部门信息 返回部门和岗位信息
    public function get_stat($depar)
    {
        $arr = array();
        if(empty($depar)) return 1;

        foreach($depar as $k => $v)
        {
            $sid = $v['sid'];
            if(!empty($sid))
            {
                //组建where 语句
                $where = " sid in ( $sid ) and status > -1 ";
                $stat = M('station')->field('sid,stationname')->where($where)->select();
                
                if(!empty($stat))
                {
                    foreach($stat as $key => $val)
                    {
                        $arr[$val['sid']] = $val['stationname'];//构建一维数组
                    }
                    $depar[$k]['sid'] =  $arr;//构建三维数组
                    $arr=array();
                }else{
                    $depar[$k]['sid'] = 0;
                }    
            }else{

                $depar[$k]['sid'] = 0;
            }
        }
        return $depar;
    }




    public function add($username = '', $password = '', $repassword = '',$criticalname='', $email = ''){

    	
        //显示部门岗位选项
        $this->dps(); 


        //添加员工信息
        if(IS_POST){
             $arr=I('post.');
            //添加员工信息
            if(!$arr['uid']){
               
                $this->jibenyanzheng($arr);
               
                //获取身份证号码后4位
                $hou4=substr($arr['IDnumber'],-4);
                $ad['username']=$arr['username'].$hou4;                
                $ad['password']=123456;
                $ad['email']=rand(0,100000).rand(99,9999).'@qq.com';
                //注册到员工中心，用于登录
                $User   =   new UserApi();                
                $addone['id']=$User->register($ad['username'],$ad['password'],$ad['email']);

                //注册二级密码
                $pass['password']=password_md5($ad['password']);                
                $pass['uid']=$addone['id'];
                M('secondary_password')->add($pass);       

                //根据员工中心注册成功返回员工id
                if($addone['id']>0){                   
                    
                    $arr['realname']=$arr['username'];
                    $arr['uid']=$addone['id'];
                    $arr['nickname']=$arr['username'];                        
                    $user = $arr;
                    //添加员工基本信息
                    $add=M('Member')->add($user); 
                    if($add){                            
                        //添加部门岗位信息                            
                        $sele=M('dss')->add($arr);
                        if($sele){
                            //审批
                            $Appro = new ApproApi;
                                $res = json_decode($Appro->appr_arr($arr['uid'],$arr['dperson'])) ;
                                if($res !=1)
                                {
                                  
                                    $this->error($res);die;
                                }

                             $this->success('员工添加成功！',U('index'));die;                    
                        } else {
                            M('ucenter_member')->where("id='%d'",$addone['id'])->delete();

                            $this->error('员工添加失败！');die;
                        }               
                    }else{
                        $re=M('ucenter_member')->where("id='%d'",$addone['id'])->delete();
                        if($re){
                            $this->error('员工添加失败！');die;
                        }                         
                    }             
                }elseif($addone['id']==0){
                    $this->error('该员工已存在！');die;  
                }  
            }
        }

        $this->display();
    }

    //修改员工信息
    public function edit(){

        //显示部门岗位
         $this->dps(); 
      //显示员工信息
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
        } 
        

        //是否有权限显示查看薪资
       /* $rule=$this->rule();

        echo $rule;
        if(UID==1){
            $rule=1;
        }
        $this->assign('rule',$rule);*/

        
        $denguid=UID;
        $deng=M('Member')->where('uid='.$denguid)->find();
        $this->assign('look',$deng['looksalary']);
        
        if(IS_POST){
        	$arr=I('post.');                      
            $this->jibenyanzheng($arr);
                
             //获取基本信息新增   
            if($arr['uid']){//如果存在工号，说明是修改
                $arr['realname']=$arr['username'];
                //用于查询数据对比是否进行了更改
                $findd=M('Member')->where('uid='.$arr['uid'])->find();
                if($findd){
                    $ew=congruent($findd,$arr);                    
                    if($ew!=22){
                       // $this->jibenyanzheng($arr);
                        $updat=M('Member')->where('uid='.$arr['uid'])->save($arr); 
                        if(!$updat){                            
                            $this->error('用户编辑失败！',U('edit?id='.$arr['uid']));
                        }else{

                           $jichange=ture;//基本信息改变成功
                        }

                    }else{
                        $jichange=false;//基本信息部分没有改变
                    }

                   

                   //判断部门岗位是否发生了变化
                    $findst=M('dss')->where("uid='%d'",$arr['uid'])->order('dssid desc')->find();
                    $ds['sid']=$arr['sid'];
                    $ds['uid']=$arr['uid'];
                    $ds['did']=$arr['did'];
                    $st=congruent($findst,$ds); 
                    if($st!=3){
                       
                        //如果员工岗位发生变化，需要将变化前的所在项目信息全部复制一边
                        //查询员工现有的岗位信息等
                        $dssnow=M('dss')->where('uid='.$arr['uid'].' and status=0')->select();

                        if($dssnow){
                            //修改原有项目信息状态
                            M('dss')->where('uid='.$arr['uid'].' and status=0')->setField('status',1);
                        }
                        $countn=0;
                        foreach($dssnow as $value){
                            $value['sid']=$arr['sid'];
                            $value['did']=$arr['did'];
                            $value['uid']=$arr['uid'];
                            $value['caozuorenid']=UID;
                            unset($value['dssid']);                             
                           $resul=M('dss')->add($value);                 
                           if($resul){
                                $countn += 1;
                           }
                        }

                        if($countn==count($dssnow)){

                            $gangchange=ture;                                                              
                                
                        }else{
                           
                            $this->error('用户编辑失败！',U('edit?id='.$arr['uid']));  
                        }                            
                        
                    }else{
                         $gangchange=0;                            
                    }

                    if(!$gangchange && !$jichange){
                        $this->success('您未作出任何修改！'.$countn);
                    }else{
                        $this->success('用户编辑成功！',U('index')); 
                    }
                 
                }
            }
        }
        $this->display();
    }

    public function showselfsalary($uid){
        //显示项目信息
                $project=M('project')->where('status>-1')->select();
                $this->assign('project',$project);

                //查询项目情况
                $where['d.status']=array('eq',0);
                $where['uid']=$uid;
                $find=M('dss')->where('uid='.$uid.' and status=0')->order('dssid desc')->find();
                if($find['projectid']){              

                    $projectslef=M('dss')
                    ->alias('d')
                    ->join('ganen_project as p ON p.id=d.projectid')
                    ->field('p.name,d.projectsalary,d.projectid,d.dssid,d.uid,d.sid,d.did')
                    ->where($where)
                    ->order('d.dssid desc')
                    ->select();
                }else{
                    $projectslef[]=$find;
                }
                //echo '<pre>';
                //var_dump($find);
                $this->assign('sel',$projectslef);

                //查询薪资情况
                $where=array('uid'=>$uid,'status'=>array('eq',0));
                $salaryone=M('salarychange')->where($where)->order('said desc')->find();
                $this->assign('salarychange',$salaryone);
    }

    //验证二级密码查看薪资
    public function salary(){
            if(IS_GET){
                $uid=I('get.uid');         
                

                if($uid && $uid==$_SESSION['biao']){
                    $this->showselfsalary($uid);
                    $this->assign('adduid',$uid);
                   
                }else{
                     $this->assign('error',1);
                } 
            }else{
                $this->assign('error',1);
            }  
             $this->display();
            
    }

    //编辑薪资信息
    public function salarychange(){
        if(IS_POST){
            $arr=I('post.');
            $this->xinziyanzheng($arr);
            //项目减少
            //根据获的数组prid的中对应的值去查询是否进行了删除，值就是dssid
           //获得员工项目原有的id是否还存在，
           $where=array('uid'=>$arr['uid'],'status'=>0);
           $sele=M('dss')->where($where)->order('dssid desc')->select();
           $count=0;
           if($sele[0]['projectid']){
               foreach($sele as $val){
                    //如果不存在了说明该项目已经结束
                    if(!in_array($val['dssid'],$arr['prid'])){
                       $sta= M('dss')->where('dssid='.$val['dssid'])->setField('status',1);
                       if($sta){
                            $count += 1;
                       }
                    }                    
               }

               //如果项目全部删除时（即没有获得项目数组是空），需要添加一条记录
               if(empty($arr['prid'])){
                    $arr['status']=0;
                    $arr['caozuorenid']=UID;
                   M('dss')->add($arr);
                   unset($arr['status']); 
               } 
           }                

        //项目增加
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
                $newarr[$k]['caozuorenid']=UID;//登陆者的id，也就是操作人的id                      
           }
           $newnum=0;
           foreach($newarr as $v){
                $ra=M('dss')->add($v);//进行添加操作
                if($ra){
                    $newnum += 1;//每天加成功一次记录一次，用于后面的判断
                }
           }

            //修改薪资部分 
            //查看现有薪资            
            $where=array('uid'=>$arr['uid'],'status'=>array('eq',0));
            $salaryone=M('salarychange')->where($where)->order('said desc')->find();
            $arr['caozuorenid']=UID;
            if($salaryone){
                $ew=congruent($salaryone,$arr);
                if($ew!=5){
                   // $this->jibenyanzheng($arr);
                    M('salarychange')->where($where)->setField('status',1);
                    $updat=M('salarychange')->add($arr);
                    if(!$updat){                            
                        $gangwei=0;//薪资变化失败
                    }else{
                       $gangwei=ture;//薪资变化成功
                    }
                }else{
                    $weibian=ture;
                }
            }else{
                
                $updat=M('salarychange')->add($arr);
                if($updat){
                    $xinzi=ture;
                }else{
                    $xinzi=0;
                }
            }

            if($newnum==0 && $count==0 && $weibian){
                $this->error('您未作出任何修改！',U('index'));
            }
            if($xinzi || $gangwei || $newnum || $count){
                 $this->success('用户编辑成功！',U('index')); 
                
            }else{
                $this->error('用户编辑失败！',U('salary?id='.$arr['uid']));
            }                           
        }
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

    //修改二级密码
    public function updatetwopassword(){
        if(IS_POST){
            $password   =   I('post.old');
            empty($password) && $this->error('请输入原密码');

            $data['password'] = I('post.password');
            empty($data['password']) && $this->error('请输入新密码');
            $match=preg_match_all('/^\d{6}$/', $data['password']);
            if(!$match){
                $this->error('密码必须是6位纯数字!');
            }
            $repassword = I('post.repassword');
            empty($repassword) && $this->error('请输入确认密码');

            if($data['password'] !== $repassword){
                $this->error('您输入的新密码与确认密码不一致');
            }
            //核对数据库数据
            $where['uid']=UID;
            $where['password']=password_md5($password);
            $where['status']=array('eq',1);
            $find=M('secondary_password')->where($where)->find();
            //如果核对成功
            if($find){  
                //进行修改操作                         
                $res=M('secondary_password')->where('uid='.UID)->setField('password',password_md5($repassword));
                if($res || password_md5($repassword)==$find['password']){
                    $this->success('您已修改密码成功 ！');
                }else{
                    $this->error('密码修改失败 ！'); 
                }
            }else{
               $this->error('原密码不正确'); 
            }

        }
        $this->display();
    }

    //查看薪资权限的获取
    public function rule(){
        //获取查看薪资信息对应的权限的id
            $where['name']='Admin/User/salary';
            $where['status']=array('eq',1);
            $auth_ruleid=M('auth_rule')->field('id')->where($where)->find();

            //获取员工id所在的组
            $userwhere['uid']=UID;

            $file=M('auth_group_access')->alias('ac')->join('ganen_auth_group as ag ON ag.id=ac.group_id')->field('ag.rules')->where($userwhere)->select();

            foreach($file as $val){
                //如果在数组中说明有查看薪资权限
                
                if(strpos($val['rules'],$auth_ruleid['id'])){
                    return 1;
                }else{
                    return 0;
                }
            }

    }
    //验证密码
	public function verify(){
        if(IS_POST){
            $shuju=I('post.');
            
            //$shuju['uid']=240;
            //$shuju['password']=123456;
            
            $result=$this->rule();

            if($result){

                 $p2 = password2(UID,$shuju['password']);                
               
                if($p2>0){

                   //构造数组 
                   $fan['uid']=$shuju['uid'];                   
                   $_SESSION['biao']=$shuju['uid'];                               
                    $arr=array(
                        'status'=>1,//成功
                        'data'=>$fan,
                        'msg'=>''
                        );             
                    die(json_encode($arr));

                }elseif($p2=='-2'){
                    $arr=array(
                        'status'=>0,//失败
                        'data'=>array(),
                        'msg'=>'密码错误'
                        );             
                    die(json_encode($arr));
                        
                }else{
                    $arr=array(
                        'status'=>0,//失败
                        'data'=>array(),
                        'msg'=>'用户不存在或被禁用'
                        );             
                    die(json_encode($arr));
                        
                }
            }else{
                $arr=array(
                    'status'=>0,//失败
                    'data'=>$file,
                    'msg'=>'您没有查看权限'
                    );             
                die(json_encode($arr));
            }
        }else{
           $arr=array(
                'status'=>0,//失败
                'data'=>array(),
                'msg'=>'您没有传值'
                );             
            die(json_encode($arr)); 
        }
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
