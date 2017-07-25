<?php

namespace Admin\Controller;
use User\Api\UserApi;
use Think\Controller;

class AipConroller extends AdminController{
	//该接口用于查询用户名
	public function membername(){
		//获取传过来的员工名字
		$name=I('get.name');

		$list=M('member')->field('uid,realname')->where('like %'.$name.'%')->select();
		if($list){	
			die(json_encode($list));
		}else{
			$arr=array('error'=>'不存在满足条件的员工');
			die(json_encode($arr));
		}
	}
} 