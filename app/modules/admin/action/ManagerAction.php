<?php
namespace app\admin\action;

use app\admin\service\ManagerService;
use app\admin\utils\Lang;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\string\StringUtils;
use herosphp\utils\JsonResult;

/**
 * 管理员控制器
 * @author  yangjian<yangjian102621@gmail.com>
 */
class ManagerAction extends CommonAction {

    protected $serviceClass = ManagerService::class;

    /**
     * 列表
     * @param HttpRequest $request
     */
    public function index() {
        $this->setView("manager/manager-list");

    }

    public function clist(HttpRequest $request)
    {
        $res = parent::clist($request);
        $res->output();
    }

    /**
     * 添加数据
     * @param HttpRequest $request
     */
    public function add(HttpRequest $request) {

        $this->setView('admin/admin_add');
        $this->loadRoles();
    }

    /**
     * 修改数据
     * @param HttpRequest $request
     */
    public function edit(HttpRequest $request) {

        $this->setOpt($this->actionTitle.'修改');
        parent::_edit($request);
        $item = $this->getTemplateVar('item');
        $roleIds = StringUtils::jsonDecode($item['role_ids']);
        $this->assign('roleIds', $roleIds);
        $this->setView('admin/admin_edit');

        $this->loadRoles();
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
     * 修改密码
     * @param HttpRequest $request
     */
    public function modifyPass(HttpRequest $request) {

        $oldpass = $request->getParameter('oldpass', 'trim');
        //验证原始密码
        $password = genPassword($oldpass, $this->loginUser->getSalt());
        if ($password != $this->loginUser->getPassword()) {
            JsonResult::fail(Lang::OLD_PASS_ERROR);
        }
        // 这里防止线上 demo 别人不小心修改了密码，导致其他人无法登录这里直接返回成功了。
        JsonResult::success(Lang::MD_PASS_SUCCESS);
        $newpassword = $request->getParameter('newpassword','trim');
        $password = genPassword($newpassword, $this->loginUser->getSalt());
        if ($this->service->set('password', $password, $this->loginUser->getId())) {
            $this->service->logout();
            JsonResult::success(Lang::MD_PASS_SUCCESS);
        } else {
            JsonResult::fail(Lang::MD_PASS_FAIL);
        }

    }

    /**
     * 删除数据
     * @param HttpRequest $request
     * @return JsonResult|void
     */
    public function delete(HttpRequest $request)
    {
        parent::delete($request)->output();
    }
}