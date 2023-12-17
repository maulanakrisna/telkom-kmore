<?php

// Step 1
$judul = "Daftar User";
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
$query = "SELECT a.*, b.nm_loker, c.nm_profile FROM user a JOIN loker b ON a.id_loker=b.id_loker JOIN profile c ON a.id_profile=c.id_profile";
if (!empty($_REQUEST[sortby]))
	$query .= " ORDER BY $_REQUEST[sortby] $_REQUEST[ascdesc]";
else
	$query .= "";
echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$numrows = mysql_num_rows($result);
echo "<center>";

if ($numrows > 0)
{
	# print table header
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
	echo "</td><td align='right'>";
	?>
	<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=11" method="post">
	<input type="submit" name="submit" value="Add User">
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
	<!-- <td><a href='adm_user_detail.php?id=<?= $row[nik]; ?>' title="User Detail" class="thickbox"><?= $row[nik]; ?></a></td> -->
	<td><a href='adm_user_edt.php?id=<?= $row[nik]; ?>' title="User Detail" class="thickbox"><?= $row[nik]; ?></a></td>
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
		echo " <a href=\"$file&p=1&sortby=$_REQUEST[sortby]&ascdesc=$_REQUEST[ascdesc]\"><< First</a> | ";
		echo "<a href=\"$file&p=$previous&sortby=$_REQUEST[sortby]&ascdesc=$_REQUEST[ascdesc]\">< Previous</a> | ";
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
	echo "<br>Sorry, data not found!";
}
echo "</center>";
?>
