<?php
namespace app\admin\service;

use herosphp\cache\CacheFactory;
use herosphp\cache\FileCache;
use herosphp\model\CommonService;
use app\admin\dao\AdminMenuDao;
use herosphp\utils\ArrayUtils;

/**
 * 管理员菜单服务
 * @author yangjian<yangjian102621@gmail.com>
 */
class AdminMenuService extends CommonService {

	protected $modelClassName = AdminMenuDao::class;

    const MENU_CACHE_KEY = "admin_menu_cache";

    /**
     * 获取菜单缓存
     */
    public function getMenuCache() {
        $cacher = CacheFactory::create(FileCache::class);
        $items = $cacher->get(self::MENU_CACHE_KEY);
        if (empty($items)) {
            $this->updateMenuCache();
            return $cacher->get(self::MENU_CACHE_KEY);
        }
        return $items;
    }

    /**
     * 更新菜单缓存
     */
    public function updateMenuCache() {
        // 获取一级菜单
        $topMenus = $this->order('sort ASC')->where('pid', 0)->find();
        $menus = $this->order('sort ASC')->find();
        foreach($topMenus as $k => $v) {
            $topMenus[$k]['subs'] = ArrayUtils::filterArrayByKey('pid', $v['id'], $menus);
        }

        $cacher = CacheFactory::create(FileCache::class);
        $cacher->set(self::MENU_CACHE_KEY, $topMenus, 0);
    }

}