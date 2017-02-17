<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/2/17
 * Time: 上午9:55
 */


namespace Org\Util;
/**
 * 与 OAuth2 服务器 通讯验证 核心类 (PHP版)
 *
 * @description OAuth2 说明请大家参考 木蚂蚁接口文档
 *
 *
 * @author   Mumayi-Team (zhaopan赵攀)
 * @version  1.0
 * @datetime 2013-12-31
 */
class Oauth2
{
    /*
     * 以下几个参数 具体作用和用法 详见 木蚂蚁接口文档
     */

    //分配的客户端 ID
    public $client_id;

    //分配的客户端 密匙
    public $client_secret;

    //获得的 用户授权 访问令牌
    public $access_token;

    //刷新 访问令牌 (过期时使用)
    public $refresh_token;


    // 最后一个收到的HTTP代码 (用于调试，忽略)
    public $http_code;

    //预留字段 (忽略)
    public $url;

    //设置 基础链接
    public $host = "http://api.zzlphp.com/home/oauth/";

    //请求超时时间
    public $timeout = 30;

    //链接超时时间
    public $connecttimeout = 30;

    //是否验证 SSL 证书
    public $ssl_verifypeer = FALSE;

    //请求结果处理格式
    public $format = 'json';

    //以 JSON_DECODE 方式解析
    public $decode_json = TRUE;

    //预留字段 (忽略)
    public $http_info;

    //CURL 函数使用 (验证使用， 忽略)
    public $useragent = 'Mumayi Team OAuth2 v1.0';

    //是否 开启 请求调试 (开启时 将输出 详细的请求结果)
    public $debug = FALSE;

    //边界 (CURL使用 写入在Header的分界线, 忽略)
    public static $boundary = '';

    //返回 OAuth 令牌 请求地址
    function accessTokenURL()  { return $this->host.'access_token'; }

    //返回 OAuth 自动授权 请求地址
    function authorizeURL()    { return $this->host.'authorize'; }

