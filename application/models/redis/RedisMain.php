<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/RedisServer.php';

/**
 * Main Redis Model , also as a parent class of other redis services
 *
 * @package 	bbcloud
 * @subpackage 	application
 * @category 	models/redis
 * @author 		lugui <463232672@qq.com>
 * @copyright 	CopyRight 2016
 * @since 		Version 1.0
 * @license
 */
class RedisMain extends RedisServer{

	/**
	 * 公共前缀，主要针对不同环境有变化的
	 * @var string
	 */
	public $main_prefix = '';

	/**
	 * 初始化数据信息
	 */
	public function __construct(){

		$CI =& get_instance();
		$CI->config->load('redis',TRUE);
		$config = $CI->config->item('redis','redis');
		parent::__construct($config);
		$prefix = $CI->config->item('prefix','redis');
		if(isset($prefix[ENVIRONMENT])){
			$this->main_prefix = $prefix[ENVIRONMENT];
		}

	}

	/**
	 * 释放redis连接
	 */
	public function __destruct(){
		if(isset($this->conn)){
			$this->conn->close();
		}
	}

}