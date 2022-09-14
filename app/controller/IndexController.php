<?php
declare(strict_types=1);

namespace app\controller;

use herosphp\annotation\Controller;
use herosphp\annotation\Get;
use herosphp\core\BaseController;

#[Controller(IndexController::class)]
class IndexController extends BaseController
{
    #[Get(uri: '/')]
    public function index(): string
    {
        return "hello herosphp";
    }
}
