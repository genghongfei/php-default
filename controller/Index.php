<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/9 下午4:28
 */


namespace controller;


class Index extends \g\Controller
{
    public function get(){
        new \model\Test();
        phpinfo();
        $this->display('index.html',['a' => 123]);
    }
}