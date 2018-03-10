<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/11 上午12:37
 */


namespace controller\admin;


class Logout extends \g\Controller
{
    public function get(){
        session_destroy();
        \g\Http::go("/admin/login");
    }
}