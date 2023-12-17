<?
	include("include/dbcon.php");
	if (isset($_REQUEST['idk'])) {
	$id = $_REQUEST['idk'];
	$filename = "upload";
	$query   = "SELECT name, type, size, path FROM ".$filename." WHERE id = '$id'";
	#echo "$query<br>";
	$result  = mysql_query($query) or die('Error, query failed');
	list($name, $type, $size, $filePath) = mysql_fetch_array($result);

	header("Content-Disposition: attachment; filename=$name");
	header("Content-length: $size");
	header("Content-type: $type");

	readfile($filePath);
	#$query   = "INSERT INTO feedback SET (id_know,nik) VALUES ('$_REQUEST[idk]','$_SESSION[nik_login]')";
	#query_sql($query,$res);
	#echo "Sukses";
	exit;
	}
?>
