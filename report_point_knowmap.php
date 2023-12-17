<?php

// Step 1
$judul = "Knowledge Map Sharing";
require_once ("include/dbcon.php");
$recordsPerPage = 10;
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
$query  = "SELECT a.id_map AS idnya,a.sharing_status,b.nm_map,COUNT(*) AS jml FROM knowledge a JOIN knowledge_map b ON a.id_map=b.id_map ";
$query .= "WHERE sharing_status='6' AND DATE(t_mulai) BETWEEN DATE('$tmulai') AND DATE('$takhir') GROUP BY a.id_map";
//$query .= "WHERE DATE(t_mulai) BETWEEN DATE('$tmulai') AND DATE('$takhir') GROUP BY a.id_map";
#echo "$query<br>";

$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);

echo "<h3>$judul</h3>";
$mulai = date("d-m-Y", mktime(0, 0, 0, 1, 1, date("y")));
$akhir = date("d-m-Y");
// periode
?>
<table class="spacer" width="750" border="0">
	<td>
		<form action="?mn=4" method="post">
		<label for="start-date">Period:&nbsp;</label><input name="start-date" id="start-date" class="date-pick" readonly value="<? if (empty($_REQUEST["start-date"])) echo $mulai; else echo $_REQUEST["start-date"]; ?>"/>
		<label for="end-date">to:&nbsp;&nbsp;</label><input name="end-date" id="end-date" class="date-pick" readonly value="<? if (empty($_REQUEST["end-date"])) echo $akhir; else echo $_REQUEST["end-date"]; ?>"/>
		<input type="submit" value="&nbsp;Go&nbsp;">
		<form>
	</td>
<?
# print table header
echo "<center>";
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>Knowledge Map</th><th>Sharing</th></tr></thead>";
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
		<td><A HREF="report_point_knowmap_d.php?id=<?= $row["idnya"]; ?>&d1=<?= $tmulai; ?>&d2=<?= $takhir; ?>&mn=1&height=400&width=760" title="<?= $judul; ?> Detail" class="thickbox"><?= $row["nm_map"]; ?></A></td>
		<td align="right" width="10%"><?= $row["jml"]; ?></td>
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
	$file = 'report.php?mn=4';

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
	echo "<tr><td colspan='5' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
