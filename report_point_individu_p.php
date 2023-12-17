<?
session_start();
include ("include/convertdatetime.php");
if (!isset($_SESSION['nik_login']))
{
	Header("Location:login.php");
}
else
{
	$_SESSION['page']=3;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Report Point Inividu</title>
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
$judul = "Report Sharing Point Individu";
require_once ("include/dbcon.php");
$recordsPerPage = 30;
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
$query = "SELECT a.nik AS niknya, b.nama, c.nm_loker,".
		 "SUM(IF(poin=10,10,IF(poin=15,15,0))) bidang, ".
		 "SUM(IF(poin=20,20,0)) rdc, SUM(IF(poin=30,30,0)) ext, ".
		 "SUM(IF(poin=2,2,0)) part, SUM(poin) total ".
		 "FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker ".
		 "WHERE a.nik <> '602217' GROUP BY a.nik ORDER BY total DESC";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<center>";
echo "<h3>$judul</h3>";

# print table header
echo "<table width='760'>";
echo "<thead><tr><th>No.</th><th>Nik</th><th>Nama</th><th>Loker</th><th>Sharing<br>Bidang</th>".
	 "<th>Sharing<br>RDC</th><th>Sharing<br>External</th><th>Participant</th><th>Jumlah<br>Point</th></tr>";
echo "</thead>";
echo "<tbody>";

if ($num <> 0)
{
	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<td><?= $row["niknya"]; ?></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></td>
		<td align="right"><?= $row["bidang"]; ?></td>
		<td align="right"><?= $row["rdc"]; ?></td>
		<td align="right"><?= $row["ext"]; ?></td>
		<td align="right"><?= $row["part"]; ?></td>
		<td align="right"><? if(empty($row["total"])) echo "0"; else echo $row["total"]; ?></td>
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
	$file = '?mn=2';

	# previous page
	if ($pageNum > 1)
	{
		$previous = $pageNum-1;
		echo " <a href=\"$file&p=1'\"><< First</a> | <a href=\"$file&p=$previous\">< Previous</a> | ";
	}

	# Google Style...
	# first number
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

	# middle number
	# command below is just in case if this page include from other file PHP
	$page = substr($page,strlen($page),-2);
	$number .= " <b>$page</b> ";
	for ($i = $pageNum+1; $i <= ($pageNum+4); $i++)
	{ 
		if ($i > $maxPage)
			break;
		$number .= "<a href=\"$file&p=$i\">$i</a> ";
	}

	# last number
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
	echo "<tr><td colspan='9' align='center'>Sorry, no individual sharing point report at this time!</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>

</body>
</html>
