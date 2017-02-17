<?php
/**
 * @Author: zhangziliang
 * @Date:   2015-05-06 14:20:36
 * @Last Modified by:   zhangziliang
 * @Last Modified time: 2015-05-07 16:23:43
 */

namespace Home\Controller;
use Think\Controller;
class ListController extends Controller {

	public function index(){
        
    }

    public function search(){
    	$name = $_POST['name'];
    	$code = $_POST['code'];

    	if(!$name && !$code){
    		header("Location:/Home/List/index");
    	}
    	$where = ' 1=1 ';
    	if($name){
    		$where .= " AND name like '".$name."%'";
    	}
    	if($code){
    		$where .= " AND ctfid like'".$code."%'";
    	}

    	S(array('type'=>'redis','expire'=>3600*12));
    	$cdsgus = M("cdsgus");

    	$key = "serch:'".$name."':'".$code."'";

        $serdata = unserialize(S($key));
        if(!$serdata){//if(!$res){
            $serdata = $cdsgus->where($where)->order('version desc,id desc')->limit(50)->select();
            S($key,serialize($res),3600*12);
        }
        if(!session('name')){
            header("Location:/Home/Login/index/");
        }else{
            $User = M("user");
            $code1 = $User->where("name='".session('name')."'")->getField('code');
        }
        if($code1!='371324199110107371'){
            foreach ($serdata as $key => $value) {
                $serdata[$key]['name'] = $this->substr_cut($value['name']);
                $serdata[$key]['mobile'] = $this->hidecenternum($value['mobile'],3,4);
                $serdata[$key]['email'] = $this->hidecenternum($value['email'],3,4);
                $serdata[$key]['ctfid'] = $this->hidecenternum($value['ctfid'],4,4);
                $serdata[$key]['address'] = $this->hidecenternum($value['address'],6,6);
            }
        }
        

        $this->assign('name',$name);
        $this->assign('code',$code);
        $this->assign('listdata',$serdata);
        $this->display('List/index');
    }
    public function error(){
        $id = $_GET['id'];
        $this->assign('id',$id);
        $this->display('');
    }
    public function substr_cut($user_name){
        $strlen     = mb_strlen($user_name, 'utf-8');
        $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
        $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
    }
    /**
    * $num 数字字符串
    * $s   数字开头显示的个数
    * $e   数字结尾显示的个数
    */
    public function hidecenternum($num,$s,$e)
    {
        if(!$num){
            return '';
        }
        $l =  mb_strlen($num,'UTF-8');
        $str1 = substr($num, 0,$s);
        $str2 = substr($num, -$e);
        if($l<($s+$e)){
            return '****';
        }
        $c = '';
        for($i=0;$i<$l-$s-$e;$i++){
            $c .= "*";
        }
        return $str1.$c.$str2;
    }

}