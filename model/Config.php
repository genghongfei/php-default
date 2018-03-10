<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/11 上午2:14
 */


namespace model;


class Config extends \g\Model
{
    public function __call($name, $arguments)
    {
        $key = preg_replace_callback("/[A-Z]+/",function ($reg){
            return '_'.strtolower($reg[0]);
        },$name);
        $row = $this->fetchRow('fkey=?',strtoupper($key));
        if($row){
            return $row['fval'];
        }
        return '';
    }
}