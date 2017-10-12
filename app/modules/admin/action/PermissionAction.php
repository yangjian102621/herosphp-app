<?php
namespace app\admin\action;

use app\admin\utils\Lang;
use herosphp\core\Loader;
use herosphp\core\WebApplication;
use herosphp\http\HttpRequest;
use app\admin\service\AdminPermissionService;
use herosphp\utils\JsonResult;

/**
 * 管理员权限控制器
 * @author  yangjian<yangjian102621@gmail.com>
 */
class PermissionAction extends CommonAction {

    protected $serviceClass = AdminPermissionService::class;

    protected $actionTitle = "管理员权限";

    /**
     * 列表
     * @param HttpRequest $request
     */
    public function index(HttpRequest $request) {

        $name = $request->getParameter('name', 'trim');
        $key = $request->getParameter('key', 'trim');
        $group = $request->getParameter('group', 'trim');
        if (!empty($name)) {
            $this->service->where('name', 'LIKE', "%{$name}%");
        }
        if (!empty($key)) {
            $this->service->where('pkey', 'LIKE', "%{$key}%");
        }
        if (!empty($group)) {
            $this->service->where('pgroup', $group);
        }
        parent::index($request);

        $items = $this->getTemplateVar('items');
        $appConfigs = WebApplication::getInstance()->getConfigs();
        $permissionsConfigs = $appConfigs['permission_group'];
        foreach($items as $key => $val) {
            $items[$key]['groupName'] = $permissionsConfigs[$val['pgroup']];
        }
        $this->assign('items', $items);
        $this->setOpt($this->actionTitle."列表");
        $this->setView("role/permission_index");

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
        parent::_insert($data);
    }

    /**
     * 更新数据
     * @param HttpRequest $request
     */
    public function update(HttpRequest $request) {
        $data = $request->getParameter('data');
        $id = $request->getParameter('id', 'intval');
        parent::_update($data, $id);
    }
}