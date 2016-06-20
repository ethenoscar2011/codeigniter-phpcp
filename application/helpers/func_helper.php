<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/***********************************************
 * 该文件记录了所有常用的功能函数
 *************************************************/

/************************************************
 * 以一种格式化的格式输出变量内容
 *
 * @param mixed $vars    要输出的变量
 * @param string $label    标记内容
 * @param boolean $return 是否返回内容
 * @return
 *************************************************/
function dump($vars, $label = '', $return = FALSE) {
    if (ini_get('html_errors'))
    {
        $content = "<pre>\n";
        if ($label != '')
        {
            $content .= "<strong>{$label} :</strong>\n";
        }
        $content .= htmlspecialchars(print_r($vars, TRUE));
        $content .= "\n</pre>\n";
    }
    else
    {
        $content = $label . " :\n" . print_r($vars, TRUE);
    }
    if ($return)
    {
        return $content;
    }
    echo $content;

}//end of func  dump

/***********************************
 * 设置HEADER响应头的编码格式为utf8
 ***********************************/
function set_header_utf8()
{
    header("Content-type:text/html;charset=utf-8");
    
}//end of func set_header_utf8

/********************************************************
 * 以二维数组的第二维的某个下标元素的值，转换为三维数组
 * 
 * @param array $db_arr   要进行处理的数组
 * @param string $filed   元素的名字
 * @return array    返回三维数组
 * @example   
 * $a = array(
 *         [0] => array(
 *              ['t']=> 'abc',
 *              ......
 *          ),
 *          [1] => array(
 *              ['t']=> 'abc',
 *              ......
 *          ),
 *          [2] => array(
 *              ['t']=>'def',
 *              ......
 *          ),
 *     );
 * 调用:arr_rebuild_by($a,'t')后转变为：
 *      array(
 *          ['abc'] => array(
 *              [0] => array(
 *                  ['t'] => 'abc',
 *                  ......
 *              ),
 *              [1] => array(
 *                  ['t'] => 'abc',
 *                  ......
 *              ),
 *          ),
 *          ['def'] => array(
 *              [0] => array(
 *                  ['t']=>'def',
 *                  ......
 *              ),
 *          ),
 *      );
 *********************************************************/
function rebuild_db_arr($db_arr , $filed)
{
    $new_arr = array();
    
    foreach($db_arr as $key=>$val)
    {
        if(!isset($new_arr[$val[$filed]]))
        {
            $new_arr[$val[$filed]][0] = $val;
        }
        else 
        {
            array_push($new_arr[$val[$filed]], $val);
        }
    }
    
    return $new_arr;
    
}//end of func rebuild_db_arr

/************************************************
 * 数据库常用的二维数组合并操作
 * 
 * @param array $db_arr1   要合并的数组
 * @param array $db_arr2   要合并的另一个数组
 * @param string $field   要进行合并的字段名字
 * @return array   返回合并后的数组
 * @example $db_arr1 = array(
 *                  array('uid'=>1,'username'=>'test1'),
 *              );
 *              $db_arr2 = array(
 *                  array('uid'=>1,'usex'=>'1'),
 *                  array('uid'=>2,'usex'=>'2')
 *              );
 *              merge_db_arr($db_arr2, $db_arr1, 'uid');
 *              返回数据是：
 *              array(
 *                  array('uid'=>1,'usex'=>'1','username'=>'test1'),
 *                  array('uid'=>2,'usex'=>2)
 *              )
 ************************************************/
function merge_db_arr($db_arr1,$db_arr2,$field)
{
    $new_arr = array();
    
    foreach($db_arr1 as $arr1)
    {
        $tmp_arr = $arr1;
        if(isset($arr1[$field]))
        {
            $f_value = $arr1[$field];
            foreach ($db_arr2 as $arr2)
            {
                if(isset($arr2[$field]) &&  $arr2[$field] === $f_value)
                {
                    $tmp_arr = array_merge($arr1, $arr2);
                }
            }
        }
        
        array_push($new_arr,$tmp_arr);
        
    }
    
    return $new_arr;
    
}//end of func merge_db_arr

/***************************************************
 * 判断目录是否可写，如果目录不存在，可以自定义是否创建
 * 
 * @param string $dir_path   目录路径
 * @param boolean $is_create   目录不存在时是否允许创建目录
 * @return boolean   true则代表目录可写
 ****************************************************/
