<?php
/**
 * 示例demo，因为开发时间的原因，部分应该要永久储存的数据使用了session作为容器，在实际开发中，可以通过数据库来实现
 * vo/xxVO.php做为业务逻辑消息通信的对象，可以根据业务需求自行扩展
 * log日志有助于程序调试分析
 * --
 * Example demo, because of the development time, some should be permanently stored data using session as a container, in the actual development can be achieved through the database
 * vo / xxVO.php as business logic objects messaging, you can expand their own business needs
 * log analysis helps debugging
 */
require_once 'config.php';
require_once 'vo/tokenVO.php';
require_once 'vo/bucketVO.php';
require_once 'vo/upReponseVO.php';
require_once 'vo/transResultVO.php';
require_once 'vo/processVO.php';
require_once 'vo/thumbVO.php';
require_once 'ApiHttpUtil.php';

date_default_timezone_set('PRC');

//header('content-type:application/json');

session_start();

$tokenVO = null;
$bucketVO = null;

$go = $_GET['m'];

$api = new ApiHttpUtil();

if(isset($go) && $go != "")
{
  // 所有的api访问都要基于有效的token
  // All api access must be based on valid token
  if(!getAccessToken())
  {
    back(GET_TOKEN_ERROR);
  }
}

// 根据go参数跳转到对应的方法
// Method according to jump to the corresponding parameter 'go'
switch($go)
{
  case 'create'      : createBucket();     break; //创建bucket，此处需要一个bucket名称的参数
  case 'upload'      : uploadFile();       break; //上传文件
  case 'process'     : getProcess();       break; //查看进度
  case 'thumb'       : getThumbnail();     break; //查看缩略图，此处没有实现好
  case 'token'       : getMyAccessToken(); break; //获取token
  case 'upresult'    : getMyUpReponse();   break; //获取上传结果
  case 'transresult' : getMyTransResult(); break; //获取传输结果
  case 'bucket'      : getMyBucket();      break; //获取正在用的bucket，貌似没有api用于获取bucket列表，是否要开发者维护？
  case 'bucketlist'  : getMyBucketList();  break; //获取bucket列表
  case 'show3d'      : getShow3DParam();   break; //获取token和urm
  default            : back(METHOD_ERROR); break;
}


// 获取token
// 创建bucket
// 上传文件
// 开始传输
// 查询进度
// 获取缩略图
// --
// Get token
// Create a bucket
// Upload file
// Begin transmission
// Check progress
// Get thumbnail

function getAccessToken()
{
  global $api, $tokenVO;
  $step = 0;

  if(isset($_SESSION['tokenObj']))
  {
    $tokenVO = $_SESSION['tokenObj'];
    if($tokenVO->isExpired())
    {
      $step = 1;
    }
  }
  else
  {
    $step = 1;
  }

  if($step == 1)
  {
    $tokenVO = $api->getAccessToken();

    if($tokenVO == null)
    {
      return false;
    }

    $_SESSION['tokenObj'] = $tokenVO;
  }

  return true;
}

function uploadFile()
{
  global $api , $tokenVO ;

  $name = $_FILES["file"]["name"];
  $path = $_FILES["file"]["tmp_name"];

  //demo直接上传文件
  //$name = "a.dwfx";
  //$path = $name;

  list($fName,$fExt) = explode(".", $name);

  $pSize = filesize($path);
  $fh = fopen($path, "rb");
  $content = fread($fh, $pSize);
  fclose($fh);

  //此处的key为bucketKey，程序流程是希望前端先获取bucketList，然后用户挑选一个bucket上传
  $key = $_GET['key'];

  if(!isset($key))
  {
    back(UPLOAD_FILE_ERROR);
    return;
  }

  $fileName = $fName.'_'.time().'.'.$fExt;

  $upReponseVO = $api->uploadFile($tokenVO->token_type, $tokenVO->access_token, $content, $key, $fileName, $pSize);

  if($upReponseVO == null)
  {
    back(UPLOAD_FILE_ERROR);
    return;
  }

  $_SESSION['upReponseVO'] = $upReponseVO;

  $urn = $upReponseVO->id;

  $transResultVO = $api->startTranslation($tokenVO->token_type, $tokenVO->access_token, $urn);

  if($transResultVO == null)
  {
    back(TRANS_FILE_ERROR);
    return;
  }

  $_SESSION['transResultVO'] = $transResultVO;

  back(json_encode($transResultVO));
  back('&nbsp;<a href="http://localhost/client3/info.html?process">view result</a>');
}

