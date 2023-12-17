<?php

// Step 1
$judul = "Library";
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
#$query = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker FROM knowledge a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE sharing_status='6'";
$query  = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker, d.nm_map FROM knowledge a JOIN user b ON a.nik=b.nik ";
$query .= "JOIN loker c ON b.id_loker=c.id_loker JOIN knowledge_map d ON a.id_map=d.id_map ";
$query .= "WHERE sharing_status='6'";
/*
$query  = "SELECT a.*, b.nama, b.id_bidang, c.nm_loker FROM knowledge a JOIN user b ON a.nik=b.nik ";
$query .= "JOIN loker c ON b.id_bidang=c.id_loker ";
$query .= "WHERE sharing_status='6'";
*/

if (!empty($_REQUEST['keyword']))
	$query .= " AND (a.abstraksi LIKE '%".$_REQUEST[keyword]."%' OR b.nama LIKE '%".$_REQUEST[keyword]."%')";

$query .= " ORDER BY a.t_mulai DESC";

#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

# search table
?>
<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=4" method="post">
<input type="text" size="25" name="keyword" value="<?= $_REQUEST[keyword] ?>">
<input type="submit" name="submit" value="Cari" class="button">
</form>
<center>


<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>
<tbody>
<?
if ($num <> 0)
{
	// print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
<tr>
<td><? $qp = "SELECT photopath FROM photo WHERE nik = '$row[submitter]' ORDER BY date DESC LIMIT 1";
		$resp = mysql_query($qp);
		while ($rowp = mysql_fetch_array($resp)){
			echo '<img src="'.$rowp[photopath].'">';
		}
		?><?= $row["nama"]; ?></td>	
		<td><a href="archives_detail.php?idk=<?= $row["id_know"]; ?>&mn=1&height=400&width=700" title="View Sharing Knowledge Detail" class="thickbox"><?= $row["judul"]; ?></a>
		<br><?= $row["abstraksi"]; ?><br><?= ConvertJustDate($row["t_mulai"]); ?>&nbsp;.&nbsp;
		<?
			if (!empty($row['randomkey'])){
				$qfile = "SELECT name, path FROM upload WHERE randomkey = '$row[randomkey]'";
				$resfile = mysql_query($qfile);
				while ($rowfile = mysql_fetch_array($resfile)){
					echo "<a href = '".$rowfile['path']."'>".$rowfile['name']."</a>";
				}
				
			}
		?>&nbsp;.&nbsp;
		<a href="home.php?mn=61&id=<?= $row[id_know];?>&randomkey=<?= $row[randomkey];?>">
		<?
			$que = "SELECT * FROM feedback WHERE id_know = '$row[id_know]' AND randomkey = '$row[randomkey]'";
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
	echo "</table>";
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
	echo "<tr><td colspan='5' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table>";
}
echo "</center>";
?>