function is_dir_writable($dir_path,$is_create=FALSE)
{
    if($dir_path==='')
    {
        return FALSE;
    }
    
    if(file_exists($dir_path))
    {
        if(is_dir($dir_path) && is_writable($dir_path))
        {
            return TRUE;
        }
        return FALSE;
    }
    else
    {
        if($is_create)
        {
            if(FALSE === mkdir($dir_path,0777,TRUE))
            {
                return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }
    
}//end of func is_dir_writable

/*****************************************
 * 判断是否手机号码
 * 
 * @param string $mobile   要验证的手机号
 * @return boolean   TRUE代表是手机号码
 *****************************************/
function is_mobile($mobile)
{
    if(preg_match("/^1[3-9][0-9]\d{8}$/", $mobile))
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
    
}//end of func $mobile

/**********************************************
 * 判断邮箱地址是否合法
 * 
 * @param string $email   要验证邮箱地址
 * @return boolean   TRUE代表是合法的
 *******************************************/
function is_email($email)
{
    return (bool)filter_var($email,FILTER_VALIDATE_EMAIL);
    
}//end of func is_email

/*********************************************************************
 * 判断是否符合安全性的密码
 * 
 * @param string $pwd   要验证的密码
 * @param boolean $strict   是否启用严格密码,TRUE时密码必须由字母和数字组成
 * @return boolean   如果符合安全性则返回TRUE
 *********************************************************************/
function is_good_pwd($pwd,$strict=FALSE)
{
    if(!$strict)
    {
        return preg_match("/^[A-Za-z0-9]{6,20}$/", $pwd);
    }
    else
    {
        return preg_match("/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,20}$/", $pwd);
    }
    
}//end of func is_good_pwd

/************************************************
 * 判断字符串是否超过了预定义的长度值
 * 特别注意：该函数只适合非中文形式的字符串
 * 
 * @param string $str   要检查的字符串
 * @param integer $len   要检查的长度上限
 * @return boolean   TRUE代表没有超过，是合法的
 *************************************************/
function is_legal_len($str,$len)
{
    if(!is_int($len))
    {
        return FALSE;
    }
    
    if(!isset($str{$len+1}))
    {
        return FALSE;
    }
    
    return TRUE;
    
}//end of func is_legal_len

/***************************************************************************
 *  获取字符串的长度信息，允许中文编码格式
 *  
 * @param string $str   要检查的字符串
 * @param integer $unicode_type   字符串的编码格式，1为utf-8，2为gbk编码
 * @return number   返回字符串的字符总数
 ***************************************************************************/
function get_str_len($str,$unicode_type=1)
{
    if($str === '')
    {
        return 0;
    }
    $match_arr = array();
    switch ($unicode_type)
    {
        case 2://gbk编码格式的情况
            $str = @iconv('gbk', 'utf-8', $str);
            break;
        case 1://utf-8编码格式的情况
        default:
            break;
    }
    preg_match_all('/./us', $str, $match_arr);
    return count($match_arr[0]);
    
}//end of func get_str_len

/**********************************************************************************
 * 比较判断数值的大小
 * 
 * @param integer $num1   要比较的第一个数值
 * @param integer $num2   要比较的第二个数值
 * @return integer   0数字相等，1第一个数大于第二个数，-1第一个数小于第二个数
 *********************************************************************************/
function compare_num($num1,$num2)
{
    if(function_exists('bccomp'))
    {
        return bccomp($num1, $num2,$scale);
    }
    else 
    {
        if($num1 < $num2)
        {
            return -1;
        }
        elseif($num1 === $num2)
        {
            return 0;
        }
        else 
        {
            return 1;
        }
    }
    
}//end of func compare_num

/****************************************************************
 * 获取带有中文的字符串长度是否在规定的长度内
 * 
 * @param string $str   要检查的字符串
 * @param integer $len   要验证的长度上限
 * @param integer $unicode_type   字符串的编码类型，1为utf8，2为gbk
 * @return boolean   TRUE代表长度合法
 ***************************************************************/
function is_legal_len_ch($str, $len,$unicode_type=1)
{
    $str_len = get_str_len($str,$unicode_type);
    return $str_len <= $len;
    
}//end of func is_legal_len_ch

/*************************************************************
 * 随机生成指定位数的只包含1-9的数字串
 * 
 * @param integer $bits   生成的数字串位数
 * @return boolean|string   位数不合法返回FALSE,否则返回数字串
 **********************************************************/
function gen_rand_numbers($bits=5)
{
    if( $bits<=0 )
    {
        return FALSE;
    }
    
    $number = '';
    for($i=0; $i<$bits; ++$i)
    {
        $n = mt_rand(1,9);
        $number .= $n;
    }
    
    return $number;
    
}//end of func gen_rand_numbers

/*********************************************************************
 * 随机生成指定位数的字符串
 * 
 * @param integer $bits   要生成的字符串位数
 * @return boolean|string   返回FALSE如果参数不合法，否则返回生成的字符串
 *********************************************************************/
function gen_rand_keys($bits)
{
    if( $bits<=0 )
    {
        return FALSE;
    }
    
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$%#@!()^_-+';
    $str_len = strlen($str);
    $key = '';
    for($i=0; $i<$bits; ++$i)
    {
        $n = mt_rand(0, $str_len-1);
        $key .= $str[$n];
    }
    
    return $key;
    
}//end of func gen_rand_keys

/*********************************************************
 * 将要进行SQL查询的WHERE IN里面参数数组进行序列化成字符串
 * @param array $num_arr
 * @return string
 * @example 
 * 如传入gen_sql_inparam(array('0','1','3'))变成'0','1','3'
 ******************************************************/
function gen_sql_inparam($num_arr)
{
    $sql_param = '';
    foreach ($num_arr as $num)
    {
        $sql_param .= '\''.$num.'\',';
    }
    
    return substr($sql_param, 0,strlen($sql_param)-1);
    
}//end of func gen_sql_inparam

/*********************************************************
 * 进行DES加密
 * 
 * @param string $text    要加密的数据内容
 * @param string $key    加密使用的密钥,长度必须是8位
 * @param string $iv    偏移量
 * @return string   返回加密后的字符串内容
 ********************************************************/
function des_encrypt($text,$key,$iv)
{
    $size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
    $str = pkcs5_pad ( $text, $size );
    if(!check_php_level('5.0.0'))
    {
        $data=mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_ENCRYPT, $iv);
    }
    else
    {
        $cipher = mcrypt_module_open(MCRYPT_DES,'','cbc','');
        mcrypt_generic_init($cipher, $key, $iv);
        $data = mcrypt_generic($cipher,$str);
        mcrypt_generic_deinit($cipher);
    }
    return base64_encode($data);
    
}//end of func des_encrypt

/**************************************************************
 * 对DES数据进行解密操作
 * 
 * @param string $text    要加密的数据内容
 * @param string $key    加密使用的密钥,长度必须是8位
 * @param string $iv    偏移量
 * @return string     返回解密后的字符串内容
 *************************************************************/
function des_decrypt($text,$key,$iv)
{
    $str = base64_decode ($text);
    if(!check_php_level('5.0.0'))
    {
        $str = mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_DECRYPT, $iv );
    }
    else
    {
        $cipher = mcrypt_module_open(MCRYPT_DES,'','cbc','');
        mcrypt_generic_init($cipher, $key, $iv);
        $str = mdecrypt_generic($cipher,$str);
        mcrypt_generic_deinit($cipher);
    }
    $str = pkcs5_unpad( $str );
    return $str;
    
}//end of func des_decrypt

