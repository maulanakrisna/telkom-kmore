<?php

// Step 1
$judul = "Daftar Bidang RDC";
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
$query = "SELECT * FROM bidang WHERE id_bidang <> '100'";
if (isset($_REQUEST[sortby]))
	$query .= " ORDER BY $_REQUEST[sortby] $_REQUEST[ascdesc]";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$numrows = mysql_num_rows($result);
echo "<center>";

if ($numrows > 0)
{

	# print table header
	#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
	echo "<table class='spacer' width='750' border='0'><tr><td colspan='2'><h3>$judul</h3></td></tr>";
	echo "</table>";
	echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
	echo "<thead><tr><th>No.</th><th>Bidang</th><th>Acronym</th><th>E-mail</th></tr></thead>";
	echo "<tbody>";

	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
	?>
		<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<!-- <td><a href='adm_user_detail.php?id=<?= $row[nik]; ?>' title="User Detail" class="thickbox"><?= $row[nik]; ?></a></td> -->
		<!-- <td><a href='adm_user_edt.php?id=<?= $row[nm_loker]; ?>' title="User Detail" class="thickbox"><?= $row[nik]; ?></a></td> -->
		<td><a href="?mn=32&idb=<?= $row[id_bidang]; ?>"><?= $row[nm_bidang]; ?></a></td>
		<td><?= $row[singkatan]; ?></td>
		<td><?= $row[email]; ?></td>
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
	$file = 'admin.php?mn=3';

	# previous page
	if ($pageNum > 1)
	{
		$previous = $pageNum-1;
		echo " <a href=\"$file&p=1\"><< First</a> | <a href=\"$file&p=$previous\">< Previous</a> | ";
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
	echo "<br>Sorry, data not found!";
}
echo "</center>";
?>
