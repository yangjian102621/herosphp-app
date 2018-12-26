<?php
namespace app\admin\action;

use app\admin\service\ManagerLoginLogService;
use herosphp\http\HttpRequest;

/**
 * 管理员登录日志
 * @author  yangjian<yangjian102621@gmail.com>
 */
class ManagerLoginLogAction extends CommonAction {

    protected $serviceClass = ManagerLoginLogService::class;

    /**
     * 列表
     * @param HttpRequest $request
     */
    public function index() {
        $this->setView("manager/manager-login-log");

    }

    public function clist(HttpRequest $request)
    {
        $res = parent::clist($request);
        $res->output();
    }

}