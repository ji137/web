<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}


    protected function _initialize(){
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
    }

	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}
	
	protected function querylists($model,$query,$params =array()){
	
		$REQUEST    =   (array)I('request.');
		 
	
		if( isset($REQUEST['r']) ){
			$listRows = (int)$REQUEST['r'];
		}else{
			$listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
		}
		 
		$query = $query.',@pagesize='.$listRows;
		 
		$model  =   M('','',$model);
		$data = $model->queryResults($query);

		if (!isset($data['totalpage'])|| $data['totalpage']>0){
			 
			$total = $data[1][0]['totalpage'];
			tolog('------------'.$total);
			$page = new \Think\Page($total, $listRows, $REQUEST);
			if($total>$listRows){
				$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
			}
			$p =$page->show();
			$this->assign('_page', $p? $p: '');
			$this->assign('_total',$total);
			$data = $data[0];
		}
		else
		{
			$data=array();
		}
		 
		return $data;
		 
	}
	
	
	

}
