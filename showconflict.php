<?
/*
-----
file asal  : sharing_schedule.php, sharing_req_his.php, newsticker.php
file tujuan: save2db.php?sw=16
-----
session_start();
if (!isset($_SESSION['nik']))
{
	Header("Location:login.php");
}
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>KMORE - Sharing Knowledge Detail</title>
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
}
</style>
</head>

<?
include ("include/convertdatetime.php");
include("include/dbcon.php");
$q = "SELECT a.nik, a.judul, a.t_mulai, a.t_akhir, a.sharing_status, b.nama, c.nm_loker FROM knowledge a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE a.id_know='$_REQUEST[idk]'";
#echo "$q<br>";
$result = mysql_query($q);
$row = mysql_fetch_array($result);

?>

<body>
<br>
<p/><b>Request Sharing ini bentrok dengan:</b>

<table width="100%" cellpadding='5' cellspacing='1' align="center">
<tr>
	<td class="mycolor">Title/Theme:</td>
	<td class="mycolor2"><?= $row["judul"]; ?></td>
</tr>
<tr>
	<td class="mycolor">Contributor:</td>
	<td class="mycolor2"><?= $row["nama"]; ?></td>
</tr>
<tr>
	<td class="mycolor">Bidang:</td>
	<td class="mycolor2"><?= $row["nm_loker"]; ?></td>
</tr>
<tr>
	<td class="mycolor">Date:</td>
	<td class="mycolor2"><?= tampilkan_waktunya($row["t_mulai"]); ?></td>
</tr>
<tr>
	<td class="mycolor">Time:</td>
	<td class="mycolor2"><?= substr($row["t_mulai"],11,5)."&nbsp;s/d&nbsp;".substr($row["t_akhir"],11,5); ?></td>
</tr>
<tr>
	<td class="mycolor">Status:</td>
	<td class="mycolor2"><? if ($row["sharing_status"]=="1") echo "Request"; elseif ($row["sharing_status"]=="3") echo "Schedulling"; ?></td>
</table>
<br>
<!-- <center><input type="submit" value="Close" onclick="tb_remove();"></center> -->
<br>
</body>
</html>
