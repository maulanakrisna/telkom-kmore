<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>KMORE - Report Point Bidang</title>
<style type="text/css" media="print">
<!--
body
{
	margin: 0 Auto;
	text-align: center;
	width: 100%
	color : #000000; background : #ffffff; font-family : Tahoma, arial, Times, serif; font-size: 11pt;
}
table {
	border: 1px solid #666666;
	border-collapse: collapse;
	font-size: 10pt;
}
th { border: 1px solid #666666; background: #BEBEBE }
td { border: 1px solid #666666;	padding: 0 5; }
-->
</style>
</head>

<body>
<?php

// Step 1
$judul = "Sharing Point Bidang";
require_once ("include/dbcon.php");

// Step 2
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
$query  = "SELECT c.id_loker, c.acronym, c.nm_loker, SUM(a.poin) AS nilai FROM sharing_activity a ";
$query .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker ";
$query .= "WHERE id_bidang<>100 AND id_inv_status < 3 AND poin IS NOT NULL AND a.id_know IN (";
$query .= "SELECT id_know FROM knowledge WHERE DATE(t_mulai) BETWEEN DATE('$tmulai') AND DATE('$takhir') ORDER BY t_mulai";
$query .= ") GROUP BY c.nm_loker ORDER BY nilai DESC, id_inv_status";

#echo "$query<br>";
$result = mysql_query($query) or die('Mysql Err. 1');
$num = mysql_num_rows($result);

echo "<h3>$judul</h3>";
$mulai = date("d-m-Y", mktime(0, 0, 0, 1, 1, date("y")));
$akhir = date("d-m-Y");
// periode
echo "<table class='spacer' border='0'><tr>";
?>
	<td>
		<label for="start-date">Periode:&nbsp;</label><input name="start-date" id="start-date" class="date-pick" readonly value="<? if (empty($_REQUEST["start-date"])) echo $mulai; else echo $_REQUEST["start-date"]; ?>"/>
		<label for="end-date">s/d:&nbsp;</label><input name="end-date" id="end-date" class="date-pick" readonly value="<? if (empty($_REQUEST["end-date"])) echo $akhir; else echo $_REQUEST["end-date"]; ?>"/>
	</td>
<?
# print table header
echo "</table><center>";
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>Bidang</th><th>Nilai</th></tr></thead>";
echo "<tbody>";

if ($num <> 0)
{
	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top">
		<td align="right" width="5%"><?= $no; ?>.</td>
		<td><a href="report_point_bidang_d.php?id=<?= $row["id_loker"]?>&height=500&width=780" title="Sharing Point Bidang Detail" class="thickbox"><?= $row["nm_loker"]; ?></a></td>
		<td align="right" width="10%"><?= $row["nilai"]; ?></td>
	</tr>
<?
		$no++;
	}
	echo "</tbody>";
	echo "</table><p>";
	include ("report_show_graph.php");
}
else
{		
	echo "<tr><td colspan='9' align='center'>Data tidak ada</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
</body>
</html>