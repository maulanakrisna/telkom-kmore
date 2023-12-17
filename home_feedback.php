<?
/*
-----
file asal  : index.php?mn=5
file tujuan: save2db.php?sw=17
-----
*/

// Step 1
$judul = "Feedback";
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
$query  = "SELECT a.*, b.judul, b.t_mulai, b.t_akhir, c.nama ";
$query .= "FROM sharing_activity a JOIN knowledge b ON a.id_know=b.id_know JOIN user c ON b.nik=c.nik ";
$query .= "WHERE a.nik='$_SESSION[nik_login]' AND a.attend=1 AND a.id_inv_status=3 AND a.feedback_status='0'";
if (!empty($_REQUEST[sortby])) {
	$query .= " ORDER BY $_REQUEST[sortby] $_REQUEST[ascdesc]";
} else {
	$query .= " ORDER BY b.t_mulai";
}
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

# sort table
echo "<table class='spacer' width='750' border='0'><tr><td colspan='2'>";
?>
	<form action="<?= $_SERVER["PHP_SELF"]?>?mn=1" method="post">
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

<?
echo "</td><td align='right'><a href='?mn=51'>Feedback History</a></td></tr></table>";
echo "<center>";

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>Date/Time</th><th>Title/Theme</th><th>Contributor</th></tr></thead>";
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
		<td><?= ConvertJustDate($row["t_mulai"]); ?><br>
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td><a href="home_feedback_d.php?idk=<?= $row["id_know"]; ?>&mn=5&height=400&width=700" title="Sharing Knowledge Feedback" class="thickbox"><?= $row["judul"]; ?></a></td>
		<td><?= $row["nama"]; ?></td>
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
	echo "</center>";
}
else
{		
	echo "<tr><td colspan='5' align='center'>Sorry, no sharing knowledge feedback at this time!</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
