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
<title>KMORE - Sharing Point Individu Detail</title>
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
<body>
<?php

// Step 1
$judul = "Sharing Point Breakdown";
require_once ("include/dbcon.php");

// Step 2
$q  = "SELECT a.id_know,c.id_inv_status,c.nm_inv_status, b.judul, a.poin FROM sharing_activity a ";
$q .= "JOIN knowledge b ON a.id_know=b.id_know JOIN inv_status c ON a.id_inv_status=c.id_inv_status ";
$q .= "WHERE a.nik = '$_REQUEST[nik]' AND attend='1' ORDER BY a.id_inv_status, id_know";
#echo "$q<br>";
query_sql($q,$result);
echo "<table class='spacer' border='0' align='center'><tr><td><h3>$judul</h3></td></tr></table>";

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1' width='700'>";
echo "<thead><tr><th>No.</th><th>Status</th><th>Title/Theme</th><th>Point</th></tr></thead>";
echo "<tbody>";

# print table rows
$no = 1;
$total = 0.00;
while ($row = mysql_fetch_array($result))
{
?>
	<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<td><? if ($row["id_inv_status"]==3) echo "Audience"; else echo $row["nm_inv_status"]; ?></td>
		<td><?= $row["judul"]; ?></td>
		<td align="right"><?= $row["poin"]; ?></td>
	</tr>
<?
	$total += $row["poin"];
	$no++;
}
echo "<tr><td colspan='3'>Total:</td><td align='right'><b>".number_format($total,2)."</b></td></tr>";
echo "</tbody>";
echo "</table><p>";
?>
</body>
</html>
