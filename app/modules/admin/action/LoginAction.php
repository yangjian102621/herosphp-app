<?php
namespace app\admin\action;

use herosphp\core\Controller;
use herosphp\http\HttpRequest;

/**
 * 用户登录
 * @author  yangjian<yangjian102621@gmail.com>
 */
class LoginAction extends Controller {

    /**
     * 首页方法
     * @param HttpRequest $request
     */
    public function index( HttpRequest $request ) {

        $this->setView("admin/login");

    }
  
}