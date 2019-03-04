<?php

namespace app\api;

use herosphp\api\interfaces\IApiListener;

/**
 * API 模块监听器，这里需要实现系统通用 IApiListener 接口
 * @author yangjian<yangjian102621@gmail.com>
 */
 class ModuleListener implements IApiListener {

     /**
      * white list
      * @var array
      */
     private static $whiteList = array(
         '/user/register' => 1
     );

     /**
      * API authorization interception processing
      * @param $params
      * @return bool
      */
     public function authorize($params = null)
     {
         // TODO: Implement authorize() method.
         return true;
     }

     /**
      * determine if a request need authrization
      * @param $url
      * @return bool
      */
     public function needAuthrize($url)
     {
        if (!isset(self::$whiteList[$url])) {
            return true;
        } else {
            return false;
        }
     }
 }
