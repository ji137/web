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
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

	//系统首页
    public function index(){
        $this->display();
    }
     
    public function oneday(){

        $this->display();
    }  

     public function threeday(){
        $id= I('id');
        if (empty($id)){
          $map['status']=3;
          $map['datediff(now(),from_unixtime(addtime))']=array('eq',3); 
          $list= M('remembers')->field('id,from_unixtime(addtime) as adddate,datediff(now(),from_unixtime(addtime)) as flag')
          ->where($map)->select();  
          $this->assign('list',$list);  
        }
        else
        {
          $map=array();
          $map['id']=$id;
         //$map['datediff(from_unixtime(addtime),now())']=array('lt',0); 
          $detail= M('remembers')->field('id,content')
          ->where($map)->select();  
          $this->assign('detail',$detail); 
        } 
   
        $this->display();
    }  

     public function sevenday(){
        $id= I('id');
        if (empty($id)){
          $map['status']=7;
          $map['datediff(now(),from_unixtime(addtime))']=array('eq',7); 
          $list= M('remembers')->field('id,from_unixtime(addtime) as adddate,datediff(now(),from_unixtime(addtime)) as flag')
          ->where($map)->select();  
          $this->assign('list',$list);  
        }
        else
        {
          $map=array();
          $map['id']=$id;
         //$map['datediff(from_unixtime(addtime),now())']=array('lt',0); 
          $detail= M('remembers')->field('id,content')
          ->where($map)->select();  
          $this->assign('detail',$detail); 
        } 
   
        $this->display();
    }  

}