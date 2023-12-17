<?php
// Show member of Portal
/*
Are you using SQL Server 2005 Express? Don't miss the server name syntax Servername\SQLEXPRESS 
where you substitute Servername with the name of the computer where the SQL Server 2005 Express installation resides.

Standard security:
Driver={SQL Native Client};Server=myServerAddress;Database=myDataBase;Uid=myUsername;Pwd=myPassword;
*/

// RDCDB - OK!
$dbhost  = "10.14.0.64:1433";
$dbuser  = "ip";
$dbpass  = "telkomrdc";
$dbname  = "member";
$dbtable = "v_WargaRistiNew";

/*
(3) Email
(4) id
(0) IDUser
(6) JobTitle
(5) LastLogin
(1) NamaLengkap
(2) NIK
(7) Posisi
(9) UnitKerja
(8) UnitKerjaDesc
*/
?>
<html>
<head>
<title>ADOdb (Apache on Windows)</title>
<style type="text/css">
body { font-family: tahoma; font-size: 0.9em; }
table tr td { font-family: tahoma; font-size: 0.8em; }
td { border-style: solid; padding-left: 4px; padding-right: 4px }
</style>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" width=800>
<?
echo "<h1>POINT User Login...</h1>";
echo "<h3>Connecting <font color=\"blue\">RDCDB</font> (MS SQL Server) using ADOdb via Apache (Windows)</h3>";
# connect to a DSN "DSN_NAME" with a user "Bob" and password "Marley"
$db_conn = mssql_pconnect($dbhost, $dbuser, $dbpass) or die ("<strong>ERROR: Connection to MyServer2k failed</strong>");

# query the users table for all fields
$query = mssql_select_db($dbname,$db_conn) or die ("<strong>ERROR: Selecting database failed</strong>");

# perform the query
#
$sql = "SELECT * FROM ".$dbtable." ORDER BY LastLogin DESC";
echo "$sql<br>";
$result = mssql_query($sql, $db_conn) or die ("<strong>ERROR: Query failed</strong>");

# fetch the data from the database
#$rs = mssql_fetch_row($result);
#echo"Here is your MS SQL data: $rs[0]";

echo "Result is:<P/>";
echo "<table border=1 cellspacing=0 cellpadding=1 width=900>";
echo "<tr bgcolor='#F0F0F0' align='center'><th>No.</th><th>NIK</th><th>Nama</th><th>Last Login</th><th>e-Mail Address</th><th>Bidang</th></tr>";
$i=1;
while ($rs = mssql_fetch_array($result))
{
	$info = explode('/', $rs[NamaLengkap]);
	echo "<tr valign='top'>";
	echo "<td align='right'>$i.</td><td>$rs[NIK]</td><td>$info[0]</td><td>".date('d-m-Y H:i:s',strtotime($rs[LastLogin]))."</td><td>$rs[Email]</td><td>$rs[UnitKerjaDesc]</td>";
	echo "</tr>";
	echo "<tr><td></td><td colspan='5'><i>$rs[id]</i></td></tr>";
	$i++;
}
echo "</table>";
?>
</body>
</html>
