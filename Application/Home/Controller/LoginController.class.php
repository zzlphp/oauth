<?php
/**
 * @Author: zhangziliang
 * @Date:   2015-05-07 08:46:50
 * @Last Modified by:   zhangziliang
 * @Last Modified time: 2015-05-07 11:32:20
 */
namespace Home\Controller;

use Think\Controller;
use Org\Util\Oauth2;
class LoginController extends Controller{
    public function _initialize(){

    }

    public function index(){
        $this->oauth = new Oauth2('1000','zhangziliang');
        $keys = array();
        //keys 数组 赋值 code 值 (换取token, 必须参数)
        $keys['code'] = $_GET['code'];
        //keys 数组 赋值 回调地址信息 (换取token, 必须参数)
        $keys['redirect_uri'] = 'http://o.zzlphp.com/home/login?type=mumayidev';
        //根据 code 获取 token
        //var_dump( $token = $this->oauth->getAccessToken( 'code', $keys )) ;
        $token = $this->oauth->getAccessToken( 'code', $keys ) ;
        var_dump( $token);exit;
        //现在已经得到token，并且将access_token写到对象里了。就可以请求资源了
        var_dump( $this->oauth->get_uid());
        die;
    }
}