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
        $where['realname']=array('like','%'.$name.'%');
        $list=M('member')->field('uid,realname')->where($where)->select();
        if($list){  
            die(json_encode($list));
        }else{
            $arr=array('error'=>'不存在满足条件的员工');
            die(json_encode($arr));
        }
    }

}
