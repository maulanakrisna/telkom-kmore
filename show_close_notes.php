<?
session_start();
if (!isset($_SESSION['nik_login']))
{
	Header("Location:login.php");
}
?>
<table>
<?
include ("include/convertdatetime.php");
include ("include/dbcon.php");
$q = "SELECT a.*,b.nama FROM closing_notes a JOIN user b ON a.nik=b.nik WHERE id_know='$_REQUEST[id]'";
#echo "$q<br>";
query_sql($q,$result);
$num = mysql_num_rows($result);
if ($num > 0) {
	while ($r = mysql_fetch_object ($result)) {
		$yournotes = str_replace("\r", "<br/>", $r->notes);
		echo "<tr><td>";
		echo tampilkan_waktunya($r->created)."<br>";
		echo "$r->nik - ";
		echo "$r->nama<br>";
		echo "$yournotes<br>";
		echo "----------";
		echo "</td></tr>";
	}
} else {
	echo "<tr><td>Tidak ada catatan</td></tr>";
}
?>
<tr><td><br><input type="submit" value="Close" onclick="self.parent.tb_remove();"></td></tr>
</table>