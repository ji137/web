<?php

namespace Addons\demo;
use Common\Controller\Addon;

/**
 * 示列插件
 * @author 无名
 */

    class demoAddon extends Addon{

        public $info = array(
            'name'=>'demo',
            'title'=>'示列',
            'description'=>'这是一个临时描述',
            'status'=>1,
            'author'=>'无名',
            'version'=>'0.1'
        );
        
         public $admin_list = array(
                'model'=>'Hooks',		//要查的表
			   'fields'=>'*',			//要查的字段
		   	    'map'=>'',				//查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
			     'order'=>'id desc',		//排序,
			     'listKey'=>array( 		//这里定义的是除了id序号外的表格里字段显示的表头名
				    '字段名'=>'表头显示名'
			  ),
        );

        public $custom_adminlist = 'demo.html';

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的AdminIndex钩子方法
        public function AdminIndex($param){
            $this->display('demo');
        }

    }