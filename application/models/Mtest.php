<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The test Model
 *
 * @package 	codeigniter-phpcp
 * @subpackage 	application
 * @category 	models
 * @author 		lugui <463232672@qq.com>
 * @copyright 	CopyRight 2016
 * @since 		Version 1.0
 * @license
 */
class Mtest extends MY_Model{

	/**
	 * The relate table name
	 * @var string
	 */
	public $table_name = 'test';

	/**
	 * Default constructor
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * test add data
	 * @param array $data_arr
	 * @return mixed
	 */
	public function add_test($data_arr){
		// return $this->add_data($this->masterTest1,$this->table_name,$data_arr);
		$this->masterTest1->insert($this->table_name,$data_arr);
		return array('affected_rows'=>$this->masterTest1->affected_rows(),'insert_id'=>$this->masterTest1->insert_id());
	}

	/**
	 * test query all data
	 * @return  mixed
	 */
	public function query_all(){
		return $this->slaveTest1->get($this->table_name)->result_array();
	}


}