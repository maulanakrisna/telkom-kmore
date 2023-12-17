<?php

// Step 1
$judul = "Sharing Point Individu";
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
#$query = "SELECT a.nik AS niknya, b.nama, SUM(a.poin) AS nilai, c.nm_loker FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE a.nik<>'602217' GROUP BY a.nik ORDER BY nilai DESC, id_inv_status";
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
$query  = "SELECT a.nik AS niknya, b.nama, c.nm_loker, ".
		  "SUM(IF(tipe=1,poin,IF(tipe=2,poin,0))) bidang, ".
		  "SUM(IF(tipe=3,poin,0)) rdc, SUM(IF(tipe=4,poin,0)) ext, ".
		  "SUM(IF(tipe=5,poin,0)) part, SUM(poin) total ".
		  "FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker ".
		  "WHERE a.nik <> '602217' AND poin IS NOT NULL AND a.id_know IN (";
$query .= "SELECT id_know FROM knowledge WHERE DATE(t_mulai) BETWEEN DATE('$tmulai') AND DATE('$takhir') ORDER BY t_mulai";
$query .= ") GROUP BY a.nik ORDER BY total DESC";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);

echo "<h3>$judul</h3>";
$tmulai = date("d-m-Y", mktime(0, 0, 0, 1, 1, date("y")));
$takhir = date("d-m-Y");
// periode
echo "<table class='spacer' width='750' border='0'><tr>";
?>
	<td>
		<form action="?mn=2" method="post">
		<label for="start-date">Period:&nbsp;</label><input name="start-date" id="start-date" class="date-pick" readonly value="<? if (empty($_REQUEST["start-date"])) echo $tmulai; else echo $_REQUEST["start-date"]; ?>"/>
		<label for="end-date">to:&nbsp;&nbsp;</label><input name="end-date" id="end-date" class="date-pick" readonly value="<? if (empty($_REQUEST["end-date"])) echo $takhir; else echo $_REQUEST["end-date"]; ?>"/>
		<input type="submit" value="&nbsp;Go&nbsp;">
		<form>
	</td>
<?
echo "<td align='right'><!-- <a href='report_point_individu_p.php' title='Print Preview'>Print</a> --></td></tr></table>";
echo "<center>";

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>NIK</th><th>Nama</th><th>Loker</th><th>Sharing<br>Bidang</th>".
	 "<th>Sharing<br>Internal</th><th>Include<br>External</th><th>Participant</th><th>Point</th></tr>";
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
		<!-- <td><A HREF="#?id=<?= $row["id_know"]; ?>&mn=1" title="Sharing Point Individu Detail" class="thickbox"><?= $row["niknya"]; ?></A></td> -->
		<td><a href="report_point_brdown.php?nik=<?= $row["niknya"]; ?>&height=400&width=750" title="<?= $row["nama"]; ?> Sharing Point Breakdown" class="thickbox"><?= $row["niknya"]; ?></a></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></td>
		<td align="right"><?= $row["bidang"]; ?></td>
		<td align="right"><?= $row["rdc"]; ?></td>
		<td align="right"><?= $row["ext"]; ?></td>
		<td align="right"><?= $row["part"]; ?></td>
		<? if(isset($row["total"])) { ?>
		<td align="right"><?= $row["total"]; ?></td>
		<? } ?>
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
	echo "<tr><td colspan='9' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
