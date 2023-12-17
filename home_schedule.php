<?php

// Step 1
$judul = "Schedule";
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
$query  = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker, d.nm_map FROM knowledge a JOIN user b ON a.nik=b.nik ";
$query .= "JOIN loker c ON b.id_loker=c.id_loker JOIN knowledge_map d ON a.id_map=d.id_map ";
$query .= "WHERE sharing_status='3' AND a.t_mulai > NOW()";

if (!empty($_REQUEST['keyword']))
	$query .= " AND a.abstraksi LIKE '%".$_REQUEST[keyword]."%'";

$query .= " ORDER BY a.t_mulai DESC";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

# search table
?>
<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=1" method="post">
<input type="text" size="25" name="keyword" value="<?= $_REQUEST[keyword] ?>">
<input type="submit" name="submit" value="Cari" class="button">
</form><center>
<?

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th>";
echo "<th>Date/Time&nbsp;<a href='?mn=1&sort=t_mulai&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=1&sort=t_mulai&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Title/Theme&nbsp;<a href='?mn=1&sort=judul&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=1&sort=judul&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Contributor&nbsp;<a href='?mn=1&sort=nama&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=1&sort=nama&by=DESC'><img src='images/down.png' border='0'></a>";
#echo "</th><th>Bidang&nbsp;<a href='?mn=1&sort=nm_loker&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=1&sort=nm_loker&by=DESC'><img src='images/down.png' border='0'></a></th></tr></thead>";
echo "</th><th>Bidang</th></tr></thead>";
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
		<td width="39%"><a href="sharing_detail.php?idk=<?= $row["id_know"]; ?>&mn=1&height=400&width=700" title="Sharing Knowledge Detail" class="thickbox"><?= $row["judul"]; ?></a><br><?= $row['abstraksi'];?></td>
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
	$file = '?mn=1';

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
	echo "<tr><td colspan='5' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
