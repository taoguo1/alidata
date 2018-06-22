<?php
namespace Core;

use Core\Lib;
class Sign
{
    public static function makeSign($param, $secret)
    {
        ksort($param);
        $plaintext = (http_build_query($param));
        return md5($plaintext.$secret);
    }

    public static function checkNotifySign($sign, $param, $secret)
    {
        if(\array_key_exists('sign',$param)) {
            unset($param['sign']);
        }
        $localSign = self::makeSign($param, $secret);
        return $sign == $localSign;
    }

    /**
     * 获取数据签名
     *
     * @param array $param 签名数组
     * @param string $code 安全校验码
     * @param string $sign_type 签名类型
     * @return array 签名字符串
     */
    public static function getSign($param)
    {
        // 去除数组中的空值和签名参数(sign/sign_type)
        $param = self::paramFilter($param);
        // 按键名升序排列数组
        $param = self::paramSort($param);
        // 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $param_str = self::createLinkstring($param);
        // 把拼接后的字符串再与安全校验码直接连接起来
        $param_str = $param_str . SIGN_CODE;
        $param_str = \base64_encode($param_str);
        // 创建签名字符串
        $sign = \strtoupper(\sha1($param_str));
        return [
            'sign' => $sign,
            'param_str'=>$param_str
        ];
    }

    /**
     * 校验数据签名
     *
     * @param string $sign 接口收到的签名
     * @param array $param  签名数组
     * @param string $code 安全校验码
     * @param string $sign_type  签名类型
     * @return boolean true正确，false失败
     */
    public static function checkSign()
    {
        $param = [];
        if ($_POST) {
            $param = $_POST;
        }
        $sign = Lib::post('sign');
        unset($param['sign']);
        $signArr = self::getSign($param);
    
        if ($signArr['sign'] != $sign) {
            $data = [
                'status' => 'fail',
                'code' => 10010,
                'msg' => '签名不正确'
            ];
            \Core\Lib::outputJson($data);
        } else {
           
            
        }
    }

    /**
     * 去除数组中的空值和签名参数
     *
     * @param array $param  签名数组
     * @return array 去掉空值与签名参数后的新数组
     */
    private static function paramFilter($param)
    {
        $param_filter = array();
        foreach ($param as $key => $val) {
            if ($key == 'sign' || $key == 'sign_type') {
                continue;
            }
//             if ($key == 'sign' || $key == 'sign_type' || ! strlen($val)) {
//                 continue;
//             }
            $param_filter[$key] = $val;
        }
        return $param_filter;
    }

    /**
     * 按键名升序排列数组
     *
     * @param array $param 排序前的数组
     * @return array 排序后的数组
     */
    private static function paramSort($param)
    {
        ksort($param);
        reset($param);
        return $param;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     *
     * @param array $param 需要拼接的数组
     * @return string 拼接完成以后的字符串
     */
    private static function createLinkstring($param)
    {
        $str = '';
        foreach ($param as $key => $val) {
            $str .= "{$key}={$val}&";
        }
        // 去掉最后一个&字符
        $str = substr($str, 0, strlen($str) - 1);
        // 如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return $str;
    }
}
