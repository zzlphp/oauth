<?php
/**
 * Created by PhpStorm.
 * User: ziziliang
 * Date: 2017/2/13
 * Time: 下午2:49
 */
namespace Org\Util;
class BaiduPan{

    public function get_ziyuan($m)
    {
        $url  ='http://www.wangpansou.cn/s.php?wp=15&ty=gn&op=baipan&q=%E5%BF%A0%E7%8A%AC%E5%85%AB%E5%85%AC%E7%9A%84%E6%95%85%E4%BA%8B&q=%E5%BF%A0%E7%8A%AC%E5%85%AB%E5%85%AC%E7%9A%84%E6%95%85%E4%BA%8B';
        $url  ="http://www.wangpansou.cn/s.php?wp=15&ty=gn&op=baipan&q={$m}&q={$m}";
        $html = $this->tocurl($url);
        $html_detail = $this->htm_fields ($html,'<strong>热门搜索：</strong>','<strong>热门搜索：</strong>','<div id="footer">');


        $find1 = 'href="http://redirect.wangpansou.cn/redirect.php?url=http%3A%2F%2Fpan.baidu.com';
        $findLen1 = strlen ( $find1 );

        $cxnums = substr_count($html_detail,'href="http://redirect.wangpansou.cn/redirect.php?url=http%3A%2F%2Fpan.baidu.com');
        for($j=1;$j<=$cxnums;$j++) {
            $tmpp[0] = 0;
            $xxx = 0;
            if ($j == 1) {
                $xxx = 0;
            } else {
                $xxx = $tmpp [$j - 1] + $findLen1;
            }
            //title
//            $tmpp [$j] = stripos($html_detail, $find1, $xxx);
//            $divv = substr($html_detail, $tmpp [$j] + $findLen1);
//            $poz = strpos($divv, '</a>');
//            $title = substr($divv, 0, $poz);
//            echo $title."<br />";
//            $title = $this->htm_fields($title,'>','>','rel="noreferrer"');


            $tmpp [$j] = stripos($html_detail, $find1, $xxx);
            $divv = substr($html_detail, $tmpp [$j] + $findLen1);
            $poz = strpos($divv, '" rel="noreferrer"');
            $res = substr($divv, 0, $poz);

            $url = 'http://pan.baidu.com'.urldecode($res);

            //如果百度云有密码 则提取密码
            $tmpp [$j] = stripos($html_detail, $find1, $xxx);
            $divv = substr($html_detail, $tmpp [$j] + $findLen1);
            $poz2 = strpos($divv, '</table>');
            $res2 = substr($divv, 0, $poz2);

            $res2 = $this->htm_fields($res2,'密码','密码:<b>','</b>');

            $str = $res2!=''?$url.'密码:'.$res2:$url;

            $result[$j]['url'] = $str;
        }
        return $result;
    }

    private function sort_out_data($str)
    {
        $arr = array('%2F','%3F','%');
        $res = array('/','?','=');
        $str = str_replace($arr,$res,$str);
        return $str;
    }

    private function tocurl($url){
        $chd = curl_init ();
        curl_setopt ( $chd, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)' );
        curl_setopt ( $chd, CURLOPT_NOBODY, 0 );
        curl_setopt ( $chd, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $chd, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $chd, CURLOPT_TIMEOUT, 200 );
        curl_setopt ( $chd, CURLOPT_URL, $url );
        $htmsd = curl_exec ( $chd );
        curl_close ( $chd );
        return $htmsd;
    }
    /**
     * parse field from html by tag
     *
     * @param   string      $htm
     * @param   string      $tag_key
     * @param   string      $tag_pre
     * @param   string      $tag_suf
     * @return  string
     */
    private function htm_fields($htm, $tag_key, $tag_pre, $tag_suf) {
        $val = '';
        $poz = strpos ( $htm, $tag_key );
        if ($poz !== false) {
            $htm = substr ( $htm, $poz );
            $poz = strpos ( $htm, $tag_pre );
            if ($poz !== false) {
                $htm = substr ( $htm, $poz + strlen ( $tag_pre ) );
                $poz = strpos ( $htm, $tag_suf );
                if ($poz !== false) {
                    $val = trim ( substr ( $htm, 0, $poz ) );
                }
            }
        }
        return $val;
    }
    /**
     *
     * 去除html标签
     * Enter description here ...
     * @param unknown_type $content
     */
    private function noHTML($content) {
        $content = preg_replace ( "/<a[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/a>/i", '', $content );
        $content = preg_replace ( "/<div[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/div>/i", '', $content );
        $content = preg_replace ( "/<font[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/font>/i", '', $content );
        $content = preg_replace ( "/<p[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/p>/i", '', $content );
        $content = preg_replace ( "/<span[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/span>/i", '', $content );
        $content = preg_replace ( "/<\?xml[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/\?xml>/i", '', $content );
        $content = preg_replace ( "/<o:p[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/o:p>/i", '', $content );
        $content = preg_replace ( "/<u[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/u>/i", '', $content );
        $content = preg_replace ( "/<b[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/b>/i", '', $content );
        $content = preg_replace ( "/<meta[^>]*>/i", '', $content );
        $content = preg_replace ( "/<\/meta>/i", '', $content );
        $content = preg_replace ( "/<!--[^>]*-->/i", '', $content ); //注释内容
        $content = preg_replace ( "/<p[^>]*-->/i", '', $content ); //注释内容
        $content = preg_replace ( "/style=.+?['|\"]/i", '', $content ); //去除样式
        $content = preg_replace ( "/class=.+?['|\"]/i", '', $content ); //去除样式
        $content = preg_replace ( "/id=.+?['|\"]/i", '', $content ); //去除样式
        $content = preg_replace ( "/lang=.+?['|\"]/i", '', $content ); //去除样式
        $content = preg_replace ( "/width=.+?['|\"]/i", '', $content ); //去除样式
        $content = preg_replace ( "/height=.+?['|\"]/i", '', $content ); //去除样式
        $content = preg_replace ( "/border=.+?['|\"]/i", '', $content ); //去除样式
        $content = preg_replace ( "/face=.+?['|\"]/i", '', $content ); //去除样式
        $content = preg_replace ( "/face=.+?['|\"]/", '', $content );
        $content = preg_replace ( "/face=.+?['|\"]/", '', $content );
        $content = str_replace ( "&nbsp;", "", $content );
        $content = addslashes ( $content );
        return $content;
    }
}