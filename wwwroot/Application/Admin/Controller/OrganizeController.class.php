<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 组织机构控制器
 * @author huajie <banhuajie@163.com>
 */
class OrganizeController extends AdminController {

    /**
     * 部门信息列表
     * @author huajie <banhuajie@163.com>
     */
    public function index(){
    	//所属部门信息显示
      	$department=M('department')->select();
      	$this->assign('department',$department);
      	$this->assign('departmen',$department);
        $this->display();
    }
    public function add(){
    	//显示所属部门信息
    	$department=M('department')->select();
    	$this->assign('department',$department);    	
    	$a=I('post.');    	
    	if(!empty($a)){    		
	    	$result=M('department')->add($a);
	    	if(!$result){
	    		$this->error('用户添加失败！');
	    	} else {
	    		$this->success('用户添加成功！',U('index'));
	    	}
    	}
    	$this->display();
    }
    //岗位信息
    public function station(){
    	$station=M('station')->select();
    	$this->assign('station',$station);    	
    	$this->display();
    }
    public function addstation(){
    	//显示所属部门信息
    	$department=M('department')->select();
    	$this->assign('department',$department);
    	
    	$a=I('post.');
    	if(!empty($a)){
    		$result=M('station')->add($a);
    		if(!$result){
    			$this->error('用户添加失败！');
    		} else {
    			$this->success('用户添加成功！',U('station'));
    		}
    	}
    	$this->display();
    }
    

}
