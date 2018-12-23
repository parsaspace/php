<?php

namespace  parsaspace;

use parsaspace\Exceptions\ApiException;
use parsaspace\Exceptions\HttpException;



class ParsaSpace{

    const   Base = 'https://api.parsaspace.com/';
    private $url;
    private $apikey;


    public function __construct($apikey){

        if (!extension_loaded('curl')) {
            die('cURL library is not loaded');
            exit;
        }
        if (is_null($apikey)) {
            die('apikey is empty');
            exit;
        }
        $this->apikey = $apikey;

    }

    public function GetFileList($domain , $path){

        $this->url = "v1/files/list";
        $data = array(
            "domain" => $domain,
            "path" => $path
        );
        return  $this->executeCurl($data);
    }

    public function Delete($domain , $path){
        $this->url = "v1/files/remove";
        $data = array(
            "domain" => $domain,
            "path" => $path,
         );
        return  $this->executeCurl($data);
    }

    public function Rename($domain , $source , $destination	){

        $this->url = "v1/files/rename";
        $data = array(
            "domain" => $domain,
             "source" => $source,
            "destination" => $destination
        );
        return  $this->executeCurl($data);

    }


    public function Move($domain , $source , $destination){

        $this->url = "v1/files/move";
        $data = array(
            "domain" => $domain,
            "source" => $source,
            "destination" => $destination
        );
        return  $this->executeCurl($data);
    }


    public function Copy($domain , $source , $destination){

        $this->url = "v1/files/Copy";
        $data = array(
            "domain" => $domain,
            "source" => $source,
            "destination" => $destination
        );
        return  $this->executeCurl($data);
    }


    public function CreateFolder($domain , $path){

        $this->url = "v1/files/Createfolder";
        $data = array(
            "domain" => $domain,
            "path" => $path
        );
        return  $this->executeCurl($data);

    }


    public function Upload($domain , $path , $file , $filename= null){



        $this->url = "v1/files/upload";
        $data = array(
            "domain" => $domain,
            "path" => $path,
            "file" => curl_file_create($file, null ,  $filename )
         );


        $headers = array(
            'Authorization:' . 'Bearer ' . $this->apikey ,
            'content-type: multipart/form-data'
        );


        $path = $this->Get_path();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $data );

        $response     = curl_exec($ch);
        $code         = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $curl_errno   = curl_errno($ch);
        $curl_error   = curl_error($ch);


        

        if ($curl_errno) {
            throw new  HttpException($curl_error, $curl_errno);
        }
        $json_response = json_decode($response);
        if ($code != 200 && is_null($json_response)) {
            throw new  HttpException("Request have errors", $code);
        } else {
            if ($json_response->result != 'success') {
                throw new ApiException($json_response->message);
            }
            return $json_response;
        }

    }


    public function RemoteUpload($domain , $path , $url , $filename , $checkid){
        $this->url = "v1/remote/new";
        $data = array(
            "domain" => $domain,
            "path" => $path,
            "url" => $url,
            "filename" => $filename,
            "checkid" => $checkid

        );
        return  $this->executeCurl($data);


    }

    public function RemoteUploadStatus($checkid){

        $this->url = "v1/remote/status";
        $data = array(
            "checkid" => $checkid
        );
        return  $this->executeCurl($data);

    }


    private function Get_path(){

        return    self::Base . $this->url;

    }

    private function executeCurl( $data = null ){


        $headers = array(
            'Authorization:' . 'Bearer ' . $this->apikey ,
            'application/x-www-form-urlencoded'
        );

        if(!is_null($data)) {
            $date_string = http_build_query($data);
        }

         $path = $this->Get_path();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $date_string );

        $response     = curl_exec($ch);
        $code         = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $curl_errno   = curl_errno($ch);
        $curl_error   = curl_error($ch);



        if ($curl_errno) {
            throw new  HttpException($curl_error, $curl_errno);
        }
        $json_response = json_decode($response);
        if ($code != 200 && is_null($json_response)) {
            throw new  HttpException("Request have errors", $code);
        } else {
             if ($json_response->result != 'success') {
                throw new ApiException($json_response->message);
            }
            return $json_response;
        }

        
    }

}



