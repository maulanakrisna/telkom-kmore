<?php

// Step 1
$judul = "Daftar Hadir Sharing Knowledge";
require_once ("include/dbcon.php");

// Step 2
$query = "SELECT a.*,b.nama,c.nm_loker,d.nm_confirm FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker JOIN confirm d ON a.id_confirm=d.id_confirm WHERE id_know='$_REQUEST[idk]' AND attend=1 ORDER BY a.id_inv_status,c.id_loker,b.nik";
#echo "$query<br>";
$result = mysql_query($query) or die('Mysql Err. 1');
$num = mysql_num_rows($result);

echo "<center>";
if ($num <> 0)
{
	#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
	echo "<table class='spacer' width='550' border='0'><tr><td colspan='2'><h3>$judul</h3></td></tr>";
	echo "<tr><td colspan='2'>Jumlah yang hadir: $num orang</td></tr></table>";

	# print table header
	echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
	echo "<thead><tr><th>No.</th><th>NIK</th><th>Nama</th><th>Bidang</th><th>Confirmation</th></tr></thead>";
	echo "<tbody>";

	# print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top">
		<td align="right" width="10px"><?= $no; ?>.</td>
		<td><?= $row["nik"]; ?></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["nm_loker"]; ?></td>
		<td><?= $row["nm_confirm"]; ?></td>
	</tr>
<?
		$no++;
	}
	echo "</tbody>";
	echo "</table><p>";
}
else
{		
	echo "<br><br><br><br>Tidak ada data<br>";
}
#echo "<br><input type='submit' name='submit' value='Close' onclick='tb_remove();'></center>";
?>
