<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'ddwk',
    'DB_USER' => 'root',
    'DB_PWD'  => 'root',
    'DB_PORT' => '3306',
	'DB_PREFIX' => 'ddwk_',
	
	
	//session设置
	'SESSION_OPTIONS'=>array(
		//'expire'=>60,
		'path'=>'./Public/session'
	),
	
	//缓存设置
	'CACHE_OPTION'=>array(
	    'type'=>'Redis',
		'expire'=>60,
	    'host'=>'127.0.0.1',  
        'port'=>6379, 
	),
);