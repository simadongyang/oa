<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;


/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PerapproController extends AdminController {

     public function index(){
       
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
         $res=M('Appro')->where("uid = $uid")->group('time')->select();
        //var_dump($res);die;      

        $this->assign('_list', $res);
        $this->display();
    }
    public function wait_(){
        $find = [0,1];
     $replace = ['q','e'];
        $arr = [3,2,4,1,0];
    print_r(str_replace($find,$replace,$arr));die;

        //当前用户的uid
        echo '<pre>';
        $map['id']  = array(' in',array('1','4','8'));
         $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //删除后把 aids 改为 -1
                    $str = -1;
                     //更改数据
                     //得到uid
                     $uid = $val['uid'];
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                
                }
                die;

        //判断是否为当前审批人
        foreach($res as $key=>$val)
        {

           $arr = explode(',',$val['aids']);
           if($arr[0] == $uid)
           {
            $nres[] = $res[$key];
           }
        }
        $this->assign('_list', $nres);
        $this->display();
    }
    public function wait(){
        //当前用户的uid
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
        $res=M('Appro')
        ->where("aid=$uid and status = -2")
        ->select();
        //判断是否为当前审批人
        foreach($res as $key=>$val)
        {

           $arr = explode(',',$val['aids']);
           if($arr[0] == $uid)
           {
            $nres[] = $res[$key];
           }
        }
        $this->assign('_list', $nres);
        $this->display();
    }
     public function his(){
       
        $uid = $_SESSION['onethink_admin']['user_auth']['uid'];
        $list   = $this->lists('Appro',["aid = $uid"]);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->display();
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
            $info = M('Project')->field(true)->find($id);
            //$menus = M('Project')->field(true)->select();
           // $menus = D('Common/Tree')->toFormatTree($menus);

            //$menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            //$this->assign('Menus', $menus);
            if(false === $info){
                $this->error('获取后台菜单信息错误');
            }
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
        if(M('Appro')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_appro', 'Appro', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
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

                 $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //删除后把 aids 改为 -1
                    $str = -1;
                     //更改数据
                     //得到uid
                    $uid = $val['uid'];
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                
                }
                $this->forbid('Appro', $map );
                break;
            case 'resumeuser':
               // 删除 aids 里面的id
                $suid = $_SESSION['onethink_admin']['user_auth']['uid'];
                $res=M('Appro')
                 ->where($map)
                ->select();
                foreach($res as $key=>$val)
                {
                    //查到要改的数据
                    $uid = $val['uid'];
                     $nres=M('Appro')->where("uid = $uid")->select();
                     $arr = explode(',',$nres[0]['aids']);
                     //删除第一个元素
                     if($arr[0] == $suid)
                     {
                        array_shift($arr);
                     }else{
                        return false;
                     }
                     
                     $str=implode(',',$arr);
                     //判断是否为空
                     if(empty($str)) $str = 0;
                     //更改数据
                    $Ures = M("Appro"); 
                    // 要修改的数据对象属性赋值
                    $data['aids'] = $str;
                    $Ures->where("uid = $uid")->save($data); // 根据条件更新记录
                
                }
                $this->resume('Appro', $map );
                break;
            case 'deleteuser':
                $this->delete("Appro", $map );
                break;
            default:
                $this->error('参数非法');
        }
    }
   


}
