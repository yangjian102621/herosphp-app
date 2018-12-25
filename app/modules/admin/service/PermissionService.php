<?php
namespace app\admin\service;
use app\admin\dao\AdminDao;
use app\admin\dao\PermissionDao;
use herosphp\model\CommonService;

/**
 * 管理员权限服务
 * @author yangjian
 */
class PermissionService extends CommonService {

    protected $modelClassName = PermissionDao::class;
}