/************************************************************
 * 使用PKCS5PADDING填充算法
 * 
 * @param string $text    要填充的数据内容
 * @param integer $block_size    密文使用的块大小
 * @return string    返回算法填充后的数据内容
 ***********************************************************/
function pkcs5_pad($text, $block_size)
{
    $pad = $block_size - (strlen ( $text ) % $block_size);
    return $text . str_repeat ( chr ( $pad ), $pad );
    
}//end of func pkcs5_pad

/************************************************************
 * 解析PKCS5PADDING填充算法生成的数据
 * 
 * @param string $text    要进行解析的数据内容
 * @return boolean|string    返回解析成功的数据，否则返回FALSE
 ***********************************************************/
function pkcs5_unpad($text)
{
    $pad = ord ( $text {strlen ( $text ) - 1} );
    if ($pad > strlen ( $text ))
    {
        return FALSE;
    }
    if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
    {
        return FALSE;
    }
    return substr ( $text, 0, - 1 * $pad );
    
}//end of func pkcs5_unpad

/*****************************
 * 创建全球唯一码
 *****************************/
function create_guid($make_trim=TRUE) 
{
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);
    $uuid = chr(123)
    .substr($charid, 0, 8).$hyphen
    .substr($charid, 8, 4).$hyphen
    .substr($charid,12, 4).$hyphen
    .substr($charid,16, 4).$hyphen
    .substr($charid,20,12)
    .chr(125);
    if($make_trim)
    {
        $uuid = trim($uuid,'{}');
    }
    return $uuid;
}

