<?php
/**
 * Created by PhpStorm.
 * User: shuyu
 * Date: 2019/7/23
 * Time: 16:54
 */

//定义日志接口
interface Log
{
    public function write();
}
class FileLog implements  Log{

    //重写方法
    public function write()
    {
        // TODO: Implement write() method.
        echo "file log write...";
    }
}
class DateBaseLog implements Log{
    public function write()
    {
        // TODO: Implement write() method.
        echo "datebase log write...";
    }
}
class User{
    protected $fielLog;

    public function __construct(Log $log)
    {
        $this->fielLog = $log;
    }
    public function login(){
        echo 'login success...';
        $this->fielLog->write();
    }
}

function make($concrete){
    $reflector  = new ReflectionClass($concrete);
    $constructor = $reflector->getConstructor();
    if(is_null($constructor)){
        return $reflector->newInstance();
    }
    $dependencies = $constructor->getParameters();
    $instances = getDependencies($dependencies);
    return $reflector->newInstanceArgs($instances);
}
function getDependencies($paramters){

    $dependencies = [];
    foreach ($paramters as $paramter) {
        $dependencies[] = make($paramter->getClass()->name);
    }
    return $dependencies;
}

//$user = new User(new DateBaseLog());
//$user->login();

$user = make('User');
$user->login();
exit;
