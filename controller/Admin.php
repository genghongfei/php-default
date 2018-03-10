<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/10 下午11:12
 */


namespace controller;


abstract class Admin extends \g\Controller
{

    /**
     * 判断账号是否登陆
     * @return bool|void
     */
    protected function before(){
        $adminId = $this->getAdminId();
        if($adminId){
            return true;
        }
        $ref = $_SERVER['REQUEST_URI'];
        \g\Http::setSession('from_url',$ref);
        \g\Http::go('\admin\login');
    }
    protected function getAdminId(){
        return \g\Http::session('admin_id',0);
    }

    /**
     * 使用模版
     * @param $tpl
     * @param array $data
     */
    protected function layout($tpl,$data = []){
        $file = ROOT.'views'.DS.$tpl;
        extract($data);
        ob_start();
        include $file;
        $data['__LAYOUT_CONTENTS__'] = ob_get_clean();
        $layoutFile = ROOT.'views'.DS.'admin/layout.html';
        extract($data);
        include $layoutFile;
    }
}