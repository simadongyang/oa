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
use Think\Controller;
/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class UserController extends AdminController {

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){       

        
        $field='uid,realname,sex,birthday,phone,iscompletion,entrytime';
        $map['status']  =   array('egt',0);
        $list   = $this->lists('Member', $map,'','',$field);
        

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
       
        $this->assign('_list', $list);
        
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
    	//查询员工表最后一个id
        $findlast=M('Member')->order('uid desc')->find();
        $gonghao['a']=$findlast['uid']+1;
        $gonghao['b']=rand(0,100000).'_gonghao'.$gonghao['a'];
        $this->assign('gonghao',$gonghao);

    	//显示所属部门信息
    	$department=M('department')->select();
        //$department=tree($department);
        $department=getTrees($department);
    	$this->assign('department',$department);
    	
    	//显示项目信息
    	$project=M('project')->select();
    	$this->assign('project',$project);
    	
    	//显示岗位信息
    	$station=M('station')->select();
    	$this->assign('station',$station);
    	
        //获取基本信息新增
        if(IS_POST){
        	$arr=I('post.');
            if($arr['leixing1']=='基本信息'){
                /* 检测密码 */
                if($password != $repassword){
                    $this->error('密码和重复密码不一致！');
                }

                /* 调用注册接口注册用户 */
                $User   =   new UserApi;
                $uid    =   $User->register($username, $password, $email,$criticalname);
                if(0 < $uid){ //注册成功
                	$arr['realname']=$arr['username'];
                	$arr['uid']=$uid;
                	$arr['nickname']=$username;
                	$arr['status']=1;
                    $user = $arr;






                    $su=D('Member')->create($user);
                    /*if(!$su){
                         echo $User->getError();exit;
                       //$this->error('用户添加失败！'.'8888');
                   }  */                  
                   





                   
                    if(!D('Member')->add($user)){
                        $this->error('用户添加失败！');
                    } else {
                        $this->success('用户添加成功！',U('index'));
                    }
                } else { //注册失败，显示错误信息
                    $this->error($this->showRegError($uid));
                }
            }
        } else {
            $this->meta_title = '新增用户';           
        }
        //获取部门、岗位等信息新增
        if(IS_POST){
            $arr=I('post.');
            if($arr['leixing2']=='岗位'){
                if(!$arr['uid']){
                     $this->error('请先填写基本信息，然后通过查看详情信息进行添加岗位等信息');
                }
                
                //查询该岗位是否为普通岗
                $find=M('station')->where('sid='.$arr['sid'])->find();
                if($find['isstaff']==1){
                    $resl=M('dss')->add($arr);
                }else{
                   $num=(count($arr)-30)/2;
                   $newarr=array();
                   for($i=1;$i<=$num;$i++){
                        $k=$i-1;
                        $newarr[$k]['status']=1;
                        $newarr[$k]['uid']=$arr['uid'];
                        $newarr[$k]['did']=$arr['did'];
                        $newarr[$k]['sid']=$arr['sid'];                        
                        $newarr[$k]['projectid']= $arr['p'.$i];
                        $newarr[$k]['projectsalary']=$arr['ps'.$i];                       
                    }
                   $countnum=0;
                   foreach($newarr as $v){                    
                        
                        $ra=M('dss')->add($v);
                        if($ra){
                             $countnum += 1;                             
                       } 
                   }
                   //添加薪资部分
                   $salaryone=M('salarychange')->add($arr);
                   if($countnum==$num && $salaryone){
                        $resl=true;
                   }
                }
                //判断是否添加成功
                if($resl){
                    $this->success('用户添加成功！'.$newarr[0]['did'],U('index'));
                } else {
                    $this->error('用户添加失败！');
                }
                
            }
        }


        $this->display();
    }

    /*
    * 查看员工信息或修改信息


    */
    public function update(){
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
            if($find['iscompletion']=='是'){
                $find['shi']='checked';
            }else{
                $find['fou']='checked';
            }

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
        $where=array('uid'=>$id);
        $sel=M('dss')->where($where)->order('dssid desc')->select();
        //判断员工现在是否有所属项目
        if($sel[0]['projectid']){
            $where=array('uid'=>$id,'status'=>1);
            $sel=M('dss')->where($where)->select();

            foreach($sel as &$v){
                //获取员工所在项目名称
                $find=M('project')->where('id='.$v['projectid'])->find();
                $v['projectname']=$find['name'];            
            }
        }

        //根据员工的岗位等信息获取该岗位是否为普通岗
        $isstaff=M('station')->where('sid='.$sel[0]['sid'])->find();
        $this->assign('isstaff',$isstaff['isstaff']);
        

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
            $arr['uid']=$arr['gonghao'];
            //编辑员工的岗位、薪资等信息
            if($arr['leixing2']=='岗位'){
                //判断是否为普通岗
                $findst=M('station')->where('sid='.$arr['sid'])->find();
                if($findst['isstaff']==1 && $arr['panduan']==1){
                    $resul=M('dss')->add($arr);
                    if($resul){
                        $this->success('用户编辑成功！',U('index'));                    
                    } else {
                        $this->error('用户编辑失败',U('update?id='.$arr['gonghao']));
                    } 

                }
                //获取提交数组的个数并判断有几个项目
               $num=(count($arr)-32)/3;
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
              
               //如果$count的值等于项目的个数，说明操作成功
               if($countnum==$num || $result){
                    $this->success('用户编辑成功！',U('index'));                    
                } else {
                    $this->error('用户编辑失败',U('update?id='.$arr['gonghao']));
                } 

               
            }
        }


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
