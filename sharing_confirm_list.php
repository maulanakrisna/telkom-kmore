<?
/*
-----
file asal  : sharing_schedule.php, sharing_req_his.php, newsticker.php
file tujuan: save2db.php?sw=16
-----
*/
session_start();
/*
if (!isset($_SESSION['nik_login']))
{
	Header("Location:login.php");
}
*/

// Step 1
$judul = "Sharing Knowledge Confirmation List";
require_once ("include/dbcon.php");

// Step 2
// Show Total Confirm
$showconfirm = array();
$query = "SELECT b.nm_confirm, COUNT(a.id_confirm) AS jumlah FROM sharing_activity a JOIN confirm b ON a.id_confirm=b.id_confirm WHERE  a.nik<>'$_SESSION[nik_login]' AND id_know='$_REQUEST[idk]' GROUP BY a.id_confirm;";
#echo "$query<br>";
query_sql($query,$result);
while ($r=mysql_fetch_array($result)) {
	$showconfirm[] = "<label>$r[nm_confirm]:</label>&nbsp;$r[jumlah]";
}

// who has confirmed
$query = "SELECT a.*,b.nama,c.nm_loker,d.nm_confirm FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker JOIN confirm d ON a.id_confirm=d.id_confirm WHERE a.nik<>'$_SESSION[nik_login]' AND id_know='$_REQUEST[idk]' AND a.id_confirm <> '0' ORDER BY a.id_inv_status,c.id_loker,b.nik";
#echo "$query<br>";
query_sql($query,$result);
$num = mysql_num_rows($result);

if ($num <> 0)
{
	#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
	echo "<table class='spacer' width='580' border='0'><tr><td colspan='2'><h3>$judul</h3></td></tr>";
	echo "<tr><td>".implode(", ",$showconfirm)."</td></tr></table>";

	# print table header
	echo "<table id='myTable' width='580' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
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
	echo "<br><br><br><br><center>Maaf, belum ada yang konfirmasi untuk sharing knowledge ini</center>";
}
echo "<br><center><input type='submit' name='submit' value='Close' onclick='tb_remove()'></center>";
?>
