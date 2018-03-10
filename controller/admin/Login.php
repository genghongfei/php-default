<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/10 下午11:22
 */


namespace controller\admin;




class Login extends \g\Controller
{
    public function get($msg = '')
    {
        if(\g\Http::session('admin_id')){
            $this->go();
        }
        $this->display('admin/login.html',['error_msg' => $msg]);
    }

    public function post(){
        $name = \g\Http::post('admin_name');
        $pass = \g\Http::post('admin_pass');
        $adminUser = new \model\admin\AdminUser();
        $adminRow = $adminUser->fetchRowByName($name);
        if($adminRow && $adminRow['fpass'] = $adminUser->pwd($pass)){
            \g\Http::setSession('admin_id' ,$adminRow['fuid']);
            \g\Http::setSession('admin_name',$name);
            $this->go();
        }else{
            $this->get('账号或者密码错误');
        }
    }
    private function go(){
        $url = '/admin/index';
        if(\g\Http::session('from_url')){
            $url = \g\Http::session('from_url');
        }
        \g\Http::go($url);
    }

}