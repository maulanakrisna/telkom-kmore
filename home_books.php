<?php

// Step 1
$judul = "Books";
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
$query  = "SELECT * FROM book";

if (!empty($_REQUEST['keyword']))
	$query .= " WHERE judul LIKE '%".$_REQUEST[keyword]."%' OR abstraksi LIKE '%".$_REQUEST[keyword]."%' OR pengarang LIKE '%".$_REQUEST[keyword]."%' OR jenis LIKE '%".$_REQUEST[keyword]."%'";

$query .= " ORDER BY judul";
#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

# search table
?>
<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=8" method="post">
<input type="text" size="25" name="keyword" value="<?= $_REQUEST[keyword] ?>">
<input type="submit" name="submit" value="Cari" class="button">
</form><center>
<?

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th>";
echo "<th>Judul</th>";
echo "<th>Pengarang</th>";
echo "<th>Abstraksi</th>";
echo "<th>Penerbit";
echo "</th><th>Jenis</th></tr></thead>";
echo "<tbody>";

if ($num <> 0)
{
	// print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top">
		<td ><?= $no; ?>.</td>
		<td ><?= $row["judul"]; ?><br>
		<?
		if (!empty($row[randomkey])){ 
			$qb = "SELECT name,path FROM upload WHERE randomkey = '$row[randomkey]'";
			$resb = mysql_query($qb) or die ("1");
			while ($rowb = mysql_fetch_array($resb)){
				echo "<a href = '".$rowb[path]."'>".$rowb[name]."</a>"; 
			}
		}
		?></td>
		<td ><?= $row["pengarang"]; ?></td>
		<td ><?= $row["abstraksi"]; ?></td>
		<td ><?= $row["penerbit"]; ?></td>
		<td><?= $row["jenis"]; ?></td>
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
	$file = '?mn=8';

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
}
else
{		
	echo "<tr><td colspan='5' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