    //构造函数 赋值 一些必要参数
    function __construct($client_id, $client_secret, $access_token = NULL, $refresh_token = NULL) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
        // $this->debug = true;
    }

    /**
     * authorize接口
     *
     * 对应API：{@link http://u.mumayi.com/oauth/authorize Oauth2/authorize}
     *
     * @param string $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
     * @param string $response_type 支持的值包括 code 和token 默认值为code
     * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
     * @param string $display 授权页面类型 可选范围:
     *  - default        默认授权页面
     *  - mobile        支持html5的手机
     *  - popup            弹窗授权页
     *  - wap1.2        wap1.2页面
     *  - wap2.0        wap2.0页面
     *  - js            js-sdk 专用 授权页面是弹窗，返回结果为js-sdk回掉函数
     *  - apponweibo    站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
     * @return array
     */
    function getAuthorizeURL( $url, $response_type = 'code', $state = NULL, $display = NULL ) {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['redirect_uri'] = $url;
        $params['response_type'] = $response_type;
        $params['state'] = $state;
        $params['display'] = $display;
        return $this->authorizeURL() . "&" . http_build_query($params);
    }

    /**
     * access_token接口
     *
     * 对应API：{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
     *
     * @param string $type 请求的类型,可以为:code, password, token
     * @param array $keys 其他参数：
     *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
     *  - 当$type为password时： array('username'=>..., 'password'=>...)
     *  - 当$type为token时： array('refresh_token'=>...)
     * @return array
     */
    function getAccessToken( $type = 'code', $keys ) {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['client_secret'] = $this->client_secret;
        if ( $type === 'token' ) {
            $params['grant_type'] = 'refresh_token';
            $params['refresh_token'] = $keys['refresh_token'];
        } elseif ( $type === 'code' ) {
            $params['grant_type'] = 'authorization_code';
            $params['code'] = $keys['code'];
            $params['redirect_uri'] = $keys['redirect_uri'];
        } elseif ( $type === 'password' ) {
            $params['grant_type'] = 'password';
            $params['username'] = $keys['username'];
            $params['password'] = $keys['password'];
        } else {
            echo json_encode(array('error' => '错误的授权类型['.$type.'(token, code)]'));
            die;
        }

        $response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
        //var_dump($response);die;
        $token = json_decode($response, true);

        if ( is_array($token) && !isset($token['error']) ) {
            $this->access_token = $token['access_token'];
            //$this->refresh_token = $token['refresh_token'];
        } else {
            echo json_encode(array('error' => '获取访问令牌失败。['.$token['error'].']'));
            die;
        }
        return $token;
    }

    /**
     * 解析 signed_request
     *
     * @param string $signed_request 应用框架在加载iframe时会通过向Canvas URL post的参数signed_request
     *
     * @return array
     */
    function parseSignedRequest($signed_request) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        $sig = self::base64decode($encoded_sig) ;
        $data = json_decode(self::base64decode($payload), true);
        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') return '-1';
        $expected_sig = hash_hmac('sha256', $payload, $this->client_secret, true);
        return ($sig !== $expected_sig)? '-2':$data;
    }

    /**
     * @ignore
     */
    function base64decode($str) {
        return base64_decode(strtr($str.str_repeat('=', (4 - strlen($str) % 4)), '-_', '+/'));
    }

    /**
     * 读取jssdk授权信息，用于和jssdk的同步登录
     *
     * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
     */
    function getTokenFromJSSDK() {
        $key = "mumayijs_" . $this->client_id;
        if ( isset($_COOKIE[$key]) && $cookie = $_COOKIE[$key] ) {
            parse_str($cookie, $token);
            if ( isset($token['access_token']) && isset($token['refresh_token']) ) {
                $this->access_token = $token['access_token'];
                $this->refresh_token = $token['refresh_token'];
                return $token;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 从数组中读取access_token和refresh_token
     * 常用于从Session或Cookie中读取token，或通过Session/Cookie中是否存有token判断登录状态。
     *
     * @param array $arr 存有access_token和secret_token的数组
     * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
     */
    function getTokenFromArray( $arr ) {
        if (isset($arr['access_token']) && $arr['access_token']) {
            $token = array();
            $this->access_token = $token['access_token'] = $arr['access_token'];
            if (isset($arr['refresh_token']) && $arr['refresh_token']) {
                $this->refresh_token = $token['refresh_token'] = $arr['refresh_token'];
            }

            return $token;
        } else {
            return false;
        }
    }

    /**
     * 以 GET 请求方式 请求 OAuth 验证服务器
     *
     * @return mixed
     */
    function get($url, $parameters = array()) {
        $response = $this->oAuthRequest($url, 'GET', $parameters);
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * 以 POST 请求方式 请求 OAuth 验证服务器
     *
     * @return mixed
     */
    function post($url, $parameters = array(), $multi = false) {
        $response = $this->oAuthRequest($url, 'POST', $parameters, $multi );
        if ($this->format === 'json' && $this->decode_json) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * 请求 OAuth 验证服务器 进行验证
     *
     * @return string
     */
    function oAuthRequest($url, $method, $parameters, $multi = false) {

        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = "{$this->host}{$url}.{$this->format}";
        }

        switch ($method) {
            case 'GET':
                if(!empty($parameters))
                    $url = $url . '&' . http_build_query($parameters);

                return $this->http($url, 'GET');
            default:
                $headers = array();
                if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
                    $body = http_build_query($parameters);
                } else {
                    $body = self::build_http_query_multi($parameters);
                    $headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
                }

                //return $this->http($url, $method, $body, $headers);
                return $this->http($url, $method, $body, $headers);
        }
    }

    /**
     * 使用 CURL 模拟一个 HTTP 请求
     *
     * @description (需要开启 CURL 扩展)
     *
     * @return string API results
     *
     */
    function http($url, $method, $postfields = NULL, $headers = array()) {
        $this->http_info = array();
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($postfields)) {
                    $url = "{$url}?{$postfields}";
                }
        }

        if ( isset($this->access_token) && $this->access_token )
            $headers[] = "Authorization: OAuth2 ".$this->access_token;

        if ( !empty($this->remote_ip) ) {
            if ( defined('SAE_ACCESSKEY') ) {
                $headers[] = "SaeRemoteIP: " . $this->remote_ip;
            } else {
                $headers[] = "API-RemoteIP: " . $this->remote_ip;
            }
        } else {
            if ( !defined('SAE_ACCESSKEY') ) {
                $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
            }
        }
        curl_setopt($ci, CURLOPT_URL, $url );
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;

        if ($this->debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);

            echo "=====headers======\r\n";
            print_r($headers);

            echo '=====request info====='."\r\n";
            print_r( curl_getinfo($ci) );

            echo '=====response====='."\r\n";
            print_r( $response );
        }
        curl_close ($ci);
        return $response;
    }

    /**
     * 从结果中 获取 header 参数 (CURL 使用)
     *
     * @return int
     *
     * @ignore (忽略)
     */
    function getHeader($ch, $header) {
        $i = strpos($header, ':');
        if (!empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }
        return strlen($header);
    }

    /**
     * 把要传递的参数 重新构造一下 (多维数组)
     *
     * @ignore (忽略)
     */
    public static function build_http_query_multi($params) {
        if (!$params) return '';

        uksort($params, 'strcmp');

        $pairs = array();

        self::$boundary = $boundary = uniqid('------------------');
        $MPboundary = '--'.$boundary;
        $endMPboundary = $MPboundary. '--';
        $multipartbody = '';

        foreach ($params as $parameter => $value) {

            if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
                $url = ltrim( $value, '@' );
                $content = file_get_contents( $url );
                $array = explode( '?', basename( $url ) );
                $filename = $array[0];

                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
                $multipartbody .= "Content-Type: image/unknown\r\n\r\n";
                $multipartbody .= $content. "\r\n";
            } else {
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                $multipartbody .= $value."\r\n";
            }

        }

        $multipartbody .= $endMPboundary;
        return $multipartbody;
    }

    public function get_uid() {
        $hostUrl = 'http://api.zzlphp.com/home/resource/userinfo?type=mumayi';
        $params = array(
            'access_token' => $this->access_token,
        );
        return $this->get( $hostUrl, $params );
    }
}