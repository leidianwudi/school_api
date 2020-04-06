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
    "url": "school/getProfession",
    "title": "查询专业",
    "name": "getProfession",
    "description": "<p>根据名称和页数查询专业</p>",
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
            "type": "string",
            "optional": true,
            "field": "profession",
            "description": "<p>专业名称</p>"
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
          "content": "{\n\"profession\": \"世界史\"  //专业名称\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/School/SchoolController.php",
    "groupTitle": "school"
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
            "type": "string",
            "optional": true,
            "field": "profession",
            "description": "<p>专业名称</p>"
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
  },
  {
    "type": "post",
    "url": "school/getSubject1",
    "title": "查询首选科目",
    "name": "getSubject1",
    "description": "<p>查询首选科目</p>",
    "group": "school",
    "parameter": {
      "fields": {
        "Parameter": [
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
          "content": "{\n\"subject1\":   //首选科目\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/School/SchoolController.php",
    "groupTitle": "school"
  },
  {
    "type": "post",
    "url": "school/getSubject2",
    "title": "查询再选科目",
    "name": "getSubject2",
    "description": "<p>查询再选科目</p>",
    "group": "school",
    "parameter": {
      "fields": {
        "Parameter": [
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
          "content": "{\n\"subject2\":   //再选科目\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/School/SchoolController.php",
    "groupTitle": "school"
  },
  {
    "type": "post",
    "url": "subject/getSubject",
    "title": "查询可选的专业课程",
    "name": "getSubject",
    "description": "<p>查询可选的专业课程</p>",
    "group": "subject",
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
            "type": "string",
            "optional": true,
            "field": "profession",
            "description": "<p>学校名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "subject1",
            "description": "<p>学校名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": true,
            "field": "subject2",
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
          "content": "{\n\"id\": 981,\n\"collectId\": 207369,\n\"school\": \"北京交通大学(威海校区)\",       //学校\n\"profession\": \"通信工程(中外合作办学)\",   //招生专业(类)\n\"professionSub\": \"\",                     //包含专业\n\"subject1\": \"仅物理\",                    //首选科目要求\n\"subject2\": \"不提再选科目要求\",           //再选科目要求\n\"updTime\": \"2019-08-05 12:11:52\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/Subject/SubjectController.php",
    "groupTitle": "subject"
  }
] });
