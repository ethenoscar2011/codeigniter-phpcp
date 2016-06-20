<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This is a file to config the map between dbname and the dbconnections array
 *
 * @package 	codeigniter-phpcp
 * @subpackage 	application
 * @category 	config
 * @author 		lugui <463232672@qq.com> 
 * @copyright 	CopyRight 2016
 * @since 		Version 1.0
 * @license
 * @example 
 * 
 * Assuming that your config is like this :
 * 
 * $config['bbcloud'] = array(
 * 		'master' => array('master01','master02'),
 * 		'slave' => array('slave01','slave02')
 * );
 *
 * What you should do is to make sure that your database.php config file has the 
 * master01,master02,slave01,slave02 configurations
 * 
 * 
 */

$config['test1'] = array(
	'master' => array('masterone'),
	'slave' => array('slaveone')
);

$config['test2'] = array(
	'master' => array('mastertwo'),
	'slave' => array('slavetwo')
);