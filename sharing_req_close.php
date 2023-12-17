<?
// Step 1
$judul = "Close Sharing Task";
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
$query  = "SELECT a.id_know,a.judul,a.nik,a.t_mulai,a.t_akhir,a.sharing_status,b.nama,b.id_bidang,c.nm_loker ";
$query .= "FROM knowledge a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker ";
$query .= "WHERE a.nik='$_SESSION[nik_login]' AND sharing_status BETWEEN '3' AND '5' AND a.t_akhir < NOW()";

if (!empty($_REQUEST['sort']))
	$query .= " ORDER BY $_REQUEST[sort] $_REQUEST[by]";
else
	$query .= " ORDER BY a.t_mulai";

#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

echo "<center>";

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th>";
echo "<th>Date/Time&nbsp;<a href='?mn=3&sort=t_mulai&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=3&sort=t_mulai&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Title/Theme&nbsp;<a href='?mn=3&sort=judul&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=3&sort=judul&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Status</th></tr></thead>";
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
		<td width="24%"><?= ConvertJustDate($row["t_mulai"]); ?>&nbsp;&nbsp;
		<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td>
		<? if ($row["sharing_status"]==3) { ?>
		<A HREF="?idk=<?= $row["id_know"]; ?>&mn=31" title="Close Sharing Task"><?= $row["judul"]; ?></A>
		<? } else { echo $row["judul"]; } ?>
		</td>
		<?
		if ($row["sharing_status"]=='3') $status = "<b><font color='red'>Have to close</font></b>";
		elseif ($row["sharing_status"]=='5') $status = "Closing";
		elseif ($row["sharing_status"]=='2') $status = "Reject";
		?>
		<td width="15%"><?= $status; ?></td>
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
	$file = '?mn=3';

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
