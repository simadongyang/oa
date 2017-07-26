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
            if(!empty($res['pid']))
            {
                $data=$data.','.sima($res['pid']);
            }  
            return trim($data,',');
        }
         
        return sima($did);
    }
    // 生成审批数据 
    // $nid 入职者的id
    // $did 本部门的id
    public function appr($nid)
    {
              
        if(empty($nid))
        {
            return json_encode('-1');
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
          return json_encode('-1');
    }
    
}