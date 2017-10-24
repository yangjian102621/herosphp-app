<?php
/*---------------------------------------------------------------------
 * 当前访问application配置信息.
 * 注意：此处的配置将会覆盖同名键值的系统配置
 * ---------------------------------------------------------------------
 * Copyright (c) 2013-now http://blog518.com All rights reserved.
 * ---------------------------------------------------------------------
 * Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * ---------------------------------------------------------------------
 * Author: <yangjian102621@gmail.com>
 *-----------------------------------------------------------------------*/
$config = array(

    'template' => 'default',    //默认模板
    /**
     * 模板编译缓存配置
     * 0 : 不启用缓存，每次请求都重新编译(建议开发阶段启用)
     * 1 : 开启部分缓存， 如果模板文件有修改的话则放弃缓存，重新编译(建议测试阶段启用)
     * -1 : 不管模板有没有修改都不重新编译，节省模板修改时间判断，性能较高(建议正式部署阶段开启)
     */
    'temp_cache' => 0,

    /**
     * 用户自定义模板标签编译规则
     * array( 'search_pattern' => 'replace_pattern'  );
     */
    'temp_rules' => array(),

    'host' => $_SERVER['HTTP_HOST'],     //网站主机名
    //默认访问的页面
    'default_url' => array(
        'module' => 'admin',
        'action' => 'login',
        'method' => 'index' ),

    'template' => 'default',    //默认模板
    'temp_cache' => 0,      //模板引擎缓存

    //短链接映射
    'url_mapping_rules' => array(
        '^\/newsdetail-(\d+)\/?$' => '/news/article/detail/?id=${1}',
        '^\/admin\/?$' => '/admin/index/login',
    ),

    //以上都框架内置的配置变量，请不要删除，下面是用户自定义的变量可以添加或者删除
    'site_name' => 'HerosPHP 快速开发平台',
    'site_desc' => 'HerosPHP 快速开发平台',
    'site_author' => 'yangjian102621@gmail.com',
    'site_copyright' => '2016 &copy; HerosPHP by BlackFox',

    'rsa_private_key' => __DIR__.'/keys/rsa_private_key.pem',
    'rsa_public_key' => __DIR__.'/keys/rsa_public_key.pem',

    // 后台权限分组
    'permission_group' => array(
        'system' => '系统管理',
        'user' => '用户管理'
    )

);

return $config;