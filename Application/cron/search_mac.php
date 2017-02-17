<?php
header("Content-type: text/html; charset=utf-8");

$con = mysql_connect("127.0.0.1","root","liang123");
if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

mysql_select_db('wechat');
mysql_query("SET NAMES UTF8");

//error_reporting( E_ALL&~E_NOTICE );

$detailurl = "http://www.aa7a.cn/category-54-b0.html";

$chd = curl_init ();
curl_setopt ( $chd, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)' );
curl_setopt ( $chd, CURLOPT_NOBODY, 0 );
curl_setopt ( $chd, CURLOPT_FOLLOWLOCATION, 1 );
curl_setopt ( $chd, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $chd, CURLOPT_TIMEOUT, 200 );
curl_setopt ( $chd, CURLOPT_URL, $detailurl );
//curl_setopt ( $chd, CURLOPT_REFERER, $urlss );
$htmsd = curl_exec ( $chd );
curl_close ( $chd );

$html_detail = htm_fields ($htmsd,'<div id=goods>','<div id=goods>','<form name="selectPageForm"');
$find1 = 'html" target="_blank"><span>';
$find2 = '<div class="price">';

$findLen1 = strlen ( $find1 );
$findLen2 = strlen ( $find2 );
$cxnums = substr_count($html_detail,'html" target="_blank"><span>');
for($j=1;$j<=$cxnums;$j++){
    $tmpp[0] = 0;
    $xxx = 0;
    if ($j == 1) {
        $xxx = 0;
    } else {
        $xxx = $tmpp [$j - 1] + $findLen1;
    }
    $tmpp [$j] = stripos ( $html_detail, $find1, $xxx );
    $divv = substr ( $html_detail, $tmpp [$j] + $findLen1 );
    $poz = strpos ( $divv, "</a>" );
    $res= substr ( $divv, 0, $poz );
    //$res= substr($res,7);
    //$res ="【".$res;
    //echo $res."\r\n";
    $arrre = explode("</span>",$res);
    //print_r($arrre);
    //echo $arrre[1]."\r\n";

    $tmp['title'] = iconv('gb2312','utf-8',$arrre[0]);//$arrre[1];
    $tmp['detail'] = iconv('gb2312','utf-8',$arrre[1]);

    //$tmp['title'] = $arrre[1];

    $tmpp1[0] = 0;
    $xxx1 = 0;
    if ($j == 1) {
        $xxx1 = 0;
    } else {
        $xxx1 = $tmpp1 [$j - 1] + $findLen2;
    }
    $tmpp1 [$j] = stripos ( $html_detail, $find2, $xxx1 );
    //echo $tmpp1 [$j].'-';
    $divv1 = substr ( $html_detail, $tmpp1 [$j] + $findLen2 );
    $poz1 = strpos ( $divv1, "</div>" );
    $res1= substr ( $divv1, 0, $poz1 );


    //echo substr($res1,54)."\r\n";

    $tmp['price'] =  iconv('gb2312','utf-8',$res1);//$arrre[1];$res1;//substr($res1,54);


    //echo "lenth:".mb_strlen($res1,'UTF-8');

    $sql = "SELECT COUNT(*) FROM `mac_log` WHERE title='".$tmp['title']."' and time='".date('Y-m-d')."'";

    $query = mysql_query($sql);

    $res = mysql_fetch_array($query);

    if($res[0]==0){
       echo  $sql = "INSERT `mac_log`(`title`,`detail`,`price`,`time`) VALUES('".$tmp['title']."','".$tmp['detail']."','".$tmp['price']."','".date('Y-m-d')."')";

        mysql_query($sql);
        sleep(2);
        echo "success...\r\n";
    }else{
        sleep(1);
        echo "jump.........\r\n";
    }

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
function htm_fields($htm, $tag_key, $tag_pre, $tag_suf) {
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
function noHTML($content) {
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

function microtime_float() {
    list ( $usec, $sec ) = explode ( " ", microtime () );
    return (( float ) $usec + ( float ) $sec);
}
function getRegionDetail($pinyin){
    global $dbh;
    $sql = "select * from sys_region where sr_pinyin like '".$pinyin."%'";
    $result = $dbh->select_row($sql);
    return $result;
}

function hasProduct($a_name,$apn_title,$apn_type,$apn_identity,$apn_credit,$apn_estate)
{
    GLOBAL $dbh;
    $sql = "select apn_id from agency_product_normal
				where a_name='".$a_name."' and apn_title='".$apn_title."' and apn_type='".$apn_type."'
				and apn_identity = ".$apn_identity." and 	apn_credit = ".$apn_credit." and apn_estate=".$apn_estate."
				";
    $res = $dbh->select_one($sql);
    return $res;
}
