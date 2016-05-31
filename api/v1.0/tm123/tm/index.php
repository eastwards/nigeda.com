<?php

require '../../../../Slim/Slim.php';

//注册Slim框架自带的自动加载类
\Slim\Slim::registerAutoloader();

//创建实例
$app = new \Slim\Slim();
//显示
$app->get('/','tmRandom');
//添加
//$app->post('/','addHandle');
//删除
//$app->delete('/:id','deleteHandle');
//条件查询
//$app->get("/:search","searchHandle");

//显示
//$app->get('/hello/','showHandle');
$fopen = fopen('./log.log', 'wb');//新建文件命令 
$log = $app->getLog();
$log->setLevel(\Slim\Log::INFO);
$log->setEnabled(true);
$log->setWriter(new \Slim\LogWriter($fopen));


//获取商标随便列表
function tmRandom(){
    global $app;
    $get    = $app->request->get();
    $params = empty($get['data']) ? array() : json_decode($get['data'],1);
    try{
        $obj    = require_once('../../../include.php');
        $result = spring::out('openapi')->request($params);
        echoResult($result);
    }catch(PDOException $e){
        echo '{"err":'.$e->getMessage().'}';
    }
}



function echoResult($data)
{
    global $log;
    $result = json_encode($data);
    //$log->notice($result);
    echo $result;
}

//运行应用
$app->run();