<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/3
 * Time: 14:33
 */

namespace Core\Extend;


class FileLog
{
    protected static $baseFolder = 'Logs';
    protected static $fullPath = '';
    protected static $log = '';
    protected static $rootFolder = '';

    public static function setPath($folder, $fileName = '')
    {
        self::$rootFolder =  getcwd();
        $pathArr = [self::$baseFolder];
        if (empty($fileName)) {
            $fileName =  'hour-'.\date('H');
        }

        if(!empty($folder))
        {
            if(\strstr($folder,'/')){
                $folder = \explode('/',$folder);
            }
            else{
                $folder = [$folder];
            }


            \array_push($folder,\date('Ymd',\time()));
            $path = $folder;

            foreach($path as $subFolder)
            {
                \array_push($pathArr, $subFolder);
                $tmpPath = \implode(DIRECTORY_SEPARATOR, $pathArr);
                if(!\is_dir($tmpPath))
                {
                    \mkdir($tmpPath);
                }
            }
            unset($tmpPath, $subFolder, $path);
        }
        self::$fullPath = \implode(DIRECTORY_SEPARATOR, $pathArr).DIRECTORY_SEPARATOR.$fileName;
    }

    public static function setLog($title, $log, $isErr = false, $key = '')
    {
        if(\is_array($log)){
            $log = \json_encode($log,JSON_UNESCAPED_UNICODE);
        }
        if (!empty($key)) {
            $title .= '-' . $key;
        }
        if ($isErr) {
            $title .= '_ERROR';
        }
        self::$log .= '[ ' . date('Y-m-d H:i:s',\time()) . ' ] - ' . ' [ '. $title .' ] ' . PHP_EOL . $log . PHP_EOL;

    }

    public static function write()
    {
        if (self::$rootFolder != getcwd()) {
            chdir(self::$rootFolder);
        }
        if(!empty(self::$fullPath) && !empty(self::$log)) {
            self::$log = '**********************************'. PHP_EOL . self::$log .  PHP_EOL . PHP_EOL;
            file_put_contents(self::$fullPath, self::$log, FILE_APPEND);
        }
    }
}