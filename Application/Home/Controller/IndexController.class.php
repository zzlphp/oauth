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
        $this->display();
    }
}