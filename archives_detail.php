<?
/*
-----
file asal  : sharing_schedule.php, sharing_req_his.php, newsticker.php
file tujuan: save2db.php?sw=16
-----
*/
session_start();
/*
if (!isset($_SESSION['nik_login']))
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
a.thick:link { text-decoration: none; color: #0000FF; }
a.thick:visited { text-decoration: none; color: #0000FF; }
a.thick:hover { text-decoration: underline; color: #FF0000; }
}
</style>
</head>

<?
include("include/dbcon.php");
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
$q  = "SELECT a.*, b.nama, b.id_bidang, e.nm_loker, c.nama AS sip, d.nm_map FROM knowledge a ";
$q .= "JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik JOIN knowledge_map d ON a.id_map=d.id_map JOIN loker e on a.unitkerja=e.nm_loker ";
$q .= "WHERE a.id_know='$_REQUEST[idk]'";
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
$inv_nmbid = implode(", ",$nm_bidang);


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
<!-- <table width="100%" cellpadding='5' cellspacing='0' align="center" rules='rows' frame='below'> -->
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
		<td class="mycolor2"><?= $row["nm_loker"]; ?></td>
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
		<td class="mycolor2"><?= $inv_nmbid; ?></td>
	</tr>
	<tr>
		<td class="mycolor">External Audience:</td>
		<td class="mycolor2"><textarea rows="5" cols="97" readonly><?= $row["ext_audience"]; ?></textarea></td>
	</tr>
	<tr>
		<td class="mycolor">Attendance List:</td>
		<td class="mycolor2"><a href="sharing_attend.php?idk=<?= $_REQUEST[idk]; ?>&height=500&width=780" title="Daftar Hadir" class="thickbox"><img src="images/clipboard.gif" border="0"></a></td>
	</tr>
	<?
	if (isset($row[randomkey]))
	{
		$tSQL = "SELECT * FROM upload WHERE randomkey='".$row[randomkey]."'";
		#echo "$tSQL<br>";
		query_sql($tSQL,$result);
		// check if query-results are not empty
		if (mysql_num_rows($result) <> 0)
		{
	?>
	<tr>
		<td class="mycolor">File Download:</td>
		<td class="mycolor2">
	<?
			while ($r = mysql_fetch_array ($result))
			{
				#echo strstr($inv_bid,$_SESSION[id_bidang])."<br>";
				#if (strstr($inv_bid,$_SESSION[id_bidang]))
				/*
				if (strpos($inv_bid,$_SESSION[id_bidang]))
					echo "<A HREF='takefile.php?idk=$r[id]&ids=$_REQUEST[id]' class='menu'>$r[name]</A>&nbsp;";
				else
					echo "$r[name]&nbsp;";
				*/
				echo "<A HREF='takefile.php?idk=$r[id]' class='menu'>$r[name]</A>&nbsp;";
			}
		}
	}
	?>
		</td>
	</tr>
	<?
	$q = "SELECT a.*,b.nama FROM sharing_notes a JOIN user b ON a.nik=b.nik WHERE a.id_know='$_REQUEST[idk]'";
	#echo "$query<br>";
	$result = mysql_query($q) or die('Mysql Err. 1');
	$n = mysql_num_rows($result);

	if ($n > 0)
	{
	?>
	<tr>
		<td class="mycolor">Catatan:</td>
		<td class="mycolor2">
	<?
		while ($r=mysql_fetch_array($result))
		{
			$yournotes = str_replace("\r", "<br/>", $r->notes);
			echo tampilkan_waktunya($r[created])."&nbsp;".substr($r[created],11,8)."<br>";
			echo "$r[nik] - $r[nama]<br>";
			echo "$yournotes<br>";
			echo "----------<br>&nbsp;";
		}
	?>
		</td>
	</tr>
	<?
	}
	?>
</table>
<!--
<br>
<center>
<input type="submit" value="Close" onclick="self.parent.tb_remove();">
</center>
-->
</body>
</html>
