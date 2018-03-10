<?php
/**
 *  支持gitHub中的WebHook
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/10 下午10:49
 */


namespace controller\git\deploy;


class GitWebHook extends \g\Controller
{
    public function get(){
        $req = json_decode(file_get_contents("php://input"),true);
        $gitName = $req['repository']['name'];
        (new \model\git\GitApp())->deployByGitName($gitName);
    }
}