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
    public function partment($did)
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
            if(!empty($res['pid']))
            {
                $data=$data.','.sima($res['pid']);
            }  
            return trim($data,',');
        }
         
        return sima($did);
    }
    // 生成审批数据
    public function appr($nid,$did)
    {
              
        if(empty($nid) || empty($did))
        {
            $this->error('审批生成失败');
        }
        $res = M('Dss')
            ->field('did')
            ->where("uid = $nid")
            ->find();
        if(!empty($res)) return ;
       // 判断是否为第一次添加
       
        $pids = $this -> partment($did);
        if(empty($pids)) $this->error('岗位信息有误');
            //当前用户的uid
            $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
            $res=M('Department')
            ->field('dperson')
             ->where("did = $did")
             ->find();
            // 判断是否为本部门的负责人
            $dp = $res['dperson'];
            if($uid != $dp)
            {
                $pids = $dp . ',' .$pids;
            }
            //生成 审批
            $arr_pids = explode(',',$pids);
            //审批名称
            $name = '入职';
            // 申请原因
            $reason = '新人入职';
            foreach($arr_pids as $k => $v)
            {
                $Appro = M("Appro"); // 实例化User对象
                $data['uid'] = $nid;
                $data['aids'] = $pids;
                $data['aid'] = $v;
                $data['name'] = $name;
                $data['reason'] = $reason;
                $data['time'] = time();
                $data['status'] = -2;
                $Appro->add($data);
            }
    }
    public function test()
    {
        // $nid 新入职的id $did 本部门id\
        $nid =144440;
        $did = 7;
         $this->appr($nid,$did);
    }
}