<?
/*
-----
file asal  : sharing_schedule.php, sharing_req_his.php, newsticker.php
file tujuan: save2db.php?sw=16
-----
*/
session_start();
if (!isset($_SESSION['nik_login']))
{
	Header("Location:login.php");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>KMORE - Sharing Point Individu Detail</title>
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<link type="text/css" href="style/master.css" rel="stylesheet"/>
<style type="text/css">
table tr td {
	padding: 3 5 3 5;
	vertical-align: top;
	border: 0px solid #408080;
}

h2 {
	padding: 0 auto;
	text-align: center;
}
.mycolor {
	background: #A9D3BB;
}
.mycolor2 {
	background: #F2F2F2;
a.thick:link { text-decoration: none; color: #0000FF; }
a.thick:visited { text-decoration: none; color: #0000FF; }
a.thick:hover { text-decoration: underline; color: #FF0000; }
}
</style>
</head>
<body>
<?php

// Step 1
$judul = "Sharing Point Individu Detail";
require_once ("include/dbcon.php");

// Step 2
$q = "SELECT a.nik, a.nama, b.nm_loker FROM user a JOIN loker b ON a.id_bidang=b.id_loker WHERE a.nik='$_REQUEST[nik]'";
#echo "$query<br>";
$result = mysql_query($q) or die('Mysql Err. 1');
$rs = mysql_fetch_array($result);

$query = "SELECT tipe, SUM(poin) AS nilai FROM sharing_activity WHERE nik='$_REQUEST[nik]' GROUP BY tipe";
#echo "$query<br>";
$result = mysql_query($query) or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<center>";
if ($num==0)
{
	echo "<br><br><br><br>Sorry, no points at this time";
}
else
{
	$yourpoint = array();
	$i = 0;
	while ($r = mysql_fetch_array($result))
	{
		if ($r[tipe]<3)
			$nilai_bid  += $r[nilai];
		elseif ($r[tipe]==3)
			$nilai_rdc   = $r[nilai];
		elseif ($r[tipe]==4)
			$nilai_rdc_e = $r[nilai];
		else
			$nilai_part  = $r[nilai];
		$i++;
	}
?>

<br>
	<h2><?= $judul; ?></h2>
	<!-- <table width="500px" cellpadding='5' cellspacing='0' rules='rows' frame='below'> -->
	<table align="center">
	<tr>
		<td class="mycolor">Nama:</td>
		<td class="mycolor2" width="75%"><?= $rs["nama"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">NIK:</td>
		<td class="mycolor2"><?= $rs["nik"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Lokasi Kerja:</td>
		<td class="mycolor2">RDC - <?= $rs["nm_loker"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Bidang:</td>
		<td class="mycolor2"><?= $nilai_bid; ?></td>
	</tr>
	<tr>
		<td class="mycolor">RDC:</td>
		<td class="mycolor2"><?= $nilai_rdc; ?></td>
	</tr>
	<tr>
		<td class="mycolor">RDC dan external:</td>
		<td class="mycolor2"><?= $nilai_rdc_e; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Participant:</td>
		<td class="mycolor2"><?= $nilai_part; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Total Points:</td>
		<td class="mycolor2"><?= $nilai_bid + $nilai_rdc + $nilai_rdc_e + $nilai_part; ?></td>
	</tr>
	</table>
	<br>
	<input type="submit" name="submit" value="Close" onclick="self.parent.tb_remove();">
<?
}
?>
</center>
</body>
</html>
