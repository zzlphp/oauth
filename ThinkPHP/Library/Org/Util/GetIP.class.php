<?php
/**
 * @Author: zhangziliang
 * @Date:   2015-05-06 16:49:33
 * @Last Modified by:   microsoft_liang
 * @Last Modified time: 2015-05-06 16:59:38
 */
namespace Org\Util;

class GetIP{

	var $client_ip = '';
	var $server_ip = '';

	/**
	 * 获取客户端IP地址
	 * @return string
	 */
	function get_client_ip() { 
	    if(getenv('HTTP_CLIENT_IP')){ 
	        $this->client_ip = getenv('HTTP_CLIENT_IP'); 
	    } elseif(getenv('HTTP_X_FORWARDED_FOR')) { 
	        $this->client_ip = getenv('HTTP_X_FORWARDED_FOR'); 
	    } elseif(getenv('REMOTE_ADDR')) {
	        $this->client_ip = getenv('REMOTE_ADDR'); 
	    } else {
	        $this->client_ip = $_SERVER['REMOTE_ADDR'];
	    } 
	    return $this->client_ip; 
	}   
	/**
	* 获取服务器端IP地址
	 * @return string
	 */
	function get_server_ip() { 
	    if (isset($_SERVER)) { 
	        if($_SERVER['SERVER_ADDR']) {
	            $this->server_ip = $_SERVER['SERVER_ADDR']; 
	        } else { 
	            $this->server_ip = $_SERVER['LOCAL_ADDR']; 
	        } 
	    } else { 
	        $this->server_ip = getenv('SERVER_ADDR');
	    } 
	    return $this->server_ip; 
	}
    /**
     * 根据IP获取地理位置信息
     */
    public function handleIp($ip)
    {
        $ch     = curl_init();
        $url    = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip;
        curl_setopt($ch, CURLOPT_TIMEOUT,3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);
        $res = str_replace('var remote_ip_info =','',$res);
        $res = str_replace(';','',$res);
        $res = json_decode($res,true);
        $res_ip['city']      = isset($res['city'])?$res['city']:'上海';
        $res_ip['province']  = isset($res['province'])?$res['province']:'未知';
        $res_ip['district']  = isset($res['district'])?$res['district']:'未知';

        return $res_ip;
    }
}
