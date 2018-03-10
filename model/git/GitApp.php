<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/11 上午1:02
 */


namespace model\git;


class GitApp extends \g\Model
{
    public function fetchAll(){
        return $this->fetchRows('1=1');
    }
    public function fetchRowByGitName($name){
        $list = $this->fetchAll();
        if($list){
            foreach ($list AS $item){
                $gitname = strstr(basename($item['fgiturl']),'.');
                if($gitname == $name){
                    return $item;
                }
            }
        }
        return null;
    }
    public function deployByGitName($gitName){
        $conf = $this->fetchRowByGitName($gitName);
        if($conf){
            $path = $conf['fpath'].DS.$gitName;
            $cmd = sprintf("cd %s && git pull",$path);
            if(!file_exists($path)){
                $cmd = sprintf("cd %s && git clone %s",$conf['fpath'],$conf['fgiturl']);
                if(!file_exists($conf['fpath'])){
                    mkdir($conf['fpath'],0777,true);
                }
            }
            system($cmd);;
        }
    }
}