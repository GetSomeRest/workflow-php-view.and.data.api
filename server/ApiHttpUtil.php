<?php
/**
 * 
 * User: Rain
 * Date: 14-6-14
 * Time: 下午5:16
 * Email:galikaixin@iuxlabs.com
 */


class ApiHttpUtil
{
    private $ch;
    private $url;
    private $data;
    private $headers;
    private $method;

    private $response;

    public function getAccessToken()
    {
        $this->url = MAIN_API_URL . GET_TOKEN_API_URL;
        $this->data = 'client_id='.CLIENT_ID.'&client_secret='.SECRET.'&grant_type=client_credentials';
        $this->headers = ['Content-type: application/x-www-form-urlencoded'];
        $this->method = 'post';

        $this->response = $this->sendRequest();

        if(isset($this->response))
        {
            $res = $this->json();

            if(isset($res->errorCode))
            {
                staticLog('error','[error] get token error, reason:['.$res->developerMessage.'] ');
                return null;
            }

            staticLog('log','[success] get token success, token:'.$res->access_token);

            $resVO = new TokenVO($res->access_token, $res->expires_in , $res->token_type);

            return $resVO;
        }

        return null;
    }

    //创建桶
    public function createBucket($author, $token , $bucketName)
    {
        $this->url = MAIN_API_URL . CREATE_BUCKET_API_URL;
        $this->data = '{"bucketKey":"'.$bucketName.'","policy":"temporary"}' ;
        $this->headers = ["Content-type: application/json", "Authorization: ".$author." ".$token];
        $this->method = 'post';

        $this->response = $this->sendRequest();

        if(isset($this->response))
        {
            $res = $this->json();

            if(isset($res->errorCode))
            {
                staticLog('error', 'create bucket error, reason:['.$res->developerMessage.']');
                return null;
            }

            staticLog('log','[success] create bucket success, key:'.$res->key);

            $resVO = new bucketVO($res->createDate, $res->key, $res->owner, $res->permissions[0]->access,$res->permissions[0]->serviceId,$res->policyKey);

            return $resVO;
        }

        return null;
    }

    //上传文件，返回urn
    public function uploadFile($author, $token ,$content ,$key ,$fileName ,$size)
    {
        $this->url = MAIN_API_URL . UPLOAD_FILE_API_URL . $key .'/objects/'.$fileName;
        $this->data = $content;
        $this->headers = ["Content-type: application/octet-stream", "Authorization: ".$author." ".$token, "Content-Length: ".$size , "Expect: "];
        $this->method = 'put';

        $this->response = $this->sendRequest();

        if(isset($this->response))
        {
            $res = $this->json();

            if(isset($res->errorCode))
            {
                staticLog('error','[error] upload file error, reason:['.$res->developerMessage.']');
                return null;
            }

            $inRes = $res->objects[0];

            staticLog('log','[success] upload file request success, Urn:'.$inRes->id.' , 64baseUrn:'.base64_encode($inRes->id));

            //TODO:不雅观的写法
            $p = 'bucket-key';
            $p2 = 'sha-1';
            $p3 = 'content-type';

            $resVO = new UpReponseVO($inRes->location,$inRes->size, $res->$p, $inRes->key,$inRes->id, $inRes->$p2,$inRes->$p3);

            return $resVO;
        }

        return null;
    }

    //开始传输
    public function startTranslation($author, $token , $urn)
    {
        $this->url = MAIN_API_URL . START_TRANSLATION_API_URL;
        $this->data = '{"urn":"'.$urn.'"}';
        $this->headers = ["Content-type: application/json", "Authorization: ".$author." ".$token];
        $this->method = 'post';

        $this->response = $this->sendRequest();

        if(isset($this->response))
        {
            $res = $this->json();

            if(isset($res->errorCode))
            {
                staticLog('error','[error] startTranslation error, reason:['.$res->developerMessage.']');
                return null;
            }

            staticLog('log','[success] start translation , result:'.$res->Result);

            $resVO = new TransResultVO($res->Result);

            return $resVO;
        }

        return null;
    }

    //获取传输进度
    public function checkProcess($author , $token , $urn)
    {
        $this->url = MAIN_API_URL . CHECK_PROCESS_URL;
        $this->url .= $urn;
        $this->headers = ["Authorization: ".$author." ".$token];
        $this->method = 'get';

        $this->response = $this->sendRequest();

        if(isset($this->response))
        {
            $res = $this->json();

            if(isset($res->errorCode))
            {
                staticLog('error','[error] get process error, reason:['.$res->developerMessage.']');
                return null;
            }

            $resVO = new ProcessVO($res->status,$res->progress);

            staticLog('log','[success] check Process , status: '.$res->status.', process: '.$res->progress);

            return $resVO;
        }

        return null;
    }

    //获取缩略图
    public function getThumbnail($author, $token , $urn)
    {
        $this->url = MAIN_API_URL . GET_THUMB_URL;
        $this->url .= $urn;
        $this->headers = ["Authorization: ".$author." ".$token];
        $this->method = 'get';

        $this->response = $this->sendRequest();

        if(isset($this->response))
        {
            $res = $this->json();

            if(isset($res->errorCode))
            {
                staticLog('error','[error] get thumbnail error, reason:['.$res->developerMessage.']');
                return null;
            }

            staticLog('log','[success] getThumbnail success');

            return $res;
        }

        return null;
    }

    private function sendRequest()
    {
        $this->ch = curl_init();

        $this->setCurl(CURLOPT_URL , $this->url);
        $this->setCurl(CURLOPT_RETURNTRANSFER , 1);
        $this->setCurl(CURLOPT_HTTPHEADER , $this->headers);

        switch($this->method)
        {
            case 'post' :
                $this->setCurl(CURLOPT_POST, 1);
                $this->setCurl(CURLOPT_POSTFIELDS, $this->data);
                break;
            case 'get' :
                $this->setCurl(CURLOPT_HTTPGET,1);
                break;
            case 'put' :
                $this->setCurl(CURLOPT_CUSTOMREQUEST, 'put');
                $this->setCurl(CURLOPT_POSTFIELDS, $this->data);
                break;
        }

        $response = curl_exec($this->ch);//接收返回信息

        $r = curl_getinfo($this->ch);
        $http_code = $r['http_code'];

        staticLog('log','url:'.$r['url'].' ,http_code:'.$http_code.' ,reponse:'.$response);

        if(curl_errno($this->ch))
        {
            curl_close($this->ch); //关闭curl链接

            staticLog('error',curl_error($this->ch));

            return null;
        }

        curl_close($this->ch); //关闭curl链接

        //httpcode验证
        if($http_code == 200)
            return $response;
        return null;
    }

    private function json()
    {
        return json_decode($this->response);
    }

    private function setCurl($key, $value)
    {
        curl_setopt($this->ch, $key , $value);
    }

}
