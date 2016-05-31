<?php
/**
 * Spring out include file.
 * 方便其他项目调用本项目框架功能代码。(同Spring框架不适用本方式)
 */
set_time_limit(0);                                          //设置超时时间
$webDir = dirname(__FILE__);                                //获取当前路径
$libDir = realpath($webDir . '/../Spring');
      
define('WebDir', $webDir);                                  //定义项目路径
define('ActionDir', $webDir.'/App/Console');                //定义控制器存放路径
require_once($libDir.'/Spring.php');                             //载入框架入口文件
require_once(ConfigDir.'/app.config.php');                       //载入应用全局配置

return Spring::out();

?>
