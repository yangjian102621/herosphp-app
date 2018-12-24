<?php
namespace app\admin\action;

use herosphp\http\HttpRequest;

/**
 * 后台
 * @author  yangjian<yangjian102621@gmail.com>
 */
class HomeAction extends CommonAction {

    /**
     * 列表
     * @param HttpRequest $request
     */
    public function index(HttpRequest $request) {

        $this->assign('title', 'Herosphp 系统管理后台');
        $this->setView("index");

    }

    /**
     * 404 页面
     */
    public function page404(HttpRequest $request) {
        $this->setView('404');
    }

    /**
     * 403 页面
     */
    public function page403(HttpRequest $request) {
        $this->setView('403');
    }

}