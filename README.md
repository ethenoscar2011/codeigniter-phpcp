# codeigniter-phpcp
Here comes some changes of ci framework,include db seperated and the php connection pool ...

### environment seperate
As the ci defines , there are three enviroments for us , including development | testing | production.
So you can find out three more directories in the config directory ,with the names development ,testing, production
>development : the config file here enabled while you index.php is set to development enviroment

>testing : the config file here enabled while you index.php is set to testing enviroment

>production : the config file here enabled while you index.php is set to production enviroment

>and files under the root of config directory will enabled while the framework cannot find the file in the enviroment directories

### database seperate
You can see that I put a dbmap.php file in each enviroment directory , and this is the most important config for the seperating
```
<?php 
#./application/config/dbmap.php

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

```
This will be used in the `./application/core/MY_DbConnection.php` file , which is the main db connection controller of this function , and you may find the `MY_Model.php` in the same directory , it will be the parent of the all models instead of the CI_Model and we will mention it latter.

Now let's see an example of using the seperate db connection
```
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
```
You may see the code like `$this->slaveTest1` and `$this->masterTest1` above , as it relates to the dbmap's test1's slave and master , to know more about this function , you should see the config file `database.php`
```
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'masterone';
$query_builder = TRUE;

$db['masterone'] = array(
	'dsn'	=> 'mysql:host=localhost;dbname=test;charset=utf8',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'root',
	'database' => 'test',
	'dbdriver' => 'pdoproxy',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);



$db['mastertwo'] = array(
	'dsn'	=> 'mysql:host=localhost;dbname=test;charset=utf8',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'root',
	'database' => 'test',
	'dbdriver' => 'pdoproxy',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);


$db['slaveone'] = array(
	'dsn'	=> 'mysql:host=localhost;dbname=test;charset=utf8',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'root',
	'database' => 'test',
	'dbdriver' => 'pdoproxy',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);


$db['slavetwo'] = array(
	'dsn'	=> 'mysql:host=localhost;dbname=test;charset=utf8',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'root',
	'database' => 'test',
	'dbdriver' => 'pdoproxy',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

```

I think you should be very clearly about the db seperate now , if you cannot understand I would be very guilty .

### php pool connection support
As the concurrent volume is more and more important , a lot of way to improve it comes out , like caching or load balancing etc.

Here I just describe the way I got these days , while my boss wanted me to make a db pool like java .

Thanks to github and the developers of php-cp (php connection pool) , I finally got the way  
> See https://github.com/swoole/php-cp

I clone it from the github and compile it into my php modules , as authors said , I copy the `pool.ini` to my `/etc/` and change the config the same as my dsn in the `database.php `,  and the most important thing is that we should start the pool_server with root

If you want to know more , please go to the github mentioned above, here show my compile process and config :
```
git  clone https://github.com/swoole/php-cp.git
cd php-cp
phpize
./configure
make && make install
echo "extension=connect_pool.so" >> /etc/php5/apache2/php.ini && echo "extension=connect_pool.so" >> /etc/php5/cli/php.ini
cp pool.ini /etc/
cp pool_server /var/www/html/script

------------------------------------------------------------------------------------------------------

vim /etc/pool.ini

[common]
log_file = /tmp/phpcp.log
;the num of TCP connections release every idel_time cycles(连接空闲后的回收力度，值越大回收的越快，但是会造成更多的消耗)
recycle_num = 2
;In this period of time,if no process use this connection ,the TCP connection will release(空闲连接回收的发呆时间 单位秒)
idel_time = 2
;;max query package len,exceed will throw exception(最大转发的数据包,超过跑异常5M)
max_read_len = 5242880
;run as daemonize(是否开启守护进程化)
daemonize = 1
;If the num of connection to max, whether to use the queue buffer, set to 0 throw an exception(连接都被占用后,再获取连接是否使用队列缓冲,设置为0直接抛异常)
use_wait_queue = 1
;the get_disable_list() function returns the maximum number of IP(get_disable_list函数最多返回多少个失效结点,防止网络抖动踢掉所有的机器)
max_fail_num = 1
;just the pool_server process port(端口)
port = 6253

;注意数据源需要加 ''
;PDO数据源要与new PDO的第一个参数完全一致（包括顺序）
;如果不配置数据源 那么默认最大是20 最小是1 ，在第一次查询的时候自动创建
['mysql:host=localhost;dbname=test;charset=utf8'];mysql配置
pool_min = 2
pool_max = 30

------------------------------------------------------------------------------------------------------

php start /var/www/html/script/pool_server

```
After the server start , you can change the `database.php` ,modify the `dbdriver` to `pdoproxy` , and you can use the normal ci db usage to doing you work , while the proproxy driver doing the release backend .

If you just want the driver , you can get it in the directory `./system/database/drivers/pdoproxy`


### MY_Model.php - The parent of all Models 
`MY_Model.php` extends the `MY_DbConnection` , supports the db seperate and supplies some common method for quick queries , but it's not the most important one , you can alse extends `MY_DbConnection` in you  model directly.

### RedisServer
You can find the `RedisServer.php` in the `./application/libraries` , which will be the parent of the `./application/models/redis/RedisMain.php` , make some common config of the redis , and you can see the redis config in the config directory `redis.php`
```
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
```
I will describe the usage later