/***************************************************************
 * 创建目录信息（可多级创建）
 * 
 * @param string $dir_path  要创建的目录路径
 * @param integer|string $privilege  创建的目录的访问权限
 * @param boolean $multi_dir  是否支持多级目录创建
 * @return boolean  TRUE目录存在或者创建成功，FALSE创建目录失败
 **************************************************************/
function make_dir($dir_path,$privilege=0777,$multi_dir=TRUE)
{
    if(is_dir($dir_path))
    {
        return TRUE;
    }
    if(mkdir($dir_path,$privilege,$multi_dir))
    {
        return TRUE;
    }
    return FALSE;
    
}

/************************************
 * 获取php的版本号
 * @return string 
 ***********************************/
function get_php_version()
{
    $php_version = explode( '-', phpversion() );
    return  $php_version[0];
    
}//end of func get_php_version

/*********************************************************
 * 判断当前环境php版本是否大于大于等于指定的一个版本
 * @param string $version
 * @return boolean
 ********************************************************/
function check_php_level( $version = '5.0.0' ) 
{
    $php_version = explode( '-', phpversion() );
    // =0表示版本为5.0.0  ＝1表示大于5.0.0  =-1表示小于5.0.0
    $is_pass = strnatcasecmp( $php_version[0], $version ) >= 0 ? true : false;
    return $is_pass;
}

/***********************************************************
 * 获取毫秒时间戳
 * @return float
 **********************************************************/
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/***********************************************************
 * 格式化时间戳，精确到毫秒，x代表毫秒
 * microtime_format('Y年m月d日 H时i分s秒 x毫秒', 1270626578.66000000)
 * @param $tag
 * @param $time
 * @return mixed
 **********************************************************/
function microtime_format($tag, $time)
{
    list($usec, $sec) = explode(".", $time);
    $date = date($tag,$usec);
    return str_replace('x', $sec, $date);
}

/***********************************************************
 * 获取毫秒时间戳
 * @return float
 **********************************************************/
function get_millisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}

/***********************************************************
 * 获得当前的域名
 **********************************************************/
function get_domain(){ 
    /* 协议 */ 
    $protocol = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://'; 
    /* 域名或IP地址 */ 
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) { 
        $host = $_SERVER['HTTP_X_FORWARDED_HOST']; 
    } elseif (isset($_SERVER['HTTP_HOST'])) { 
        $host = $_SERVER['HTTP_HOST']; 
    } else { 
        /* 端口 */ 
        if (isset($_SERVER['SERVER_PORT'])) { 
            $port = ':' . $_SERVER['SERVER_PORT']; 
            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))  { 
                $port = ''; 
            } 
        } else { 
            $port = ''; 
        } 
        if (isset($_SERVER['SERVER_NAME'])) { 
            $host = $_SERVER['SERVER_NAME'] . $port; 
        } elseif (isset($_SERVER['SERVER_ADDR'])) { 
            $host = $_SERVER['SERVER_ADDR'] . $port; 
        } 
    }
    return $protocol . $host;
}

/**
 * 将uuid转成8个字节的uuid
 * @return string 
 */
function create_short_uuid(){
    $chars = array_merge(range(0,9),range('a','z'),range('A','Z'));
    $uuid = str_replace('-','',create_guid());
    $short_uuid = '';
    for($i=0;$i<8;++$i){
        $str = substr($uuid,$i*4,4);
        $map_int = hexdec($str);
        $short_uuid .= $chars[$map_int % 62];
    }
    return $short_uuid;
}