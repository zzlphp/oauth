<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/1/22
 * Time: 下午4:59
 */
namespace Home\Controller;
use Think\Controller;
use Org\Util\GetIP;
use Org\Util\BaiduPan;
use Org\Util\Shujubao;
class WechatController extends Controller {
    private $dyh_msg="您好，欢迎关注'是谁打了个喷嚏'/可爱\r\n\r\n回复:正在上映 \r\n  查看正在热映的电影\r\n\r\n回复:即将上映 \r\n  查看即将上映的电影\r\n\r\n回复:[电影+电影名称] \r\n 搜索百度云盘资源 \r\n如:电影乘风破浪\r\n\r\n回复:[地名+天气] \r\n 查看一周天气预报 \r\n如:上海天气\r\n\r\n还可发任何指令和我聊天哦!/坏笑";
    public function index()
    {
        $res = $this->get_movie();
        foreach ($res[0]['data'] as $k=>$v){
            $new_arr[$k]['Title']       = $v['tvTitle'];
            $new_arr[$k]['Description'] = isset($v['story']['data']['storyBrief'])?$v['story']['data']['storyBrief']:'';
            $new_arr[$k]['PicUrl']      = $v['iconaddress'];
            $new_arr[$k]['Url']         = $v['m_iconlinkUrl'];
        }

        print_r($new_arr);
        /*
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'zhangziliang';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            echo $_GET['echostr'];
        }else{
            return false;
        }
        */
        //===========校验
        $access_token = $this->getAccessToken('wx229cf155ad8608b3','b73631cb7cb22dd8bc9162852225d806');
        $data = array();
        $json_str = file_get_contents("php://input");
        $array = json_decode($json_str,true);

        $this->php_write('/tmp/accept.log',date('Y-m-d H:i:s').'====='.$json_str."\r\n",'a+');
        if(is_array($array) && !empty($array)){
            $Wx = M("wx_message_accept");
            $data_s                   = array();
            $data_s['ToUserName']     = $array['ToUserName'];
            $data_s['FromUserName']   = $array['FromUserName'];
            $data_s['CreateTime']     = $array['CreateTime'];
            $data_s['MsgType']        = $array['MsgType'];
            $data_s['Content']        = $array['Content'];
            $data_s['MsgId']          = $array['MsgId'];

            $Wx->data($data_s)->add();
            $sql = $Wx->getLastSql();
            $this->php_write('/tmp/accept.log',date('Y-m-d H:i:s').'====='.$sql."\r\n",'a+');

            //发送信息
            $send_data                      = array();
            $send_data['touser']            = $data_s['FromUserName'];
            $send_data['msgtype']           = $data_s['MsgType'];
            $send_data['text']['content']   = $this->get_content($data_s['Content']);
            //################################################
            $this->php_write('/tmp/senddata.log',date('Y-m-d H:i:s').'====='.json_encode($send_data)."\r\n",'a+');

            $send_data = json_encode($send_data,JSON_UNESCAPED_UNICODE);

            $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
            $res = $this->curlPost($url,$send_data);

            $this->php_write('/tmp/senddata.log',date('Y-m-d H:i:s').'====='.$res."^_^\r\n",'a+');
            echo 'success';
        }
    }
    public function get_content($city)
    {
        $content = $this->get_xiaohua();
        $weather = $this->get_weather($city);
        $weather_arr = json_decode($weather,true);
        if(is_array($weather_arr) && !empty($weather_arr) && $weather_arr['desc']=='OK'){
            $content1 = $city.' 当前温度:'.$weather_arr['data']['wendu']."°C\n\n".$weather_arr['data']['ganmao']."\n\n";
            $content2 = '';
            foreach ($weather_arr['data']['forecast'] as $key => $value) {
                $content2 .= $value['date'].":".$value['type']."\n".$value['low'].' ~ '.$value['high']."\n";
            }
            $content = $content1.$content2;
        }
        return $content;
    }
    public function get_weather($city)
    {
        $url = "http://wthrcdn.etouch.cn/weather_mini?city=".$city."";
        $string = $this->juhecurl($url);
        return $string;
    }
    public function edit_weather($city,$string)
    {
        $weather_arr = json_decode($string,true);
        $content = '暂时无法获取';
        if(is_array($weather_arr) && !empty($weather_arr) && $weather_arr['desc']=='OK'){
            $content1 = $city.'的当前温度:'.$weather_arr['data']['wendu']."°C\n\n".$weather_arr['data']['ganmao']."\n\n";
            $content2 = '';
            foreach ($weather_arr['data']['forecast'] as $key => $value) {
                $content2 .= $value['date'].":".$value['type']."\n".$value['low'].' ~ '.$value['high']."\n";
            }
            $content = $content1.$content2;
        }
        return $content;
    }
    public function get_xiaohua()
    {
        $appkey = 'b73738cdd77c5b9191031c63d8f8aed1';
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
    public function get_movie($city)
    {
        $appkey = 'a745870774cfb316fdd71eea6d933d6a';
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
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
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
    public function is_utf8($str)
    {
        return preg_match('//u', $str);
    }
    public function php_write($file_name,$data,$method="a+")
    {
        $filenum=@fopen($file_name,$method);
        flock($filenum,LOCK_EX);
        $file_data=fwrite($filenum,$data);
        fclose($filenum);
        return $file_data;
    }
    public function getAccessToken($appid='wx229cf155ad8608b3',$appsecret = 'b73631cb7cb22dd8bc9162852225d806')
    {
//        define("APPID", "wx229cf155ad8608b3");
//        define("APPSECRET", "b73631cb7cb22dd8bc9162852225d806");

        $token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
        $res = file_get_contents($token_access_url); //获取文件内容或获取网络请求的内容
        //echo $res;
        $result = json_decode($res, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
        $access_token = $result['access_token'];
        return  $access_token;
    }

    public function curlPost($url, $post='', $autoFollow=0)
    {
        $ch = curl_init();
        $user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.87 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:61.152.245.68', 'CLIENT-IP:61.152.245.68'));  //构造IP
        curl_setopt($ch, CURLOPT_REFERER, "http://www.dfd88.com/");   //构造来路
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_COOKIE,'gr_user_id=57ea2f43-83c2-4f23-9842-9930c4741a17; gr_session_id_99d3cf80c0818733=ae60e028-9798-4c20-b59a-7b32cd8a511c');
        if($autoFollow){
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  //启动跳转链接
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);  //多级自动跳转
        }
        if($post!=''){
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    /**
     * Unicode字符转换成utf8字符
     * @param  [type] $unicode_str Unicode字符
     * @return [type]              Utf-8字符
     */
    public function unicode_to_utf8($unicode_str) {
        $utf8_str = '';
        $code = intval(hexdec($unicode_str));
        //这里注意转换出来的code一定得是整形，这样才会正确的按位操作
        $ord_1 = decbin(0xe0 | ($code >> 12));
        $ord_2 = decbin(0x80 | (($code >> 6) & 0x3f));
        $ord_3 = decbin(0x80 | ($code & 0x3f));
        $utf8_str = chr(bindec($ord_1)) . chr(bindec($ord_2)) . chr(bindec($ord_3));
        return $utf8_str;
    }
    //###################################################################
    //###################################################################
    //###################################################################
    //#######################################################微信订阅号测试
    //###################################################################
    //###################################################################
    //###################################################################
    public function dyh()
    {
        $options = array (
            'token' => 'ziziliang', // 填写你设定的key
            'appid' => 'wx3c8dfdb384f2b925', // 填写高级调用功能的appid
            'appsecret' => '51085cd5ca92ffa5f4dd271b7da85935' // 填写高级调用功能的密钥
        );
        $redis_option['expire'] = 7200;

        $Wechat = new \Vendor\Weixin\Wechat($options);
        $cache  = new \Think\Cache\Driver\Redis($redis_option);

        //------------获取accessToken
        /*
        if($cache->get('accesstoken')){
            $accessToken = $cache->get('accesstoken');
        }else{
            $accessToken = $this->getAccessToken('wx3c8dfdb384f2b925','51085cd5ca92ffa5f4dd271b7da85935');
            $cache->set('accesstoken',$accessToken);
        }
        $Wechat->setAccessToken($accessToken);
        //自定义菜单
        $menuStatus = $Wechat->createMenu($this->getMenu());*/


        //获取消息发送者的用户openid
        $msgtype       = $Wechat->getRev()->getRevType();
        if($msgtype == $Wechat::MSGTYPE_EVENT){
            $action = $Wechat->getRev()->getRevEvent();
            if($action['event']=="subscribe"){
                $str=$this->dyh_msg;
                $Wechat->text($str)->reply();
            }else if($action['event']=="unsubscribe"){

            }else if($action['event']=="CLICK"){

            }else if($action['event']=="LOCATION"){
                $res = $Wechat->getRev()->getRevData();
                $this->php_write('/tmp/dyh.log',date('Y-m-d H:i:s')."---res---\r\n".json_encode($res)."\r\n",'a+');
            }
        }else{
            //自动回复信息
            $fromUser       = $Wechat->getRev()->getRevFrom();
            $content        = $Wechat->getRev()->getRevContent();

            if($content=='天气'||$content=='上海#电影'||$content=='上海#天气'){
                $str=$this->dyh_msg;
                $Wechat->text($str)->reply();
                $c = $str;
                $Wechat->text($c)->reply();
            }else if($content=='正在上映'){
                $res = $this->get_movie('上海');
                if(is_array($res) && !empty($res)){
                    foreach ($res[0]['data'] as $k=>$v){
                        $new_arr[$k]['Title']       = $v['tvTitle'];
                        $new_arr[$k]['Description'] = isset($v['story']['data']['storyBrief'])?$v['story']['data']['storyBrief']:'';
                        $new_arr[$k]['PicUrl']      = $v['iconaddress'];
                        $new_arr[$k]['Url']         = $v['m_iconlinkUrl'];
                        if($k>5){
                            break;
                        }
                    }
                    $Wechat->news($new_arr)->reply();
                    $c = json_encode($new_arr);
                }
            }else if($content=='即将上映'){
                $res = $this->get_movie('上海');
                if(is_array($res) && !empty($res)){
                    foreach ($res[1]['data'] as $k=>$v){
                        $new_arr[$k]['Title']       = $v['tvTitle'];
                        $new_arr[$k]['Description'] = isset($v['story']['data']['storyBrief'])?$v['story']['data']['storyBrief']:'';
                        $new_arr[$k]['PicUrl']      = $v['iconaddress'];
                        $new_arr[$k]['Url']         = $v['m_iconlinkUrl'];
                        if($k>5){
                            break;
                        }
                    }
                    $Wechat->news($new_arr)->reply();
                    $c = json_encode($new_arr);
                }
            }else if(strstr($content,'天气')){
                $city = str_replace('天气','',$content);
                if($city==''){
                    $c = $this->dyh_msg;
                }else{
                    $c = $this->edit_weather($city,$this->get_weather($city));
                }
                $Wechat->text($c)->reply();
            }else if(strpos($content,'电影')!==false){
                $m = str_replace('电影','',$content);
                $c = $this->dyh_msg;
                if($m!=''){
                    $c = '';
                    $Bd = new BaiduPan();
                    $res = $Bd->get_ziyuan($m);
                    if(is_array($res) && !empty($res)){
                        foreach ($res as $k=>$v){
                            $c .= $v['url']."\r\n";
                        }
                    }
                }
                $Wechat->text($c)->reply();
            }else if(strpos($content,'菜名')!==false){
                $Shujubao = new Shujubao();
                $m = str_replace('菜名','',$content);
                $content = $Shujubao->get_caipu($m);
                $key = array_rand($content['data'],1);
                $c = $this->sort_out_data($content['data'][$key]['msg']);
                $Wechat->text($c)->reply();
            }else{
                $c = $this->sort_out_data($this->chat($content));
                $Wechat->text($c)->reply();
            }

            $log                   = array();
            $log['FromUserName']   = $fromUser;
            $log['CreateTime']     = time();
            $log['MsgType']        = $msgtype;
            $log['Content']        = $content;
            $id = $this->wxLog($log);
            $this->wxLogUpdate($id,$c);
        }
        echo '';
    }
    private function sort_out_data($str)
    {
        $arr = array('{br}','菲菲','{face:74}','2206566989','1873185078','梅州行网站','<p>','</p>','<h2>','</h2><hr>');
        $res = array('\r\n','"谁打了个喷嚏"','/可爱','280229278','280229278','www.zzlphp.com',"\r\n","\r\n",'',":\r\n");
        $str = str_replace($arr,$res,$str);
        return $str;
    }
    private function wxLog($array)
    {
        $Wx = M("wx_message_accept");
        $data_s                   = array();
        $data_s['FromUserName']   = $array['FromUserName'];
        $data_s['CreateTime']     = time();
        $data_s['MsgType']        = $array['MsgType'];
        $data_s['Content']        = $array['Content'];

        $Wx->data($data_s)->add();
        return $Wx->getLastInsID();
    }
    private function wxLogUpdate($id,$c)
    {
        $Wx = M("wx_message_accept");
        $data_s                   = array();
        $data_s['Rcontent']       = $c;

        $Wx->where('id='.intval($id))->save($data_s);
    }
    public function qyh()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'ziziliang';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            echo $_GET['echostr'];
        }else{
            return false;
        }
    }
    private function chat($msg)
    {
        //************1.按更新时间查询笑话************
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
}