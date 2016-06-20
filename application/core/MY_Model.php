<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require_once "MY_DbConnection.php";

/**
 * Parent model class
 *
 * @package 	bbcloud
 * @subpackage 	application
 * @category 	core
 * @author 		lugui <463232672@qq.com>
 * @copyright 	CopyRight 2016
 * @since 		Version 1.0
 * @license
 */
class MY_Model extends MY_DbConnection{

	/**
	 * 记录数据库查询语句错误信息的数组
	 * @var array
	 */
	public $sql_errors = [];

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 添加数据表数据
	 * @param mixed  $dbconn     数据库连接句柄
	 * @param string $table_name 数据库表明
	 * @param array  $data_arr   数据库表数据数组映射
	 * @return mixed 			 FALSE时候操作失败，否则返回受影响行数和插入的id号
	 */
	public function add_data($dbconn,$table_name,$data_arr){
		$result = $dbconn->insert($table_name,$data_arr);
		if( FALSE === $result ){
			$this->save_sql_error($dbconn->last_query(),$dbconn->error());
			return FALSE;
		}
		return array('affected_rows'=>$dbconn->affected_rows(),'insert_id'=>$dbconn->insert_id());
	}

	/**
	 * 更新数据表数据
	 * @param  mixed  $dbconn     数据库连接句柄
	 * @param  string $table_name 数据库表名
	 * @param  array  $data_arr   要更新的数组映射
	 * @param  mixed  $where_arr  过滤条件
	 * @return mixed              FALSE代表操作失败，否则返回受影响的行数
	 */
	public function update_data($dbconn,$table_name,$data_arr,$where_arr = NULL){
		$result = $dbconn->update($table_name,$data_arr,$where_arr);
		if(FALSE === $result){
			$this->save_sql_error($dbconn->last_query(),$dbconn->error());
			return FALSE;
		}
		return $dbconn->affected_rows();
	}

	/**
	 * 删除数据表数据
	 * @param  mixed  $dbconn     数据库连接句柄
	 * @param  string $table_name 数据库表名
	 * @param  mixed  $where_arr  过滤条件
	 * @return mixed              FALSE代表操作失败，否则返回受影响的行数
	 */
	public function delete_data($dbconn,$table_name,$where_arr = ''){
		$result = $dbconn->delete($table_name,$where_arr);
		if(FALSE == $result){
			$this->save_sql_error($dbconn->last_query(),$dbconn->error());
			return FALSE;
		}
		return $dbconn->affected_rows();
	}

	/**
	 * 保存SQL的错误信息或者调试信息
	 * @param  string $query     查询语句
	 * @param  mixed  $errorinfo 错误信息具体
	 * @param  string $mark      调试或者错误标记
	 * @return             
	 */
	public function save_sql_error($query,$errorinfo='',$mark=''){
		$this->sql_errors[] = [
			'query' => $query,
			'errorinfo' => $errorinfo,
			'mark' => $mark
		];
		return $this;
	}

}