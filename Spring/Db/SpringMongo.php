<?
/**
 +------------------------------------------------------------------------------
 * Spring框架 mongo数据库访问接口
 +------------------------------------------------------------------------------
 * @mobile	13183857698
 * @qq		78252859
 * @author  void <lkf5_303@163.com>
 * @version 3.1.4
 +------------------------------------------------------------------------------
 */
class SpringMongo implements IDataSource
{
	/**
	 * 连接数据库配置文件
	 */
	public  $configFile = null;

	/**
	 * 增删改操作选项
	 */
	public $options     = array(
		'safe'     => true,
		'fsync'    => true,
		'multiple' => true,
		);

	/**
	 * 当前数据库对象
	 */
	private  $db       = null;

	/**
	 * 当前连接对象
	 */
	private  $connect  = null;

	/**
	 * 异常信息
	 */
	private  $errorMsg = null;


	/**
	 * 初始化
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		if ( !class_exists('mongo') )
		{ 
			throw new SpringException('Not Support : mongo');
		}
	}
	
	/**
	 * 清理资源
	 *
	 * @access	public
	 * @return	void
	 */
	public function __destruct()
	{
		$this->close();
		$this->db         = null;
		$this->options    = null;
		$this->connect    = null;
		$this->errorMsg   = null;
		$this->configFile = null;
	}
	
	/**
	 * 打开数据库连接
	 *
	 * @access	private
	 * @return	void
	 */
	private function connect()
	{
		if ( $this->connect == null )
		{
			if ( !file_exists($this->configFile) ) 
			{
				throw new SpringException("数据库配置文件：".$this->configFile."不存在!");
			}
			require($this->configFile);
			
			try
			{
				$server        = $user && $password ? "mongodb://{$user}:{$password}@{$host}" : "mongodb://{$host}";
				$this->connect = new Mongo($server);
				$this->db      = $this->connect->$db;  
			}
			catch ( MongoConnectionException $e )
			{
				$this->errorMsg = $e->getMessage();
				ErrorHandle::record($this->errorMsg);
			}
		}		
	}
	
	/**
	 * 关闭数据库连接
	 *
	 * @access	private
	 * @return	void
	 */
	private function close()
	{
		if ( $this->connect ) 
		{
			$this->connect->close();
		}
		$this->connect = null;
	}
	
	/**
	 * 构造条件
	 *
	 * @access	private
	 * @param	array  $where 过滤字段
	 * @return	array
	 */
	private function where($where)
	{
		$wh = array();
		if ( isset($where['eq']) && is_array($where['eq']) && !empty($where['eq']) ) {
			foreach ($where['eq'] as $key => $value) {
				$wh[$key] = is_numeric($value) ? ( is_int($value) ? intval($value) : floatval($value) ) : $value;
			}
		}

		if ( isset($where['in']) && is_array($where['in']) && !empty($where['in']) ) {
			foreach ($where['in'] as $key => $value) {
				if ( is_array($value) ) {
					$wh[$key] = array('$in' => $value);
				}
			}
		}

		if ( isset($where['scope']) && is_array($where['scope']) && !empty($where['scope']) ) {
			foreach ($where['scope'] as $key => $value) {
				if ( is_array($value) && count($value) == 2  && $value[0] < $value[1]) {
					$value[0] = is_numeric($value[0]) ? ( is_int($value[0]) ? intval($value[0]) : floatval($value[0]) ) : $value[0];
					$value[1] = is_numeric($value[1]) ? ( is_int($value[1]) ? intval($value[1]) : floatval($value[1]) ) : $value[1];
					$wh[$key] = array('$gte' => $value[0], '$lte' => $value[1]);
				}
			}
		}

		if ( isset($where['like']) && is_array($where['like']) && !empty($where['like']) ) {
			foreach ($where['like'] as $key => $value) {
				if ( is_array($value) ) {
					$wh[$key] = new MongoRegex("\/*".implode('', $value)."/i");
				} else {
					$wh[$key] = new MongoRegex("\/*".$value."/i");
				}
			}
		}
		
		return $wh;
	}
	
	/**
	 * 构造排序规则
	 *
	 * @access	private
	 * @param	array	$order 排序字段
	 * @return	array
	 */
	private function order($order)
	{
		$orderBy = array();
		foreach ( $order as $key => $value )
		{
			$orderBy[$key] = strtolower($value) == 'desc' ? -1 : 1;
		}
		return $orderBy;
	}
	
	/**
	 * 获取一条数据
	 *
	 * @access	public
	 * @param	string	$tableKey	数据表标识
	 * @param	array	$rule		数据查询规则
	 * @return	array
	 */
	public function findOne($tableKey, $rule)
	{
		 $this->connect();
		 if ( $this->errorMsg ) 
		 {
			 return array();
		 }
		 
		 $where = $this->where($rule);
		 $col   = isset($rule['col']) && is_array($rule['col']) 
			      ? $rule['col'] 
			      : array();
		 
		 return $this->db->$tableKey->findOne($where, $col);
	}
	
	/**
	 * 获取多条数据
	 *
	 * @access	public
	 * @param	string	$tableKey	数据表标识
	 * @param	array	$rule		数据查询规则
	 * @return	array
	 */
	public function find($tableKey, $rule)
	{
		$this->connect();
		if ( $this->errorMsg ) 
		{
			return array();
		}
		
		$rows   = array();
		$where  = $this->where($rule);
		$col    = isset($rule['col']) && is_array($rule['col']) 
			      ? $rule['col'] 
			      : array();
		$order  = isset($rule['order']) 
			      ? $this->order($rule['order']) 
			      : array();
		$limit  = isset($rule['limit']) ? intval($rule['limit']) : 1;
		$limit  = $limit <=0 ? 1 : $limit;
		$cursor = $this->db->$tableKey->find($where, $col)->sort($order)->limit($limit);
		foreach ($cursor as $row)
		{
			$rows[] = $row;
		}
		
		return $rows;
	}

