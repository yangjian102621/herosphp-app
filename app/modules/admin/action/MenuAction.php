<?php
namespace app\admin\action;

use app\admin\service\AdminMenuService;
use app\admin\utils\Lang;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;

/**
 * 后台菜单控制器
 * @author  yangjian<yangjian102621@gmail.com>
 */
class MenuAction extends CommonAction {

    protected $serviceClass = AdminMenuService::class;

    protected $actionTitle = "菜单";

    /**
     * 列表
     * @param HttpRequest $request
     */
    public function index(HttpRequest $request) {

        $items = $this->service->getMenuCache();
        $this->assign('items', $items);

        //获取所有的一级菜单
        $menus = $this->service->where('pid', 0)->find();
        $this->assign('menus', $menus);
        $this->setOpt($this->actionTitle."列表");
        $this->setView("menu/menu_index");

    }

    /**
     * 添加数据
     * @param HttpRequest $request
     */
    public function add(HttpRequest $request) {

    }

    /**
     * 修改数据
     * @param HttpRequest $request
     */
    public function edit(HttpRequest $request) {

        parent::_edit($request);
        $item = $this->getTemplateVar('item');
        if (empty($item)) {
            JsonResult::fail(Lang::FETCH_FAIL);
        } else {
            $json = new JsonResult();
            $json->setCode(JsonResult::CODE_SUCCESS);
            $json->setItem($item);
            $json->output();
        }

    }

    /**
     * 插入数据
     * @param HttpRequest $request
     */
    public function insert(HttpRequest $request) {
        $data = $request->getParameter('data');
        $data['enable'] = 1;
        //生成密码盐
        parent::_insert($data, function($service) {
            $service->updateMenuCache();
        });
    }

    /**
     * 更新数据
     * @param HttpRequest $request
     */
    public function update(HttpRequest $request) {

        $data = $request->getParameter('data');
        $id = $request->getParameter('id', 'intval');
        parent::_update($data, $id, function($service) {
            $service->updateMenuCache();
        });
    }

    /**
     * 启用|禁用
     * @param HttpRequest $request
     */
    public function enable(HttpRequest $request)
    {
        parent::enable($request, function($service) {
            $service->updateMenuCache();
        });
    }

    /**
     * 删除菜单
     * @param HttpRequest $request
     */
    public function delete(HttpRequest $request)
    {
        $id = $request->getParameter('id');
        $item = $this->service->where('pid', $id)->findOne();
        if (!empty($item)) {
            JsonResult::fail("请先删除子菜单");
        }
        parent::delete($request, function($service) {
            $service->updateMenuCache();
        });
    }

}