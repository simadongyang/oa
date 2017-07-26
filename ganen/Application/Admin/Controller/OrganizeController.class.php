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
        foreach($department as &$val){
            $person=M('Member')->where("uid='%d'",$val['dperson'])->find();
            $val['personname']=$person['realname'];
        }
      	$this->assign('department',$department);      	
        $this->display();
    }
    public function add(){
    	//显示所属部门信息
    	$department=M('department')->where('status>-1')->select();
        //构造新数组       
        $department=getTrees($department);
    	$this->assign('department',$department);    	
    	$a=I('post.');    	
    	if(!empty($a)){
            //查询指定的部门负责人是否存在           
           /* $res=M('Member')->where("realname='%s'",$a['dperson'])->find();
            if($res){
                $a['dperson']=$res['uid'];
            }else{
                $this->error('不存在该员工，请核对！');
            }*/
            if($a['did']){//确定是否编辑
                if($a['did']==1){
                    $a['dpid']=0;
                }

                //用于查询数据是否发生改变
                $findd=M('department')->where('did='.$a['did'])->find();
                if($findd){

                    $ew=congruent($findd,$a);
                    
                    if($ew){
                        $this->success('您未作出任何编辑！'.$ew,U('index'));
                    }
                }
                //用于编辑修改
                $result=M('department')->where('did='.$a['did'])->save($a);
                

                if(!$result){
                    $this->error('部门编辑失败！'.$ew);
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
            //获取负责人姓名
            $findone=M('Member')->where('uid='.$done['dperson'])->find();            
            $done['personname']=$findone['realname'];
            $this->assign('done',$done);
        }
    	$this->display();
    }

    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对集团执行该操作!");
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
    	$station=M('station')->where('status>-1')->select();
        
        foreach($station as &$v){
            $v['dpid']=$v['spid'];
            $v['dname']=$v['stationname'];
            $v['did']=$v['sid'];
            if ($v['isstaff']==1) {
                $v['isstaff']='否';
            }else{
                $v['isstaff']='是';
            }
        }
        //构造新数组       
        $station=getTrees($station);
    	$this->assign('station',$station);    	
    	$this->display();
    }
    //新增岗位信息
    public function addstation(){
    	//显示所属部门信息
    	$station=M('station')->where('status>-1')->select();
        foreach($station as &$value){
            $value['dpid']=$value['spid'];
            $value['dname']=$value['stationname'];
            $value['did']=$value['sid'];
        }

        //构造新数组       
        $station=getTrees($station);
        //var_dump($station);exit;
    	$this->assign('station',$station);
    	
    	$a=I('post.');
        //var_dump($a);exit;
    	if(!empty($a)){
            //判断是否进行修改
            if($a['sid']){

                /*//用于查询数据对比是否进行了更改
                 $findd=M('station')->where('sid='.$a['sid'])->find();
                if($findd){
                    $ew=array_diff($findd,$a);
                    if($ew){
                        $this->success('您未作出任何编辑！',U('index'));
                    }
                }*/

            //用于进行修改                
                $result=M('station')->where('sid='.$a['sid'])->save($a);
                if(!$result){
                    $this->error('岗位编辑失败！');
                } else {
                    $this->success('岗位编辑成功！',U('station'));
                }
            }else{//进行添加数据
        		$result=M('station')->add($a);
        		if(!$result){
        			$this->error('岗位添加失败！');
        		} else {
        			$this->success('岗位添加成功！',U('station'));
        		}
            }
    	}
        //显示编辑岗位信息
        $sid=I('get.sid');
        if($sid){
            $sone=M('station')->where('sid='.$sid)->find();
            
            $this->assign('sone',$sone);

        }
    	$this->display();
    }
    //操作
    public function changeStatus_s($method=null){

        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对总经理执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['sid'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('station', $map );
                break;
            case 'resumeuser':
                $this->resume('station', $map );
                break;
            case 'deleteuser':
                $this->delete('station', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

}
