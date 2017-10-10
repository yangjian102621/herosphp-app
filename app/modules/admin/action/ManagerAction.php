<?php
namespace app\admin\action;

use herosphp\http\HttpRequest;
use app\admin\service\AdminService;

/**
 * 管理员控制器
 * @author  yangjian<yangjian102621@gmail.com>
 */
class ManagerAction extends CommonAction {

    protected $serviceClass = AdminService::class;

    protected $actionTitle = "管理员";

    /**
     * 列表
     * @param HttpRequest $request
     */
    public function index(HttpRequest $request) {
        parent::index($request);

        $this->setOpt("管理员列表");
        $this->setView("admin/admin_index");

    }
  
}