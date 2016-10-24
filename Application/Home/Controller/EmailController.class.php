<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: azhai <1229991003@qq.com> <http://www.ji137.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 文件控制器
 * 主要用于邮件处理
 */

class EmailController extends \Think\Controller {

    
    public function index(){
    	$map=array(
    		 'a.notice' =>array('in','0,1'),
    		 'a.status'=>array('gt',2)
    		 );
        $variable = M('remembers a')->join('users b on a.userid=b.id')
        ->field('a.*,b.email,datediff(now(),from_unixtime(a.addtime)) as days')
        ->where($map)
        ->select();
        foreach ($variable as $key => $value) {
           $update_date = $value['days'];
           $status=$value['status'];
           if ($update_date>2 & $update_date<7 & $status===0){
              sendMail('1229991003@qq.com','JI137 提醒','你有要记住的内容 http://ji137.enhwa.com');	 
              M('remembers')->where(array('id'=>$value['id']))->setField('notice',1);    	
           }
           if ($update_date>6){
              sendMail('1229991003@qq.com','JI137 提醒','你有要记住的内容 http://ji137.enhwa.com');	 
              M('remembers')->where(array('id'=>$value['id']))->setField('notice',2);    	
           }
        }
    }
}
