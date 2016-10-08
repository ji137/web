<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 朱亚杰 <zhuyajie@topthink.net>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Model\AuthGroupModel;


/**
 * 权限管理控制器
 * Class AuthManagerController
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
class PicturesController extends AdminController{
   

    public function index(){
    	
    	$this->display();
    }
       
    
    protected function checkDynamic(){
    	$cates = AuthGroupModel::getAuthCategories(UID);
    	switch(strtolower(ACTION_NAME)){
    		case 'index':   //文档列表
    		case 'add':   // 新增
    			$cate_id =  I('cate_id');
    			break;
    		case 'edit':    //编辑
    		case 'update':  //更新
    			$doc_id  =  I('id');
    			$cate_id =  M('Document')->where(array('id'=>$doc_id))->getField('category_id');
    			break;
    		case 'setstatus': //更改状态
    		case 'permit':    //回收站
    			$doc_id  =  (array)I('ids');
    			$cate_id =  M('Document')->where(array('id'=>array('in',$doc_id)))->getField('category_id',true);
    			$cate_id =  array_unique($cate_id);
    			break;
    	}
    	if(!$cate_id){
    		return null;//不明
    	}elseif( !is_array($cate_id) && in_array($cate_id,$cates) ) {
    		return true;//有权限
    	}elseif( is_array($cate_id) && $cate_id==array_intersect($cate_id,$cates) ){
    		return true;//有权限
    	}else{
    		return false;//无权限
    	}
    }
    
    /**
     * 显示左边菜单，进行权限控制
     * @author huajie <banhuajie@163.com>
     */
    protected function getMenu(){
    	//获取动态分类
    	$cate_auth  =   AuthGroupModel::getAuthCategories(UID); //获取当前用户所有的内容权限节点
    	$cate_auth  =   $cate_auth == null ? array() : $cate_auth;
    	$cate       =   M('Category')->where(array('status'=>1))->field('id,title,pid,allow_publish')->order('pid,sort')->select();
    
    	//没有权限的分类则不显示
    	if(!IS_ROOT){
    		foreach ($cate as $key=>$value){
    			if(!in_array($value['id'], $cate_auth)){
    				unset($cate[$key]);
    			}
    		}
    	}
    
    	$cate           =   list_to_tree($cate);    //生成分类树
    
    	//获取分类id
    	$cate_id        =   I('param.cate_id');
    	$this->cate_id  =   $cate_id;       	
    
    	//获取面包屑信息
    	$nav = get_parent_category($cate_id);
    	$this->assign('rightNav',   $nav);
    	$this->assign('show_stock', IS_ROOT || $this->checkRule('Admin/Pictures/index'));
  
    }
    
    public function Add(){
    	//$this->getMenu();
        //$Pictures = D('Pictures');
        if(IS_POST){ //提交表单
            if(false !== $Pictures->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $Pictures->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        }  
            /* 获取分类信息 */
            $this->assign('info',       null);
            $this->assign('category', $cate);
            $this->meta_title = '新增分类';
            $this->display('edit');
        
    }

    /**
     * 查询库存信息
     */
    public function sup(){
    	$this->getMenu();
    	/*
    	$CustID= BUSINESSID;
    	$page = I('p');
    	$sp = I('sp');
    	$cd = I('cd');
    	
    	
    	$findinfo='';
    	 
    	if (!empty($sp)){
    		$findinfo.= '[商品名称 : '.$sp.'] , ';
    	} 
    	if (!empty($cd)){
    		$findinfo.= '[生产企业 : '.$cd.'] , ';
    	}
    	 
    	if (!empty($findinfo))
    		$this->assign('findinfo','<span style="padding-left:20px;font-size:14px"><b><a style="cursor:pointer" class="clearfind">清空条件</a></b>  查询内容  : ->'.trim($findinfo,',').'</span>');
    	
    
    	$pageindex=(!empty($page))?$page:1;
    	$covercus=1;
      	
      	$sql= "exec Ds_Get_business_web @flag=0,@dwbh='$CustID',
    	@pageindex=$pageindex,@covercus=$covercus,@prodid='$prodid',@ypmc='$sp',@cdmc='$cd'";
        	
    	$data = $this->querylists('DB_MSSQL_INFO', $sql);
    	
    	$this->assign('_list',$data);*/
    	$this->display();
    }    

    
    public function report(){
    	$this->getMenu();
    	/*$CustID= BUSINESSID;

    	$page = I('p');
    	$sp = I('sp');
    	$cd = I('cd');
    	$ksrq = I('bdt');
    	$jsrq = I('edt');
    	
    	$findinfo='';
    	if (!empty($sp)){
    		$findinfo.= '[商品名称 : '.$sp.'] , ';
    	}
    	if (!empty($cd)){
    		$findinfo.= '[生产企业 : '.$cd.'] , ';
    	}
    	if (!empty($ksrq)){
    		$findinfo.= '[入库期间  : '.$ksrq.' 至 '.$jsrq.'],';
    	}
    	if (!empty($findinfo)) 
    	   $this->assign('findinfo','<span style="padding-left:20px;font-size:14px"><b><a style="cursor:pointer" class="clearfind">清空条件</a></b>  查询内容  : -> '.trim($findinfo,',').'</span>');
    	   
    	$pageindex=(!empty($page))?$page:1;
    	$covercus=1;
    	 
    	$sql= "exec Ds_Get_business_web @flag=1,@dwbh='$CustID',
    	@pageindex=$pageindex,@covercus=$covercus,
    	@ksrq='$ksrq',@jsrq='$jsrq',
    	@prodid='$prodid',@ypmc='$sp',@cdmc='$cd'";
    	
    	$data = $this->querylists('DB_MSSQL_INFO', $sql);
    	 
    	$this->assign('_list',$data);*/
    	$this->display();
    }
   

}
