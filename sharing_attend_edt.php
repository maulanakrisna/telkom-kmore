<?php

// Step 1
$judul = "Sharing Knowledge Attendance Sheet";
require_once ("include/dbcon.php");

// Step 2
$query = "SELECT a.*,b.nama,c.nm_loker,d.nm_confirm FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker JOIN confirm d ON a.id_confirm=d.id_confirm WHERE id_know='$_REQUEST[idk]' AND a.id_confirm BETWEEN '1' AND '2' ORDER BY a.id_inv_status,c.id_loker,b.nik";
#echo "$query<br>";
query_sql($query,$result);
$num = mysql_num_rows($result);

$query = "SELECT a.*,b.nama,c.nm_loker,d.nm_confirm FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker JOIN confirm d ON a.id_confirm=d.id_confirm WHERE id_know='$_REQUEST[idk]' ORDER BY a.id_inv_status,c.id_loker,b.nik";
#echo "$query<br>";
query_sql($query,$result);

#echo "<table class='spacer' width='750' border='0'><tr><td><h3>$judul</h3></td><td align='right' style='padding-right:2px'>Page: $pageNum</td></tr>";
echo "<h3>$judul</h3>";
echo "Confirmed: $num ";

# print table header
echo "<form action='save2db.php' method='post'>";
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>NIK</th><th>Nama</th><th>Bidang</th><th>Confirm</th><th>Attend</th></tr></thead>";
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
		<td><input type="checkbox" id="cattend[]" name="attend[]" value="<?= $row["nik"]; ?>" <? if ($row["attend"]==1 && $row["nik"]==$_SESSION["nik_login"]) echo 'checked disabled'; ?>></td>
	</tr>
<?
	$no++;
}
echo "</tbody>";
echo "<input type='hidden' name='idk' value='$_REQUEST[idk]'>";
echo "<input type='hidden' name='sw' value='19'>";
echo "<tr align='center'><td colspan='6'><input type='submit' name='submit' value='Submit' class='button'></td></tr>";
echo "</form>";
echo "</table>";
?>
