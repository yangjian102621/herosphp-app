<?php
namespace app\admin\service;
use app\admin\dao\AdminDao;
use app\admin\dao\MenuDao;
use herosphp\core\Loader;
use herosphp\model\CommonService;

/**
 * 菜单服务
 * @author yangjian
 */
class MenuService extends CommonService {

    protected $modelClassName = MenuDao::class;

    /**
     * 获取分组后的菜单列表
     */
    public function getMenusByGroup() {

        $res = [];
        $appConfigs = Loader::config('app', 'env.'.ENV_CFG);
        foreach($appConfigs['menu_group'] as $key => $val) {
            $items = parent::where('groups', $key)->order("sort ASC")->find();
            foreach($items as $value) {
                $res[$key][] = $value;
            }
        }
        return $res;
    }

    /**
     * 获取当前登录用户的可见菜单
     * @param $user
     * @return array
     */
    public function getUserMenus($user) {
        $res = [];
        $appConfigs = Loader::config('app', 'env.'.ENV_CFG);
        foreach($appConfigs['menu_group'] as $key => $val) {
            $items = parent::where('groups', $key)->where('status', 1)->order("sort ASC")->find();
            foreach($items as $value) {
                if ($user['super'] || isset($user['permissions'][$value['permission']])) {
                    $res[$key][] = $value;
                }
            }
        }
        return $res;
    }
}