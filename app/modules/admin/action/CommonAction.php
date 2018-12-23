<?php
namespace app\admin\action;

use app\admin\model\Admin;
use app\admin\service\AdminMenuService;
use app\admin\service\AdminService;
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

    // 操作名称
    protected $actionTitle;

    public function __construct()
    {
        parent::__construct();
        $request = WebApplication::getInstance()->getHttpRequest();

        //获取当前登陆管理员
        $adminService = Loader::service(AdminService::class);
        $loginUser = $adminService->getLoginManager();
//        if (!$loginUser) {
//            location("/admin/login/index");
//        }
        $this->loginUser = ModelTransformUtils::map2Model(Admin::class, $loginUser);
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

        //加载菜单
        $this->loadLeftMenu();
    }

    /**
     * @param HttpRequest $request
     * 首页列表
     */
    public function index(HttpRequest $request) {
        $this->page = $request->getParameter('page', 'intval');
        if ( $this->page <=0 ) {
            $this->page = 1;
        }
        if (empty($this->service)) {
            $this->service = Loader::service($this->serviceClass);
        }

        $sqlBuilder = $this->service->getSqlBuilder();
        $items = $this->service->page($this->getPage(), $this->getPagesize())->order($this->getOrder())->find();
        $this->service->getModelDao()->setSqlBuilder($sqlBuilder);
        $total = $this->service->setSqlBuilder($sqlBuilder)->count();
        $this->PageView($total);
        $this->assign('items', $items);
    }

    /**
     * 获取数据列表
     * @param HttpRequest $request
     * @return JsonResult
     */
    public function list(HttpRequest $request) {

        $page = $request->getIntParam("page");
        $pageSize = $request->getIntParam("pagesize");
        $this->setPage($page);
        $this->setPageSize($pageSize);
        if (empty($this->service)) {
            $this->service = Loader::service($this->serviceClass);
        }

        $sqlBuilder = $this->service->getSqlBuilder();
        $items = $this->service->page($this->getPage(), $this->getPagesize())->order($this->getOrder())->find();
        $total = $this->service->setSqlBuilder($sqlBuilder)->count();
        $res = new JsonResult(JsonResult::CODE_SUCCESS, "数据获取成功.");
        $res->setCount($total);
        $res->setPage($this->getPage());
        $res->setPagesize($this->getPageSize());
        $res->setItems($items);
        return $res;

    }

    /**
     * 分页
     * @param $total
     * @param $pagesize
     * @param $page
     */
    protected function PageView($total){

        //初始化分页类
        $pageHandler = new Page($total, $this->getPagesize(), $this->getPage(), 3);
        //获取分页数据
        $pageData = $pageHandler->getPageData(DEFAULT_PAGE_STYLE);
        //组合分页HTML代码
        if ( $pageData ) {
            $pageMenu = '<ul class="am-pagination tpl-pagination">';
            $pageMenu .= '<li><a href="'.$pageData['first'].'">首页</a></li>';
            $pageMenu .= '<li><a href="'.$pageData['prev'].'">上一页</a></li> ';
            foreach ( $pageData['list'] as $key => $value ) {
                if ( $key == $this->page ) {
                    $pageMenu .= '<li class="am-active"><a href="#">'.$key.'</a></li> ';
                } else {
                    $pageMenu .= '<li><a href="'.$value.'">'.$key.'</a></li> ';
                }
            }
            $pageMenu .= '<li><a href="'.$pageData['next'].'">下一页</a></li> ';
            $pageMenu .= '<li><a href="'.$pageData['last'].'">末页</a></li>';
            $pageMenu .= '<li class="am-disabled page-count">总共'.ceil($pageData['total']/$this->pageSize).'页</li>';
            $pageMenu .= '</ul>';
        }
        $this->assign('pageMenu', $pageMenu);
    }

    /**
     * @param HttpRequest $request
     * 编辑数据
     */
    protected function _edit(HttpRequest $request) {
        $id = $request->getParameter('id', 'trim');
        if (empty($id)) {
            JsonResult::fail(Lang::NO_RECOEDS);
        } else {
            $item = $this->service->findById($id);
            $this->assign('item', $item);
        }
    }

    /**
     * 添加数据
     * @param $data
     * @param $callback
     */
    protected function _insert($data, $callback) {

        $data['addtime'] = date("Y-m-d H:i:s");
        $data['updatetime'] = date("Y-m-d H:i:s");
        if ($this->service->add($data)) {
            if (!is_null($callback)) {
                call_user_func($callback, $this->service);
            }
            JsonResult::success(Lang::INSERT_SUCCESS);
        } else {
            $message = WebApplication::getInstance()->getAppError()->getMessage();
            JsonResult::fail(empty($message) ? Lang::INSERT_FAIL : $message);
        }
    }

    /**
     * 更新数据
     * @param array $data
     * @param $id
     * @param $callback
     */
    protected function _update(array $data, $id, $callback) {
        if (empty($id)) {
            JsonResult::fail(Lang::NO_RECOEDS);
        }
        $data['updatetime'] = date('Y-m-d H:i:s');
        if ($this->service->update($data, $id)) {
            if (!is_null($callback)) {
                call_user_func($callback, $this->service);
            }
            JsonResult::success(Lang::UPDATE_SUCCESS);
        } else {
            $message = WebApplication::getInstance()->getAppError()->getMessage();
            JsonResult::fail(empty($message) ? Lang::UPDATE_FAIL : $message);
        }
    }

    /**
     * 启用|禁用 操作
     * @param HttpRequest $request
     */
    public function enable(HttpRequest $request, $callback) {
        $id = $request->getParameter('id', 'trim');
        $enable = $request->getParameter('enable');
        if (empty($id)) {
            JsonResult::fail(Lang::OPT_FAIL);
        }
        if ($this->service->set('enable', $enable, $id)) {

            if (!is_null($callback)) {
                call_user_func($callback, $this->service);
            }

            JsonResult::success(Lang::OPT_SUCCESS);
        } else {
            JsonResult::fail(Lang::OPT_FAIL);
        }
    }

    /**
     * 设置操作名称
     * @param $name
     */
    protected function setOpt($name){
        $this->assign('optTitle', $name);
    }

    /**
     * 删除单条数据
     * @param HttpRequest $request
     */
    public function delete( HttpRequest $request, $callback) {

        $id = $request->getParameter('id', 'trim');
        if ( empty($id) ) {
            JsonResult::fail(Lang::NO_RECOEDS);
        }
        if ( $this->service->delete($id) ) {

            if (!is_null($callback)) {
                call_user_func($callback, $this->service);
            }

            JsonResult::success(Lang::DELETE_SUCCESS);
        } else {
            JsonResult::fail(Lang::DELETE_FAIL);
        }
    }


    /**
     * 加载左侧的菜单
     * 设置最大的菜单200条
     */
    protected function loadLeftMenu() {
        $service = Loader::service(AdminMenuService::class);
        $items = $service->getMenuCache();
        $this->assign('adminMenus', $items);
    }


    /**
     * 删除多条数据
     * @param HttpRequest $request
     */
    public function deletes( HttpRequest $request ) {
        $ids = $request->getParameter('ids');
        if (empty($ids)){
            JsonResult::fail(Lang::NO_RECOEDS);
        }
        if ($this->service->deletes($ids)) {
            JsonResult::success(Lang::DELETE_SUCCESS);
        } else {
            JsonResult::fail(Lang::DELETE_FAIL);
        }
    }

    /**
     * @param HttpRequest $request
     */
    public function exists(HttpRequest $request) {

        $field = $request->getParameter('field', 'trim');
        $value = $request->getParameter('value', 'trim');
        if ($this->checkField($field, $value)) {
            JsonResult::fail();
        } else {
            JsonResult::success();
        }
    }

    /**
     * @param $field
     * @param $value
     * @return bool
     * 检验某个字段的值是否在数据库中存在，用于保持某个字段的唯一性
     */
    protected function checkField($field, $value) {
        $value = trim($value);
        $exists = $this->service->where($field,$value)->findOne();
        if ( $exists ) {
            return true;
        }
        return false;
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

    /**
     * @return mixed
     */
    public function getActionTitle()
    {
        return $this->actionTitle;
    }

    /**
     * @param mixed $actionTitle
     */
    public function setActionTitle($actionTitle)
    {
        $this->actionTitle = $actionTitle;
    }


}
