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
class UapiController extends AdminController {

    public function membername(){
       
        //获取传过来的员工名字
        $name=I('get.name');
       if(!$name){
            $arr=array(
                'status'=>1,//失败
                'data'=>array(),
                'msg'=>''
                );             
            die(json_encode($arr));
        }
        $where['realname']=array('like','%'.$name.'%');
        $where['status']=array('gt','-1');
        $where['_logic'] = "and";
        $list=M('member')->field('uid,realname')->where($where)->select();

        
            $arr=array(
                'status'=>1,//成功
                'data'=>$list,
                'msg'=>''
                ); 
            die(json_encode($arr));      

    }

    // author sima
    // 拒绝申请
    public function resume($id)
    {
        //得到传过来的id
        
        $id = array_unique((array)$id);
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $arr=array(
                'status'=>-1,//失败
                'msg'=>'数据错误'
                ); 
            die(json_encode($arr));      
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
            //if(empty($str)) $str = 0;
            //当全部审批通过后 允许其登陆后台
            if(empty($str))
             {
                //设置状态
                $str = 0;
                //将用户加入权限组
                $Auth = M("Auth_group_access"); 
                // 要修改的数据对象属性赋值
                $data['uid'] = $uid;
                //默认组
                $data['group_id'] = 9;
                $Auth->add($data); // 添加记录
             }
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
        $arr=array(
                'status'=>1,//成功
                'msg'=>'您已经成功拒绝'
                ); 
            die(json_encode($arr));    
    }
    //核对查看薪资二级密码，返回值

    public function salary(){

        if(IS_POST){
            $shuju=I('post.');
            
            //$shuju['uid']=240;
            //$shuju['password']=123456;
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
                    $result=ture;
                }
            }


            if($result){

                 $p2 = password2(UID,$shuju['password']);                
               
                if($p2>0){

                   //构造数组 
                   $fan['uid']=$shuju['uid'];                   
                   $_SESSION['biao']=$shuju['uid'].'_'.time();
                   $fan['biaoshi']=eqiu($_SESSION['biao']);             
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




}
