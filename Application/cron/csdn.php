<?php
/**
 * @Author: zhangziliang
 * @Date:   2015-05-07 14:33:45
 * @Last Modified by:   zhangziliang
 * @Last Modified time: 2015-05-07 15:04:25
 */
$file = fopen("/tmp/www.csdn.net.sql", "r") or exit("Unable to open file!");
//Output a line of the file until the end is reached
//feof() check if file read end EOF
while(!feof($file))
{
	$tmp = explode('#', fgets($file));
	print_r($tmp);
	//---------------------------------------------------------------------------
	$con = mysql_connect("localhost","root","root123456");
	if (!$con)
	{
	  die('Could not connect: ' . mysql_error());
	}

	mysql_select_db('phone');
	mysql_query("SET NAMES UTF8");
	//---------------------------------------------------------------------------
	$sql = "SELECT COUNT(*) FROM `csdn` WHERE name='".$tmp[0]."' and pwd='".$tmp[1]."'";

	$query = mysql_query($sql);

	$res = mysql_fetch_array($query);

	if($res[0]==0){
		$sql = "INSERT `csdn`(`name`,`pwd`,`email`) VALUES('".$tmp[0]."','".$tmp[1]."','".$tmp[2]."')";

		mysql_query($sql);
		echo "success...\r\n";
	}else{
		echo "jump.........\r\n";
	}
}
fclose($file);