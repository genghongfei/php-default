<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/9 下午5:16
 */


namespace model\pac;


class PacApp extends \g\Model
{
    public function fetchProxy($appName){
        $row = $this->fetchRow('fname=?',$appName);
        if($row){
            return $row['fproxy'];
        }
        return 'SOCKS5 127.0.0.1:3001;';
    }
}