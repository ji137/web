<?php

namespace Addons\test;
use Common\Controller\Addon;

/**
 * 示列插件
 * @author 无名
 */

    class testAddon extends Addon{

        public $info = array(
            'name'=>'test',
            'title'=>'示列',
            'description'=>'这是一个临时描述',
            'status'=>1,
            'author'=>'无名',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        
        public function AdminIndex($param){
        	$config = $this->getConfig();
        	$this->assign('addons_config', $config);
        	if($config['display'])
        		$this->display('test');
        }

    }