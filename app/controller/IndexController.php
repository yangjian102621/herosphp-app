<?php

declare(strict_types=1);

namespace app\controller;

use app\service\UserService;
use herosphp\annotation\Controller;
use herosphp\annotation\Get;
use herosphp\annotation\Inject;
use herosphp\core\BaseController;

#[Controller(IndexController::class)]
class IndexController extends BaseController
{
    #[Inject(name: UserService::class)]
    protected UserService $userSer;

    #[Get(uri: '/')]
    public function index(): string
    {
        $this->userSer->login();
        return 'hello herosphp';
    }
}
