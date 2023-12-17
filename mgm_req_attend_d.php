<?
/*
-----
file asal  : mgm_req_attend_d.php
file tujuan: save2db.php?mn=16
-----
*/
session_start();
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
	background: #F9F9F9;
}
</style>
</head>

<body>
<?
/*
if ($_SESSION['found']==0)
{
	echo "<br><br><center>Sorry, you are not allowed to approve request of attending because your session is expired! Please re-login to <a href='http://portal.telkom.co.id'>POINT</a></center><br><br>";
}
else
{
*/
	include("include/dbcon.php");

	// get daftar hadir
	#$q = "SELECT * FROM sharing_activity WHERE id_know = '$_REQUEST[id]' AND nik = '$_SESSION[nik_login]'";
	// get Request to Attend
	$q = "SELECT * FROM req_to_attend WHERE id_know = '$_REQUEST[idk]' AND nik = '$_REQUEST[nik]'";
	#echo "$q<br>";
	$result = mysql_query($q);
	$r = mysql_fetch_array($result);
	$konfirmasi=$r[id_confirm];
	#$id_inv_status=$r[id_inv_status];

	// request sharing knowledge detail
	$q = "SELECT a.*, b.nama, c.nama AS sip, d.nm_map FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik JOIN knowledge_map d ON a.id_map=d.id_map WHERE a.id_know='$_REQUEST[idk]'";
	#echo "$q<br>";
	$result = mysql_query($q);
	$row = mysql_fetch_array($result);
	$nik_nya = $row["nik"];

	// show sharing member
	if (!empty($row[member])) {
		$nik_member = $row[member];
		$find_nik = str_replace(",", "','",$nik_member);
		$members = array();
		$q = "SELECT nama FROM user WHERE nik IN ('$find_nik')";
		#echo "\$q: $q<br>";
		$result = mysql_query($q);
		while ($r = mysql_fetch_array($result)) {
			$members[] = $r[nama];
		}
		$inmember = implode(", ",$members);
	}

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
?>

<br>
	<!-- <table width="100%" cellpadding='5' cellspacing='0' align="center" rules='rows' frame='below'> -->
	<table width="600" cellpadding='5' cellspacing='1' align="center">
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
	if (!empty($row["member"])) {
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
	</table>
	<br>

	<center>
	<table border="0" style="padding: 2px">
	<tr>
	<td>
	<form action="save2db.php" method="post">
	<label>Request:</label>
	<select name="id_confirm">
	<?
		$tSQL = "SELECT * FROM confirm WHERE id_confirm IN ('6','4')"; //6: Approve, 4: Reject
		$result = mysql_query($tSQL);
		while ($listrow = mysql_fetch_array($result)) {
	?>
		<option value="<?= $listrow["id_confirm"]; ?>"><? echo $listrow["nm_confirm"]; ?></option>
	<?
		}
	?>
	</select>
	<input type="submit" name="submit" value="Submit">
	<input type="hidden" name="idk" value="<?= $_REQUEST[idk]; ?>">
	<input type="hidden" name="nik_commit" value="<?= $_SESSION[nik_login]; ?>">
	<input type="hidden" name="nik" value="<?= $_REQUEST[nik]; ?>">
	<input type="hidden" name="sw" value="23">
	</form>
	</td>
	<td>or</td>
	<td>
		<input type="submit" value="Cancel" onclick="tb_remove();">
	</td>
	</tr>
	</table>
	</center>
<? #} ?>
</body>
</html>
