<?php 

/**
 * Redis 控制服务器
 *
 * 用来做 Redis 相关的中转处理
 * 
 * @package 	bbcloud
 * @subpackage 	application
 * @category 	library
 * @author 		lugui <463232672@qq.com>
 * @copyright 	CopyRight 2016
 * @since 		Version 1.0
 * @license
 */
class RedisServer {

	/**
	 * Redis 服务器的 Host/IP
	 * @var string
	 */
	private $_host = null;

	/**
	 * Redis 服务器的端口，默认 6379
	 * @var integer
	 */
	private $_port = 6379;

	/**
	 * Redis 服务器的密码
	 * @var string
	 */
	private $_password = null;

	/**
	 * 是否启用Redis的长链接
	 * @var boolean
	 */
	private $_pconnect = false;

	/**
	 * Redis 服务器的连接句柄
	 * @var mixed
	 */
	private $_conn = null;

	/**
	 * 构造函数
	 * @param array $config 配置信息
	 */
	public function __construct($config = array()){
		if(!empty($config)){
			$this->setAttributes($config);
		}
	}

	/**
	 * 连接 redis 
	 * @return  
	 */
	public function connect(){

		$this->_conn = new Redis();

		if($this->_pconnect){
			$this->_conn->pconnect($this->_host,$this->_port);
		}else{
			$this->_conn->connect($this->_host,$this->_port);
		}
		
		if(!empty($this->_password)){
			$this->_conn->auth($this->_password);
		}

		return $this;
	}

	/**
	 * 批量设置属性信息
	 * @param array $config 配置信息
	 */
	public function setAttributes($config = array()){

		$setting = array(
			'host' => $this->_host,
			'port' => $this->_port,
			'password' => $this->_password,
			'pconnect' => $this->_pconnect
		);
		$setting = array_merge($setting,$config);
		foreach($setting as $key => $value){
			$key = '_'.$key;
			$this->$key = $value;
		}
		$this->connect();
		return $this;

	}

	/**
	 * 获取属性信息
	 * @param  string $property_name 属性名称
	 * @return mixed                 属性的值
	 */
	public function __get($property_name){
		$property_name = '_'.$property_name;
		if(isset($this->$property_name)){
			return $this->$property_name;
		}
		return null;
	}

	/**
	 * 设置属性信息
	 * @param string $property_name 属性名称
	 * @param mixed  $value         属性的值
	 */
	public function __set($property_name,$value){
		$property_name = '_'.$property_name;
		$this->$property_name = $value;
	}


}