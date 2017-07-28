<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Api\ApproApi;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class TeamController extends AdminController {

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
       


        $res=M('Project')
          ->alias('p')
          ->field('m.realname,p.*,d.dname')
          ->where('p.status != -1')
          ->join(' left join ganen_member m on p.charge = m.uid')
          ->join(' left join ganen_department d on p.pro_id = d.did')
          ->select();    
        $this->assign('_list', $res);
        $this->meta_title = '用户信息';
        $this->display();
    }
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
    public function ok()
    {

      // echo C('IS_APPRO');
        //die;
       // $nid = 10;
       // echo '<pre>';
        //var_dump($this->partment($nid));die;
       // $res = M('Dss')->field('did')->where("uid = $nid")->find();
       //var_dump($res);die;
       $nid = 74;
       $ids = '1,2,3,4';; 
       $ids = ''; 
       $Appro = new ApproApi;  
       var_dump($Appro->appr_arr($nid,$ids ));die;
        if($Appro->appr($nid) == -1)
        {
            $this->error('审批新增失败');die;
        }
    }
    public function add(){
       if(IS_POST){
            $Menu = D('Project');
            $data = $Menu->create();
            if($data){
                $id = $Menu->add();
                if($id){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_menu', 'Menu', $id, UID);
                    $this->success('新增成功', Cookie('__forward__'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Menu->getError());
            }
        } else {
            
            $this->display();
        }
    }

    public function edit($id = 0)
    {
        if(IS_POST){
            $Pro = D('Project');
            $data = $Pro->create();
            if($data ){
                if($data['id']) // 编辑
                {
                    if($Pro->save()!== false){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_pro', 'Pro', $data['id'], UID);
                    $this->success('更新成功',U('Team/index'));
                    } else {
                        $this->error(json_encode($data));
                    }
                }else{ // 新增

                    if(empty($data['name']) || empty($data['area']) || empty($data['h_name']) || empty($data['depar']) ) 
                    {
                        $this->error('请将数据填写完整');
                    }
                     $id = $Pro->add();
                    

                    if($id){
                        // S('DB_CONFIG_DATA',null);
                        //记录行为
                        action_log('add_pro', 'Pro', $id, UID);
                        $this->success('新增成功', U('Team/index'));
                    } else {
                        $this->error('新增失败');
                    }
                }
                
            } else {
                $this->error($Pro->getError());
            }
        } else { //编辑页
            $info = array();
            /* 获取数据 */
             $info=M('Project')
              ->alias('p')
              ->field('m.realname,p.*')
              ->join('left join ganen_member m on p.charge = m.uid')
              ->find($id);  
             //var_dump($res);die;
            $res = M('Department')->field('dname,did')->where('is_pro = 1')->select();

            $this->assign('pro', $res);
            $this->assign('info', $info);
            $this->meta_title = '编辑';
            $this->display();
        }
    }
    /**
     * 删除项目
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Project')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_project', 'Project', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

   
   
    /**
     * 修改密码初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updatePassword(){
        $this->meta_title = '修改密码';
        $this->display();
    }

   /**
     * 项目状态修改
     * @author sima <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Project', $map );
                break;
            case 'resumeuser':
                $this->resume('Project', $map );
                break;
            case 'deleteuser':
                $this->delete("Project", $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function add_t()
    {
         if(IS_POST)
        {
            $arr=I('post.'); 
           // var_dump($arr);die;
            $data['name']  = 'test';
            $data['area']  = 'test';
            $data['h_name']  = 'test';
            $data['depar']  = 'test';
            $data['charge']  = 'test';

            if(!M('Project')->add($arr))
            {
                $this->error('用户添加失败！');
            } else {
               // $this->success('',U('add'));
              $this->success('操作完成','admin/team/add',3);
            }

        }
       //  $this->display('team/add');
        // $this->redirect('Team/add');
    }


    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }

}
