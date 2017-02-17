<?php
namespace Home\Controller;
use Org\Util\GetIP;
use Org\Util\Juhe;
use Org\Util\BaiduPan;
use Org\Util\Shujubao;
use Think\Controller;
class IndexController extends Controller {

    public function index()
    {
        $client_id   = 1000;
        $type        = 'zhangziliang';
        $url = "http://api.zzlphp.com/home/oauth/authorize?redirect_uri=http://o.zzlphp.com/home/login/index/client_id/{$client_id}/type/{$type}&response_type=code&client_id={$client_id}&type={$type}";
        $this->assign('url',$url);
        $this->display();
    }
}