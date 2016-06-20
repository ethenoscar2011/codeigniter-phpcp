<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**********************************************************
 * 				该文件记录了常用的与输出结果相关的函数
 ************************************************************/

/**************************************************
 * 对数据结果以统一的json输出情况进行处理
 * 
 * @param string $result   “true” or "false"
 * @param array $data   要返回的详细数据信息
 * @param string $msg   要提示的内容
 * @param string $code   返回的状态码
 * @param string $call_back   如果是jsonp请求
 * @return string   返回组合后的json数据
 ****************************************************/
function json_result($result,$msg,$data=array(),$code='',$call_back='')
{
	$result_arr = array(
		'result' => (string)$result,
		'msg' => (string)$msg,
		'code' => (string)$code
	);
	
	if(!empty($data))
	{
		$result_arr['data'] = $data;
	}
		
	$json_str = json_encode($result_arr);
	if(!empty($call_back))
	{
		$json_str = $call_back.'('.$json_str.')';
	}
	
	return $json_str;
	
}//end of func json_result

/*************************************************
 * 返回请求成功数据的json格式
 * 
 * @param string $msg   要返回的消息内容
 * @param string $code   要返回的状态码
 * @return string   返回经过处理生成的json字符串
 ***************************************************/
function json_success($msg,$data=array(),$code='')
{
	$call_back = '';
	if(isset($_GET['callback']))
	{
		$call_back = trim($_GET['callback']);
	}
	
	return json_result('true', $msg,$data,$code,$call_back);
	
}//end of func json_success

/*****************************************************
 * 返回请求失败数据的json格式
 * 
 * @param string $msg   要返回的消息内容
 * @param string $code   要返回的状态码
 * @return string   返回经过处理生成的json字符串
 ******************************************************/
function json_error($msg,$code='')
{
	$call_back = '';
	if(isset($_GET['callback']))
	{
		$call_back = trim($_GET['callback']);
	}
	
	return json_result('false', $msg,'',$code,$call_back);
	
}//end of func json_error