<?php
/**
 * Created by PhpStorm.
 * User: zhangziliang
 * Date: 15/6/4
 * Time: 下午2:44
 */
$file = fopen("/tmp/renren.txt", "r") or exit("Unable to open file!");
//Output a line of the file until the end is reached
//feof() check if file read end EOF
while(!feof($file))
{
    $tmp = explode('	', fgets($file));
    //---------------------------------------------------------------------------
    $con = mysql_connect("localhost","root","root123456");
    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }

    mysql_select_db('phone');
    mysql_query("SET NAMES UTF8");
    //---------------------------------------------------------------------------
    $sql = "SELECT COUNT(*) FROM `renren` WHERE name='".$tmp[0]."' and pwd='".$tmp[1]."'";

    $query = mysql_query($sql);

    $res = mysql_fetch_array($query);

    if($res[0]==0){
        $qqarr = explode('@qq',$tmp[0]);
        if(isset($qqarr[1])){
            $qq = $qqarr[0];
            preg_match_all('/\d+/',$qq,$arr);
            $str = '';
            if(!empty($arr)){
                foreach($arr as $k=>$v){
                    $str .= $v[$k];
                }
                $qq = $str;
            }

        }else{
            $qq = '';
        }
        $sql = "INSERT `renren`(`name`,`pwd`,`qq`) VALUES('".$tmp[0]."','".$tmp[1]."','".$qq."')";

        mysql_query($sql);
        echo "success...\r\n";
    }else{
        echo "jump.........\r\n";
    }
}
fclose($file);