<?php
namespace app\demo\action;

use herosphp\cache\CacheFactory;
use herosphp\cache\FileCache;
use herosphp\core\Controller;
use herosphp\core\Loader;
use herosphp\http\HttpRequest;
use herosphp\utils\RedisUtils;

/**
 * 缓存测试
 * @since           2015-01-28
 * @author          yangjian<yangjian102621@gmail.com>
 */
class CacheAction extends Controller {

    /**
     * 通用缓存
     * @param HttpRequest $request
     */
    public function index( HttpRequest $request ) {

        $CACHE = CacheFactory::create(FileCache::class);
        $key  = 'test.cache.key';
        $CACHE->set($key, 'this is the test cache data. fuck it whatever!', 10);
        $data = $CACHE->get($key);
        __print($data);
        die();
    }

    public function detail( HttpRequest $request ) {
        $CACHER = CacheFactory::create('file');
        $CACHER->baseKey('article')->ftype('detail')->factor('299');
        $item = $CACHER->get(null);
        if ( !$item ) {
            $model = Loader::model('article');
            $item = $model->getItem(299);
            $CACHER->set(null, $item);
            __print("生成动态缓存成功！");
        } else {
            __print($item);
        }

        die();
    }

    //静态缓存测试
    public function html( HttpRequest $request ) {
        $CACHER = CacheFactory::create('html');
        $CACHER->baseKey('article')->ftype('detail')->factor('299');
        $item = $CACHER->get(null);
        if ( !$item ) {
            $model = Loader::model('article');
            $item = $model->getItem(299);
            $this->assign('item', $item);
            $html = $this->getExecutedHtml();
            if ( $CACHER->set(null, $html) ) {
                __print("生成静态缓存成功！");
            }

        } else {
            echo $item;
        }
        die();
    }

    //memcache 测试
    public function memory( HttpRequest $request ) {

        $CACHER = CacheFactory::create('memo');
        $key = 'test.data';
        $data = $CACHER->get($key);
        if ( !$data ) {
            $CACHER->set($key, "测试 Memcache 缓存数据!");
        }
        __print($data);
        die();
    }

    //redis 测试
    public function redis( HttpRequest $request ) {

        $CACHER = CacheFactory::create('redis');
        $CACHER->set("test_key", "this is the test data", 10);

        var_dump($CACHER->get("test_key"));
        die();
    }
}
?>
