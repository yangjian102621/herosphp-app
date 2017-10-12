<?php
namespace app\admin\action;

use app\admin\utils\Lang;
use herosphp\core\Loader;
use herosphp\core\WebApplication;
use herosphp\http\HttpRequest;
use app\admin\service\AdminRoleService;
use app\admin\service\AdminPermissionService;
use herosphp\string\StringUtils;
use herosphp\utils\JsonResult;

/**
 * 管理员角色控制器
 * @author  yangjian<yangjian102621@gmail.com>
 */
class RoleAction extends CommonAction {

    protected $serviceClass = AdminRoleService::class;

    protected $actionTitle = "管理员角色";

    /**
     * 列表
     * @param HttpRequest $request
     */
    public function index(HttpRequest $request) {
        parent::index($request);

        $this->setOpt($this->actionTitle."列表");
        $this->setView("role/role_index");

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
        $rids = $request->getParameter('rids');
        $data['role_ids'] = StringUtils::jsonEncode($rids);
        //生成密码盐
        $data['salt'] = StringUtils::genGlobalUid();
        $data['password'] = genPassword($data['password'], $data['salt']);
        parent::_insert($data);
    }

    /**
     * 更新数据
     * @param HttpRequest $request
     */
    public function update(HttpRequest $request) {
        $data = $request->getParameter('data');
        $id = $request->getParameter('id', 'intval');
        $password = $request->getParameter('password', 'trim');
        $rids = $request->getParameter('rids');
        $data['role_ids'] = StringUtils::jsonEncode($rids);
        if (!empty($password)) {
            $data['password'] = genPassword($password, $data['salt']);
        }
        parent::_update($data, $id);
    }

    /**
     * 获取权限列表
     * @param HttpRequest $request
     */
    public function getPermissions(HttpRequest $request) {

        $id = $request->getParameter('id', 'intval');
        $permissionService = Loader::service(AdminPermissionService::class);
        $permissions = $permissionService->find();
        $permissions = arrayGroup($permissions, 'pgroup');
        if (empty($permissions)) {
            JsonResult::fail(Lang::GET_PERMISSION_FAIL);
        } else {
            //整理权限数组
            $appConfigs = WebApplication::getInstance()->getConfigs();
            $permissionConfigs = $appConfigs['permission_group'];
            $items = [];
            foreach($permissions as $key => $value) {
                $items[$key]['groupName'] = $permissionConfigs[$key];
                $items[$key]['permissionList'] = $value;
            }
            // 获取当前角色的初始化权限
            $role = $this->service->findById($id);
            $p = StringUtils::jsonDecode($role['permissions']);
            $json = new JsonResult();
            $json->setCode(JsonResult::CODE_SUCCESS);
            $json->setItems($items);
            $json->setItem($p);
            $json->output();
        }
    }

    /**
     * 保存权限
     * @param HttpRequest $request
     */
    public function savePermissions(HttpRequest $request) {

        $roleId = $request->getParameter('roleId', 'intval');
        $permissions = $request->getParameter('permissions');
        if ($this->service->set('permissions', StringUtils::jsonEncode($permissions), $roleId)) {
            JsonResult::success(Lang::SAVE_PERMISSION_SUCCESS);
        } else {
            JsonResult::fail(Lang::SAVE_PERMISSION_FAIL);
        }
    }
}