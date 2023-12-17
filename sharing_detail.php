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
<title>KMORE - Sharing Knowledge Detail</title>
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
//include ("getid_wind_tester.php"); //localhost to db dits server
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
		<td class="mycolor2"><textarea rows="5" cols="97" readonly><?= $row["ext_audience"]; ?></textarea></td>
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
		<td class="mycolor">Note:</td>
		<td class="mycolor2">
	<?
		$i = 0;
		while ($r=mysql_fetch_array($result))
		{
			$yournotes = str_replace("\r", "<br/>", $r[notes]);
			echo tampilkan_waktunya($r[created])."&nbsp;".substr($r[created],11,8)."<br>";
			echo "$r[nik] - $r[nama]<br>";
			echo "$yournotes<br>";
			$i++;
			if ($i<$n)
				echo "----------<br>";
		}
	?>
		</td>
	</tr>
	<?
	}
	?>
</table>

<br>
<center>

<?
if ($_SESSION[nik_login]==$nik_nya)
{	// This is your Sharing Knowldge
	echo "<input type='submit' value='Close' onclick='tb_remove();'>";
}
else
{
	#echo "\$id_confirm: $id_confirm AND \$id_inv_status: $id_inv_status<br>";
	if ($num == 0)
	{	// you are not listed in invitation, so you can request to attend

		#if (strlen($_SESSION['nik_login'])<>0) {
		if (strlen($_SESSION['nama'])==0) {
			echo "Sorry, you are not allowed to request to attend because your session is expired! Please re-login to <a href='http://portal.telkom.co.id'>POINT</a>";
		}
		else
		{
?>
<table border="0" style="padding: 2px">
<tr>
<td>
<form action="save2db.php" method="post">
	<input type="submit" name="submit" value="Request To Attend">
	<input type="hidden" name="idk" value="<?= $_REQUEST[idk]; ?>">
	<input type="hidden" name="sw" value="16">
	<input type="hidden" name="nik" value="<?= $_SESSION[nik_login]; ?>">
</form>
</td>
<!--
<td>
	#<input type="submit" value="Close" onclick="self.parent.tb_remove();">
	<input type="submit" value="Close" onclick="tb_remove();">
</td>
-->
</tr>
</table>
<?
		}
	}
	else
	{	// you are listed in invitation
		if ($id_inv_status==3 && $id_confirm==0)
		{
			#echo "Anda telah diundang, silakan klik <a href='index.php?mn=2' class='confirm'>disini</a>";
			echo "Anda telah diundang, silakan klik <a href='sharing_confirm.php?idk=$_REQUEST[idk]&mn=1&height=400&width=700' title='Confirm The Invitation' class='thickbox'>disini</a>";
		}
		// you have been confirmed
		elseif ($id_inv_status==3 && $id_confirm>0)
		{
			$q = "SELECT nm_confirm_id FROM confirm WHERE id_confirm='$id_confirm'";
			#echo "\$q: $q<br>";
			$result = mysql_query($q);
			$r = mysql_fetch_array($result);
			echo "Anda telah <b>$r[nm_confirm_id]</b> pada sesi sharing ini";
		}
		// you have been requested to attend but not have approval
		elseif ($id_inv_status==4 && $id_confirm==5)
		{
			echo "Anda telah meminta <b>ijin untuk hadir</b> pada sesi sharing ini";
		}
		elseif ($id_inv_status==4 && $id_confirm==1)
		{
			echo "Ijin untuk hadir Anda telah <b>disetujui</b>";
		}
		#echo "<br><br>";
		#echo "<center><input type='submit' value='Close' onclick='self.parent.tb_remove();'></center>";
	}
}
?>
<br><br>
</center>
</body>
</html>
