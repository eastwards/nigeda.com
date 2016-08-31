<?
//Spring框架 类地图(键为类名、值为类文件路径)
return array(
	'ErrorHandle'		 => LibDir.'/Core/ErrorHandle.php',
	'SpringException'    => LibDir.'/Core/SpringException.php',
	'ServiceFactory'     => LibDir.'/Core/ServiceFactory.php',
	'Application'        => LibDir.'/Core/Application.php',
	'WebApplication'     => LibDir.'/Core/WebApplication.php',
	'ConsoleApplication' => LibDir.'/Core/ConsoleApplication.php',
	'IncludeApplication' => LibDir.'/Core/IncludeApplication.php',
	'Orm'			     => LibDir.'/ORM/Core/Orm.php',
	'Object'             => LibDir.'/Core/Object.php',
	'IEntity'            => LibDir.'/ORM/Core/IEntity.php',
	'Entity'             => LibDir.'/ORM/Core/Entity.php',
	'Request'			 => LibDir.'/MVC/Core/Request.php',
	'ModelActionBase'    => LibDir.'/MVC/Core/ModelActionBase.php',
	'Action'             => LibDir.'/MVC/Core/Action.php',
	'RpcAction'			 => LibDir.'/MVC/Core/RpcAction.php',
	'ConsoleAction'      => LibDir.'/MVC/Core/ConsoleAction.php', 
	'Model'			     => LibDir.'/MVC/Core/Model.php',
	'Module'			 => LibDir.'/MVC/Core/Module.php',
	'Session'		     => LibDir.'/MVC/Util/Session.php',
	'MessageBox'         => LibDir.'/MVC/Util/MessageBox.php',
	'Form'			     => LibDir.'/MVC/Util/Form.php',
	'FileSearcher'       => LibDir.'/MVC/Util/FileSearcher.php',
	'DbQuery'			 => LibDir.'/Db/DbQuery.php',
	'DataPage'           => LibDir.'/Util/Tool/DataPage.php',
	'Encrypt'            => LibDir.'/Util/Tool/Encrypt.php',
	'IO'                 => LibDir.'/Util/Tool/IO.php',
	'Log'                => LibDir.'/Util/Tool/Log.php',
	'RpcServer'		     => LibDir.'/Util/Rpc/RpcServer.php',
	'RpcClient'			 => LibDir.'/Util/Rpc/RpcClient.php',
	'RpcConcurrent'      => LibDir.'/Util/Rpc/RpcConcurrent.php',
	'MemCacheApi'		 => LibDir.'/ORM/Util/memcache.api.php',
	'FileCacheApi'		 => LibDir.'/ORM/Util/filecache.api.php',
	'BaseCacheApi'		 => LibDir.'/ORM/Util/basecache.api.php',
	'SolrApi'            => LibDir.'/ORM/Util/solr.api.php',
	'SphinxApi'			 => LibDir.'/ORM/Util/sphinx.api.php',
	);
?>