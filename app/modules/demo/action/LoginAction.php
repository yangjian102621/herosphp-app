<?php
/**
 * Created by PhpStorm.
 * User: yangjian
 * Date: 18-4-11
 * Time: 上午11:30
 */

namespace app\demo\action;


use herosphp\cache\CacheFactory;
use herosphp\cache\FileCache;
use herosphp\cache\interfaces\ICache;
use herosphp\core\Controller;
use herosphp\http\HttpRequest;
use herosphp\qrcode\QRcode;
use herosphp\utils\JsonResult;

/**
 * 扫码登录
 * @author yangjian
 * Class LoginAction
 * @package app\demo\action
 */
class LoginAction extends Controller
{
    /**
     * @var ICache
     */
    private $cacher;
    const LOGIN_STATUS = "LOGIN_STATUS";
    const LOGIN_ING = "LOGIN_ING";
    const LOGIN_OK = "LOGIN_OK";

    public function C_start() {
        parent::C_start();
        $this->cacher = CacheFactory::create(FileCache::class);
    }

    /**
     * 登录页面
     */
    public function index(HttpRequest $request) {

        $this->setView("login");

    }

    /**
     * 生成二维码
     * @param HttpRequest $requests
     */
    public function qrcode(HttpRequest $request) {
        QRcode::png("http://192.168.0.118/demo/login/check?username=xiaoming")->size(300)->show();
    }

    /**
     * 获取支付状态接口
     * @param HttpRequest $request
     */
    public function status(HttpRequest $request) {

        //超时时间
        $timeout = 15;
        while($timeout > 0) {
            $data = $this->cacher->get(self::LOGIN_STATUS);
            if ($data == self::LOGIN_ING) {
                $this->cacher->set(self::LOGIN_STATUS, "", 3600);
                die($data);
            }
            if ($data == self::LOGIN_OK) {
                $this->cacher->set(self::LOGIN_STATUS, "", 3600);
                die($data);
            }
            $timeout = $timeout-1;
            sleep(1);
        }
        die("TIME_OUT");
    }

    /**
     * 扫码后跳转的页面
     * @param HttpRequest $request
     */
    public function check(HttpRequest $request) {
        $username = $request->getParameter("username");
        //登录中
        $this->cacher->set(self::LOGIN_STATUS, self::LOGIN_ING, 3600);
        $this->setView("login_check");
    }

    /**
     * 登录操作
     * @param HttpRequest $request
     */
    public function loginCheck(HttpRequest $request) {

        $username = $request->getParameter("username");
        //login check code here
        if ($username != "") {
            $this->cacher->set(self::LOGIN_STATUS, self::LOGIN_OK, 3600);
        }
        die(self::LOGIN_OK);
    }

    /**
     * 登录成功跳转页面
     */
    public function success() {
        $this->setView("login_success");
    }
}