<?php
namespace app\admin\service;
use app\admin\dao\AdminDao;
use app\admin\dao\RoleDao;
use herosphp\model\CommonService;

/**
 * 管理员角色服务
 * @author yangjian
 */
class RoleService extends CommonService {

    protected $modelClassName = RoleDao::class;
}