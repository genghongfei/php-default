<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/11 上午12:26
 */


namespace model\admin;


class AdminUser extends \g\Model
{
    public function pwd($pass){
        return md5("admin_".$pass);
    }
    public function fetchRowByName($name){
        return $this->fetchRow("fname=?",$name);
    }
}