<?php
namespace app\admin\action;

use herosphp\core\Controller;
use herosphp\files\FileUpload;
use herosphp\http\HttpRequest;
use herosphp\utils\JsonResult;

/**
 * 图片上传
 * @author  yangjian<yangjian102621@gmail.com>
 */
class UploadAction extends Controller {

    /**
     * 首页方法
     * @param HttpRequest $request
     */
    public function index( HttpRequest $request ) {

        $dir = "/static/upload/".date('Y')."/".date('m')."/";
        $config = array(
            "upload_dir" => APP_ROOT.$dir,
            //允许上传的文件类型
            'allow_ext' => 'jpg|jpeg|png|gif',
            //图片的最大宽度, 0没有限制
            'max_width' => 0,
            //图片的最大高度, 0没有限制
            'max_height' => 0,
            //文件的最大尺寸
            'max_size' =>  1020*1024 * 10,     /* 文件size的最大 1MB */
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
  
}