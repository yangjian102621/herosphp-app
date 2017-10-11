<?php
namespace app\admin\action;

use app\admin\utils\Lang;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use app\admin\service\AdminService;
use app\admin\service\AdminRoleService;
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
}