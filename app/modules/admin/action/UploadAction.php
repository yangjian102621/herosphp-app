<?php
namespace app\admin\action;

use herosphp\core\Controller;
use herosphp\files\FileUpload;
use herosphp\files\FileUtils;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * 图片上传
 * @author  yangjian<yangjian102621@gmail.com>
 */
class UploadAction extends Controller {

    // 允许上传的图片后缀名
    const ALLOW_IMG_EXT = 'jpg|jpeg|png|gif';
    //最大上传 5MB 大小的文件
    const MAX_FILESIZE = 1024*1024*2;

    /**
     * 本地文件上传
     * @param HttpRequest $request
     */
    public function index( HttpRequest $request ) {

        $dir = "/static/upload/".date('Y')."/".date('m')."/";
        $config = array(
            "upload_dir" => APP_ROOT.$dir,
            //允许上传的文件类型
            'allow_ext' => self::ALLOW_IMG_EXT,
            //图片的最大宽度, 0没有限制
            'max_width' => 0,
            //图片的最大高度, 0没有限制
            'max_height' => 0,
            //文件的最大尺寸
            'max_size' => self::MAX_FILESIZE
        );
        $upload = new FileUpload($config);
        $result = $upload->upload('file');
        $json = new JsonResult();
        if ( $result ) {
            $json->setCode(JsonResult::CODE_SUCCESS);
            $json->setItem($dir.$result['file_name']);
        } else {
            $json->setCode(JsonResult::CODE_FAIL);
            $json->setMessage($upload->getUploadMessage());
        }
        $json->output();

    }

    /**
     * 上传图片到七牛云
     * @param HttpRequest $request
     */
    public function qiniu(HttpRequest $request) {

        if (!$_FILES['file']) {
            JsonResult::fail("没有上传任何文件");
        }
        $filePath = $_FILES['file']['tmp_name'];
        $fileExt = FileUtils::getFileExt($_FILES['file']['name']);
        $json = new JsonResult();
        $allowFileExts = explode('|', self::ALLOW_IMG_EXT);
        if (!in_array($fileExt, $allowFileExts)) {
            $json->setCode(\JsonResult::CODE_FAIL);
            $json->setMessage("非法的文件后缀名.");
            $json->output();
        }
        if (filesize($filePath) > self::MAX_FILESIZE) {
            $json->setCode(\JsonResult::CODE_FAIL);
            $json->setMessage("文件大小超出限制 2MB.");
            $json->output();
        }

        // 上传到七牛后保存的文件名
        $key = "image-" . time() . mt_rand(1000, 9999) . ".{$fileExt}";

        // 初始化 UploadManager 对象并进行文件的上传。
        $upConfigs = getConfig('qiniu_upload_configs');
        $uploadMgr = new UploadManager();
        // 构建鉴权对象
        $auth = new Auth($upConfigs['ACCESS_KEY'], $upConfigs['SECRET_KEY']);
        // 生成上传 Token
        $token = $auth->uploadToken($upConfigs['BUCKET']);

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            $json->setCode(JsonResult::CODE_FAIL);
            $json->setMessage($err->message());
        } else {
            $json->setCode(JsonResult::CODE_SUCCESS);
            $json->setMessage("上传成功.");
            $json->setItem($upConfigs['BUCKET_DOMAIN'] . $ret['key']);
        }
        $json->output();
    }
  
}