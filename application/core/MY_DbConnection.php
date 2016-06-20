<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The db connection manage class
 *
 * @package 	bbcloud
 * @subpackage 	application
 * @category 	core
 * @author 		lugui <463232672@qq.com>
 * @copyright 	CopyRight 2016
 * @since 		Version 1.0
 * @license
 */
class MY_DbConnection extends CI_Model{

	/**
	 * Record the db connections keys
	 * @var array
	 */
	protected $conns = [];

	/**
	 * Constructor
	 */
	public function __construct(){
		parent::__construct();
		$this->lang->load('dbconnection_lang');
		$this->load->helper('array');
	}

	/**
	 * Get the db connection by manual name
	 *
	 * The key is like masterBbcloud or slaveBbcloud :
	 * If the prefix is master , then it will read the database config file for the bbcloud's master config array and get random one of it 
	 * If the prefix is slave  , then it will read the database config file for the bbcloud's slave  config array and get random one of it 
	 *
	 * As we kown  the master db is maintaining the writting operations of database while slave db handdling the read operations 
	 * 
	 * @param  string $key The request attribute
	 * @return mixed      
	 */
	public function get_dbconn($key){

		$is_master = strpos($key,'master');

		if($is_master === 0){
			$dbname = $this->_get_dbname(substr($key,strlen('master')),'master');
			get_instance()->$key = $this->load->database($dbname,TRUE);
			$this->conns[] = $key;
			return get_instance()->$key;
		}

		$is_slave = strpos($key,'slave');
		if($is_slave === 0){
			$dbname = $this->_get_dbname(substr($key,strlen('slave')),'slave');
			get_instance()->$key = $this->load->database($dbname,TRUE);
			$this->conns[] = $key;
			return get_instance()->$key;
		}
		show_error($this->lang->line('dbconn_dbname_illegal'));

	}
	
	/**
	 * Get the model attribute 
	 * it will try the system attribute first , and then the manual ones
	 * 
	 * @param  string $key The attribute name
	 * @return mixed      
	 */
	public function __get($key){

		if(isset(get_instance()->$key)){
			return get_instance()->$key;
		}
		return $this->get_dbconn($key);

	}

	/**
	 * Destruct the db connections while the request end
	 */
	public function __destruct(){
		$CI = &get_instance();
		array_walk($this->conns,function($value) use (&$CI){
			if(isset($CI->$value)){
				$CI->$value->close();
			}
		});
		unset($this->conns);
	}

	/**
	 * Get the db name which will be connected
	 * 
	 * @param  string $name the map dbname
	 * @param  string $type the map type : slave or master
	 * @return string       the db name
	 */
	private function _get_dbname($name,$type){

		$dbname = lcfirst($name);
		if(!$dbname) show_error($this->lang->line('dbconn_dbname_illegal'));
		$this->config->load('dbmap',TRUE);
		$map_arr = $this->config->item($dbname,'dbmap');
		if(!isset($map_arr[$type]) || empty($map_arr[$type])){
			show_error($this->lang->line('dbconn_dbname_not_exist'));
		}
		return count($map_arr[$type]) == 1 ? $map_arr[$type][0] : random_element($map_arr[$type]);

	}


}