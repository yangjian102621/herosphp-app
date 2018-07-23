<?php
namespace app\admin\action;

use herosphp\core\Controller;
use herosphp\files\FileUpload;
use herosphp\files\FileUtils;
use herosphp\http\HttpRequest;
use herosphp\string\StringUtils;
use herosphp\utils\JsonResult;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

//发送跨域头信息,指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型,这里可以指定，也可以不指定，不过个人觉得还是指定好一些
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

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

        $fileType = $request->getParameter('fileType');
        $base64 = $request->getParameter('base64');
        if (empty($fileType)) {
            $fileType = "image";
        }
        // 初始化 UploadManager 对象并进行文件的上传。
        $upConfigs = getConfig('qiniu_upload_configs');
        $uploadMgr = new UploadManager();
        // 构建鉴权对象
        $auth = new Auth($upConfigs['ACCESS_KEY'], $upConfigs['SECRET_KEY']);
        // 生成上传 Token
        $token = $auth->uploadToken($upConfigs['BUCKET']);

        if ($base64) {
            $data = $_POST['img_base64_data'];
            $filename = "{$fileType}-".time().".png";
            $res = $this->base64Upload($data, $filename, $token);
            $json = new JsonResult();
            if ($res) {
                $res = StringUtils::jsonDecode($res);
                $json->setCode(JsonResult::CODE_SUCCESS);
                $json->setItem(array("url" => $upConfigs['BUCKET_DOMAIN'].$res['key'], "title" => "涂鸦"));
            } else {
                $json->setCode(JsonResult::CODE_FAIL);
                $json->setMessage("上传涂鸦失败!");
            }
            $json->output();
        }

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

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            $json->setCode(JsonResult::CODE_FAIL);
            $json->setMessage($err->message());
        } else {
            $json->setCode(JsonResult::CODE_SUCCESS);
            $json->setMessage("上传成功.");
            $json->setItem(array("url" => $upConfigs['BUCKET_DOMAIN'] . $ret['key'], "title" => $_FILES['file']['name']));
        }
        $json->output();
    }

    /**
     * 图片列表
     * @param HttpRequest $request
     */
    public function filelist(HttpRequest $request) {

        $fileType = $request->getParameter('fileType');
        if ($fileType == '') {
            $fileType = "image";
        }
        $marker = $request->getParameter('marker'); //上次列举返回的位置标记，作为本次列举的起点信息。

        if ($marker == "no_records") {
            JsonResult::fail("没有更多的文件了");
        }
        // 要列取文件的公共前缀
        $prefix = $fileType."-";

        // 本次列举的条目数
        $limit = 15;
        $delimiter = '/';

        // 初始化 UploadManager 对象并进行文件的上传。
        $upConfigs = getConfig('qiniu_upload_configs');
        // 构建鉴权对象
        $auth = new Auth($upConfigs['ACCESS_KEY'], $upConfigs['SECRET_KEY']);
        $bucketManager = new BucketManager($auth);
        list($ret, $err) = $bucketManager->listFiles($upConfigs['BUCKET'], $prefix, $marker, $limit, $delimiter);

        $result = new JsonResult();
        if ($err !== null) {
            $result->setCode(JsonResult::CODE_FAIL);
            $result->setMessage($err);
        } else {
            $files = array();
            $result->setCode(JsonResult::CODE_SUCCESS);
            foreach($ret["items"] as $value) {
                $filename = $value['key'];
                if (strpos($value['mimeType'], 'image') !== false) { //如果是图片则获取尺寸
                    $imgSize = $this->getImgSize($value['key']);

                }
                array_push($files, array(
                    "thumbURL" => $upConfigs['BUCKET_DOMAIN'].$filename,
                    "oriURL" => $upConfigs['BUCKET_DOMAIN'].$filename,
                    "filesize" => $value['fsize'],
                    "width" => intval($imgSize["width"]),
                    "height" => intval($imgSize["height"])));
            }
            $result->setItems($files);
            if (array_key_exists('marker', $ret)) {
                $result->setItem($ret['marker']);
            } else {
                $result->setItem("no_records");
            }
        }
        $result->output();
    }

    private function getImgSize($filename) {
        $json = file_get_contents(QINIU_BUCKET_DOMAIN."{$filename}?imageInfo");
        return json_decode($json, true);
    }

    /**
     * 上传 base64 图片
     * @param $data
     * @param $filename
     * @param $upToken
     * @return bool|mixed
     */
    private function base64Upload($data, $filename, $upToken)
    {

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $data, $match)) {

            $imgData = str_replace($match[1], '', $data); //去掉图片的声明前缀

            /**
             * upload.qiniu.com 上传域名适用于华东空间。华北空间使用 upload-z1.qiniu.com，
             * 华南空间使用 upload-z2.qiniu.com，北美空间使用 upload-na0.qiniu.com。
             */
            $url = "http://upload-z2.qiniu.com/putb64/-1/key/".base64_encode($filename);
            $headers = array();
            $headers[] = 'Content-Type:image/png';
            $headers[] = 'Authorization:UpToken ' . $upToken;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $imgData);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        return false;

    }
  
}