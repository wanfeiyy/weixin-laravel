<?php
/**
 * 应用入口文件
 * @author  KavMors(kavmors@163.com)
 * @since   2.0
 */
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

//定义平台变量
define('APP_ID', 'wxb66a64ab300796e2');           //应用ID
define('APP_SECRET', 'e7a795d18a6c38a73eec65ad4929ecf8');       //应用密匙
define('APP_NAME', 'test');         //应用名称
define('WECHAT_ID', 'obkNVs7CHVUnIRM983XHFdysZ7rU');        //公众平台微信号
define('TOKEN', 'feis');            //令牌
define('ENCODING_AESKEY', '');  //加密所用的AES_KEY
define('DB_USER', 'root');          //数据库用户
define('DB_PWD', '123456');           //数据库密码

//引入框架入口文件
require './wlight/Wlight.php';

//配置微信平台“服务器配置”后修改Application里的文件