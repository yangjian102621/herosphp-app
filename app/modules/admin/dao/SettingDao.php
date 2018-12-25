<?php
/**
 * 系统配置 dao
 * @author  yangjian
 */

namespace app\admin\dao;

use herosphp\filter\Filter;
use herosphp\model\MysqlModel;

class SettingDao extends MysqlModel {

    public function __construct() {

        //创建model对象并初始化数据表名称
        parent::__construct('settings');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
        $this->autoPrimary = true;

        $this->filterMap = array(
            'item_value' => array(NULL, NULL, Filter::DFILTER_SANITIZE_TRIM|Filter::DFILTER_MAGIC_QUOTES)
        );
    }
} 