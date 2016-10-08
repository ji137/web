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
class CertimgController extends HomeController {

	public function index(){
	  if (IS_GET)
	  {
		
	  	$dh= I('dh');
		$ph= I('ph','');
		$p=I('p',1);
		$sql= "exec GetCertReport @dh='$dh',@ph='$ph',@pageindex=$p";
		$data =$this->querylists('DB_MSSQL_INFO_IMG',$sql);
		$this->assign('_list',$data);
     }
	  $this->display();
	}
	
	
	public function detail() {
	  
	     	$id = I ( 'id' );
	            
	            $cache = F($id);
	            $cache=(array)json_decode($cache);
	            tolog($cache);
	            $isexists = file_exists($cache['path']);
                        if (!$isexists){ 
                        tolog('data');                     
  		$sql = "exec GetCertReport @imgid=$id,@status=1";
		$model = M ( '', '', 'DB_MSSQL_INFO_IMG' );
		$data = $model->query ( $sql );
		$size = $data [0] ['ImgSize'];
                        $size = sizeFormat($size);

		$imgpath=C('CERT_IMG_PATH');

		$fileName = $imgpath. $id . '.jpg';
		
		$img = $data [0] ['ImgData'];
		
		$img = file_put_contents ( $fileName, $img );
		$f= array('path'=>$fileName,'size'=>$size);
		$f=json_encode($f);
		F($id,$f);
		$response = array (
				'status' => 1,
				'filename' => $fileName,
				'info' => array('size'=>$size) 
		);
	         }else
	         {
	         	  tolog('cache');                     
  		
	         	$response = array (
				'status' => 1,
				'filename' => $cache['path'],
				'info' => array('size'=>$cache['size']) 
				);
	         	
	         }
		
		$this->assign ( 'response', $response );
		$this->display ();
	}
	

  



	
	
}
