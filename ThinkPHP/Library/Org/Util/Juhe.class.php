<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/2/13
 * Time: 上午9:49
 */
namespace Org\Util;

class Juhe{
    private $xiaohua_key;
    private $movie_key;
    private $phone_key;

    public function __construct()
    {
        $this->xiaohua_key = 'b73738cdd77c5b9191031c63d8f8aed1';
        $this->movie_key   = 'a745870774cfb316fdd71eea6d933d6a';
        $this->phone_key   = '78138c479317f5a548d10960bb7bdc45';
    }

    /**
     * @param $phone
     * @return Array
     * 根据手机号获取手机号信息
     * 返回参数说明：
        名称	类型	说明
        error_code	int	返回码
        reason	string	返回说明
        result	string	返回结果集
        province	string	省份
        city	string	城市
        areacode	string	区号
        zip	string	邮编
        company	string	运营商
        card	string	卡类型
     */
    public function get_phone_info($phone)
    {
        $appkey = $this->phone_key;
        $url = "http://apis.juhe.cn/mobile/get";
        $params = array(
            "phone" => $phone,
            "dtype" => "json",
            "key" => $appkey,//您申请的key
        );
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        $return = $result['result'];
        return $return;
    }
    /**
     * @param $city 城市名称
     * @return mixed
     * 根据城市名称 获取正在上映和即将上映的电影
     */
    public function get_movie($city)
    {
        $appkey = $this->movie_key;
        $url = "http://op.juhe.cn/onebox/movie/pmovie";
        $params = array(
            "city" => $city,
            "dtype" => "json",
            "key" => $appkey,//您申请的key
        );
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        $return = $result['result']['data'];
        return $return;
    }
    /**
     * @return mixed
     * 随机获取一个笑话
     */
    public function get_xiaohua()
    {
        $appkey = $this->xiaohua_key;
        //************1.按更新时间查询笑话************
        $url = "http://japi.juhe.cn/joke/content/list.from";
        $params = array(
            "sort" => "desc",//类型，desc:指定时间之前发布的，asc:指定时间之后发布的
            "page" => "1",//当前页数,默认1
            "pagesize" => "20",//每次返回条数,默认1,最大20
            "time" => time(),//时间戳（10位），如：1418816972
            "key" => $appkey,//您申请的key
        );
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        if($result){
            if($result['error_code']=='0'){
                $res = $result['result']['data'];
                $tt = array_rand($res,1);
                return $res[$tt]['content'];
            }else{
                echo $result['error_code'].":".$result['reason'];
            }
        }else{
            echo "请求失败";
        }
    }
    /**
     * @param $city 城市名称
     * @return bool|mixed
     * 根据城市名称获取一周天气情况 string
     */
    public function get_weather($city)
    {
        $url = "http://wthrcdn.etouch.cn/weather_mini?city=".$city."";
        $string = $this->juhecurl($url);
        $weather_arr = json_decode($string,true);
        $content = '暂时无法获取';
        if(is_array($weather_arr) && !empty($weather_arr) && $weather_arr['desc']=='OK'){
            $content1 = $city.'的当前温度:'.$weather_arr['data']['wendu']."°C\r\n".$weather_arr['data']['ganmao']."\r\n";
            $content2 = '';
            foreach ($weather_arr['data']['forecast'] as $key => $value) {
                $content2 .= $value['date'].":".$value['type']."\r\n".$value['low'].' ~ '.$value['high']."\r\n";
            }
            $content = $content1.$content2;
        }
        return $content;
    }

    /**
     * @param $msg 输入的内容
     * @return string 返回的内容
     * 机器人聊天对话
     */
    public function botchat($msg)
    {
        $url = "http://api.qingyunke.com/api.php";
        $params = array(
            "key" => "free",
            "appid" => 0,
            "msg" => $msg
        );
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        if($result){
            if($result['result']=='0'){
                return $result['content'];
            }else{
                return 'null';
            }
        }else{
            return  "null";
        }
    }
    /**
     * @param $url
     * @param bool $params
     * @param int $ispost
     * @return bool|mixed
     * 聚合API CURL
     */
    public function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}