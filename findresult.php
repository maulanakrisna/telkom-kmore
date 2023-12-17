<?
/*
-----
file asal  : header.php
file tujuan: -
-----
*/
$judul = "Results";
require_once ("include/dbcon.php");
$recordsPerPage = 5;
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

$query = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker, d.nm_map FROM knowledge a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker JOIN knowledge_map d ON a.id_map=d.id_map WHERE req_status<>'Request' AND (a.judul LIKE '%$_REQUEST[keyword]%' OR a.nik LIKE '%$_REQUEST[keyword]%' OR b.nama LIKE '%$_REQUEST[keyword]%' OR c.nm_loker LIKE '%$_REQUEST[keyword]%' OR d.nm_map LIKE '%$_REQUEST[keyword]%' OR a.jenis LIKE '%$_REQUEST[keyword]%')";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$numrows = mysql_num_rows($result);

if ($numrows > 0)
{
	# print table header
	#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr></table>";
	echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td></tr></table>";
	echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
	echo "<thead><tr><th>No.</th><th>Tanggal/Jam</th><th>Judul</th><th>Loker</th><th>Pembicara</th><th>Bidang</th></tr></thead>";
	echo "<tbody>";

	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
	?>
		<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<td><?= ConvertJustDate($row["t_mulai"]); ?></td>
		<td><?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td><A HREF="sharing_detail.php?id=<?= $row["id_know"]; ?>&mn=1" title="Sharing Knowledge Detail" class="thickbox"><?= $row["judul"]; ?></A></td>
		<td><?= $row["nama"]; ?></td>
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
	$file = 'admin.php?mn=1';

	echo "<center>";
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
	echo "</center>";
}
	else
{		
	echo "<br><center>Sorry, data not found!</center>";
}
?>
