<?
// Step 1
$judul = "Sharing Requests History";
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

// Step 2
$query = "SELECT a.*, b.nama, b.id_bidang, c.acronym, d.nama AS appname FROM knowledge a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker JOIN user d ON a.app_req_by=d.nik WHERE a.app_req_by IS NOT NULL AND a.sharing_status IN ('2','3')";
if (!empty($_REQUEST[sortby]))
	$query .= " ORDER BY $_REQUEST[sortby] $_REQUEST[ascdesc]";
else
	$query .= " ORDER BY a.app_req_at DESC";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

# sort table
echo "<table class='spacer' width='750' border='0'><tr><td>";
?>
<!--	<form name="sort" action="<?= $_SERVER["PHP_SELF"]?>?mn=13" method="post">
	Sort by: <select name="sortby" onChange="getState(this.value)">
	<option value="">- Pilih -</option>
	<option value="a.nik" <? if ($_REQUEST[sortby]=='a.nik') echo " selected" ?>>NIK</option>
	<option value="b.nama" <? if ($_REQUEST[sortby]=='b.nama') echo " selected" ?>>Nama</option>
	<option value="c.nm_loker" <? if ($_REQUEST[sortby]=='c.nm_loker') echo " selected" ?>>Bidang</option>
	</select>
	<select name="ascdesc" onChange="getState(this.value)">
	<option value="ASC" <? if ($_REQUEST[ascdesc]=='ASC') echo " selected" ?>>Ascending</option>
	<option value="DESC" <? if ($_REQUEST[ascdesc]=='DESC') echo " selected" ?>>Descending</option>
	</select>
	<input type="submit" name="submit" value="&nbsp;Go&nbsp;" <? if($num==0) echo 'disabled'; ?>>
	</form>
	-->
<?
echo "</td><td align='right'><a href='?mn=1'>New Sharing Requests</a></td></tr></table>";
echo "<center>";

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>Judul</th><th>Pembicara</th><th>Bidang</th><th>Approved By/Tgl</th><th>Status</th></tr></thead>";
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
		<!--
		<td><?= ConvertJustDate($row["t_mulai"]); ?><br>
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		-->
		<td><A HREF="sharing_detail_view.php?idk=<?= $row["id_know"]; ?>&height=400&width=650" title="Request Sharing Review" class="thickbox"><?= $row["judul"]; ?></A></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["acronym"]; ?></td>
		<td><?= $row["appname"]; ?><br>
			<?= date("d-m-Y H:i:s",strtotime($row["app_req_at"])); ?></td>
		<td><? if ($row["sharing_status"]=="3") echo "Aproved"; elseif ($row["sharing_status"]=="2") echo "Reject"; ?></td>
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
	$file = '?mn=13';

	# previous page
	if ($pageNum > 1)
	{
		$previous = $pageNum-1;
		echo " <a href=\"$file&p=1&sortby=$_REQUEST[sortby]&ascdesc=$_REQUEST[ascdesc]\"><< First</a> | <a href=\"$file&p=$previous&sortby=$_REQUEST[sortby]&ascdesc=$_REQUEST[ascdesc]\">< Previous</a> | ";
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
			$number .= "<a href=\"$file&p=$i&sortby=$_REQUEST[sortby]&ascdesc=$_REQUEST[ascdesc]\">$i</a> ";
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
		$number .= "<a href=\"$file&p=$i&sortby=$_REQUEST[sortby]&ascdesc=$_REQUEST[ascdesc]\">$i</a> ";
	}

	# last number
	$number .= ($pageNum+2 < $maxPage ? " ... <a href=\"$file&p=$maxPage\">$maxPage</a> " : " ");

	echo $number;

	// next page
	if ($pageNum < $maxPage)
	{
		$next = $pageNum+1;
		echo "<a href=\"$file&p=$next&sortby=$_REQUEST[sortby]&ascdesc=$_REQUEST[ascdesc]\"> Next ></a> | <a href=\"$file&p=$maxPage&sortby=$_REQUEST[sortby]&ascdesc=$_REQUEST[ascdesc]\"> Last >></a> ";
	}
}
else
{		
	echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>