	/**
	 * 获取多条数据(数据分页时用)
	 *
	 * @access	public
	 * @param	string	$tableKey	数据表标识
	 * @param	array	$rule		数据查询规则
	 * @return	array
	 */
	public function findAll($tableKey, $rule)
	{
		$this->connect();
		if ( $this->errorMsg ) {
			return array(
				'total'  => 0,
				'record' => array(),
			);
		}

		$page   = isset($rule['page']) ? intval($rule['page']) : 1;
		$page   = $page <= 0 ? 1 : $page;
		$page   = $page > 10000 ? 1 : $page;
		$limit  = isset($rule['limit']) ? intval($rule['limit']) : 1;
		$limit  = $limit <=0 ? 1 : $limit;
		$col    = isset($rule['col']) && is_array($rule['col']) 
			      ? $rule['col'] 
			      : array();
		$offset = ($page-1)* $limit;
		$offset = ($offset < 0) ? 0 : $offset;
		$where  = $this->where($rule);
		$order  = isset($rule['order']) ? $this->order($rule['order']) : array();
		$rows   = array();
		$total  = $this->db->$tableKey->find($where, $col)->count();
		$cursor = $this->db->$tableKey->find($where, $col)->sort($order)
			->skip($offset)->limit($limit);
		
		foreach ( $cursor as $row )
		{
			$rows[] = $row;
		}

		return array(
			'total'  => $total,
			'record' => $rows,
			);
	}
	
	/**
	 * 统计数据
	 *
	 * @access	public
	 * @param	string	$tableKey	数据表标识
	 * @param	array	$rule		数据查询规则
	 * @return	int
	 */
	public function count($tableKey, $rule)
	{
		$this->connect();
		if ( $this->errorMsg ) 
		{
			return 0;
		}
		
		$where = $this->where($rule);
		$col   = isset($rule['col']) && is_array($rule['col']) 
			     ? $rule['col'] 
			     : array();

		return $this->db->$tableKey->find($where, $col)->count();
	}
	
	/**
	 * 创建一条数据
	 *
	 * @access	public
	 * @param	string	$tableKey	数据表标识
	 * @param	array	$data		数据信息[键值对]
	 * @return	int     0失败、大于0成功
	 */
	public function create($tableKey, $data)
	{
		if ( !is_array($data) || empty($data) ) 
		{
			return 0;
		}
		
		$this->connect();
		if ( $this->errorMsg ) 
		{
			return 0;
		}

		$data = $this->convert($data);
		try
		{
			$this->db->$tableKey->insert($data, $this->options);
			$result = 1;
		}
		catch( MongoCursorException $e ) 
		{
			$result = 0;
			$this->errorMsg = $e->getMessage();
			ErrorHandle::record($this->errorMsg);
		}
		return $result;
	}
	
	/**
	 * 修改数据
	 *
	 * @access	public
	 * @param	string	$tableKey	数据表标识
	 * @param	array	$data		数据信息[键值对]
	 * @param	array	$rule		数据修改规则
	 * @return	bool
	 */
	public function modify($tableKey, $data, $rule)
	{
		if ( !is_array($data) || empty($data) || !is_array($rule) || empty($rule) ) 
		{
			return false;
		}
		
		$this->connect();
		if ( $this->errorMsg ) 
		{
			return false;
		}

		$data  = array( '$set' => $this->convert($data) );
		$where = $this->where($rule);
		$where = $this->convert($where);
		
		try
		{
			$this->db->$tableKey->update($where, $data, $this->options);
			$bool = true;
		}
		catch( MongoCursorException $e ) 
		{
			$bool = false;
			$this->errorMsg = $e->getMessage();
			ErrorHandle::record($this->errorMsg);
		}
		return $bool;
	}
	
	/**
	 * 删除数据 
	 *
	 * @access	public
	 * @param	string	$tableKey	数据表标识
	 * @param	array	$rule		数据删除规则
	 * @return	bool
	 */
	public function remove($tableKey, $rule)
	{
		if ( !is_array($rule) || empty($rule) ) 
		{
			return false;
		}
		
		$this->connect();
		if ( $this->errorMsg ) 
		{
			return false;
		}

		$where = $this->where($rule);
		$where = $this->convert($where);

		try
		{
			$bool = $this->db->$tableKey->remove($where, $this->options);
			$bool = true;
		}
		catch( MongoCursorException $e ) 
		{
			$bool = false;
			$this->errorMsg = $e->getMessage();
			ErrorHandle::record($this->errorMsg);
		}
		return $bool;
	}
	
	/**
	 * 清空表数据
	 *
	 * @access	public
	 * @param	string	$tableKey	表名
	 * @return	bool
	 */
	public function drop($tableKey)
	{
		$this->connect();
		if ( $this->errorMsg ) 
		{
			return false;
		}
		
		return $this->db->$tableKey->drop();
	}

	/**
	 * 日志记录
	 *
	 * @access	private
	 * @return	void
	 */
	private function record()
	{
		return '';
	}
	
	/**
	 * 数据类型转换
	 *
	 * @access	private
	 * @param	array	$data  键值对数据
	 * @return	array
	 */
	private function convert($data)
	{
		if ( !is_array($data) || empty($data) )
		{
			return array();
		}

		foreach ( $data as $key => $value )
		{
			$data[$key] = is_numeric($value) ? ( is_int($value) ? intval($value) : floatval($value) ) : $value;
		}
		return $data;
	}
}
?>