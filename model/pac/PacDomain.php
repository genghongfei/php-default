<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/9 下午5:19
 */


namespace model\pac;


class PacDomain extends \g\Model
{
    public function fetchDomains(){
        return $this->fetchRows('1=1');
    }
}