<?php
namespace app\admin\service;
use app\admin\dao\ManagerLoginLogDao;
use herosphp\model\CommonService;

/**
 * 管理员登录日志
 * ----------------
 * @author yangjian<yangjian102621@gmail.com>
 */
class ManagerLoginLogService extends CommonService {

    protected $modelClassName = ManagerLoginLogDao::class;

}