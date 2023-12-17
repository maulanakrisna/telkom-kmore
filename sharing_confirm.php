<? session_start(); ?>
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
include("include/dbcon.php");

// request sharing knowledge detail
$q = "SELECT a.*, b.nama, c.nama AS sip, d.nm_map FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik JOIN knowledge_map d ON a.id_map=d.id_map WHERE a.id_know='$_REQUEST[idk]'";
#echo "$q<br>";
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$nik_nya = $row["nik"];

# get invited bidang
// e.g. "140,150,160"
$inv_bid = $row[inv_bidang];
$find_bid = str_replace(",", "','",$inv_bid);
#echo "\$inv_bid:$inv_bid<br>";

$nm_bidang = array();
$q = "SELECT nm_loker FROM loker WHERE id_loker IN ('$find_bid')";
#echo "\$q: $q<br>";
$res = mysql_query($q);
while ($r = mysql_fetch_array($res)) {
	$nm_bidang[] = $r[nm_loker];
}
$inv_bid = implode(", ",$nm_bidang);

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
	<table width="650" cellpadding='5' cellspacing='1' align="center" class="detail">
	<tr>
		<td class="mycolor">Judul/Tema:</td>
		<td class="mycolor2" width="75%"><?= $row["judul"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Kategori Knowledge:</td>
		<td class="mycolor2"><?= $row["nm_map"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Jenis Materi:</td>
		<td class="mycolor2"><?= $row["jenis"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Pembicara:</td>
		<td class="mycolor2"><?= $row["nama"]; ?></td>
	</tr>
	<?
	if (!empty($row[member]))
	{
	?>
	<tr>
		<td class="mycolor">Pembicara Lain:</td>
		<td class="mycolor2"><?= $inmember; ?></td>
	</tr>
	<?
	}
	include ("include/convertdatetime.php");
	?>
	<tr>
		<td class="mycolor">Hari & Tanggal:</td>
		<td class="mycolor2"><?= tampilkan_waktunya($row["t_mulai"]); ?></td>
	</tr>
	<tr>
		<td class="mycolor">Jam:</td>
		<td class="mycolor2"><?= substr($row["t_mulai"],11,5)."&nbsp;s/d&nbsp;".substr($row["t_akhir"],11,5); ?></td>
	</tr>
	<tr>
		<td class="mycolor">Lokasi Ruangan:</td>
		<td class="mycolor2"><?= $row["lokasi"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Unit Kerja:</td>
		<td class="mycolor2"><?= $row["unitkerja"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Abstraksi:</td>
		<td class="mycolor2"><?= $row["abstraksi"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Harapan:</td>
		<td class="mycolor2"><?= $row["harapan"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Referensi:</td>
		<td class="mycolor2"><?= $row["referensi"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Audience:</td>
		<td class="mycolor2"><?= $inv_bid; ?></td>
	</tr>
	</table>
	<br>
	<!-- <form action="notification.php" method="post"> -->
	<?
	$mulai = date("d-m-Y H:i:s", strtotime($row["t_mulai"]));
	$now = date("d-m-Y H:i:s", strtotime("now"));
	#echo $mulai." - ".$now."<br>";
	if (strtotime($mulai) > strtotime($now ))
	{
		// cek status di daftar hadir
		$q = "SELECT * FROM sharing_activity WHERE id_know = '$_REQUEST[idk]' AND nik = '$_SESSION[nik_login]'";
		#echo "$q<br>";
		$result = mysql_query($q);
		$r = mysql_fetch_array($result);
		$konfirmasi = $r[id_confirm];

		if ($konfirmasi==0)
		{
	?>
	<center>
	<table border="0" style="padding: 2px">
	<tr>
	<td>
	<form action="save2db.php" method="post">
		<label>Confirmation:</label>
		<select name="id_confirm">
	<?
			$tSQL = "SELECT * FROM confirm LIMIT 0,3"; 
			$result = mysql_query($tSQL);
			while ($listrow = mysql_fetch_array($result))
			{
	?>
			<option value="<?= $listrow["id_confirm"]; ?>"><? echo $listrow["nm_confirm"]; ?></option>
	<?
			}
	?>
		</select>
		<input type="submit" name="submit" value="Submit">
		<input type="hidden" name="idk" value="<?= $_REQUEST[idk]; ?>">
		<input type="hidden" name="sw" value="14">
	</form>
	</td>
	<td>&nbsp;</td>
	<!--
	<td>
	<input type="submit" name="submit" value="Close" onclick="self.parent.tb_remove();">
	</td>
	-->
	</tr>
	</table>
	</center>
	<?
		}
		else
		{
			$tSQL = "SELECT nm_confirm FROM confirm WHERE id_confirm='$konfirmasi'"; 
			$result = mysql_query($tSQL);
			$r = mysql_fetch_array($result);
			echo "<center>You have <b>$r[nm_confirm]</b> this sharing knowledge invitation</center>";
			#echo "<br>";
			#echo "<center><input type='submit' value='Close' onclick='self.parent.tb_remove();'></center>";
			#echo "<br>";
		}
	}
	else
	{
		#echo "<br>";
		#echo "<center><input type='submit' value='Close' onclick='self.parent.tb_remove();'></center>";
		#echo "<br>";
	}
	?>
</body>
</html>
