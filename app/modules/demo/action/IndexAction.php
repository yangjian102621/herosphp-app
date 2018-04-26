<?php
namespace app\demo\action;

use app\admin\service\AdminPermissionService;
use app\admin\service\AdminService;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;

/**
 * 首页测试
 * @since           2015-01-28
 * @author          yangjian<yangjian102621@gmail.com>
 */
class IndexAction extends Controller {

    /**
     * 首页方法
     * @param HttpRequest $request
     */
    public function index(HttpRequest $request) {
        $this->setView("index");
        $this->assign("title", "欢迎使用Herosphp");
    }

    /**
     * 获取权限列表
     * @param HttpRequest $request
     */
    public function permissions(HttpRequest $request) {
        $service = Loader::service(AdminPermissionService::class);
        $items = $service->page(0, 100)->find();
        $res = new JsonResult();
        $res->setItems($items);
        $res->setCode(JsonResult::CODE_SUCCESS);
        $res->output();
    }

}
