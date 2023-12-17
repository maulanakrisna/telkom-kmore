<?

// Step 1
$sekarang = date("Y-m-d H:i:s");
$judul = "Invitation";
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
$query  = "SELECT a.*, b.nama, c.nik, c.id_inv_status, c.id_confirm, b.id_bidang, d.nm_loker ";
$query .= "FROM knowledge a JOIN user b ON a.nik=b.nik JOIN sharing_activity c ON a.id_know=c.id_know ";
$query .= "JOIN loker d ON b.id_bidang=d.id_loker WHERE sharing_status='3' AND c.nik = '$_SESSION[nik_login]' ";
$query .= "AND c.id_inv_status='3' AND c.id_confirm='0' AND a.t_mulai > NOW()";

if (!empty($_REQUEST['searchby']))
	$query .= " AND ".$_REQUEST['searchby']." LIKE '%".$_REQUEST[keyword]."%'";

if (!empty($_REQUEST['sort']))
	$query .= " ORDER BY $_REQUEST[sort] $_REQUEST[by]";
else
	$query .= " ORDER BY a.t_mulai";

#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

# search table
echo "<table class='spacer' width='750' border='0'><tr><td>";
?>
	<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=12" method="post">
	<select name="searchby" onChange="getState(this.value)">
	<option value="">- Choose -</option>
	<option value="judul" <? if ($_REQUEST[searchby]=='judul') echo " selected" ?>>Judul</option>
	<option value="nama" <? if ($_REQUEST[searchby]=='nama') echo " selected" ?>>Nama</option>
	</select>
	<input type="text" name="keyword" value="<?= $_REQUEST[keyword] ?>">
	<input type="submit" name="submit" value="Search">
	</form>
<?
echo "</td><td align='right'><a href='?mn=21'>Invitation History</a></td></tr></table>";
echo "<center>";

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th>";
echo "<th>Date/Time&nbsp;<a href='?mn=2&sort=t_mulai&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=2&sort=t_mulai&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Title/Theme&nbsp;<a href='?mn=2&sort=judul&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=2&sort=judul&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Contributor&nbsp;<a href='?mn=2&sort=nama&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=2&sort=nama&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Bidang</th></tr></thead>";
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
		<td><?= ConvertJustDate($row["t_mulai"]); ?><br>
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td>
		<? if ($row[t_mulai]>$sekarang) { ?>
		<A HREF="sharing_confirm.php?idk=<?= $row["id_know"]; ?>&mn=1&height=400&width=700" title="Sharing Knowledge Invitation Detail" class="thickbox"><?= $row["judul"]; ?></A>
		</td>
		<? } else { ?>
		<A HREF="#" title="Waktunya sudah lewat"><?= $row["judul"]; ?></A>
		<? } ?>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></a></td>
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
	echo "<tr><td colspan='5' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
