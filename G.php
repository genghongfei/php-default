<?php
/**
 *
 * @Author: 耿鸿飞<genghongfei@suule.com>
 * @Date: 2018/3/7 下午11:43
 */
namespace g;
define('DS',DIRECTORY_SEPARATOR);
define('ROOT',dirname(__FILE__).DS);
if($_SERVER['RUN_DEV'] == 'MacPro') { //开发机器
    define('dbdsn', 'mysql:host=127.0.0.1;dbname=gtool');
    define('dbuser', 'root');
    define('dbpass', '');
    define('LOG_DIR', '/Data/logs/apps/default/');
}
class G{
    static function run()
    {
        spl_autoload_register('\g\G::loadClass');
        $url = preg_replace("/^(\/)*/", "", $_SERVER['REQUEST_URI']);
        $url = preg_replace("/\?.*$/", "", $url);
        $className = 'Index';
        if ($url) {
            $className = ucfirst(basename($url));
            $dirName = dirname($url);
            if ($dirName != '.') {
                $className = preg_replace("/\//", "\\", $dirName) . '\\' . $className;
            }
        }
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        try {
            $class = '\\controller\\' . $className;
            $cls = new $class;
            call_user_func_array([$cls, $method], []);
        }catch (\ErrorException $e){
            GLog::error($e->getTrace());
        }

    }
    public static function loadClass($class){
        $filepath = ROOT.str_replace('\\',DS,$class).'.php';
        if(file_exists($filepath)){
            include_once $filepath;
        }
    }
}
abstract class Controller{
    public function __construct()
    {
        $this->before();
    }
    public function __destruct()
    {
        $this->after();
    }

    protected function before(){

    }
    protected function after(){

    }

    abstract function get();
    public function post(){
        $this->get();
    }
    public function delete(){
        $this->get();
    }
    public function put(){
        $this->get();
    }
    protected function display($tpl,$var = []){
        $file = ROOT.'views'.DS.$tpl;
        extract($var);
        include $file;
    }
}

/**
 * 管理后台的Controller控制器
 * Class AdminController
 * @package g
 */
class AdminController{

}
class Model{
    private $table_name = '';
    public function __construct($tableName = '')
    {
        if($tableName){
            $this->table_name = $tableName;
        }else{
            $class = basename(str_replace('\\',DS,get_called_class()));
            $class = preg_replace_callback("/[A-Z]+/",function ($reg){
                return '_'.strtolower($reg[0]);
            },$class);
            $this->table_name = substr($class,1);
        }
    }
    public function add($data){
        $sql = sprintf("INSERT INTO `%s`(`%s`) VALUES(%s)",$this->table_name,implode('`,`',array_keys($data)),substr(str_repeat(',?',count($data))));
        $args = array_values($data);
        array_unshift($args,$sql);
        return call_user_func_array([\g\Db::ins(),'insert'],$args);
    }
    public function delete($where){
        $args = func_get_args();
        $args[0] = sprintf("DELETE FROM %s WHERE %s",$this->table_name,$where);
        return call_user_func_array([\g\Db::ins(),'delete'],$args);
    }
    public function fetchRow($where){
        $args = func_get_args();
        $args[0] = sprintf("SELECT * FROM %s WHERE %s",$this->table_name,$where);
        return call_user_func_array([\g\Db::ins(),'fetchRow'],$args);
    }
    public function fetchRows($where){
        $args = func_get_args();
        $args[0] = sprintf("SELECT * FROM %s WHERE %s",$this->table_name,$where);
        return call_user_func_array([\g\Db::ins(),'fetchRows'],$args);
    }
}

class Db{
    /**
     * @var \PDO
     */
    private $pdo;
    private static $self = false;

    /**
     * @return bool|Db
     */
    public static function ins(){
        if(self::$self === false){
            self::$self = new self;
        }
        return self::$self;
    }
    private function __construct()
    {
        $this->pdo = new \PDO(dbdsn,dbuser,dbpass,[\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;']);
    }

    public function begin(){
        if(!$this->pdo->inTransaction()){
            return $this->pdo->beginTransaction();
        }
        return true;
    }
    public function commit(){
        if($this->pdo->inTransaction()){
            return $this->pdo->commit();
        }
    }
    public function rollBack(){
        if($this->pdo->inTransaction()){
            return $this->pdo->rollBack();
        }
    }

    /**
     * 插入数据
     * @param $sql
     * @return string
     */
    public function insert($sql){
        $st = call_user_func_array([$this,'query'],func_get_args());
        return $this->pdo->lastInsertId();
    }

    /**
     * 删除记录
     * @param $sql
     * @return mixed
     */
    public function delete($sql){
        $st = call_user_func_array([$this,'query'],func_get_args());
        return $st->rowCount();
    }

    /**
     * 获取多条记录
     * @param $sql
     * @return mixed
     */
    public function fetchRows($sql){
        $st = call_user_func_array([$this,'query'],func_get_args());
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * 获取单条记录
     * @param $sql
     * @return mixed
     */
    public function fetchRow($sql){
        $st = call_user_func_array([$this,'query'],func_get_args());
        return $st->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * 更新数据
     * @param $sql
     * @return mixed
     */
    public function update($sql){
        $st = call_user_func_array([$this,'query'],func_get_args());
        return $st->rowCount();
    }

    /**
     * @param $sql
     * @param array $args
     * @return \PDOStatement
     */
    private function query($sql){
        $args = func_get_args();
        array_shift($args);
        $st = $this->pdo->prepare($sql);
        $st->execute($args);
        //$st->fetch()
        return $st;
    }
}

class Http{
    static function get($k,$def = ''){
        return self::_fetchDateByKey($_GET,$k,$def);
    }
    static function post($k,$def = ''){
        return self::_fetchDateByKey($_POST,$k,$def);
    }
    static function request($k,$def = ''){
        return self::_fetchDateByKey($_REQUEST,$k,$def);
    }
    static function cookie($k,$def = ''){
        return self::_fetchDateByKey($_COOKIE,$k,$def);
    }
    static function session($k,$def = ''){
        session_start();
        return self::_fetchDateByKey($_SESSION,$k,$def);
    }
    static function setSession($k,$v){
        session_start();
        $_SESSION[$k] = $v;
    }
    private static function _fetchDateByKey($data,$key,$def){
        return isset($data[$key]) ? $data[$key] : $def;
    }
    static function go($url){
        header(sprintf("Location: %s",$url));
        die();
    }
}

class GLog{
    public static function error(){
        $args = func_get_args();
        if($args){
            self::saveLog('error',$args);
        }
    }
    private static function saveLog($name,$args){
        $fpath = sprintf('%s%s%s.log',LOG_DIR,date("Y/m/d-"),$name);
        $dir = dirname($fpath);
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        file_put_contents($fpath,sprintf("[%s] %s\n",date("H:i:s"),json_encode($args,JSON_UNESCAPED_UNICODE)),FILE_APPEND|LOCK_EX);
    }
}
