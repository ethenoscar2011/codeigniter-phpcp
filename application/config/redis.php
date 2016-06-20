<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is a file to config the redis information
 *
 * @package 	bbcloud
 * @subpackage 	application
 * @category 	config
 * @author 		lugui <463232672@qq.com> 
 * @copyright 	CopyRight 2016
 * @since 		Version 1.0
 * @license
 * @example 
 * 
 * 
 */

$config['redis'] = array(
	'host' => '',
	'port' => 6379,
	'password' => ''
);

//--------------------------------------------------------------------------------------------------
// 不同环境的前缀配置，目前有开发环境、测试环境和生产环境三种
// 此处不配置生产环境的前缀，主要是为了兼容之前的一些redis键值
//--------------------------------------------------------------------------------------------------
$config['prefix'] = array(
	'development' => 'dev_',
	'testing' => 'test_',
	'production' => ''
);