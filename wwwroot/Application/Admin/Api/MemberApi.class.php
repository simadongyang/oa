<?php
//该接口用于查询用户名
namespace Admin\Api;
class MemberAip{
	public function membername(){
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