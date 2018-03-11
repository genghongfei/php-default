<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/10 下午11:00
 */


namespace controller\git\nginx;


class Index extends \controller\Admin
{
    public function get(){
        $data = [];
        $this->layout("admin/git/nginx/index.html",$data);
    }
    public function put(){

    }
    public function post(){

    }
}