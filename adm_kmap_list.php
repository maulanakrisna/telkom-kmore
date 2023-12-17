<?php

// Step 1
$judul = "Knowledge Map";
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
$query = "SELECT * FROM knowledge_map";
if (!empty($_REQUEST['keyword']))
	$query .= " WHERE nm_map LIKE '%".$_REQUEST[keyword]."%'";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$numrows = mysql_num_rows($result);
echo "<h3>$judul</h3>";
echo "<center>";

# search table
?>
	<table class='spacer' width='750' border='0'><tr><td colspan='2'>
	<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=4" method="post">
	<input type="text" name="keyword" value="<?= $_REQUEST[keyword] ?>">
	<input type="submit" name="submit" value="Search" class="button">
	</form>
	</td>
	<td align='right'><!--
	<form name="addkmap" action="<?= $_SERVER["PHP_SELF"]?>?mn=41" method="post">
	<input type="submit" value="Add Knowledge Map" class="button">
	</form>
	</td>-->
	</tr>
	</table>
<?

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th width='10px'>No.</th><th>Knowledge Map</th><th>Expert</th><th>Level</th></tr></thead>";
echo "<tbody>";

if ($numrows > 0)
{
	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
	if ($row["id_map"]%1000000==0) $spacer="&nbsp;";
					   else if ($row["id_map"]%10000==0) $spacer="&nbsp;|----&nbsp;";
					   else if ($row["id_map"]%100==0) $spacer="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|----&nbsp;";
					   else $spacer="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp&nbsp;&nbsp;&nbsp;|----&nbsp;";

	?>
		<tr valign="top">
		<td align="right"><?= $no; ?>.</td>
		<!-- <td><a href='adm_user_detail.php?id=<?= $row[nik]; ?>' title="User Detail" class="thickbox"><?= $row[nik]; ?></a></td> -->
		<!-- <td><a href='adm_user_edt.php?id=<?= $row[nm_loker]; ?>' title="User Detail" class="thickbox"><?= $row[nik]; ?></a></td> -->
		<td><?= $spacer; ?><a href="?mn=42&id=<?= $row["id_map"]; ?>"><?= $row[nm_map]; ?></a></td>
		<td><?= $row[expert]; ?></td>
		<td><?= $row[level]; ?></td>
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
	$file = 'admin.php?mn=4';

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
	echo "<br>Data tidak ada";
}
echo "</center>";
?>
