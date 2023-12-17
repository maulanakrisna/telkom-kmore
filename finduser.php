<?
/*
-----
file asal  : header.php
file tujuan: -
-----
*/
$judul = "Find User > Results Page";
require_once ("include/dbcon.php");
$recordsPerPage = 3;
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

$query = "SELECT a.*, b.nm_loker, c.nm_profile FROM user a JOIN loker b ON a.id_loker=b.id_loker JOIN profile c ON a.id_profile=c.id_profile WHERE a.nama LIKE '%$_REQUEST[keyword]%' OR a.nik LIKE '%$_REQUEST[keyword]%' OR b.nm_loker LIKE '%$_REQUEST[keyword]%'";
echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$numrows = mysql_num_rows($result);
echo "<center>";

if ($numrows > 0)
{
	# print table header
	#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
	echo "<table class='spacer' width='750' border='0'><tr><td colspan='2'><h3>$judul</h3></td></tr>";

	# sort table
	echo "<tr><td>";
?>
	<form name="sort" action="<?= $_SERVER["PHP_SELF"]?>?mn=1" method="post">
	Sort by: <select name="sortby" onChange="getState(this.value)">
	<option value="">- Pilih -</option>
	<option value="a.nik" <? if ($_REQUEST[sortby]=='a.nik') echo " selected" ?>>NIK</option>
	<option value="a.nama" <? if ($_REQUEST[sortby]=='a.nama') echo " selected" ?>>Nama</option>
	<option value="b.nm_loker" <? if ($_REQUEST[sortby]=='b.nm_loker') echo " selected" ?>>Loker</option>
	</select>
	<select name="ascdesc" onChange="getState(this.value)">
	<option value="ASC" <? if ($_REQUEST[ascdesc]=='ASC') echo " selected" ?>>Ascending</option>
	<option value="DESC" <? if ($_REQUEST[ascdesc]=='DESC') echo " selected" ?>>Descending</option>
	</select>
	<input type="submit" name="submit" value="&nbsp;Go&nbsp;">
	</form>
<?
	echo "</td><td>";
?>
	<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=13" method="post">
	Find: <input type="text" name="keyword" value="<?= $_REQUEST[keyword] ?>">
	<input type="submit" name="submit" value="&nbsp;Go&nbsp;">
	</form>
<?
	echo "</td></tr>";
	echo "</table>";
	echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
	echo "<thead><tr><th>No.</th><th>NIK</th><th>Nama</th><th>Loker</th><th>Email</th><th>Status</th></tr></thead>";
	echo "<tbody>";

	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
	?>
		<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<td><?= $row["nik"]; ?></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></td>
		<td><?= $row["email"]; ?></td>
		<td><? if($row[active]==0) echo "Not Active"; else echo "Active"; ?></A></td>
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
	$file = 'admin.php?mn=13';

	# previous page
	if ($pageNum > 1)
	{
		$previous = $pageNum-1;
		echo " <a href=\"$file&p=1&keyword=$_REQUEST[keyword]\"><< First</a> | <a href=\"$file&p=$previous&keyword=$_REQUEST[keyword]\">< Previous</a> | ";
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
			$number .= "<a href=\"$file&p=$i&keyword=$_REQUEST[keyword]\">$i</a> ";
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
		$number .= "<a href=\"$file&p=$i&keyword=$_REQUEST[keyword]\">$i</a> ";
	}

	# last number
	$number .= ($pageNum+2 < $maxPage ? " ... <a href=\"$file&p=$maxPage\">$maxPage</a> " : " ");

	echo $number;

	// next page
	if ($pageNum < $maxPage)
	{
		$next = $pageNum+1;
		echo "<a href=\"$file&p=$next&keyword=$_REQUEST[keyword]\"> Next ></a> | <a href=\"$file&p=$maxPage&keyword=$_REQUEST[keyword]\"> Last >></a> ";
	}
}
else
{		
	echo "<br>Sorry, data not found!";
}
echo "</center>";
?>
