<?php

// Step 1
$judul = "Kirim Komentar";
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
$id = $_REQUEST['id'];
$randomkey = $_REQUEST['randomkey'];

// Step 2
$query  = "SELECT a.comment, a.created, b.nama FROM feedback a JOIN user b ON a.nik = b.nik WHERE a.id_know = '$id' AND a.randomkey = '$randomkey' ORDER BY a.created DESC";

$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";
?>
<form method="post" action="save2db.php">
<p><? echo $_SESSION['error']; $_SESSION['error'] = '';?></p>
<p><textarea rows="5" cols="50" name="komentar" class="required"></textarea></p>
<p><input type="submit" value="Kirim" class="button"></p>
<p><input type="hidden" value="32" name="sw"><input type="hidden" name="randomkey" value="<?=$randomkey;?>">
<input type="hidden" name="id" value="<?=$id;?>"><input type="hidden" name="nik" value="<?=$_SESSION[nik_login];?>">
</form>
<?
# search table
echo "<table class='spacer' width='750' border='0'><tr><td colspan='2'>";
echo "</td></tr></table>";
echo "<center>";

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th>";
echo "<th>Date/Time&nbsp;</th>";
echo "<th>Nama&nbsp;</th>";
echo "<th>Komentar</th></tr></thead>";
echo "<tbody>";

if ($num <> 0)
{
	// print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top">
		<td width="4%"><?=$no;?>.</td>
		<td width="16%"><?= ($row["created"]); ?></td>
		<td width="20%"><?= $row["nama"]; ?></td>
<td ><?= $row["comment"]; ?></td>

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
	$file = '?mn=61';

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
	echo "<tr><td colspan='5' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
