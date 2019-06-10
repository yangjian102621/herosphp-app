<?php
namespace app\demo\action;

use herosphp\bean\Beans;
use herosphp\core\Controller;
use herosphp\http\HttpRequest;

/**
 * Bean工具测试
 * @since           2015-01-28
 * @author          yangjian<yangjian102621@gmail.com>
 */
class TestAction extends Controller {

    /**
     * 首页方法
     * @param HttpRequest $request
     */
    public function index( HttpRequest $request ) {

        __print($request->getParameters());
        die();
    }
  
}
?>
