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
