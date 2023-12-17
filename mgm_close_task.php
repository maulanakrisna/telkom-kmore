<?
// Step 1
$judul = "Sharing Knowledge Status";
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
#$query = "SELECT a.id_know,a.judul,a.nik,a.t_mulai,a.t_akhir,a.req_status,b.nama,b.id_bidang,c.nm_loker FROM knowledge a JOIN USER b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE a.nik='$_SESSION[nik_login]' AND report_status='0' AND a.t_mulai < NOW()";
#$query = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker FROM knowledge a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE report_status<>'2' AND a.t_akhir < NOW()";
$query = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker FROM knowledge a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker WHERE sharing_status IN ('3','5') ";
#echo "$query<br>";
if (!empty($_REQUEST['keyword'])){
	$query .= " AND (a.abstraksi LIKE '%".$_REQUEST['keyword']."%' OR b.nama LIKE '%".$_REQUEST['keyword']."%' OR a.judul LIKE '%".$_REQUEST['keyword']."%')";
	#echo $query;
}
$query .= "ORDER BY a.t_mulai DESC ";

$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);

# print table header
echo "<h3>$judul</h3><table class='spacer' width='750' border='0'><tr><td colspan='2'></td></tr>";
?>
<tr><td colspan='2'>

<form method="post" action="<?= $_SERVER['PHP_SELF'];?>?mn=3" >
<input type="text" size="25" name="keyword" value="<?= $_REQUEST[keyword] ?>">&nbsp;<input type="submit" value="Cari" class="button">
</form>

</td><td align='right'><form method = "post" action="<?= $_SERVER["PHP_SELF"]?>?mn=33"> <input type="submit" class="button" value="Closed Tasks"></form></td></tr>
<?
echo "<center>";
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>Tanggal/Jam</th><th>Judul</th><th>Pembicara</th><th>Bidang</th><th>Status</th></tr></thead>";
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
		<td><?= ConvertJustDate($row["t_mulai"]); ?><br>
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td> 
		<td><? if ($row["sharing_status"]=='5') { $status="<font color='red'><b>Closing</b></font>"; ?>
			<a href="management.php?mn=31&idk=<?= $row["id_know"]; ?>" title="Close Task Approval"><?= $row["judul"]; ?></a>
			<? } else { $status="Open"; ?>
			<a href="management.php?mn=32&idk=<?= $row["id_know"]; ?>" title="View Close Task Detail"><?= $row["judul"]; ?></a>
			<? } ?></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></td>
		<td><?= $status; ?></td>
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
	echo "</td></tr>";
}
else
{		
	echo "<tr><td colspan='6' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
