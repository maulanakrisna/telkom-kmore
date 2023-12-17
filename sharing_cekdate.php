<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>KMORE - Check Schedule Date</title>
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<link type="text/css" href="style/master.css" rel="stylesheet"/>
<style type="text/css">
table tr td {
	vertical-align: top;
	border: 0px solid #408080;
}
td {
	padding: 0 3;
}
h2 {
	padding: 0 auto;
	text-align: center;
}
.mycolor {
	background: #A9D3BB;
}
.mycolor2 {
	background: #F2F2F2;
a.thick:link { text-decoration: none; color: #0000FF; }
a.thick:visited { text-decoration: none; color: #0000FF; }
a.thick:hover { text-decoration: underline; color: #FF0000; }
a.confirm:link { text-decoration: none; color: #0000FF; }
}
</style>
</head>
<body>
<?
require_once ("include/dbcon.php");
require_once ("include/convertdatetime.php");

$q  = "SELECT b.nama,a.judul,a.t_mulai,a.t_akhir,a.sharing_status FROM knowledge a ";
$q .= "JOIN user b ON a.nik=b.nik WHERE t_mulai >= NOW() ORDER BY t_mulai";
query_sql($q,$result);
$num = mysql_num_rows($result);

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th><th>Nama</th><th>Judul</th><th>Mulai</th><th>Status</th></tr></thead>";
echo "<tbody>";

# print table rows
$no =  $offset+1;
while ($row = mysql_fetch_array($result))
{
?>
	<tr valign="top">
		<td align="right" width="10px"><?= $no; ?>.</td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["judul"]; ?></td>
		<td><?= ConvertJustDate($row["t_mulai"]); ?><br><?= substr($row["t_mulai"],11,5)." - ". substr($row["t_akhir"],11,5);; ?></td>
		<td><? if ($row["sharing_status"]=='1') echo "Request"; else echo "On Schedule"; ?></td>
	</tr>
<?
	$no++;
}
echo "</tbody>";

?>
</body>
</html>
