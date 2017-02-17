<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/1/22
 * Time: 下午4:47
 */
namespace Home\Controller;
use Think\Controller;
class GitController extends Controller {
    public function index()
    {
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $fs = fopen('/tmp/hook_log.txt', 'a+');
        fwrite($fs, 'Request on ['.date("Y-m-d H:i:s").'] from ['.$client_ip.']'.PHP_EOL);
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        fwrite($fs, 'Data: '.$json.PHP_EOL);
        fwrite($fs, '======================================================================='.PHP_EOL);
        $fs and fclose($fs);
        echo exec("sh ./update.sh");
//        echo exec("sh ../../../../tpproject/update.sh");
        echo "<br />";
        echo date('Y-m-d H:i:s').'success';
    }
}