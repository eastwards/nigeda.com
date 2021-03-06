<?
//Memcache数据缓存
$configs[] = array(
'id'        => 'mem',
'enable'    => true,
'source'    => LibDir.'/Util/Cache/MmCache.php',
'className' => 'MmCache',
'import'    => array(LibDir.'/Util/Cache/ICache.php'),
'property'  => array(
    'expire'     => 1800,
	'configFile' => ConfigDir.'/memcache.config.php',
	'objRef'	 => array('encoding' => 'encoding'),
));


//Redis数据缓存（DB-entity 在使用）
$configs[] = array(
'id'        => 'redis',
'enable'    => true,
'source'    => LibDir.'/Util/Cache/RedisCache.php',
'className' => 'RedisCache',
'import'    => array(LibDir.'/Util/Cache/ICache.php'),
'property'  => array(
    'expire'     => 1800,
    'configFile' => ConfigDir.'/redis.config.php',
    'objRef'     => array('encoding' => 'encoding'),
));

//Redis数据缓存（页面action缓存或其他 在使用）
$configs[] = array(
'id'        => 'redisHtml',
'enable'    => true,
'source'    => LibDir.'/Util/Cache/RedisCache.php',
'className' => 'RedisCache',
'import'    => array(LibDir.'/Util/Cache/ICache.php'),
'property'  => array(
    'expire'     => 1800,
    'configFile' => ConfigDir.'/redishtml.config.php',
    'objRef'     => array('encoding' => 'encoding'),
));

//redis管理、清除缓存
$configs[] = array(
'id'        => 'qcache',
'enable'    => true,
'source'    => LibDir.'/Util/Structure/RedisManager.php',
'className' => 'RedisManager',
'property'   => array(
    'configFile' => ConfigDir.'/qcache.redis.config.php',
));

//Redis队列操作(队列配置，勿清)
$configs[] = array(
'id'        => 'redisQ',
'enable'    => true,
'source'    => LibDir.'/Util/Queue/RedisQ.php', 
'className' => 'redisQ',
'import'    => array(LibDir.'/Util/Queue/IQueue.php'),
'property'  => array(
    'expire'     => 1800,
    'configFile' => ConfigDir.'/redisq.config.php',
    'objRef'     => array('encoding' => 'encoding'),
));

?>