<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/4
 * Time: 21:06
 */

namespace App\Http\Controllers\External;


use App\Http\Controllers\Controller;
use App\Service\External\StorageCore;
use Symfony\Component\HttpFoundation\Request;

//上传文件
class UploadFileController extends Controller
{
    /**
     * @api {post} external/uploadFileToCache 上传文件
     * @apiName uploadFileToCache
     * @apiDescription 上传文件到临时目录
     * @apiGroup file
     * @apiSuccessExample {json} 客户端上传文件例子
    <!DOCTYPE html>
    <html>
    <head> 
    <meta charset="utf-8"> 
    <title>上传图片</title> 
    </head>
    <body>
    <form action="http://192.168.3.229:8021/api/external/uploadFileToCache" method="post" enctype="multipart/form-data">
    <input type="file" name="file"/>
    <input type="submit" name="submit" value="上传" />
    </form>
    </body>
    </html>
     **/
    public function uploadFileToCache(Request $rep)
    {
        return Response()->json(StorageCore::uploadFileToCache($rep));
    }
}
