<?php

namespace app\controller;

use herosphp\annotation\Controller;
use herosphp\annotation\Get;
use herosphp\annotation\Post;
use herosphp\core\BaseController;
use herosphp\core\HttpRequest;
use herosphp\core\HttpResponse;
use herosphp\storage\Storage;
use herosphp\GF;
use herosphp\storage\core\Uploader;

#[Controller(name: UploadAction::class)]
class UploadController extends BaseController
{
    protected static ?Uploader $_uploader = null;

    public function __init()
    {
        parent::__init();

        static::$_uploader = Storage::getUploader();
    }

    #[Get(uri: '/upload', desc: 'upload demo')]
    public function upload(): HttpResponse
    {
        return $this->view('upload');
    }

    #[Post(uri: '/upload/do')]
    public function doUpload(HttpRequest $request)
    {
        $info = static::$_uploader->upload($request->file('src'));
        if ($info === false) {
            return GF::exportVar(static::$_uploader->getError()->getMessage());
        }
        return GF::exportVar($info);
    }

    #[Get(uri: '/upload/base64')]
    public function base64(): HttpResponse
    {
        return $this->view('upload-base64');
    }

    #[Post(uri: '/upload/base64/do')]
    public function doBase64Upload(HttpRequest $request)
    {
    }
}
