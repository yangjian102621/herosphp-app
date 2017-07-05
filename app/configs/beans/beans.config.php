<?php
use herosphp\bean\Beans;
/**
 * bean 配置.
 * @author yangjian<yangjian102621@gmail.com>
 */
return [
    "demo.user.service" => array (
        '@type' => Beans::BEAN_OBJECT,
        '@class' => 'app\demo\service\UserService',
        '@attributes' => array(
            '@bean/modelDao'=>array(
                '@type'=>Beans::BEAN_OBJECT,
                '@class'=>'app\demo\dao\UserDao',
                '@params' => array('User')
            )
        ),
    ),

    "api.user.service" => array (
        '@type' => Beans::BEAN_OBJECT,
        '@class' => 'app\api\service\UserService'
    ),
];