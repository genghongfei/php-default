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
                $gitname = strstr(basename($item['fgiturl']),'.',true);
                //var_dump($gitname,$name);
                if($gitname == $name){
                    return $item;
                }
            }
        }
        return null;
    }
    public function deployByGitName($gitName){
        $conf = $this->fetchRowByGitName($gitName);
        //var_dump($conf);
        if($conf){
            $path = $conf['fpath'].DS.$gitName;
            $cmd = sprintf("cd %s && git pull",$path);
            if(!file_exists($path)){
                $this->nginxConf($conf,$path);
                $cmd = sprintf("cd %s && git clone %s",$conf['fpath'],$conf['fgiturl']);
                if(!file_exists($conf['fpath'])){
                    mkdir($conf['fpath'],0777,true);
                }
            }
            system($cmd);
        }
    }

    /**
     * 创建Ngnx配置
     * @param $conf
     */
    private function nginxConf($conf,$path,$forceWrite = false){
        $nginxConfDir = (new \model\Config())->nginxConfDir();
        $fname = $nginxConfDir.DS.$conf['fdomain'].'.conf';
        if(file_exists($fname) === false || $forceWrite) {
            $str = 'server {
    listen 80;
    server_name  %s;
    root         %s;
	index %s;
	location / {
		try_files $uri $uri /%s?$args;
	}
    include online;
    error_page 404 /404.html;
        location = /40x.html {
    }
    access_log %s/%s.log;
    error_log %s/%s.error;
    

    error_page 500 502 503 504 /50x.html;
        location = /50x.html {
    }
}';
            $nginxConf = sprintf($str,
                $conf['fdomain'],
                $path.DS.$conf['fpublic'],
                $conf['findex'],
                $conf['findex'],
                $conf['flogpath'],
                $conf['fdomain'],
                $conf['flogpath'],
                $conf['fdomain']
            );
        }
        file_put_contents($fname,$nginxConf);
        $this->resetNginx();
    }
    private function resetNginx(){

    }
}