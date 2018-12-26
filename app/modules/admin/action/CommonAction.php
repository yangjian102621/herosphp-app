<?php
namespace app\admin\action;

use app\admin\model\Manager;
use app\admin\service\ManagerService;
use app\admin\utils\Lang;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\core\WebApplication;
use herosphp\http\HttpRequest;
use herosphp\model\CommonService;
use herosphp\utils\JsonResult;
use herosphp\utils\ModelTransformUtils;
use herosphp\utils\Page;

/**
 * admin 模块基类控制
 * @author  yangjian<yangjian102621@gmail.com>
 */
abstract class CommonAction extends Controller {

    // 页码
    protected $page = 1;

    // 每页数量
    protected $pageSize = 12;

    //排序方式
    protected $order = "id DESC";

    /**
     * 当前登陆的管理员
     * @var Admin
     */
    protected $loginUser;

    // 当前服务的 class path
    protected $serviceClass;

    /**
     * 当前服务
     * @var CommonService
     */
    protected $service;

    public function __construct()
    {
        parent::__construct();
        $request = WebApplication::getInstance()->getHttpRequest();

        //获取当前登陆管理员
        $managerService = Loader::service(ManagerService::class);
        $loginUser = $managerService->getLoginManager();
//        if (!$loginUser) {
//            location("/admin/login/index");
//        }
        $this->loginUser = ModelTransformUtils::map2Model(Manager::class, $loginUser);
        $this->assign('loginUser', $this->loginUser);

        if ($this->serviceClass != null) {
            $this->service = Loader::service($this->serviceClass);
        }

        $module = $request->getModule();
        $action = $request->getAction();
        $this->assign("index_url", "/{$module}/{$action}/index");
        $this->assign('add_url',"/{$module}/{$action}/add");
        $this->assign('edit_url',"/{$module}/{$action}/edit");
        $this->assign("insert_url", "/{$module}/{$action}/insert");
        $this->assign("update_url", "/{$module}/{$action}/update");
        $this->assign('delete_url',"/{$module}/{$action}/delete");
        $this->assign('deletes_url',"/{$module}/{$action}/deletes");
        $this->assign("noRecords", Lang::NO_RECOEDS);

    }

    /**
     * 获取数据列表
     * @param HttpRequest $request
     * @return JsonResult
     */
    public function clist(HttpRequest $request) {

        $pageNo = $request->getIntParam("pageNo");
        $pageSize = $request->getIntParam("pageSize");
        $this->setPage($pageNo);
        $this->setPageSize($pageSize);

        $sqlBuilder = $this->service->getSqlBuilder();
        $items = $this->service->page($this->getPage(), $this->getPagesize())->order($this->getOrder())->find();
        $total = $this->service->setSqlBuilder($sqlBuilder)->count();
        $res = new JsonResult(JsonResult::CODE_SUCCESS, "数据获取成功.");
        $res->setCount($total);
        $res->setPage($this->getPage());
        $res->setPagesize($this->getPageSize());
        $res->setData($items);
        return $res;

    }

    /**
     * 获取一条数据
     * @param HttpRequest $request
     */
    protected function _get(HttpRequest $request) {
        $id = $request->getParameter('id', 'trim');
        $item = $this->service->findById($id);
        $this->assign('item', $item);
    }

    /**
     * 添加数据
     * @param $data
     * @return JsonResult
     */
    protected function _insert(array $data) {

        $data['create_time'] = Carbon::now()->toDateTimeString();
        $data['update_time'] = $data['create_time'];
        $res = new JsonResult(JsonResult::CODE_SUCCESS, Lang::INSERT_SUCCESS);
        if (!$this->service->add($data)) {
            $res->setCode(JsonResult::CODE_FAIL);
            $message = WebApplication::getInstance()->getAppError()->getMessage();
            $res->setMessage(empty($message) ? Lang::INSERT_FAIL : $message);
        }
        return $res;
    }

    /**
     * 更新数据
     * @param array $data
     * @param $id
     * @return JsonResult
     */
    protected function _update(array $data, $id) {

        $res = new JsonResult(JsonResult::CODE_SUCCESS, Lang::UPDATE_SUCCESS);
        if (empty($id)) {
            $res->setCode(JsonResult::CODE_FAIL);
            $res->setMessage(Lang::NO_RECOEDS);
        }
        $data['update_time'] = Carbon::now()->toDateTimeString();
        if (!$this->service->update($data, $id)) {
            $res->setCode(JsonResult::CODE_FAIL);
            $message = WebApplication::getInstance()->getAppError()->getMessage();
            $res->setMessage(empty($message) ? Lang::UPDATE_FAIL : $message);
        }
        return $res;
    }

    /**
     * 更改字段状态
     * @param HttpRequest $request
     * @return JsonResult
     */
    public function changeStatus( HttpRequest $request) {

        $id = $request->getParameter('id', 'trim');
        $status = $request->getParameter('status', 'intval');
        if (empty($id)) {
            JsonResult::fail(Lang::INVAID_PARAMS);
        }
        $res = new JsonResult(JsonResult::CODE_SUCCESS, Lang::OPT_SUCCESS);
        if (!$this->service->set('status', $status, $id)) {
            $res->setCode(JsonResult::CODE_FAIL);
            $res->setMessage(Lang::OPT_FAIL);
        }
        return $res;
    }

    /**
     * 删除单条数据
     * @param HttpRequest $request
     * @return JsonResult
     */
    public function delete( HttpRequest $request) {

        $id = $request->getStrParam('id');
        $res = new JsonResult(JsonResult::CODE_SUCCESS, Lang::DELETE_SUCCESS);
        if ( empty($id) ) {
            $res->setCode(JsonResult::CODE_FAIL);
            $res->setMessage(Lang::NO_RECOEDS);
            return $res;
        }
        $this->service->beginTransaction();
        if (!$this->service->delete($id)) {
            $res->setCode(JsonResult::CODE_FAIL);
            $res->setMessage(Lang::DELETE_FAIL);
            $this->service->rollback();
        }
        return $res;
    }

    /**
     * 删除多条数据
     * @param HttpRequest $request
     * @return JsonResult
     */
    public function deletes( HttpRequest $request ) {

        $ids = $request->getParameter('ids');
        $res = new JsonResult(JsonResult::CODE_SUCCESS, Lang::DELETE_SUCCESS);
        if (empty($ids)){
            $res->setCode(JsonResult::CODE_FAIL);
            $res->setMessage(Lang::NO_RECOEDS);
        } else if (!$this->service->where("id", "IN", $ids)->deletes()) {
            $res->setCode(JsonResult::CODE_FAIL);
            $res->setMessage(Lang::DELETE_FAIL);
        }
        return $res;
    }

    /**
     * @param HttpRequest $request
     */
    public function exists(HttpRequest $request) {

        $field = $request->getParameter('field', 'trim');
        $value = $request->getParameter('value', 'trim');
        $exists = $this->service->where($field,$value)->findOne();
        if ($exists) {
            JsonResult::fail();
        } else {
            JsonResult::success();
        }
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        if ($page > 0) {
            $this->page = $page;
        } else {
            $this->page = 1;
        }
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize($pageSize)
    {
        if ($pageSize > 0) {
            $this->pageSize = $pageSize;
        }
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

}
