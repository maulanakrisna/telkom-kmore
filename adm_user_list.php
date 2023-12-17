<?php

// Step 1
$judul = "Daftar User";
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
$query = "SELECT a.*, b.nm_loker, c.nm_profile FROM user a JOIN loker b ON a.id_loker=b.id_loker JOIN profile c ON a.id_profile=c.id_profile";
if (!empty($_REQUEST['keyword']))
	$query .= " WHERE b.nm_loker LIKE '%".$_REQUEST[keyword]."%' OR a.nama LIKE '%".$_REQUEST[keyword]."%' OR a.nik LIKE '%".$_REQUEST[keyword]."%'";
#echo "$query<br>";
$result = mysql_query($query." ORDER BY a.nik LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$numrows = mysql_num_rows($result);
echo "<h3>$judul</h3>";
echo "<center>";

# search table
?>
	<table class='spacer' width='750' border='0'><tr><td colspan='2'>
	<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=1" method="post">
	<input type="text" name="keyword" value="<?= $_REQUEST[keyword] ?>">
	<input type="submit" name="submit" value="Search" class="button">
	</form>
	</td>
	<td align='right'>
	<form name="addkmap" action="<?= $_SERVER["PHP_SELF"]?>?mn=11" method="post">
	<input type="submit" value="Add User" class="button">
	</form>
	</td>
	</tr>
	</table>
<?
echo "</td></tr>";
echo "</table>";
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>NIK</th><th>Nama</th><th>Loker</th><th>Email</th><th>Status</th></tr></thead>";
echo "<tbody>";

if ($numrows > 0)
{
	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
	?>
	<tr valign="top">
	<td align="right"><?= $no; ?>.</td>
	<td><a href='?mn=12&nik=<?= $row[nik]; ?>' title="User Detail"><?= $row[nik]; ?></a></td>
	<td><?= $row[nama]; ?></td>
	<td><?= $row[nm_loker]; ?></td>
	<td><?= $row[email]; ?></td>
	<td><? if($row[active]==1) echo "Active"; else echo "Not Active"; ?></td>
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

	# previous page
	if ($pageNum > 1)
	{
		$previous = $pageNum-1;
		echo " <a href=\"$file&p=1&searchby=$_REQUEST[searchby]&keyword=$_REQUEST[keyword]\"><< First</a> | ";
		echo "<a href=\"$file&p=$previous&searchby=$_REQUEST[searchby]&keyword=$_REQUEST[keyword]\">< Previous</a> | ";
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
			$number .= "<a href=\"$file&p=$i&searchby=$_REQUEST[searchby]&keyword=$_REQUEST[keyword]\">$i</a> ";
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
		$number .= "<a href=\"$file&p=$i&searchby=$_REQUEST[searchby]&keyword=$_REQUEST[keyword]\">$i</a> ";
	}

	# last number
	$number .= ($pageNum+2 < $maxPage ? " ... <a href=\"$file&p=$maxPage\">$maxPage</a> " : " ");

	echo $number;

	// next page
	if ($pageNum < $maxPage)
	{
		$next = $pageNum+1;
		echo "<a href=\"$file&p=$next&searchby=$_REQUEST[searchby]&keyword=$_REQUEST[keyword]\"> Next ></a> | <a href=\"$file&p=$maxPage&searchby=$_REQUEST[searchby]&keyword=$_REQUEST[keyword]\"> Last >></a> ";
	}
}
else
{		
	echo "<br>Data tidak ada";
}
echo "</center>";
?>
