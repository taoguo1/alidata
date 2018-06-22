<?php
namespace Core;

use ReflectionClass;
use ReflectionMethod;
use Core\DB\DBQ;


class Lib
{

    // 获取当前时间戳毫秒数
    public static function getMs()
    {
        list ($usec, $sec) = explode(" ", microtime());
        return sprintf('%.0f', ((float) $usec + (float) $sec) * 1000);
    }

    /**
     * 格式化时间戳，精确到毫秒，x代表毫秒
     */
    public static function uDate($tag, $time)
    {
        $dateArr = explode(".", $time / 1000);
        $usec = $dateArr[0];
        $sec = isset($dateArr[1]) ? $dateArr[1] : 0;
        $date = date($tag, $usec);
        return str_replace('x', $sec, $date);
    }

    public static function loadFile($file)
    {
        return require_once APP_PATH . $file;
    }

    /**
     * 获取所有 以 HTTP开头的header参数
     *
     * @return array
     */
    public static function getAllHeaders()
    {
        $headers = array();
        $REQUEST_TIME_FLOAT = $_SERVER['REQUEST_TIME_FLOAT'];
        foreach ($_SERVER as $key => $value) {
            
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                $key = str_replace('_', ' ', $key);
                $key = str_replace(' ', '-', $key);
                $key = strtoupper($key);
                $headers[$key] = $value;
            }
        }
        $headers['TIME'] = $REQUEST_TIME_FLOAT;
        return $headers;
    }

    public static function getCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        $data = "";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return json_decode($tmpInfo, true);
    }

    public static function httpGet($url)
    {
        $headers = array();
        $headers[] = 'Content-Type: appliction/json';
        // 初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 执行并获取HTML文档内容
        $output = curl_exec($ch);
        // 释放curl句柄
        curl_close($ch);
        // 打印获得的数据
        return $output;
    }

    // $pars = '{partId:"50"}';
    // postCurl($url,$pars);
    public static function httpPost($url, $postData)
    {
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $output = curl_exec($ch);
        curl_close($ch);
        // 打印获得的数据
        return $output;
    }

    public static function httpPostUrlEncode($url, $postData, $upload = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        if ($upload) {
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
        }
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public static function get_param($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        Lib::loadFile('Core/Extend/Simple_html_dom.php');
        $html = str_get_html($output);
        foreach ($html->find('input[name=token]') as $e) {
            if ($e->value) {
                $token = $e->value;
                break;
            }
        }
        foreach ($html->find('input[name=bindId]') as $e) {
            if ($e->value) {
                $bindId = $e->value;
                break;
            }
        }
        foreach ($html->find('.pme-light') as $e) {
            if ($e->innertext) {
                $error_msg = $e->innertext;
                break;
            }
        }
        if (isset($error_msg) && $error_msg) {
            $arr['error_msg'] = $error_msg;
        } else {
            $arr['token'] = $token;
            $arr['bindId'] = $bindId;
        }
        return $arr;
    }

    public static function pay_submit($curlPost, $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curlPost));
        $output = curl_exec($curl);
        curl_close($curl);
        Lib::loadFile('Core/Extend/Simple_html_dom.php');
        $html = str_get_html($output);
        foreach ($html->find('.pme-success-msg') as $e) {
            if ($e->innertext) {
                $success_msg = $e->innertext;
                break;
            }
        }
        foreach ($html->find('.pme-light') as $e) {
            if ($e->innertext) {
                $error_msg = $e->innertext;
                break;
            }
        }
        if (isset($success_msg) && $success_msg) {
            $arr['error'] = 0;
            $arr['error_msg'] = $success_msg;
        } else {
            $arr['error'] = 1;
            $arr['error_msg'] = $error_msg;
        }
        return $arr;
    }

    public static function getUrl($c, $m = 'index', $pars = '')
    {
        $url = '';
        $parsArr = array();
        if ($pars) {
            $arr = explode('&', $pars);
            foreach ($arr as $k => $v) {
                $_tmpArr = explode('=', $v);
                array_push($parsArr, @$_tmpArr[1]);
            }
            $pars = implode('/', $parsArr);
            if (RUN_PATH != 'www') {
                $url = APP_SITE_PATH . RUN_PATH . "/" . $c . '/' . $m . '/' . $pars;
            } else {
                $url = APP_SITE_PATH . $c . '/' . $m . '/' . $pars;
            }
        } else {
            if ($m == 'index') {
                $m = '';
            }
            if (RUN_PATH != 'www') {
                $url = APP_SITE_PATH . RUN_PATH . "/" . $c . '/' . $m;
            } else {
                if ($c == 'index' && empty($m)) {
                    $url = APP_SITE_PATH;
                } else {
                    $url = APP_SITE_PATH . $c . '/' . $m;
                }
            }
        }
        return $url;
    }

    public static function setPagePars()
    {
        $pageArr['pageNum'] = self::request('pageNum', 1, 'int');
        $pageArr['numPerPage'] = self::request('numPerPage', 50, 'int');
        $pageArr['orderField'] = self::request('orderField');
        $pageArr['orderDirection'] = self::request('orderDirection');
        return $pageArr;
    }
	
	public static function setPagePars2()
    {
        $pageArr['pageNum'] = self::request('pageNum', 1, 'int');
        $pageArr['numPerPage'] = self::request('numPerPage', 10000, 'int');
        $pageArr['orderField'] = self::request('orderField');
        $pageArr['orderDirection'] = self::request('orderDirection');
        return $pageArr;
    }

    public static function getFunctionNotes($clsName, $function)
    {
        $func = new ReflectionMethod($clsName, $function);
        $tmp = $func->getDocComment();
        return $tmp = $tmp != '' ? nl2br($tmp) : '';
    }

    public static function getClassNotes($claName)
    {
        $func = new ReflectionClass($claName);
        $tmp = $func->getDocComment();
        return $tmp = $tmp != '' ? nl2br($tmp) : '';
    }

    public static function getFunctionName($clsName, $function)
    {
        $tmp = "";
        if (\method_exists($clsName, $function)) {
            $func = new ReflectionMethod($clsName, $function);
            $tmp = $func->getDocComment();
            
            if ($tmp) {
                if (strstr($tmp, '@name')) {
                    $flag = @preg_match_all('/@name(.*?)\n/', $tmp, $tmp);
                }
            }
            if ($tmp[1][0]) {
                $tmp = trim($tmp[1][0]);
            }
        }
        
        return $tmp = $tmp != '' ? $tmp : '';
    }

    public static function getClassName($claName)
    {
        $func = new ReflectionClass($claName);
        $tmp = $func->getDocComment();
        if ($tmp) {
            $flag = preg_match_all('/@name(.*?)\n/', $tmp, $tmp);
        }
        if ($tmp[1][0]) {
            $tmp = trim($tmp[1][0]);
        }
        return $tmp = $tmp != '' ? $tmp : '无';
    }

    /**
     * 获取所有控制器
     */
    public static function getControllerList()
    {
        $path = APP_PATH . 'App/ADMIN/Controller/';
        $handler = opendir($path);
        $arr = [];
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                $arr[] = substr($filename, 0, - 4);
            }
        }
        // 移除公共Controller
        // $publicController = explode("|", APP_ADMIN_PUBLIC_CONTROLLER);
        // $arr = array_diff($arr, $publicController);
        closedir($handler);
        return $arr;
    }

    /**
     * 获取指定类下面的所有method
     * 
     * @param
     *            $cls
     * @return array
     */
    public static function getClsMethods($cls)
    {
        $funAll = get_class_methods($cls);
        $arr = get_class_methods(get_parent_class($cls));
        if (! empty($funAll)) {
            $arr = array_diff($funAll, $arr);
        }
        return $arr;
    }

    /**
     * 获取当前页面的打开方式
     */
    public static function setTargetForm()
    {}

    public static function arrayRemove($arr, $key)
    {
        if (! array_key_exists($key, $arr)) {
            return $arr;
        }
        $keys = array_keys($arr);
        $index = array_search($key, $keys);
        if ($index !== FALSE) {
            array_splice($arr, $index, 1);
        }
        return $arr;
    }

    /**
     * json转换为中文
     *
     * @param
     *            $arr
     * @return mixed
     */
    public static function json($arr)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function('$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'), json_encode($arr));
    }

    /**
     * 输出json
     *
     * @param
     *            $arr
     */
    public static function outputJson($arr)
    {
        die(self::json($arr));
    }

    /**
     * POST接值处理
     *
     * @param
     *            $key
     * @param string $default
     * @param string $type
     * @return number|string|string
     */
    public static function post($key, $default = '', $type = '')
    {
        if ($type == 'int') {
            return ! empty($_POST[$key]) ? intval($_POST[$key]) : $default;
        } elseif ($type == 'float') {
            return ! empty($_POST[$key]) ? self::formatMoney(floatval($_POST[$key]), 2) : $default;
        } elseif ($type == 'array') {
            if (! empty($_POST[$key])) {
                return implode(',', array_filter($_POST[$key]));
            } else {
                return null;
            }
        } elseif ($type == 'json') {
            if ($_POST[$key]) {
                $json = array_filter($_POST[$key]);
                $json = array_values($json);
                return json($json);
            }
        } else {
            return ! empty($_POST[$key]) ? trim($_POST[$key]) : $default;
        }
    }

    /**
     * GET接值处理
     *
     * @param
     *            $key
     * @param string $default
     * @param string $type
     * @return number|string|string
     */
    public static function get($key, $default = '', $type = '')
    {
        if ($type == 'int') {
            return ! empty($_GET[$key]) ? intval($_GET[$key]) : $default;
        } elseif ($type == 'float') {
            return ! empty($_GET[$key]) ? self::formatMoney(floatval($_GET[$key]), 2) : $default;
        } elseif ($type == 'array') {
            if (! empty($_GET[$key])) {
                return implode(',', array_filter($_GET[$key]));
            } else {
                return null;
            }
        } elseif ($type == 'json') {
            if ($_GET[$key]) {
                $json = array_filter($_GET[$key]);
                $json = array_values($json);
                return json($json);
            }
        } else {
            return ! empty($_GET[$key]) ? trim($_GET[$key]) : $default;
        }
    }

    /**
     * REQUEST 接值处理
     *
     * @param
     *            $key
     * @param string $default
     * @param string $type
     * @return number|string|string
     */
    public static function request($key, $default = '', $type = '')
    {
        if ($type == 'int') {
            return ! empty($_REQUEST[$key]) ? intval($_REQUEST[$key]) : $default;
        } elseif ($type == 'float') {
            return ! empty($_REQUEST[$key]) ? self::formatMoney(floatval($_REQUEST[$key]), 2) : $default;
        } elseif ($type == 'array') {
            if (! empty($_GET[$key])) {
                return implode(',', array_filter($_REQUEST[$key]));
            } else {
                return null;
            }
        } elseif ($type == 'json') {
            if ($_REQUEST[$key]) {
                $json = array_filter($_REQUEST[$key]);
                $json = array_values($json);
                return json($json);
            }
        } else {
            return ! empty($_REQUEST[$key]) ? trim($_REQUEST[$key]) : $default;
        }
    }

    public static function formatMoney($money, $num = '')
    {
        if ($money == "0" || $money == "0.00" || empty($money)) {
            return sprintf("%.2f", 0.00);
        } else {
            return sprintf("%.2f", $money);
        }
    }

    /**
     * 计算字符串的长度（汉字按照两个字符计算）
     *
     * @param
     *            $str
     * @return number
     */
    public static function strLen($str)
    {
        $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
        if ($length) {
            return strlen($str) - $length + intval($length / 3) * 2;
        } else {
            return strlen($str);
        }
    }

    /**
     * 获取指定日期星期的开始日期
     *
     * @param
     *            $sdefaultDate
     * @return string
     */
    public static function getWeekStartDate($date)
    {
        // $first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $first = 1;
        // 获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($date));
        // 获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $weekStart = date('Y-m-d', strtotime("$date-" . ($w ? $w - $first : 6) . ' days'));
        return $weekStart;
    }

    /**
     * 获取指定日期星期的结束日期
     *
     * @param
     *            $sdefaultDate
     * @return string
     */
    public static function getWeekEndDate($date)
    {
        // 获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($date));
        $weekStart = self::getWeekStartDate($date);
        // 获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $weekEnd = date('Y-m-d', strtotime("$weekStart+6 days"));
        return $weekEnd;
    }

    /**
     * 判断是否移动端
     *
     * @return boolean
     */
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断是否微信浏览器
     *
     * @return boolean
     */
    public static function isWeixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 生成唯一标识符 sha1()函数， "安全散列算法（SHA1）"
     *
     * @return string
     */
    public static function createUnique()
    {
        $data = $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . time() . rand();
        return sha1($data);
    }

    /**
     * 截取UTF-8编码下字符串的函数
     *
     * @param string $str
     *            被截取的字符串
     * @param int $length
     *            截取的长度
     * @param bool $append
     *            是否附加省略号
     *            
     * @return string
     */
    public static function subStr($str, $length = 0, $append = true)
    {
        $str = trim($str);
        $strlength = strlen($str);
        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }
        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'UTF-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'UTF-8');
        } else {
            $newstr = substr($str, 0, $length);
        }
        if ($append && $str != $newstr) {
            $newstr .= '...';
        }
        return $newstr;
    }

    public static function isSafeIp($ip, $ips)
    {
        if (! $ip)
            $ip = Lib::realIp(); // 获取客户端IP
        if ($ips) {
            if (is_string($ips)) { // ip用"," 例如白名单IP：192.168.1.13,123.23.23.44,193.134.*.*
                $ips = explode(",", $ips);
            }
        } 
        if (in_array($ip, $ips)) {
            return true;
        }
        $ipregexp = implode('|', str_replace(array(
            '*',
            '.'
        ), array(
            '\d+',
            '\.'
        ), $ips));
        $rs = preg_match("/^(" . $ipregexp . ")$/", $ip);
        if ($rs){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 获得用户的真实IP地址
     *
     * @access public
     * @return string
     */
    public static function realIp()
    {
        static $realip = NULL;
        if ($realip !== NULL) {
            return $realip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = ! empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }

    /**
     * 检查目标文件夹是否存在，如果不存在则自动创建该目录
     *
     * @access public
     * @param
     *            string folder 目录路径。不能使用相对于网站根目录的URL
     *            
     * @return bool
     */
    public static function makeDir($folder)
    {
        $reval = false;
        if (! file_exists($folder)) {
            /* 如果目录不存在则尝试创建该目录 */
            @umask(0);
            /* 将目录路径拆分成数组 */
            preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);
            /* 如果第一个字符为/则当作物理路径处理 */
            $base = ($atmp[0][0] == '/') ? '/' : '';
            /* 遍历包含路径信息的数组 */
            foreach ($atmp[1] as $val) {
                if ('' != $val) {
                    $base .= $val;
                    if ('..' == $val || '.' == $val) {
                        /* 如果目录为.或者..则直接补/继续下一个循环 */
                        $base .= '/';
                        continue;
                    }
                } else {
                    continue;
                }
                $base .= '/';
                if (! file_exists($base)) {
                    /* 尝试创建目录，如果创建失败则继续循环 */
                    if (mkdir(rtrim($base, '/'), 0777)) {
                        chmod($base, 0777);
                        $reval = true;
                    }
                }
            }
        } else {
            /* 路径已经存在。返回该路径是不是一个目录 */
            $reval = is_dir($folder);
        }
        clearstatcache();
        return $reval;
    }

    /**
     * 获取服务器的ip
     *
     * @access public
     *        
     * @return string
     *
     */
    public static function realServerIp()
    {
        static $serverip = NULL;
        if ($serverip !== NULL) {
            return $serverip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['SERVER_ADDR'])) {
                $serverip = $_SERVER['SERVER_ADDR'];
            } else {
                $serverip = '0.0.0.0';
            }
        } else {
            $serverip = getenv('SERVER_ADDR');
        }
        return $serverip;
    }

    /**
     * 根据生日获取年龄
     *
     * @param
     *            $birthday
     * @return bool|string
     */
    public static function getAge($birthday)
    {
        $age = 0;
        $year = $month = $day = 0;
        if (is_array($birthday)) {
            extract($birthday);
        } else {
            if (strpos($birthday, '-') !== false) {
                list ($year, $month, $day) = explode('-', $birthday);
                $day = substr($day, 0, 2);
                // get the first two chars in case of '2000-11-03 12:12:00'
            }
        }
        $age = date('Y') - $year;
        if (date('m') < $month || (date('m') == $month && date('d') < $day))
            $age --;
        return $age;
    }

    /**
     * 计算两个日期相差的月数
     *
     * @param
     *            $date1
     * @param
     *            $date2
     * @return number
     */
    public static function getDiffMonthNum($date1, $date2)
    {
        $date1_stamp = strtotime($date1);
        $date2_stamp = strtotime($date2);
        list ($date_1['y'], $date_1['m']) = explode("-", date('Y-m', $date1_stamp));
        list ($date_2['y'], $date_2['m']) = explode("-", date('Y-m', $date2_stamp));
        return abs(($date_2['y'] - $date_1['y']) * 12 + $date_2['m'] - $date_1['m']);
    }

    /**
     * 计算两个日期相差的天数
     *
     * @param
     *            $date1
     * @param
     *            $date2
     * @return number
     */
    public static function diffBetweenTwoDays($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);
        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }

    /**
     * 密码加密
     *
     * @param
     *            $pass
     * @return string
     */
    public static function compilePassword($pass)
    {
        $key = 'www.lizhongwen.com';
        return md5($key . md5($pass));
    }

    /**
     * 密码加密
     *
     * @param
     *            $pass
     * @return string
     */
    public static function md5Encry($str)
    {
        $key = 'www.lizhongwen.com';
        return md5($key . md5($str));
    }

    /*
     * 创建订单号
     */
    public static function createOrderNo()
    {
        $yCode = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J'
        );
        $orderNo = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), - 5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderNo;
    }

    /**
     * 加密函数
     *
     * @param string $str
     *            加密前的字符串
     * @param string $key
     *            密钥
     * @return string 加密后的字符串
     */
    public static function encrypt($str, $key = 'www.lizhongwen.com')
    {
        $coded = '';
        $keylength = strlen($key);
        for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
            $coded .= substr($str, $i, $keylength) ^ $key;
        }
        return str_replace('=', '', base64_encode($coded));
    }

    /**
     * 解密函数
     *
     * @param string $str
     *            加密后的字符串
     * @param string $key
     *            密钥
     * @return string 加密前的字符串
     */
    public static function decrypt($str, $key = 'www.yidianjiuxing.com')
    {
        $coded = '';
        $keylength = strlen($key);
        $str = base64_decode($str);
        for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
            $coded .= substr($str, $i, $keylength) ^ $key;
        }
        return $coded;
    }

    public static function logs($file, $data)
    {
        file_put_contents(APP_PATH . "Logs/" . $file, $data);
    }

    /**
     * 判断是否闰年
     *
     * @param string $year
     */
    public static function isLeapYear($year = '')
    {
        if (isset($year) && $year != '') {
            $leapYear = $year;
        } else {
            $leapYear = date('Y', time());
        }
        
        if ($leapYear % 4 === 0) {
            if ($leapYear % 100 === 0) {
                if ($leapYear % 400 === 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取星期
     *
     * @param string $week
     */
    public static function getWeek($week = '')
    {
        if ($week == "1") {
            echo "星期一";
        } else if ($week == "2") {
            echo "星期二";
        } else if ($week == "3") {
            echo "星期三";
        } else if ($week == "4") {
            echo "星期四";
        } else if ($week == "5") {
            echo "星期五";
        } else if ($week == "6") {
            echo "星期六";
        } else if ($week == "0") {
            echo "星期日";
        } else {
            echo "";
        }
        ;
    }

    /**
     * 获取某个月最大天数
     *
     * @param string $year
     * @param string $month
     */
    public static function getMonthLastDay($year, $month)
    {
        if ($year == '') {
            $year = date("Y", time());
        }
        switch ($month) {
            case 4:
            case 6:
            case 9:
            case 11:
                $days = 30;
                break;
            case 2:
                if ($year % 4 == 0) {
                    if ($year % 100 == 0) {
                        $days = $year % 400 == 0 ? 29 : 28;
                    } else {
                        $days = 29;
                    }
                } else {
                    $days = 28;
                }
                break;
            default:
                $days = 31;
                break;
        }
        return $days;
    }

    /**
     * AES加密字符串
     *
     * @param string $data
     */
    public static function aesEncrypt($data)
    {
        $method = 'AES-128-CBC';
        $key = '2018zzdxjdyg0102';
        $iv = '1234567890123412';
        $ret = base64_encode(openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv));
        return $ret;
    }

    /**
     * AES解密字符串
     *
     * @param string $data
     */
    public static function aesDecrypt($data)
    {
        $method = 'AES-128-CBC';
        $key = '2018zzdxjdyg0102';
        $iv = '1234567890123412';
        $ret = openssl_decrypt(base64_decode($data), $method, $key, OPENSSL_RAW_DATA, $iv);
        return $ret;
    }

    /**
     * 将金额随机拆分
     *
     * @param string $total
     * @param string $nul
     * @param string $min
     */
    public static function splitAmount($total, $num, $min)
    {
        for ($i = 1; $i < $num; $i ++) {
            // 随机安全上限
            $safe_total = ($total - ($num - $i) * $min) / ($num - $i);
            $money = mt_rand($min * 100, $safe_total * 100) / 100;
            
            $total = $total - $money;
            $arr[$i] = $money;
        }
        $arr[$num] = $total;
        return $arr;
    }

    /**
     * 检查手机号码格式
     * 
     * @param $mobile 手机号码
     */
    public static function checkMobile($mobile)
    {
        if (preg_match('/1[3456789]\d{9}$/', $mobile))
            return true;
        return false;
    }

    /**
     * 验证是否是合法的身份证号
     * 
     * @param string $string
     *            待验证的字串
     * @return bool 如果是合法的身份证号则返回true，否则返回false
     */
    public static function isIdCard($id_card)
    {
        return 0 < preg_match("/^(?:\d{17}[\d|X]|\d{15})$/", $id_card);
    }

    /**
     * 验证是否字母数字
     * 
     * @param
     *            $string
     * @return int
     */
    public static function isLetterNum($string)
    {
        return preg_match('/^[0-9a-zA-Z]+$/', $string);
    }

    /**
     * 验证是否为正整数
     * 
     * @return int
     */
    public static function isInteger($integer)
    {
        return preg_match("/^[1-9][0-9]*$/", $integer);
    }

    /**
     * 阿里云发送短信
     */
    public static function sendSms($mobile, $type = 1, $appid, $param = [], $templateCode = "SMS_94640124")
    {
        $post = [
            'mobile' => $mobile,
            'type' => $type,
            'appid' => $appid,
            'param' => $param
        ];
        $url = EX_SERVICE . 'exchange/Aliyun/sendSms/' . $templateCode;
        $ret = Lib::httpPostUrlEncode($url, $post);
        return $ret;
    }

    /**
     * 身份证图像识别**。
     * 
     * @param type $side
     *            正反面 face 为正面，back 背面
     * @param type $image
     *            图片路径
     * @return type
     */
    public static function ocrIdcard($image = '', $side = 'face')
    {
        if (! $image) {
            return false;
        } else {
            $url = EX_SERVICE . 'exchange/Aliyun/ocrIdCard/';
            $ret = Lib::httpPostUrlEncode($url, [
                'imgurl' => $image,
                'side' => $side
            ]);
            return $ret;
        }
    }

    /**
     * 银行卡图像识别**。
     * 
     * @param type $image
     *            图片路径
     * @return type
     */
    public static function ocrBankCard($image = '')
    {
        if (! $image) {
            return false;
        } else {
            $url = EX_SERVICE . 'exchange/Aliyun/ocrBankCard/';
            $ret = Lib::httpPostUrlEncode($url, [
                'imgurl' => $image
            ]);
            return $ret;
        }
    }

    /**
     * 银行卡信息查询**。
     * 
     * @param type $cardno
     *            卡号
     * @return type
     */
    public static function searchBankCard($cardno = '')
    {
        if (! $cardno) {
            return false;
        } else {
            $url = EX_SERVICE . 'exchange/Aliyun/searchBankCard/';
            $ret = Lib::httpPostUrlEncode($url, [
                'cardno' => $cardno
            ]);
            return $ret;
        }
    }

    public static function jpush($content, $platform = 'all', $deviceid = '', $pars = [])
    {
        $post = [
            'content' => $content,
            'platform' => $platform,
            'deviceid' => $deviceid,
            'pars' => $pars
        ];
        $url = 'http://www.ydjx.com/exchange/Jpushes/send';
        return Lib::httpPostUrlEncode($url, $post);
    }

    /**
     * 替换银行卡、手机号码为**。
     * 
     * @param type $str
     *            要替换的字符串
     * @param type $startlen
     *            开始长度 默认4
     * @param type $endlen
     *            结束长度 默认3
     * @return type
     */
    public static function strReplace($str, $startlen = 4, $endlen = 3)
    {
        $repstr = "";
        if (strlen($str) < ($startlen + $endlen + 1)) {
            return $str;
        }
        $count = strlen($str) - $startlen - $endlen;
        for ($i = 0; $i < $count; $i ++) {
            $repstr .= "*";
        }
        return preg_replace('/(\d{' . $startlen . '})\d+(\d{' . $endlen . '})/', '${1}' . $repstr . '${2}', $str);
    }

    /*
     * 作用：显示前四位和后四位中间8位隐藏
     * 参数： $idCard
     * 返回值：string
     */
    public static function idCardHide($idCard = '')
    {
        if ($idCard) {
            $idCard = substr($idCard, 0, 4) . '********' . substr($idCard, - 4);
        }
        return $idCard;
    }

    /*
     * 作用：显示前四位和后四位中间隐藏
     * 参数： $card_no
     * 返回值：string
     */
    public static function accountNumberHide($card_no = '')
    {
        if (! ctype_digit($card_no)) {
            return $card_no;
        }
        $num = strlen($card_no);
        if ($num < 9) {
            return $card_no;
        }
        $num = $num - 8;
        $arr = '';
        for ($i = 0; $i < $num; $i ++) {
            $arr .= '*';
        }
        $card_no = substr($card_no, 0, 4) . $arr . substr($card_no, - 4);
        
        return $card_no;
    }

    /*
     * 作用：用*号替代姓名除姓之外的字符
     * 参数： $name,$num
     * 返回值：string
     */
    public static function starReplace($name = '', $num = 0)
    {
        if (empty($name)) {
            return $name;
        }
        if ($num && mb_strlen($name, 'UTF-8') > $num) {
            return mb_substr($name, 0, 4) . '*';
        }
        
        if ($num && mb_strlen($name, 'UTF-8') <= $num) {
            return $name;
        }
        $doubleSurname = [
            '欧阳',
            '太史',
            '端木',
            '上官',
            '司马',
            '东方',
            '独孤',
            '南宫',
            '万俟',
            '闻人',
            '夏侯',
            '诸葛',
            '尉迟',
            '公羊',
            '赫连',
            '澹台',
            '皇甫',
            '宗政',
            '濮阳',
            '公冶',
            '太叔',
            '申屠',
            '公孙',
            '慕容',
            '仲孙',
            '钟离',
            '长孙',
            '宇文',
            '司徒',
            '鲜于',
            '司空',
            '闾丘',
            '子车',
            '亓官',
            '司寇',
            '巫马',
            '公西',
            '颛孙',
            '壤驷',
            '公良',
            '漆雕',
            '乐正',
            '宰父',
            '谷梁',
            '拓跋',
            '夹谷',
            '轩辕',
            '令狐',
            '段干',
            '百里',
            '呼延',
            '东郭',
            '南门',
            '羊舌',
            '微生',
            '公户',
            '公玉',
            '公仪',
            '梁丘',
            '公仲',
            '公上',
            '公门',
            '公山',
            '公坚',
            '左丘',
            '公伯',
            '西门',
            '公祖',
            '第五',
            '公乘',
            '贯丘',
            '公皙',
            '南荣',
            '东里',
            '东宫',
            '仲长',
            '子书',
            '子桑',
            '即墨',
            '达奚',
            '褚师',
            '吴铭'
        ];
        
        $surname = mb_substr($name, 0, 2);
        if (in_array($surname, $doubleSurname)) {
            $name = mb_substr($name, 0, 2) . str_repeat('*', (mb_strlen($name, 'UTF-8') - 2));
        } else {
            $name = mb_substr($name, 0, 1) . str_repeat('*', (mb_strlen($name, 'UTF-8') - 1));
        }
        return $name;
    }

    // 生成批次号
    public static function createBatchNo()
    {
        // $pix = "Y";
        // $str_date = self::uDate("YmdHis",self::getMs());
        $ti = Lib::getMs();
        return round(10, 99) . $ti;
    }

    /**
     * 获取指定月份的第一天开始和最后一天结束的时间戳
     * 返回毫秒
     *
     * @param int $y
     *            年份 $m 月份
     * @return array(本月开始时间，本月结束时间)
     */
    public static function mFristAndLast($y = "", $m = "")
    {
        if ($y == "")
            $y = date("Y");
        if ($m == "")
            $m = date("m");
        $m = sprintf("%02d", intval($m));
        
        // 填充字符串长度
        $y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);
        
        $m > 12 || $m < 1 ? $m = 1 : $m = $m;
        $firstday = strtotime($y . $m . "01000000");
        $firstdaystr = date("Y-m-01", $firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
        
        return array(
            "firstday" => $firstday * 1000,
            "lastday" => $lastday * 1000 + 999
        );
    }

    /*
     * 作用：api接口银行卡姓名隐藏中间字符，空返回空
     * 参数： $user_name
     * 返回值：string
     */
    public static function curNameHide($user_name = '')
    {
        if (empty($user_name)) {
            return $user_name;
        }
        $strlen = mb_strlen($user_name, 'utf-8');
        $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
        $lastStr = mb_substr($user_name, - 1, 1, 'utf-8');
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
    }

    /*
     * 获取不同oem的数据库配置
     * param：$appid
     * 返回：array
     */
    public static function getDbConfig($appid){
        if(!$appid){
            exit("参数错误");
        }
        $ret = Lib::loadFile('Config/MercConfig/'.$appid.'_Config.php');
        $ret = json_decode($ret,true);
        if(!$ret['status']=='fail')
        {
            exit($ret['msg']);
        }else{
            if(!$ret['status']==-1){
                exit("该账户异常");
            }
        }
        return $ret;
    }

    /*
     * 获取不同oem的数据库配置
     * param：$appid
     * 返回：array
     */
    public static function getOemDb($appid){
        $ret = self::getDbConfig($appid);
        $dbOem = new DB([
            'databaseType' => 'mysql',
            'databaseName' => $ret['db_name'],
            'server' => $ret['db_ip'],
            'userName' => $ret['db_user'],
            'password' => $ret['db_password'],
            'charSet' => 'utf8',
            'debugMode' => false,
            'logging' => true,
            'port' => $ret['db_port'],
            'prefix' => $ret['db_prefix'],
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ],
            'command' => [
                'SET SQL_MODE=ANSI_QUOTES'
            ]
        ]);
        return $dbOem;
    }

    //写日志
    public static function pLog($name,$content,$dir = 'Temp'){
        $path = 'Logs/'.$dir.'/'.date('Y',time()).'/'.date('m',time()).'/'.date('d',time());
        if(!file_exists($path)){
            if(\mkdir($path,0777,true)){
                file_put_contents($path.'/'.date('H',time()).'-'.$name,json_encode($content)."\n",FILE_APPEND);
            }
        }else{
            file_put_contents($path.'/'.date('H',time()).'-'.$name,json_encode($content)."\n",FILE_APPEND);
        }
    }

}

