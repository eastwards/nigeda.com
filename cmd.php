<?
/**
 * 项目入口
 */
set_time_limit(0);                                              //设置超时时间
$webDir = dirname(__FILE__);                                    //获取当前路径
$libDir = realpath($webDir . '/Spring');                        //获取框架路径

define('Uri', isset($argv[1]) ? $argv[1] : 'index/index/');     //保存命令行参数        
define('WebDir', $webDir.'/web');                               //定义项目路径
define('ActionDir', WebDir.'/App/Console');                     //定义控制器存放路径
define('PHPPath', 'php');                                       //配置PHP执行路径

require($libDir.'/Spring.php');                                 //载入框架入口文件
require(ConfigDir.'/app.config.php');                           //载入应用全局配置
Spring::run(2);                                                 //启动框架
?>