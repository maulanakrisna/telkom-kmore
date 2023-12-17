<?php

// Step 1
$judul = "Sharing Point Individu";
require_once ("include/dbcon.php");

// Step 2
#$query = "SELECT tipe, SUM(poin) AS nilai FROM sharing_activity WHERE nik='$_SESSION[nik_login]' GROUP BY tipe";
if (empty($_REQUEST['start-date']))
{
	$tmulai = date("Y-m-d", mktime(0, 0, 0, 1, 1, date("y")));
	$takhir = date("Y-m-d");
}
else
{
	$tmulai = date("Y-m-d", strtotime($_REQUEST["start-date"]));
	$takhir = date("Y-m-d", strtotime($_REQUEST["end-date"]));
}

$query  = "SELECT tipe, SUM(poin) AS nilai FROM sharing_activity WHERE nik='$_SESSION[nik_login]' AND id_know IN (";
$query .= "SELECT id_know FROM knowledge WHERE DATE(t_mulai) BETWEEN DATE('$tmulai') AND DATE('$takhir')) ";
$query .= "GROUP BY tipe";
#echo "$query<br>";

$result = mysql_query($query) or die('Mysql Err. 1');
$num = mysql_num_rows($result);

echo "<h3>My Points</h3>";
$tmulai = date("d-m-Y", mktime(0, 0, 0, 1, 1, date("y")));
$takhir = date("d-m-Y");
// periode
?>
<table class='spacer' width='750' border='0'>
	<tr>
		<td>
		<form action="?mn=1" method="post">
		<label for="start-date">Period:&nbsp;</label><input name="start-date" id="start-date" class="date-pick" readonly value="<? if (empty($_REQUEST["start-date"])) echo $tmulai; else echo $_REQUEST["start-date"]; ?>"/>
		<label for="end-date">to:&nbsp;&nbsp;</label><input name="end-date" id="end-date" class="date-pick" readonly value="<? if (empty($_REQUEST["end-date"])) echo $takhir; else echo $_REQUEST["end-date"]; ?>"/>
		<input type="submit" value="&nbsp;Go&nbsp;">
		<form>
		</td>
	<td align='right'><a href='report_point_mine_p.php' title='Print Preview' class='thickbox'>Print</a></td></tr>
</table>
<?
if ($num==0)
{
	echo "<br><br>Data tidak ada<br><br>";
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

	<!-- <table width="500px" cellpadding='5' cellspacing='0' rules='rows' frame='below'> -->
	<table class='tablesorter' border='0' cellpadding='0' cellspacing='1'>
	<tr>
		<td class="mycolor">Nama:</td>
		<td class="mycolor2" width="75%"><?= $_SESSION["nama"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">NIK:</td>
		<td class="mycolor2"><?= $_SESSION["nik_login"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Lokasi Kerja:</td>
		<td class="mycolor2"><?= $_SESSION["nm_loker"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Bidang:</td>
		<td class="mycolor2"><?= $nilai_bid; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Wilayah:</td>
		<td class="mycolor2"><?= $nilai_rdc; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Wilayah dan external:</td>
		<td class="mycolor2"><?= $nilai_rdc_e; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Participant:</td>
		<td class="mycolor2"><?= $nilai_part; ?></td>
	</tr>
	<tr>
		<td class="mycolor">Total Points:</td>
		<td class="mycolor2"><?= number_format($nilai_bid + $nilai_rdc + $nilai_rdc_e + $nilai_part,2); ?></td>
	</tr>
	</table>
<?
}
?>