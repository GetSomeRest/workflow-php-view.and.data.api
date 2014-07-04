<?php
/**
 * 
 * User: Rain
 * Date: 14-6-14
 * Time: 下午5:17
 * Email:galikaixin@iuxlabs.com
 */

//env,debug模式下，可以将调试日志输出到log中
define('ENV', 'DEBUG');

//clientID
define('CLIENT_ID' , 'your client id');

//secret
define('SECRET' , 'your secret');

//default bucket，默认的bucket
define('DEFAULT_BUCKET', 'mybucket');

//main url
define('MAIN_API_URL' , 'https://developer.api.autodesk.com/');

//1:get token url  POST https://developer.api.autodesk.com/authentication/v1/authenticate
define('GET_TOKEN_API_URL' , 'authentication/v1/authenticate');

//2:Create a bucket POST https://developer.api.autodesk.com/oss/v1/buckets
define('CREATE_BUCKET_API_URL' , 'oss/v1/buckets');

//3:Upload a seed filed file PUT https://developer.api.autodesk.com/oss/v1/buckets/{bucketkey}/objects/{objectkey}
define('UPLOAD_FILE_API_URL' , 'oss/v1/buckets/');

//4:Start translation https://developer.api.autodesk.com/viewingservice/v1/bubbles
define('START_TRANSLATION_API_URL' , 'viewingservice/v1/bubbles');

//5:Get thumb GET https://developer.api.autodesk.com/viewingservice/v1/thumbnails/{base64 encoded id in previous step}
define('GET_THUMB_URL' , 'viewingservice/v1/thumbnails/');

//6:Check progress https://developer.api.autodesk.com/viewingservice/v1/bubbles/{base64 encoded id in previous step}
define('CHECK_PROCESS_URL', 'viewingservice/v1/bubbles/');

//不存在的method
define('METHOD_ERROR' , 1000);
//获取token错误
define('GET_TOKEN_ERROR' , 1001);
//创建bucket错误
define('CREATE_BUCKET_ERROR', 1002);
//文件上传错误
define('UPLOAD_FILE_ERROR' , 1003);
//文件传输错误
define('TRANS_FILE_ERROR' , 1004);
//获取缩略图错误
define('GET_THUMB_ERROR', 1005);
//获取进度错误
define('GET_PROCESS_ERROR' , 1006);


