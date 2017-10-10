<?php
namespace app\demo\service;

use herosphp\model\CommonService;

/**
 * UserService
 * @author yangjian<yangjian102621@gmail.com>
 * @date 2017-07-05
 */
class UserService extends CommonService {

	protected $modelClassName = 'app\demo\dao\UserDao';

    public function hello() {

        printf("Hello, World.");
    }

}