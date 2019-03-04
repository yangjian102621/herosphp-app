<?php
/**
 * user API service
 * User: yangjian
 * Date: 19-3-4
 * Time: 下午10:10
 */

namespace app\api\service;


use herosphp\utils\JsonResult;

class UserService
{

    /**
     * user login service
     * @param $username
     * @param $password
     */
    public function login($username, $password)
    {

        if ($username == "rock" && $password == "123456") {
            JsonResult::success("login success.");
        } else {
            JsonResult::fail("login failed.");
        }
    }

    /**
     * create a new user
     * @param $username
     * @param $password
     * @param $mobile
     */
    public function register($username, $password, $mobile)
    {
        JsonResult::success("register success.");
    }

}