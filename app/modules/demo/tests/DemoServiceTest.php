<?php
/**
 * 单元测试类
 * @author yangjian<yangjian102621@gmail.com>
 * @date 2017-06-20
 */

namespace app\demo\tests;
use app\demo\service\DemoService;
use herosphp\http\HttpClient;

class DemoServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DemoService
     */
    protected $service;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->service = new DemoService();
    }

    /**
     * @test
     */
    public function hello() {
        $this->assertTrue($this->service->hello());
    }

    /**
     * @test
     */
    public function httpclient() {

        $result = HttpClient::delete('http://api.herosphp.my/shops/11111',
            json_encode(array("username" => 'xxxxxx', "password" => "11111111")));
        print_r($result);
    }

}
