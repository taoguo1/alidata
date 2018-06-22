<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/2/24
 * Time: 18:17
 */

namespace Core\Aliyun;
use Core\Lib;

class Oss
{
    private static $obj;
    private static $oss;
    private static $conf;
    private function __construct(){
        self::$conf = Lib::loadFile('Config/Oss.php');
        self::$oss = new \OSS\OssClient(self::$conf['accessKeyId'],self::$conf['accessKeySecret'],self::$conf['endpoint']);
    }

    public static function instance(){
        if(is_null(self::$obj)){
            self::$obj = new self;
        }
        return self::$obj;
    }

    public function uploadOss($object,$file,$bucket = ''){
        if($bucket == ''){
            $bucket = self::$conf['bucket'];
        }
        return self::$oss->uploadFile($bucket,$object,$file);
    }

    public function deleteOss($object,$bucket = ''){
        if($bucket == ''){
            $bucket = self::$conf['bucket'];
        }
        return self::$oss->deleteObject($bucket,$object);
    }

}