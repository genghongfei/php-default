<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/11 上午12:44
 */


namespace controller\admin;


class Index extends \controller\Admin
{
    public function get(){
        $this->layout('admin/index.html',[]);
    }
}