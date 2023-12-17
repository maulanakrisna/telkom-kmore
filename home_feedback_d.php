<?
session_start();
/*
-----
file asal  : sharing_schedule.php, sharing_req_his.php, newsticker.php
file tujuan: save2db.php?sw=16
-----
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>KMORE - Sharing Knowledge Feedback</title>
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<link type="text/css" href="style/master.css" rel="stylesheet"/>
<style type="text/css">
table tr td {
	vertical-align: top;
	border: 0px solid #408080;
}
td {
	padding: 0 3;
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
a.confirm:link { text-decoration: none; color: #0000FF; }
}
</style>
</head>

<?
include("include/dbcon.php");
#include ("getid_linux.php"); //dits server to db dits server
#include ("getid_wind.php"); //localhost to db dits server
#include ("getid_wind_tester.php"); //localhost to db dits server
// cek daftar hadir
$q = "SELECT * FROM sharing_activity WHERE id_know = '$_REQUEST[idk]' AND nik = '$_SESSION[nik_login]'";
#echo "$q<br>";
$result = mysql_query($q);
$num = mysql_num_rows($result);
if ($num<>0)
{
	// you have invititation
	$r = mysql_fetch_array($result);
	$id_confirm = $r[id_confirm];
	$id_inv_status = $r[id_inv_status];
}
// else you can request to attend

// request sharing knowledge detail
$q = "SELECT a.*, b.nama, c.nama AS sip, d.nm_map FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik JOIN knowledge_map d ON a.id_map=d.id_map WHERE a.id_know='$_REQUEST[idk]'";
#echo "$q<br>";
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$nik_nya = $row["nik"];

// get invited bidang
// e.g. "140,150,160"
$inv_bid = $row[inv_bidang];
$find_bid = str_replace(",", "','",$inv_bid);
#echo "\$inv_bid:$inv_bid<br>";

$nm_bidang = array();
$q = "SELECT nm_loker FROM loker WHERE id_loker IN ('$find_bid')";
#echo "\$q: $q<br>";
$res = mysql_query($q);
while ($r = mysql_fetch_array($res))
{
	$nm_bidang[] = $r[nm_loker];
}
$inv_bid = implode(", ",$nm_bidang);


// show sharing member
if (!empty($row[member]))
{
	$nik_member = $row[member];
	$find_nik = str_replace(",", "','",$nik_member);
	$members = array();
	$q = "SELECT nama FROM user WHERE nik IN ('$find_nik')";
	#echo "\$q: $q<br>";
	$result = mysql_query($q);
	while ($r = mysql_fetch_array($result))
	{
		$members[] = $r[nama];
	}
	$inmember = implode(", ",$members);
}
?>

<body>
<br>
<?#= "\$_SESSION[nik_login]: $_SESSION[nik_login]<br>"; ?>
<form action="save2db.php" method="post">
<table width="650" cellpadding='5' cellspacing='1' align="center">
	<tr>
		<td class="mycolor">Title/Theme:</td>
		<td class="mycolor2" width="75%"><?= $row["judul"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Knowledge Category:</td>
		<td class="mycolor2"><?= $row["nm_map"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Sharing type:</td>
		<td class="mycolor2"><?= $row["jenis"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Contributor:</td>
		<td class="mycolor2"><?= $row["nama"]; ?></td>
	</tr>
	<?
	if (!empty($row[member])) {
	?>
	<tr>
		<td class="mycolor">Team member:</td>
		<td class="mycolor2"><?= $inmember; ?></td>
	</tr>
	<?
	}
	include ("include/convertdatetime.php");
	?>
	<tr>
		<td class="mycolor">Date:</td>
		<td class="mycolor2"><?= tampilkan_waktunya($row["t_mulai"]); ?></td>
	</tr>
	<tr>
		<td class="mycolor">Time:</td>
		<td class="mycolor2"><?= substr($row["t_mulai"],11,5)."&nbsp;s/d&nbsp;".substr($row["t_akhir"],11,5); ?></td>
	</tr>
	<tr>
		<td class="mycolor">Venue:</td>
		<td class="mycolor2"><?= $row["lokasi"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Unit:</td>
		<td class="mycolor2"><?= $row["unitkerja"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Abstraction:</td>
		<td class="mycolor2"><?= $row["abstraksi"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Expectation:</td>
		<td class="mycolor2"><?= $row["harapan"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Reference:</td>
		<td class="mycolor2"><?= $row["referensi"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Target Audience:</td>
		<td class="mycolor2"><?= $inv_bid; ?></td>
	</tr>
	<tr>
		<td class="mycolor">External Audience:</td>
		<td class="mycolor2"><?= $row["ext_audience"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Comment:</td>
		<td class="mycolor2"><textarea name="komen" rows="5" cols="97"></textarea></td>
	</tr>
</table>

<center>
<br>
<table border="0" style="padding: 2px">
<tr>
<td>
	<input type="submit" name="submit" value="Submit">
	<input type="hidden" name="idk" value="<?= $_REQUEST[idk]; ?>">
	<input type="hidden" name="sw" value="17">
</table>
</form>
</td>
<br>
</center>
</body>
</html>
