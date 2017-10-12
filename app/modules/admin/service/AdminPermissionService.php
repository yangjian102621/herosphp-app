<?php
namespace app\admin\service;
use app\admin\dao\AdminPermissionDao;
use herosphp\model\CommonService;

/**
 * 管理员角色权限服务
 * ----------------
 * @author yangjian<yangjian102621@gmail.com>
 */
class AdminPermissionService extends CommonService {

    protected $modelClassName = AdminPermissionDao::class;

}