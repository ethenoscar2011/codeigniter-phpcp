<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/******************************************************
 * 		该文件记录所有与请求相关的函数
 ******************************************************/

/********************************
 * 判断HTTP请求是否是post请求
 * 
 * @return boolean
 ********************************/
function is_post()
{
	return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
	
}//end of func is_post

/***************************************************
 * 判断HTTP请求是否通过XMLHTTP发起的，即ajax请求
 * @return boolean
 ****************************************************/
function is_ajax()
{
	$r = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
	return $r == 'xmlhttprequest';
	
}//end of func is_ajax

/**********************************************************
 * 进行HTTP GET请求，并返回相关的数据
 *
 * @param string $get_url   要进行请求的url地址
 * @param boolean $is_result   是否返回数据
 * @return mixed    返回交互的结果信息
 **********************************************************/
function curl_get($get_url,$is_return=TRUE)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);//强制协议为1.0
    curl_setopt($ch, CURLOPT_HTTPHEADER,array('Expect:'));//防止两次响应之间的耗时
    if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4'))
    {
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    curl_setopt($ch, CURLOPT_URL, $get_url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if($is_return)
    {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $getResult = curl_exec($ch);
    }
    else
    {
        curl_exec($ch);
    }
    curl_close($ch);
    if($is_return)
    {
        return $getResult;
    }
}//end of func curl_get

/***************************************************
 * 进行HTTP POST请求,主要以json格式返回信息
 *
 * @param string $post_url   要进行请求的url地址
 * @param array $post_data   进行交互需要的数据信息
 * @param boolean $is_return   是否返回数据
 * @return mixed   返回交互结果的信息
 ***************************************************/
function curl_post($post_url,$post_data,$is_return=TRUE)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); //强制协议为1.0
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //头部要送出'Expect:',防止两次响应之间的耗时或者响应出错的信息
    //设置curl默认访问为IPv4
    if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4'))
    {
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    curl_setopt($ch, CURLOPT_URL, $post_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
    if($is_return)
    {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $post_result = curl_exec($ch);
    }
    else
    {
        curl_exec($ch);
    }
    curl_close($ch);
    if($is_return)
    {
        return $post_result;
    }
}//end of func curl_post

/************************************************************************
 * 用curl进行HTTP请求-通用方法
 * @param string $request_url  请求的链接地址
 * @param string $method  请求的方法POST,GET,PUT,DELETE
 * @param string|array $data 请求时传输的数据
 * @param array $headers  请求时发送的头部信息
 * @param integer $timeout  请求超时时间(单位为:秒)
 * @return array  返回请求的结果
 *************************************************************************/
function curl_request($request_url,$method,$data=null,$headers=null,$timeout=null)
{
    if(!$request_url)
    {
        return FALSE;
    }
    if(!$method)
    {
        $method = 'GET';
    }
    if( ('GET'==strtoupper($method) || 'DELETE' ==strtoupper($method) ) && $data )
    {
        $request_url .= '?'.http_build_query($data);
    }
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $request_url);
    curl_setopt($curl_handle, CURLOPT_HEADER, false);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); //强制协议为1.0
    curl_setopt ( $curl_handle, CURLOPT_CUSTOMREQUEST, strtoupper($method ));
    // set post data
    if( strtoupper($method)=='POST' )
    {
        curl_setopt($curl_handle, CURLOPT_POST, true);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
    }
    if(strtoupper($method)=='PUT')
    {
        curl_setopt($curl_handle,CURLOPT_POSTFIELDS,$data);
    }
    //设置超时时间
    if(null !== $timeout && is_numeric($timeout)){
        curl_setopt($curl_handle, CURLOPT_TIMEOUT,$timeout); 
    }
    // set header
    if( $headers )
    {
        $header_param = array();
        foreach( $headers as $name=>$value )
        {
            $header_param[] = $name.':'.$value;
        }
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $header_param);
    }
    else
    {
        //头部要送出'Expect:',防止两次响应之间的耗时或者响应出错的信息
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Expect:'));
    }
    //设置curl默认访问为IPv4
    if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4'))
    {
        curl_setopt($curl_handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $response = curl_exec($curl_handle);
    if( false===$response )
    {
        $return = array(
            'result' => false,
            'err_msg' => 'Request error('.curl_errno($curl_handle).') - '.curl_error($curl_handle),
            );
    }
    else
    {
        $return = array(
            'result' => true,
            'response' => $response,
            );
    }
    curl_close($curl_handle);
    return $return;
}//end of func curl_request

/*******************************
 * 请求返回并弹出提示框
 * @param string $msg
 ********************************/
function back_with_msg($msg)
{
	echo "<script>alert('{$msg}');history.back()</script>";
}

/**
 * 获取所有请求的头部信息
 * @return array
 */
function get_request_headers(){
    if(function_exists('getallheaders')){
        return getallheaders();
    }
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
}