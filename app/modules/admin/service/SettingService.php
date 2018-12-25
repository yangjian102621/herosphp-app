<?php
namespace app\admin\service;
use app\admin\dao\SettingDao;
use herosphp\model\CommonService;

/**
 * 系统配置服务
 * @author yangjian
 */
class SettingService extends CommonService {

    protected $modelClassName = SettingDao::class;

    /**
     * 根据 key 获取配置的值
     * @param $key
     */
    public function getConfig($key) {
        $item = parent::where('item_key', $key)->findOne();
        return $item['item_value'];
    }
}