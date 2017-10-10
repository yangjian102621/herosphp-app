<?php
namespace app\admin\action;

use app\admin\utils\Lang;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\core\WebApplication;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use herosphp\utils\Page;

/**
 * admin 模块基类控制
 * @author  yangjian<yangjian102621@gmail.com>
 */
abstract class CommonAction extends Controller {

    // 页码
    protected $page = 1;

    // 每页数量
    protected $pageSize = 20;

    // 当前登陆的管理员
    protected $loginManager;

    // 当前服务的 class path
    protected $serviceClass;

    // 当前服务
    protected $service;

    // 操作名称
    protected $actionTitle;

    public function __construct()
    {
        parent::__construct();
        $request = WebApplication::getInstance()->getHttpRequest();

        //获取当前登陆管理员
       // $managerService = Loader::service(ManagerService::class);
        //获取当前登陆的管理员
       // $this->loginManager =$managerService->getLoginManager();
        $this->assign('loginManager', $this->loginManager);
        $module = $request->getModule();
        $action = $request->getAction();
        $this->assign("index_url", "/{$module}/{$action}/index");
        $this->assign('add_url',"/{$module}/{$action}/add");
        $this->assign('edit_url',"/{$module}/{$action}/edit");
        $this->assign("insert_url", "/{$module}/{$action}/insert");
        $this->assign("update_url", "/{$module}/{$action}/update");
        $this->assign('remove_url',"/{$module}/{$action}/remove");
        $this->assign('delete_url',"/{$module}/{$action}/delete");
        $this->assign('deletes_url',"/{$module}/{$action}/deletes");
        $this->assign("noRecords", Lang::NO_RECOEDS);
        //首页
        $this->assign('manager_index',"/admin/index/index");
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
        $items = $this->service->page($this->getPage(), $this->getPagesize())->find();
        $this->service->getModelDao()->setSqlBuilder($sqlBuilder);
        $total = $this->service->setSqlBuilder($sqlBuilder)->count();
        $this->PageView($total);
        $this->assign('items', $items);
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
            $pageMenu = '<ul class="am-pagination am-pagination-centered">';
            $pageMenu .= '<li><a href="'.$pageData['first'].'">首页</a></li>';
            $pageMenu .= '<li><a href="'.$pageData['prev'].'">上一页</a></li> ';
            foreach ( $pageData['list'] as $key => $value ) {
                if ( $key == $this->page ) {
                    $pageMenu .= '<li class="am-active"><span>'.$key.'</span></li> ';
                } else {
                    $pageMenu .= '<li><a href="'.$value.'">'.$key.'</a></li> ';
                }
            }
            $pageMenu .= '<li><a href="'.$pageData['next'].'">下一页</a></li> ';
            $pageMenu .= '<li><a href="'.$pageData['last'].'">末页</a></li>';
            $pageMenu .= $this->PageInputAll();
            $pageMenu .= '<li class="am-disabled page-count">总共'.ceil($pageData['total']/$this->pageSize).'页</li>';
            $pageMenu .= '</ul>';
        }
        $this->assign('pageMenu', $pageMenu);
    }

    /**
     * @param HttpRequest $request
     * 编辑数据
     */
    public function edit(HttpRequest $request) {
        $id = $request->getParameter('id', 'trim');
        if (empty($id)) {
            JsonResult::result(JsonResult::CODE_FAIL, Lang::NO_RECOEDS);
        } else {
            $item = $this->service->findById($id);
            $this->assign('item', $item);
        }
    }

    /**
     * @param HttpRequest $request
     * 添加数据
     */
    public function insert(HttpRequest $request) {

        $data = $request->getParameter('data');
        $data['addtime'] = date("Y-m-d H:i:s");
        $data['updatetime'] = date("Y-m-d H:i:s");
        $error = $this->service->Filter($data);
        if ($error){
            JsonResult::result(JsonResult::CODE_FAIL, $error);
        }
        if ( $this->service->add($data)) {
            JsonResult::result(JsonResult::CODE_SUCCESS, Lang::INSERT_SUCCESS);
        } else {
            JsonResult::result(JsonResult::CODE_FAIL, Lang::INSERT_FAIL);
        }
    }




    /**
     * @param HttpRequest $request
     * 更新数据
     */
    public function update(HttpRequest $request) {
        $id = $request->getParameter('id', 'trim');
        if (empty($id)) {
            JsonResult::result(JsonResult::CODE_FAIL, Lang::NO_RECOEDS);
        }
        $data = $request->getParameter('data');
        $data['updatetime'] = date('Y-m-d H:i:s');
        $error = $this->service->Filter($data);
        if ($error){
            JsonResult::result(self::CODE_FAIL,$error);
        }
        if ($this->service->update($data,$id)) {
            JsonResult::result(JsonResult::CODE_SUCCESS, Lang::UPDATE_SUCCESS);
        } else {
            JsonResult::result(JsonResult::CODE_FAIL, Lang::UPDATE_FAIL);
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
    public function delete( HttpRequest $request ) {

        $id = $request->getParameter('id', 'trim');
        if ( empty($id) ) {
            JsonResult::result(JsonResult::CODE_FAIL, Lang::NO_RECOEDS);
        }
        if ( $this->service->delete($id) ) {
            JsonResult::result(JsonResult::CODE_SUCCESS, Lang::DELETE_SUCCESS);
        } else {
            JsonResult::result(JsonResult::CODE_FAIL, Lang::DELETE_FAIL);
        }
    }


    /**
     * 加载左侧的菜单
     * 设置最大的菜单200条
     */
    protected function loadLeftMenu() {
        $service = Loader::service(ManagerMenuDao::class);
        $menuData = $service->order('sort_num asc')
            ->where('enable',1)
            ->offset(0,200)
            ->find();
        $menus = arrayGroup($menuData, 'pid');
        $this->assign('menus',$menus);
    }


    /**
     * 删除多条数据
     * @param HttpRequest $request
     */
    public function deletes( HttpRequest $request ) {
        $ids = $request->getParameter('ids');
        if (empty($ids)){
            JsonResult::result(JsonResult::CODE_FAIL, Lang::NO_RECOEDS);
        }
        if ($this->service->deletes($ids)) {
            JsonResult::result(JsonResult::CODE_SUCCESS, Lang::DELETE_SUCCESS);
        } else {
            JsonResult::result(JsonResult::CODE_FAIL, Lang::DELETE_FAIL);
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
        $this->page = $page;
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
        $this->pageSize = $pageSize;
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
