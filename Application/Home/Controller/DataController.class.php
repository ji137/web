<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */

class DataController extends HomeController {

   //保存要记住的内容
   public function save() {
       $cnt= I('cnt');
       $userinfo= session('userinfo');
       $userid= empty($userinfo['id'])?'1':$userinfo['id']; 
       $data = array(
            'userid'=>$userid,
            'content'=>$cnt,
            'addtime'=>time(),
            'status'=>3
       	);
        $rs = D('remembers')->data($data)->create();
        if($rs){
        	$rs= D('remembers')->data($data)->add();
        };
        $this->ajaxReturn($rs);
   }

      //保存要记住的内容
   public function update() {
       $id= I('id');
       $status= I('status');
       $data = array(
            'status'=>$status
        );
       $rs = D('remembers')->data($data)->where(array('id'=>$id))->save();
       $this->ajaxReturn($rs);
   }

}
