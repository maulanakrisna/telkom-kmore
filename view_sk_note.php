<?php

// Step 1
$judul = "SK/CEO Note";
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
$query  = "SELECT a.judul, a.id_letter, a.submitter, a.abstraksi, a.send_at, b.nama, a.randomkey FROM letternote_director a JOIN user b ON b.nik = a.submitter ";
$keyword = $_REQUEST['keyword'];
if (!empty($keyword)){
	$query .= " WHERE a.abstraksi LIKE '%".$keyword."%' OR a.judul LIKE '%".$keyword."%' ";
	#echo $query;
}
$query .= "ORDER BY a.send_at DESC ";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";
?>
<form method="post" action="<?= $_SERVER['PHP_SELF'];?>?mn=6" >
<input type="text" size="25" name="keyword" value="<?= $_REQUEST[keyword] ?>">&nbsp;<input type="submit" value="Cari" class="button">
</form>


<center>

<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>
<thead><tr><th>No.</th><th>Pengirim</th><th>Judul/Abstraksi</th><th>File/Komentar</th></tr></thead>
<tbody>
<?
if ($num <> 0)
{
	// print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top">
		<td ><?= $no; ?>.</td>
		<td ><? $qp = "SELECT photopath FROM photo WHERE nik = '$row[submitter]' ORDER BY date DESC LIMIT 1";
		$resp = mysql_query($qp);
		while ($rowp = mysql_fetch_array($resp)){
			echo '<img src="'.$rowp[photopath].'">';
		}
		?><br><?= $row["nama"]; ?></td>
		<td><b><?= $row["judul"]; ?></b><br>
		<?= $row["abstraksi"]; ?><br>
		<?= ($row["send_at"]); ?>
		<td>
		<?php
			$qf = "SELECT path, name FROM upload WHERE randomkey = '$row[randomkey]'";
			$resf = mysql_query($qf);
			while ($rowf = mysql_fetch_object($resf)){
				echo "<a href='".$rowf->path."'>".$rowf->name."</a>&nbsp;.&nbsp;";
			}
		?>
		<br>
		<a href="home.php?mn=61&id=<?= $row[id_letter];?>&randomkey=<?= $row[randomkey];?>">
<?
$que = "SELECT * FROM feedback WHERE id_know = '$row[id_letter]' AND randomkey = '$row[randomkey]'";
$res = mysql_query($que);
$num = mysql_num_rows($res);
echo $num;
?>&nbsp;komentar</a>
		</td>

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
	$file = '?mn=6';

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
