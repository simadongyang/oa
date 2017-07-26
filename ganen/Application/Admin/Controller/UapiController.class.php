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

}
