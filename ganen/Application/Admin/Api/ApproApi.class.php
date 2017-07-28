<?php
//该接口用于查询用户名
namespace Admin\Api;
class ApproApi{
	public function check($arr){
		die(json_encode($arr));
		$name=I('get.name');
		$list=M('member')->field('uid,realname')->where('like %'.$name.'%')->select();
		if($list){	
			die(json_encode($list));
		}else{
			$arr=array('error'=>'不存在满足条件的员工');
			die(json_encode($arr));
		}
	}

	  //递归得到pid
    private function partment($did)
    {
        $data  = array();
        function sima($pid)
        {
            $res = M('Department')
            ->alias('d')
            ->field('d.did did ,p.did pid , d.dperson dp , p.dperson pd')
            ->join('ganen_department p on d.dpid = p.did ')
            ->where("d.did = $pid")
            ->find();
            $data =$res['pd']; 
            if(!empty($res['pid'])  )
            {
                $data=$data.','.sima($res['pid']);
            }  
           return trim(trim($data,','),'0');
        }
         
        return sima($did);
    }
    // 生成审批数据 
    // $nid 入职者的id
    // $did 本部门的id
    public function appr($nid)
    {
        return 1;
        if(empty($nid))
        {
            return json_encode('-1');
        }


         //自动权限 
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
        //判断入职者的父级
         $res = M('Dss')
                ->alias('d')
                ->field('p.dpid')
                ->join('ganen_department p on d.did = p.did')
                ->where("uid = $nid")
                ->find();
         //判断添加者的父级

         $tres = M('Dss')
                ->alias('d')
                ->field('p.dpid')
                ->join('ganen_department p on d.did = p.did')
                ->where("uid = $uid")
                ->find();
            
        if( $uid == 1 || $res['dpid'] == 1 || $tres['dpid'] ==1 )
        {
            $res = M('Auth_group_access')->where("uid = $nid")->find();
            
            //如果为admin 则添加的都进入 总经理组
            if($uid == 1)
              {
                $group_id =10;
              }else{
                //如果为总经理 或 最高级以及的人 添加的都进入默认组
                //
                // //查找默认组
                  $res =M('Dss')
                 ->alias('d')
                 ->field('s.auth_group_id')
                 ->join('ganen_station s on s.sid = d.sid')
                 ->where("d.uid = $nid")
                 ->find();

                 $group_id = $res['auth_group_id'];
                 if(empty($group_id)) $group_id =10;
              } 
            
            if(empty($res))
            {
             //
             $Auth = M("Auth_group_access"); 
             $data['uid'] = $nid;
            //默认组
            $data['group_id'] = $group_id;
            $Auth->add($data); // 添加记录
            return json_encode('1');
            }
            // 要修改的数据对象属性赋值
            
        }


        //判断是否申请过审批
        if(M('Appro')->where("uid = $nid")->find())
        {
            return json_encode('1');
        }
        $res = M('Dss')->field('did')->where("uid = $nid")->find();
       
        if(empty($res)) return json_encode('-1');
       // 得到本部门id
        $did = $res['did'];
        $pids = $this -> partment($did);
        if(empty($pids)) return json_encode('-1');
            //当前用户的uid
            $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
            $res=M('Department')
            ->field('dperson')
             ->where("did = $did")
             ->find();
            // 判断是否为本部门的负责人
            $dp = $res['dperson'];
            if($uid != $dp && !empty($dp))
            {
                $pids = $dp . ',' .$pids;
            }

            //生成 审批
            $arr_pids = explode(',',trim($pids));
            //审批名称
            $name = '入职';
            // 申请原因
            $reason = '新人入职';
            //得到人事信息
            $perid =  $_SESSION['onethink_admin']['user_auth']['uid'];

            $res = D('Member')->field('realname')->where("uid = $perid")->find();
            $person = $res['realname'];
            foreach($arr_pids as $k => $v)
            {
                $Appro = M("Appro"); // 实例化User对象
                $data['uid'] = $nid;
                $data['aids'] = $pids;
                $data['perid'] = $perid;
                $data['person'] = $person;
                $data['aid'] = $v;
                $data['name'] = $name;
                $data['reason'] = $reason;
                $data['time'] = time();
                $data['status'] = -2;
                $Appro->add($data);
            }
          return json_encode('-1');
    }

