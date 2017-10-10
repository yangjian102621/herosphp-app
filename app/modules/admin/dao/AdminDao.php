<?php
/**
 * 管理员
 * @author  yangjian <yangjian102621@gmail.com>
 */

namespace app\admin\dao;

use herosphp\model\MysqlModel;

class AdminDao extends MysqlModel {

    public function __construct() {

        //创建model对象并初始化数据表名称
        parent::__construct('admin_user');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
} 