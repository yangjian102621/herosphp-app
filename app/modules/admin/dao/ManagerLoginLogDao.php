<?php
/**
 * 管理员登录日志
 * @author  yangjian <yangjian102621@gmail.com>
 */

namespace app\admin\dao;

use herosphp\model\MysqlModel;

class ManagerLoginLogDao extends MysqlModel {

    public function __construct() {

        //创建model对象并初始化数据表名称
        parent::__construct('manager_login_log');

        //设置表数据表主键，默认为id
        $this->primaryKey = 'id';
    }
} 