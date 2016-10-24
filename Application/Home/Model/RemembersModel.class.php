<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;
use User\Api\UserApi;

/**
 * 文档基础模型
 */
class RemembersModel extends Model{

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT),
    );
  

}
