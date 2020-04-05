define({ "api": [
  {
    "type": "post",
    "url": "external/uploadFileToCache",
    "title": "上传文件",
    "name": "uploadFileToCache",
    "description": "<p>上传文件到临时目录</p>",
    "group": "file",
    "success": {
      "examples": [
        {
          "title": "客户端上传文件例子",
          "content": "<!DOCTYPE html>\n<html>\n<head> \n<meta charset=\"utf-8\"> \n<title>上传图片</title> \n</head>\n<body>\n<form action=\"http://192.168.3.229:8021/api/external/uploadFileToCache\" method=\"post\" enctype=\"multipart/form-data\">\n<input type=\"file\" name=\"file\"/>\n<input type=\"submit\" name=\"submit\" value=\"上传\" />\n</form>\n</body>\n</html>",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/External/UploadFileController.php",
    "groupTitle": "file"
  }
] });
