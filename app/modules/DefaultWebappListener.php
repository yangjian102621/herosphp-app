<?php

namespace app;

use herosphp\core\WebApplication;
use herosphp\http\HttpRequest;
use herosphp\listener\IWebAplicationListener;
use herosphp\listener\WebApplicationListenerMatcher;

/**
 * 应用程序默认生命周期监听器
 * @author yangjian<yangjian102621@gmail.com>
 */
 class DefaultWebappListener extends WebApplicationListenerMatcher implements IWebAplicationListener {

     /**
      * 请求初始化之前
      * @return mixed
      */
     public function beforeRequestInit()
     {
         //设置跳过监听的uri, 比如登录页面，注册页面等
         $this->skipUrl("/user/**"); //跳过用户模块下所有请求
         $this->skipUrl("/admin/login/**"); //跳过登录控制器所有请求
         $this->skipUrl("/admin/scode/index"); //跳过验证码请求

         // TODO: Implement beforeRequestInit() method.
     }

     /**
      * action 方法调用之前
      * @return mixed
      */
     public function beforeActionInvoke(HttpRequest $request)
     {
        
     }

     /**
      * 响应发送之前
      * @return mixed
      */
     public function beforeSendResponse(HttpRequest $request, $actionInstance)
     {
         $webApp = WebApplication::getInstance();
         //注册当前app的配置信息
         $actionInstance->assign('appConfigs', $webApp->getConfigs());
         $actionInstance->assign('params', $webApp->getHttpRequest()->getParameters());
     }

     /**
      * 响应发送之后
      * @return mixed
      */
     public function afterSendResponse($actionInstance)
     {
         // TODO: Implement afterSendResponse() method.
     }

}
