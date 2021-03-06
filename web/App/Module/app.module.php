<?
/**
 * 应用公用业务组件
 *
 * 应用相关的业务方法
 * 
 * @package	Model
 * @author	void
 * @since	2015-06-12
 */
abstract class AppModule extends Module
{
	public function __construct()
	{
		//自定义业务逻辑
		$this->getUser();
	}

	/**
	 * 获取业务对象(系统对接时使用)
	 * @author	void
	 * @since	2015-03-26
	 *
	 * @access	public
	 * @param	string	$name	业务代理类名
	 * @return	object  返回业务对象
	 */
	public function importBi($name)
	{
		static $config = array();
		if ( empty($config) ) {
			require(ConfigDir.'/Extension/service.config.php');
		}
		
		static $objList = array();
		if ( isset($objList[$name]) && $objList[$name] ) {
			return $objList[$name];
		}

		$file = BiDir.'/'.strtolower($name).'.bi.php';
		require_once($file);
		$className      = $name.'Bi';
		$bi             = new $className();
		$bi->url        = $config[$bi->apiId]['url'];
		$objList[$name] = $bi;
		
		return $bi;
	}

	protected function getUser()
	{
		$userinfo = Session::get(COOKIE_USER);
		if ( empty($userinfo) ){
			$this->username = '';
			$this->userId 	= '';
			$this->isLogin 	= false;
			return false;
		}else{
			$userinfo = unserialize($userinfo);
		}
		$this->username = $userinfo['username'];
		$this->userId 	= $userinfo['userId'];
		$this->isLogin 	= true;
		return true;
	}
        
        /**
	 * 执行原生sql语句
	 * @author	void
	 * @since	2015-06-19
	 *
	 * @access	public
	 * @param	string	$dbName	数据库名
	 * @return	mixed
	 */
	protected function fetchAll($dbName, $sql)
	{
                static $db = null;
		if ( $db == null ) {
			$db = new DbQuery($dbName);
		}
		
		return $db->fetchAll($sql);
	}

}
?>