<?php
namespace app\admin\service;
use app\admin\dao\AdminDao;
use herosphp\model\CommonService;

/**
 * 管理员服务
 * ----------------
 * @author yangjian<yangjian102621@gmail.com>
 * @date 2017-03-20
 */
class AdminService extends CommonService {

    protected $modelClassName = AdminDao::class;

}