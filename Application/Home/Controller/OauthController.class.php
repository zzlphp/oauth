<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/2/16
 * Time: 下午3:49
 */
namespace Home\Controller;

use Think\Controller;

class OauthController extends Controller{
    private $oauth = NULL;

    function _initialize(){

        header("Content-Type: application/json");
        Vendor("oauth.ThinkOAuth2");
        $this -> oauth = new \ThinkOAuth2();

    }

    public function index(){

        header("Content-Type:application/json; charset=utf-8");
        $this -> ajaxReturn(null, 'oauth-server-start', 1, 'json');

    }

    public function access_token() {

        $this -> oauth -> grantAccessToken();

    }

    //权限验证
    public function authorize() {

        if (IS_POST) {

            if(true){
                //这个地方验证成功后要把用户的uid加进来
                $_POST['user_id'] = 1;
                $this -> oauth -> finishClientAuthorization(true, $_POST);    //第一个参数很重要，是用户是否授权
            }
            return;
        }

        ///表单准备
        $auth_params = $this -> oauth -> getAuthorizeParams();
        $this->assign("auth_params",$auth_params);
        $this->display();

    }

    public function addclient() {
        echo 'jack';
        if (IS_POST) {
            echo 'pd';
            $res = $this -> oauth -> addClient($_POST["client_id"], $_POST["client_secret"], $_POST["redirect_uri"]);
            if($res){
                $this->redirect("/home/Oauth/clientlist");
                die;
            }
        }

        $this->display();
    }

    public function clientlist(){
        $res = M()->table("oauth_client")->select();
        $this->assign("clients",$res);
        var_dump($res);
        $this->display();
    }
}