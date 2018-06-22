<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/6
 * Time: 9:32
 */

namespace App\WWW\Model;


use Core\Extend\FileLog;
use Core\Sign;

class ApiCall
{
    public static function query ($url, $secret, array $data)
    {
        $data['sign'] = Sign::makeSign($data,$secret);
        FileLog::setLog('apiUrl', $url);
        FileLog::setLog('apiRequest', $data);
        $json = self::call($url, $data);
        FileLog::setLog('apiResponse',$json);
        $dataArr = self::paramsValid($json);
        if(!$dataArr) {
            FileLog::setLog('返回数据验证', $dataArr, true);
            return false;
        }
        return $dataArr;

    }
    protected static function call ( $url, $postData, $upload = false )
    {
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36' );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_NOBODY, true );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_POST, true );
        if ($upload) {
            curl_setopt( $curl, CURLOPT_SAFE_UPLOAD, 1 );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData );
        } else {
            curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $postData ) );
        }
        $output = curl_exec( $curl );
        $error = curl_error($curl);
        curl_close( $curl );
        if($error) {
            return $error;
        }
        return $output;
    }

    protected static function paramsValid ( $json )
    {
        $data = \json_decode( $json, true );
        return $data;
    }
}