    //得到审批id
    public  function  appr_arr($nid,$ids)
    {
       //判断是否开启审批模式
        $is_appro = C('IS_APPRO');
        //判断是否需要自动生成权限
        if(empty($ids) || $is_appro == 0)
        {
            return  $this->auto_group($nid,$is_appro);
        }
       
        //return json_encode('444');
         //判断是否申请过审批
        if(M('Appro')->where("uid = $nid")->find())
        {
            return json_encode('1');
        }
       /* $res = M('Dss')->field('did')->where("uid = $nid")->find();
       
        if(empty($res)) return json_encode('-1');*/
       // 得到本部门id
       // $did = $res['did'];
        /*//$pids = $this -> partment($did);
        if(empty($pids)) return json_encode('-1');
            //当前用户的uid
            $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
            $res=M('Department')
            ->field('dperson')
             ->where("did = $did")
             ->find();
            // 判断是否为本部门的负责人
            $dp = $res['dperson'];
            if($uid != $dp && !empty($dp))
            {
                $pids = $dp . ',' .$pids;
            }

*/         //审批id不可为空
          //if(empty($ids)) return json_encode('审批人不可为空');
          //return json_encode('444');
            // //判断岗位信息
            $res =M('Dss')->field('sid')->where("uid = $nid")->find();
            if(empty($res['sid'])) return json_encode('岗位信息有误,请重新注册');
            //生成 审批
            $arr_pids = explode(',',$ids);
            //$arr_pids = $ids;
            //得到字符串id
            $pids = $ids;
            //审批名称
            $name = '入职';
            // 申请原因
            $reason = '新人入职';
            //得到人事信息
            $perid =  $_SESSION['onethink_admin']['user_auth']['uid'];

            $res = D('Member')->field('realname')->where("uid = $perid")->find();
            $person = $res['realname'];
            //return $arr_pids;
            $data = array();
            foreach($arr_pids as $k => $v)
            {
                $Appro = M("Appro"); // 实例化User对象
                $data['uid'] = $nid;
                $data['aids'] = $pids;
                $data['perid'] = $perid;
                $data['person'] = $person;
                $data['aid'] = $v;
                $data['name'] = $name;
                $data['reason'] = $reason;
                $data['time'] = time();
                $data['status'] = -2;
                $Appro->add($data);
            }
          return json_encode('1');
    }
    //自动进入审批组
    public function auto_group($nid,$is_appro)
    {
          //自动权限 
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
        //判断入职者的父级
         $res = M('Dss')
                ->alias('d')
                ->field('p.dpid')
                ->join('ganen_department p on d.did = p.did')
                ->where("uid = $nid")
                ->find();
         //判断添加者的父级

         $tres = M('Dss')
                ->alias('d')
                ->field('p.dpid')
                ->join('ganen_department p on d.did = p.did')
                ->where("uid = $uid")
                ->find();
           
        if( $uid == 1 || $res['dpid'] == 1 || $tres['dpid'] ==1 || $is_appro == 0)
        {
                //如果为总经理 或 最高级以及的人 添加的都进入默认组
                //
                // //查找默认组
              // return $nid;
               //return json_encode($nid);
                  $res =M('Dss')
                 ->alias('d')
                 ->field('s.auth_group_id')
                 ->join('ganen_station s on s.sid = d.sid')
                 ->where("d.uid = $nid")
                 ->find();
                  //return json_encode($res['auth_group_id']);
                //如果有默认组 则 进默认组，没有就进总经理组
                  //$res = M('Dss')->where("uid = $nid")->select();
                 //  return json_encode($res);
                 if(!empty($res['auth_group_id']))
                 {
                   //return json_encode(122);
                     $group_id = $res['auth_group_id'];
                 }else if($is_appro == 0){
                    return json_encode('岗位信息有误,请重新注册');
                 }else{
                    $group_id = 10;
                 }
             // 判断是否进组
            $res =M('Auth_group_access')->where("uid = $nid")->find();
            if(!empty($res))
            {
                $Auth = M("Auth_group_access"); // 实例化User对象
                $Auth->where("uid=$nid")->delete(); // 删除id为5的用户数据
            }
             $Auth = M("Auth_group_access"); 
             $data = array();
             $data['uid'] = $nid;
            //默认组
            $data['group_id'] = $group_id;
            $res =  $Auth->add($data); // 添加记录
            if(!$res)
            {
               return json_encode('错误号：10');
            }
            return json_encode($res);
            if(!empty($res))
            {
              //更改入职状态为
                $Member = M("Member"); // 实例化User对象
                // 要修改的数据对象属性赋值
                $data = array();
                $data['isadopt'] = 1;

                $res = $Member->where("uid = $nid")->save($data); // 根据条件更新记录
                if(empty($res))
                {
                  return json_encode('错误号：11');
                }else{
                  return json_encode(1);
                }
              
            }else{
              return json_encode('未知错误');
            }
            // 要修改的数据对象属性赋值
            
        }else{
             return json_encode('请完善审批人信息');
        }

    }
    
}