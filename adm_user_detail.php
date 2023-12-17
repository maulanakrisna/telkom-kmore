<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>KMORE - User Detail</title>
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<link type="text/css" href="style/master.css" rel="stylesheet"/>
<style type="text/css">
table tr td {
	font-family: tahoma, verdana, arial;
	font-size: 12px;
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
	background: #F9F9F9;
}
</style>
</head>

<?
include("include/dbcon.php");
$q = "SELECT a.*, b.nm_loker AS nm_bidang, c.nm_loker, d.nm_profile FROM user a JOIN loker b ON a.id_bidang=b.id_loker JOIN loker c ON a.id_loker=c.id_loker JOIN profile d ON a.id_profile=d.id_profile WHERE a.nik='$_REQUEST[id]'";
#echo "$q<br>";
$result = mysql_query($q);
$row = mysql_fetch_array($result);
?>

<body>
<br><center>
	<table width="500" cellpadding='5' cellspacing='1' align="center">
	<tr><td colspan="2" align="center"><h3>Data User</h3></td></tr>
	<tr>
		<td class="mycolor">NIK:</td>
		<td class="mycolor2" width="75%"><?= $row["nik"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Nama:</td>
		<td class="mycolor2"><?= $row["nama"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Band:</td>
		<td class="mycolor2"><?= $row["band"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Posisi:</td>
		<td class="mycolor2"><?= $row["posisi"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Bidang:</td>
		<td class="mycolor2"><?= $row["nm_bidang"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Loker:</td>
		<td class="mycolor2"><?= $row["nm_loker"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Email:</td>
		<td class="mycolor2"><?= $row["email"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Profile:</td>
		<td class="mycolor2"><?= $row["nm_profile"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Status:</td>
		<td class="mycolor2"><? if($row["active"]==0) echo "Not Active"; else echo "Active"; ?></td>
	</tr>
	</table>
	</center>
	<br>
</body>
</html>
