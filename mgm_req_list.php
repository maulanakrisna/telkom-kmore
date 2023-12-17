<?
// Step 1
$judul = "New Sharing Requests";
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
$skrg = date("Y-m-d H:i:s");
//$query = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker FROM knowledge a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE sharing_status='1' AND t_mulai > '$skrg'";
$query = "SELECT a.id_know, a.judul, a.t_mulai, a.t_akhir, a.nik, b.nama, c.nm_loker FROM knowledge a JOIN user b ON b.nik = a.nik JOIN loker c ON c.id_loker = b.id_loker WHERE a.sharing_status='1' AND a.t_mulai > '$skrg'";
if (!empty($_REQUEST[sortby]))
	$query .= " ORDER BY $_REQUEST[sortby] $_REQUEST[ascdesc]";
else
	$query .= " ORDER BY a.t_mulai";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);

# print table header
#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
echo "<h3>$judul</h3>";
echo "<table class='spacer' width='750' border='0'><tr><td colspan='2'></td></tr>";

# sort table
echo "<tr><td>";
?>
<!--	<form action="<?= $_SERVER["PHP_SELF"]?>?mn=1" method="post">
	Sort by: <select name="sortby" onChange="getState(this.value)">
	<option value="">- Pilih -</option>
	<option value="t_mulai" <? if ($_REQUEST[sortby]=='t_mulai') echo " selected" ?>>Tanggal</option>
	<option value="judul" <? if ($_REQUEST[sortby]=='judul') echo " selected" ?>>Judul</option>
	<option value="nama" <? if ($_REQUEST[sortby]=='nama') echo " selected" ?>>Nama</option>
	<option value="nm_loker" <? if ($_REQUEST[sortby]=='nm_loker') echo " selected" ?>>Bidang</option>
	</select>
	<select name="ascdesc" onChange="getState(this.value)">
	<option value="ASC" <? if ($_REQUEST[ascdesc]=='ASC') echo " selected" ?>>Ascending</option>
	<option value="DESC" <? if ($_REQUEST[ascdesc]=='DESC') echo " selected" ?>>Descending</option>
	</select>
	<input type="submit" name="submit" value="&nbsp;Go&nbsp;" <? if($num==0) echo 'disabled'; ?>>
	</form>
-->
<?
echo "</td><td align='right'><form method = post action ='".$_SERVER['PHP_SELF']."?mn=13'><input type=submit class=button value='Sharing Requests History'></form></td></tr></table>";
echo "<center>";
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>Tanggal & Jam</th><th>Judul</th><th>Pembicara</th><th>Bidang</th><th>Conflict</th></tr></thead>";
echo "<tbody>";

if ($num <> 0)
{
	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
		# check schedule if conflict with others
		if (!empty($row[conflict]))
		{
			$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status='3' AND ";
			$q .= "((t_mulai BETWEEN '$row[t_mulai]' AND '$row[t_akhir]') OR (t_akhir BETWEEN '$row[t_mulai]' AND '$row[t_akhir]')) LIMIT 1";
			#echo "$q";
			query_sql($q,$res);
			$num = mysql_num_rows($res);
			if ($num>0)
			{
				$rs = mysql_fetch_object($res);
				$conflict = "<a href='showconflict.php?idk=$rs->id_know&height=250&width=500' title='Show Conflict Schedule' class='thickbox'><font color='red'><b>Yes</b></font></a>";
			}
			else
			{
				$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status='1' AND ";
				$q .= "((t_mulai BETWEEN '$row[t_mulai]' AND '$row[t_akhir]') OR (t_akhir BETWEEN '$row[t_mulai]' AND '$row[t_akhir]')) LIMIT 1";
				#echo "$q";
				query_sql($q,$res);
				$num = mysql_num_rows($res);
				if ($num>0)
				{
					$rs = mysql_fetch_object($res);
					$conflict = "<a href='showconflict.php?idk=$rs->id_know&height=250&width=500' title='Show Conflict Request' class='thickbox'><font color='red'><b>Yes</b></font></a>";
				}
				else
				{ 
					$conflict="No";
				}
			}
		}
		else
		{ 
			$conflict="No";
		}
?>
	<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<td><?= ConvertJustDate($row["t_mulai"]); ?><br>
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td><A HREF="?idk=<?= $row["id_know"]; ?>&mn=12" title="Request Sharing Review"><?= $row["judul"]; ?></A></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></td>
		<td><?= $conflict; ?></td>
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
}
echo "</tbody>";
echo "</table><p>";
echo "</center>";
?>