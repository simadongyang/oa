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
      	$department=M('department')->where('status>-1')->select(); 
        //构造新数组       
        $department=getTrees($department);
      	$this->assign('department',$department);      	
        $this->display();
    }
    public function add(){
    	//显示所属部门信息
    	$department=M('department')->select();
        //构造新数组       
        $department=getTrees($department);
    	$this->assign('department',$department);    	
    	$a=I('post.');    	
    	if(!empty($a)){
            if($a['did']){//确定是否编辑
                $result=M('department')->where('did='.$a['did'])->save($a);
                if(!$result){
                    $this->error('部门编辑失败！');
                } else {
                    $this->success('部门编辑成功！',U('index'));
                }
            }else{//确定是否添加 		
    	    	$result=M('department')->add($a);
    	    	if(!$result){
    	    		$this->error('部门添加失败！');
    	    	} else {
    	    		$this->success('部门添加成功！',U('index'));
    	    	}
            }
    	}
        //编辑部门信息显示
        $did=I('get.did');
        if($did){
            $done=M('department')->where('did='.$did)->find();
            $this->assign('done',$done);
        }
    	$this->display();
    }

    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['did'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('department', $map );
                break;
            case 'resumeuser':
                $this->resume('department', $map );
                break;
            case 'deleteuser':
                $this->delete('department', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }













    //岗位信息
    public function station(){
    	$station=M('station')->select();
        foreach($station as &$v){
            if ($v['isstaff']==1) {
                $v['isstaff']='否';
            }else{
                $v['isstaff']='是';
            }
        }
    	$this->assign('station',$station);    	
    	$this->display();
    }
    public function addstation(){
    	//显示所属部门信息
    	$department=M('department')->select();
        //构造新数组       
        $department=getTrees($department);
    	$this->assign('department',$department);
    	
    	$a=I('post.');
        //var_dump($a);exit;
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
