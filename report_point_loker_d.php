<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>KMORE - Sharing Knowledge Detail</title>
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<link type="text/css" href="style/master.css" rel="stylesheet"/>
</head>
<body>

<?php

// Step 1
require_once ("include/dbcon.php");
$q = "SELECT nm_loker FROM loker WHERE id_loker='$_REQUEST[idb]'";
#echo "$q<br>";
query_sql($q,$res);
$r = mysql_fetch_array($res);
$judul = "Sharing Point ".$r['nm_loker'];

// Step 2
/*
$query  = "SELECT a.nik AS niknya, b.nama, c.id_loker, c.nm_loker, SUM(a.poin) AS nilai FROM sharing_activity a ";
$query .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE id_inv_status < 3 AND b.id_bidang='$_REQUEST[idb]' AND poin IS NOT NULL AND c.id_loker='".$_REQUEST["idb"]."' ";
*/
/*
$query  = "SELECT a.nik AS niknya, b.nama, c.id_loker, c.nm_loker, SUM(a.poin) AS nilai FROM sharing_activity a ";
$query .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE id_inv_status < 3 AND b.id_bidang='$_REQUEST[idb]' AND poin IS NOT NULL ";
$query .= "GROUP BY niknya ORDER BY nilai DESC, id_inv_status";
*/

$query  = "SELECT a.nik,b.nama,c.nm_loker,a.poin FROM sharing_activity a ";
$query .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker ";
$query .= "WHERE a.id_loker='$_REQUEST[idb]' AND a.id_inv_status < '3' AND a.poin IS NOT NULL ORDER BY a.poin DESC";

/*having sum(poin) is not null;*/
#echo "$query<br>";
$result = mysql_query($query) or die('Mysql Err. 1');
$num = mysql_num_rows($result);

echo "<center>";
if ($num <> 0)
{
	#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
	echo "<table class='spacer' border='0' align='center'><tr><td colspan='2'><h3>$judul</h3></td></tr>";
	echo "</td></tr>";

	# print table header
	echo "</table>";
	echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1' width='730'>";
	echo "<thead><tr><th>No.</th><th>Nik</th><th>Nama</th><th>Loker</th><th>Nilai</th></tr></thead>";
	echo "<tbody>";

	# print table rows
	$no =  $offset+1;
	$total = 0.00;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top" <? if ($no<11) echo "bgcolor='#99CCFF'"; ?>>
		<td align="right" width="10px"><?= $no; ?>.</td>
		<td><?= $row["nik"]; ?></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></td>
		<td align="right"><?= $row["poin"]; ?></td>
	</tr>
<?
		$total += $row["poin"];
		$no++;
	}
	echo "<tr><td></td><td colspan='3'>Total:</td><td align='right'><b>".number_format($total,2)."</b></td></tr>";
	echo "</tbody>";
	echo "</table><p>";

}
else
{		
	echo "<br><br><br><br>Sorry, no sharing knowledge point at this time!<br><br>";
}
?>
<input type="submit" name="submit" value="Close" onclick="self.parent.tb_remove();">
</center>
</body>
</html>
