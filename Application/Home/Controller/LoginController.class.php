<?php
/**
 * @Author: zhangziliang
 * @Date:   2015-05-07 08:46:50
 * @Last Modified by:   zhangziliang
 * @Last Modified time: 2015-05-07 11:32:20
 */

namespace Home\Controller;
use Think\Controller;

class LoginController extends Controller {
    public function index3(){
        $this->display();
    }
    public function index(){
        $Geet = new \Org\Util\Geet();
        $Geet->set_captchaid("b1ec013e5e0cbcf74deee1ed995ca6b8");
        $Geet->set_privatekey("3f1bb6c51073af944b271c9b83459aa0");
        $res = '';
        if ($Geet->register()) {
            $res =  $Geet->get_widget("float");
        } else {
            $res = "use your own captcha HTML web code!";
        }
        $this->assign("res",$res);
    	$this->display();
    }
    public function reg()
    {
        $this->display();
    }
    public function sublog(){
        $Geet = new \Org\Util\Geet();
        $Geet->set_captchaid("b1ec013e5e0cbcf74deee1ed995ca6b8");
        $Geet->set_privatekey("3f1bb6c51073af944b271c9b83459aa0");

        $geetest_challenge = I('post.geetest_challenge');
        $geetest_validate = I('post.geetest_validate');
        $geetest_seccode = I('post.geetest_seccode');

        if (isset($geetest_challenge) && isset($geetest_validate) && isset($geetest_seccode)) {

            $result = $Geet->validate($geetest_challenge, $geetest_validate, $geetest_seccode);
            if ($result == TRUE) {
                $name 	= I('post.username');
                $pwd 	= I('post.password');

                $User = M("user");
                $res = $User->where("name='".$name."' and pwd='".$pwd."'")->find();
                if(isset($res['id']) && $res['id']){
                    session('name',$res['name']);
                    $msg['num'] = 'success';
                    $msg['msg'] = 'success';
                }else{
                    $msg['num'] = 'error';
                    $msg['msg'] = 'no user';
                }

            } else if ($result == FALSE) {

                $msg['num'] = 'error';
                $msg['msg'] = 'img error,id:'.$geetest_challenge."=>".$geetest_validate."=>".$geetest_seccode;
            } else {
                $msg['num'] = 'error';
                $msg['msg'] = 'FORBIDDEN';
            }

        } else {
            $msg['num'] = 'error';
            $msg['msg'] = 'use your own captcha validate';

        }
        echo json_encode($msg);
    }
    public function subreg(){

    	$name 	= I('post.username');
    	$pwd 	= I('post.password');
    	$code 	= I('post.code');

    	$User = M("user");
    	$IP = new \Org\Util\GetIP();
        

		$data['name'] 	= $name;
		$data['pwd'] 	= $pwd;
		$data['code'] 	= $code;
		$data['ip'] 	= $IP->get_client_ip();
		$data['time'] 	= date('Y-m-d H:i:s');
		//重复的用户名
		$names = $User->where("name='".$data['name']."'")->count();
		if($names){
			$msg['num'] = 'error';
			$msg['msg'] = '重复的用户名';
			echo json_encode($msg);
			exit();
		}
		//当天IP注册数量
		$ipnums = $User->where("ip='".$data['ip']."' AND time like '".date('Y-m-d')."%'")->count();
		if($ipnums>3){
			$msg['num'] = 'error';
			$msg['msg'] = '每个IP当天注册次数限制';
		}else{
			if($User->add($data)){
				session('name',$data['name']);

				$msg['num'] = 'success';
				$msg['msg'] = 'success';
			}else{
				$msg['num'] = 'error';
				$msg['msg'] = '注册失败';
			}
		}
		
    	echo json_encode($msg);
    }
    public function subout(){
    	session('name',null); // 删除name
    	$msg['num'] = 'success';
		$msg['msg'] = 'success';
    	echo json_encode($msg);
    }

}