function getProcess()
{
  global $api, $tokenVO;

  $upReponseVO = $_SESSION['upReponseVO'];

  if($upReponseVO == null)
  {
    back(GET_PROCESS_ERROR);
    return;
  }

  $urn = $upReponseVO->id;
  $processVO = $api->checkProcess($tokenVO->token_type, $tokenVO->access_token, $urn);

  if($processVO != null)
  {
    back(json_encode($processVO));
    return;
  }

  back(GET_PROCESS_ERROR);
}

function getThumbnail()
{
  global $api, $tokenVO;

  $upReponseVO = $_SESSION['upReponseVO'];

  if($upReponseVO == null)
  {
    back(GET_PROCESS_ERROR);
    return;
  }

  $urn = $upReponseVO->id;
  $thumbVO = $api->getThumbnail($tokenVO->token_type, $tokenVO->access_token, $urn);

  if($thumbVO == null)
  {
    back(GET_PROCESS_ERROR);
    return;
  }

  back($thumbVO);
}

//创建bucket
function createBucket()
{
  global $api , $bucketVO , $tokenVO;

  //创建的bucket名称
  $bucket = $_GET['f'];

  if(!isset($bucket))
  {
    echo CREATE_BUCKET_ERROR;
    return;
  }

  $bucketVO = $api->createBucket($tokenVO->token_type, $tokenVO->access_token, $bucket);

  if($bucketVO == null)
  {
    back(CREATE_BUCKET_ERROR);
    return;
  }

  $_SESSION['bucketVO'] = $bucketVO;

  back(json_encode($bucketVO));
}

function getMyAccessToken()
{
  if(isset($_SESSION['tokenObj']))
  {
    $tokenVO = $_SESSION['tokenObj'];
    if($tokenVO->isExpired())
    {
       back("");
       return;
    }

    back(json_encode($tokenVO));
    return;
  }

  back("");
}

function getMyBucket()
{
  if(isset($_SESSION['bucketVO']))
  {
    $vo =  $_SESSION['bucketVO'];

    back(json_encode($vo));
    return;
  }

  back("");
}

function getMyBucketList()
{
  $list = [DEFAULT_BUCKET];

  if(isset($_SESSION['bucketVO']))
  {
    $vo =  $_SESSION['bucketVO'];

    array_push($list, $vo->key);
  }

  back(json_encode($list));
}

function getMyUpReponse()
{
  if(isset($_SESSION['upReponseVO']))
  {
    $vo =  $_SESSION['upReponseVO'];

    back(json_encode($vo));
    return;
  }

  back("");
}

function getMyTransResult()
{
  if(isset($_SESSION['transResultVO']))
  {
    $vo =  $_SESSION['transResultVO'];

    back(json_encode($vo));
    return;
  }

  back("");
}

function getShow3DParam()
{
  if(isset($_SESSION['upReponseVO']) && isset($_SESSION['tokenObj']))
  {
    $vo = $_SESSION['upReponseVO'];
    $vo2 = $_SESSION['tokenObj'];

    back("{'token':'".$vo2->access_token."','urn':'".$vo->id."'}");
    return;
  }

  back("");
}

function staticLog($type, $msg)
{
  if(ENV == 'DEBUG')
  {
    $fp = fopen("./".date("Y-m-d").".log",'a');

    $msg = date("Y-m-d H:i:s").'['.$type.']->'.$msg."\r";

    fwrite($fp,$msg);

    fclose($fp);
  }

  return;
}

function back($msg)
{
  echo $msg;
  return;
}
?>
