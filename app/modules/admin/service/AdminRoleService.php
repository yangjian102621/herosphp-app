<?php
namespace app\admin\service;
use app\admin\dao\AdminDao;
use app\admin\dao\AdminRoleDao;
use herosphp\model\CommonService;

/**
 * 管理员角色服务
 * ----------------
 * @author yangjian<yangjian102621@gmail.com>
 */
class AdminRoleService extends CommonService {

    protected $modelClassName = AdminRoleDao::class;

}