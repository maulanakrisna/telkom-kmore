<?php

// Step 1
$judul = "My Sharing History";
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
$query  = "SELECT a.*, b.nama, c.nm_map, (SELECT poin FROM sharing_activity WHERE a.id_know=id_know AND nik='$_SESSION[nik_login]') AS point FROM knowledge a JOIN user b ON a.nik=b.nik JOIN knowledge_map c ON a.id_map=c.id_map WHERE a.nik='$_SESSION[nik_login]' AND a.sharing_status IN ('6','7')";

if (!empty($_REQUEST['keyword']))
	$query .= " AND a.abstraksi LIKE '%".$_REQUEST[keyword]."%'";

$query .= " ORDER BY a.t_mulai DESC";

#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

# search table
?>
<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=4" method="post">
<input type="text" size="25" name="keyword" value="<?= $_REQUEST[keyword] ?>">
<input type="submit" name="submit" value="Cari" class="button">
</form>
<?

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th>";
echo "<th>Date/Time&nbsp;<a href='?mn=4&sort=t_mulai&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=4&sort=t_mulai&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Title/Theme&nbsp;<a href='?mn=4&sort=judul&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=4&sort=judul&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Point&nbsp;<a href='?mn=4&sort=point&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=4&sort=point&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "</tr></thead>";
echo "<tbody>";

if ($num <> 0)
{
	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
		if ($row["point"]<>NULL)
			$point = $row["point"];
		else
			$point = 0;
	?>
	<tr valign="top">
		<td align="right" width="5%"><?= $no; ?>.</td>
		<td width="24%"><?= ConvertJustDate($row["t_mulai"]); ?>&nbsp;&nbsp;
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td><A HREF="sharing_detail_view.php?idk=<?= $row["id_know"]; ?>&mn=1&height=400&width=650" title="Sharing Knowledge Detail" class="thickbox"><?= $row["judul"]; ?></A></td>
		<td align="right" width="5%"><?= $point; ?></td>
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
			$number .= "<b>$i</b> ";
		else
			$number .= "<a href=\"$file&p=$i\">$i</a> ";
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
