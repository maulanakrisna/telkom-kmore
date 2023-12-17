<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<style type="text/css" media="screen">
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
$judul = "Knowledge Map Sharing Detail";
include ("include/convertdatetime.php");
require_once ("include/dbcon.php");
$recordsPerPage = 15;
$pageNum = $_REQUEST['p'];
if (empty($pageNum))
{
	$offset = 0;
	$pageNum = 1;
}
else
{
	$offset = ($pageNum-1) * $recordsPerPage;
}

// Step 2
$query  = "SELECT nm_map FROM knowledge_map WHERE id_map=".$_REQUEST["id"];
$result = mysql_query($query) or die('Mysql Err. 2');
$row = mysql_fetch_array($result);
$filter = $row["nm_map"];

$query  = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker FROM knowledge a JOIN user b ON a.nik=b.nik ";
$query .= "JOIN loker c ON b.id_loker=c.id_loker WHERE sharing_status='6' AND id_map=".$_REQUEST["id"];
$query .= " AND DATE(t_mulai) BETWEEN DATE('".$_REQUEST["d1"]."') AND DATE('".$_REQUEST["d2"]."')";
#echo "$query<br>";

$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
//echo "<h3>$judul</h3>";

echo "<b>Knowledge Map: $filter<br></b>";
echo "<center>";
# print table header
echo "<table id='myTable' border='0' cellpadding='0' cellspacing='1' width='730'>";
echo "<thead><tr><th>No.</th><th>Tanggal/Jam</th><th>Judul</th><th>Pembicara</th><th>Bidang</th></tr></thead>";
echo "<tbody>";

if ($num <> 0)
{
	// print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<td width="17%"><?= ConvertJustDate($row["t_mulai"]); ?><br>
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td width="39%"><?= $row["judul"]; ?></td>
		<td width="20%"><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></td>
	</tr>
<?
		$no++;
	}
	echo "</tbody>";
	echo "</table><p>";

	// Step 3
	$result = mysql_query($query) or die('Mysql Err. 2');
	$numrows = mysql_num_rows($result);
	$maxPage = ceil($numrows/$recordsPerPage);
	$file = '?mn=4';

	// previous page
	if ($pageNum > 1)
	{
		$previous = $pageNum-1;
		echo " <a href=\"$file&p=1'\"><< First</a> | <a href=\"$file&p=$previous\">< Previous</a> | ";
	}

	// Google Style...
	// first number
	$number = ($pageNum > 3 ? " ... " : " ");
	for ($i = $pageNum-2; $i <= $pageNum; $i++)
	{ 
		if ($i < 1)
			continue;
		if ($i == $pageNum)
		{
			$number .= "<b>$i</b> ";
		}
		else
		{
			$number .= "<a href=\"$file&p=$i\">$i</a> ";
		}
	}

	// middle number
	// command below is just in case if this page include from other file PHP
	$page = substr($page,strlen($page),-2);
	$number .= " <b>$page</b> ";
	for ($i = $pageNum+1; $i <= ($pageNum+4); $i++)
	{ 
		if ($i > $maxPage)
			break;
		$number .= "<a href=\"$file&p=$i\">$i</a> ";
	}

	// last number
	$number .= ($pageNum+2 < $maxPage ? " ... <a href=\"$file&p=$maxPage\">$maxPage</a> " : " ");

	echo $number;

	// next page
	if ($pageNum < $maxPage)
	{
		$next = $pageNum+1;
		echo "<a href=\"$file&p=$next\"> Next ></a> | <a href=\"$file&p=$maxPage\"> Last >></a> ";
	}
}
else
{		
	echo "<tr><td colspan='5' align='center'>Data tidak ada</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "<br><br><input type='submit' value='Close' onclick='self.parent.tb_remove();'><br>";
echo "</center>";
?>
</body>
</html>