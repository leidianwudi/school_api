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
  },
  {
    "type": "post",
    "url": "school/getSchool",
    "title": "查询学校",
    "name": "getSchool",
    "description": "<p>根据名称和页数查询学校</p>",
    "group": "school",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "school",
            "description": "<p>学校名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>第几页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "count",
            "description": "<p>每页记录数</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回数据",
          "content": "{\n\"school\": \"中国地质大学(北京)\"  //学校名称\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/School/SchoolController.php",
    "groupTitle": "school"
  }
] });
