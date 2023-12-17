<?php

// Step 1
$judul = "Update Sharing Knowledge";
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
$query = "SELECT a.*, b.nama FROM knowledge a JOIN user b ON a.nik=b.nik WHERE a.nik='$_SESSION[nik_login]' AND a.sharing_status='6'";
if (!empty($_REQUEST[sortby])) {
	$query .= " ORDER BY $_REQUEST[sortby] $_REQUEST[ascdesc]";
}
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);

if ($num <> 0)
{
	# print table header
	#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
	echo "<table class='spacer' width='750' border='0'><tr><td colspan='2'><h3>$judul</h3></td></tr>";

	# sort table
	echo "<tr><td colspan='2'>";
?>
	<form action="<?= $_SERVER["PHP_SELF"]?>?mn=1" method="post">
	Sort by: <select name="sortby" onChange="getState(this.value)">
	<option value="">- Pilih -</option>
	<option value="t_mulai" <? if ($_REQUEST[sortby]=='t_mulai') echo " selected" ?>>Tanggal</option>
	<option value="judul" <? if ($_REQUEST[sortby]=='judul') echo " selected" ?>>Judul</option>
	<option value="point" <? if ($_REQUEST[sortby]=='point') echo " selected" ?>>Point</option>
	</select>
	<select name="ascdesc" onChange="getState(this.value)">
	<option value="ASC" <? if ($_REQUEST[ascdesc]=='ASC') echo " selected" ?>>Ascending</option>
	<option value="DESC" <? if ($_REQUEST[ascdesc]=='DESC') echo " selected" ?>>Descending</option>
	</select>
	<input type="submit" name="submit" value="&nbsp;Go&nbsp;">
	</form>

<?
	echo "</td></tr>";
	echo "</table>";
	echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
	echo "<thead><tr><th>No.</th><th>Tanggal & Jam</th><th>Judul</th><th>point</th></tr></thead>";
	echo "<tbody>";

	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
	?>
	<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<td><?= ConvertJustDate($row["t_mulai"]); ?><br>
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td><A HREF="?idk=<?= $row["id_know"]; ?>&mn=32" title="Edit Sharing Knowledge"><?= $row["judul"]; ?></A></td>
		<td><?#= $row["point"]; ?>point</td>
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
}
else
{		
	echo "<tr><td colspan='5' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
