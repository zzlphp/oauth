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
//        print_r($Juhe->get_phone_info('18616690302'));

//        $IPC = new GetIP();
//        $ip = $IPC->get_client_ip();
//        print_r($IPC->handleIp($ip));
//        $array = array(
//            'button'=>array(
//                0=>array(
//                    'type'=>'click',
//                    'name'=>'今日资源',
//                    'key'=>'MENU_KEY_MUSIC',
//                ),
//                1=>array(
//                    'type'=>'view',
//                    'name'=>'歌手简介',
//                    'url'=>'http://www.qq.com/',
//                ),
//            )
//        );
//        echo json_encode($array);
        $phone = M('phone_log');
        $where = "1=1 and time like '%".date('Y-m-d')."%'";
        $serdata = $phone->where($where)->order('id desc')->limit(50)->select();
//        echo json_encode($serdata);
        $js_data = '';
        $js_6    = '';
        $res_data = $phone->distinct(true)->field('time')->order('time asc')->select();
        foreach($res_data as $keyd=>$vald){
            $js_data .= "'".$vald['time']."',";
        }
        $js_data = substr($js_data, 0, -1);

        $iphone6 = $phone->field('price')->where("title like '%港版】苹果 iPhone 6s 银色'")->order('time asc')->select();
        foreach($iphone6 as $i6=>$v6){
            $js_6 .= $v6['price'].",";
        }
        $js_6 = substr($js_6, 0, -1);

        //糗事百科
        $QSBK = M("content");
        $where = "1=1";
        $serdata = $QSBK->where($where)->order('rand()')->limit(200)->select();
        $this->assign('qsbk_data',$serdata);
        $this->assign('js_data',$js_data);
        $this->assign('js_6',$js_6);
        $this->display();
    }
    public function phpinfo()
    {
        $BD= new BaiduPan();
        print_r($BD->get_ziyuan());
    }
    public function test()
    {
        $J = new Juhe();
        $content = $J->get_weather('上海');
        $content = date('Y-m-d').str_replace("\r\n",'<br />',$content);
        $content = $content.'<br /><img src="http://api.zzlphp.com/dyh.jpg">';
        $res = think_send_mail('945095009@qq.com', '狼灵', '狼灵',$content);
        $res = think_send_mail('zhangziliang@fangjinsuo.com', '狼灵', '狼灵',$content);
        var_dump($res);
    }
    public function get_hash_table($table,$userid) {
        $str = crc32($userid);
        if($str<0){
            $hash = "0".substr(abs($str), 0, 1);
        }else{
            $hash = substr($str, 0, 2);
        }

        return $table."_".$hash;
    }
    public function mppxf()
    {
        $array = array(10,6,2,8,1,7,5,33,632,555,13332,4535,323);
        $count = count($array);
        for($k=0;$k<=$count;$k++){
            for($j=$count-1;$j>$k;$j--){
                if($array[$j]<$array[$j-1]){
                    $tmp = $array[$j];
                    $array[$j] = $array[$j-1];
                    $array[$j-1] = $tmp;
                }
            }
        }
        print_r($array);
    }
    function ceshi_invite()
    {
        $invite = mem_invite(3435);
    }
    function mem_invite($value)
    {
        if($value % 2 ==0){
            $data = array(1);
        }
        if(!empty($data)){
            $inv = 1231231;
            return mem_invite($inv);
        }else{
            echo $value;
        }
    }
    public function setParam($id)
    {
        $sql = "update pp set num=".intval($id)." where id=1";
        $arr = M('Pp','','DB_CONFIG2')->execute($sql);
        return $arr;
    }
    public function getParam()
    {
        $sql = "select num from pp where id=1";
        $arr = M('Pp','','DB_CONFIG2')->query($sql);
        return $arr[0]['num'];
    }
    private function send_email($email_address){
        $content = '看电影没有资源?关注公众号,啥资源都有哦<br /><img src="http://api.zzlphp.com/dyh.jpg">';
        $res = think_send_mail($email_address, '狼灵', '狼灵',$content);
        return $res;
    }
    public function php_send_dyh()
    {
        $id = $this->getParam();
        $sql = "select id,EMail from demo where id>".intval($id)." limit 50";

        $arr = M('Demo','','DB_CONFIG2')->query($sql);

        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if(is_array($arr) && !empty($arr)){
            foreach ($arr as $k=>$v){
                $email_address = $v['email'];
                if(preg_match( $pattern, $email_address )){

                    $ress = $this->send_email($email_address);
                    var_dump($ress);
                }
                $res = $this->setParam($v['id']);
            }
        }
    }
    //数据分表
    public function hash_table($table_name, $user_id, $total)
    {
        return $table_name . '_' . (($user_id % $total));
    }
    public function get_table(){
        echo $this->hash_table('demo',39,100);
    }
    public function fenbiao()
    {
        $id = $this->getParam();

        $sql = "select id from demo where id>".intval($id)." limit 100000";

        $arr = M('Demo','','DB_CONFIG2')->query($sql);
        if(is_array($arr) && !empty($arr)){
            foreach ($arr as $k=>$v){
                $table = $this->hash_table('demo',$v['id'],100);
                $sql = "INSERT INTO ".$table."(`Name`,`CardNo`,`Descriot`,`CtfTp`,`CtfId`,`Gender`,`Birthday`,`Address`,`Zip`,`Dirty`,`District1`,`District2`,`District3`,`District4`,`District5`,`District6`,`FirstNm`,`LastNm`,`Duty`,`Mobile`,`Tel`,`Fax`,`EMail`,`Nation`,`Taste`,`Education`,`Company`,`CTel`,`CAddress`,`CZip`,`Family`,`Version`,`id`
) SELECT `Name`,`CardNo`,`Descriot`,`CtfTp`,`CtfId`,`Gender`,`Birthday`,`Address`,`Zip`,`Dirty`,`District1`,`District2`,`District3`,`District4`,`District5`,`District6`,`FirstNm`,`LastNm`,`Duty`,`Mobile`,`Tel`,`Fax`,`EMail`,`Nation`,`Taste`,`Education`,`Company`,`CTel`,`CAddress`,`CZip`,`Family`,`Version`,`id`
 FROM demo where demo.id = {$v['id']}";

                $arr = M($table,'','DB_CONFIG2')->execute($sql);
                var_dump($arr);
                $res = $this->setParam($v['id']);
            }
        }
    }
}