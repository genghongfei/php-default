<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/7 下午11:52
 */


namespace controller;


class Pac extends \g\Controller
{
    public function get(){
        $appName = \g\Http::get('app','MacPro');
        $this->display('pac/pac.pac',[
            'proxy' => (new \model\pac\PacApp())->fetchProxy($appName),
            'domainList' => (new \model\pac\PacDomain())->fetchDomains()
        ]);
    }
}