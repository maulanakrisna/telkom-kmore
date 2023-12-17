<?

// Step 1
$judul = "Request to Attend History";
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
/*
$query  = "SELECT a.id_know, a.nik AS niknya, b.nama, c.acronym, d.judul, d.app_req_by, e.nama AS appname, f.created FROM sharing_activity a ";
$query .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker JOIN knowledge d ON a.id_know=d.id_know ";
$query .= "JOIN user e ON d.app_req_by=e.nik JOIN req_to_attend f ON a.id_know=f.id_know AND a.nik=e.nik ";
$query .= "WHERE a.id_inv_status='4' AND id_confirm='1'";
*/
$query  = "SELECT a.id_know, a.nik AS niknya, a.id_confirm, b.nama, c.acronym, d.judul, f.nama AS approve_name, e.approve_date ";
$query .= "FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker ";
$query .= "JOIN knowledge d ON a.id_know=d.id_know JOIN req_to_attend e ON a.id_know=e.id_know AND a.nik=e.nik ";
$query .= "JOIN user f ON e.approve_by=f.nik WHERE a.id_inv_status='4' AND a.id_confirm<>5";
#echo "$query<br>";
 
$result = mysql_query($query);
$num = mysql_num_rows($result);

#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
echo "<br><h3>$judul</h3><table class='spacer' width='750' border='0'><tr><td colspan='2'></td></tr>";

# print table header
echo "</td><td align='right'><a href='?mn=2'>Request to Attend</a></td></tr></table>";
echo "<center>";
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
#echo "<thead><tr><th>No.</th><th>Tanggal/Jam</th><th>Dari</th><th>Bidang</th><th>Sharing Knowledge</th></tr></thead>";
echo "<thead><tr><th>No.</th><th>Dari</th><th>Bidang</th><th>Untuk Sharing Knowledge</th><th>Approved By</th><th>Tanggal</th><th>Status</th></tr></thead>";
echo "<tbody>";

if ($num <> 0)
{
	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top"> 
		<td align="right" width="5%"><?= $no; ?>.</td>
		<!-- <td align="left"><?#= date("d-m-Y H:i:s",strtotime($row["created"])); ?></td> -->
		<td align="left"><?= $row["nama"]; ?></td>
		<td align="left"><?= $row["acronym"]; ?></td>
		<td align="left"><a href="sharing_detail_view.php?idk=<?= $row["id_know"]; ?>&height=400&width=650" title="Sharing Knowledge Detail" class="thickbox"><?= $row["judul"]; ?></a></td>
		<td><?= $row["approve_name"]; ?></td>
		<td><?= date("d-m-Y H:i:s",strtotime($row["approve_date"])); ?></td>
		<td><? if($row["id_confirm"]==1) echo "Accept"; else echo "Reject"; ?></td>
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
	$file = '?mn=21';

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
	echo "</td></tr>";
}
else
{		
	echo "<tr><td colspan='7' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
