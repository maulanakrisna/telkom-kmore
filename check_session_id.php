<?php 
	$dbhost  = "10.14.0.64:1433";
	$dbuser  = "ip";
	$dbpass  = "telkomrdc";
	$dbtable = "v_WargaRistiNew";
	$dbname  = "member";

	# connect to a DSN "DSN_NAME" with a user "Bob" and password "Marley"
	$db_conn = mssql_pconnect($dbhost, $dbuser, $dbpass) or die ("<strong>ERROR: Connection to MyServer2k failed</strong>");

	# query the users table for all fields
	$query = mssql_select_db($dbname,$db_conn) or die ("<strong>ERROR: Selecting database failed</strong>");

	# perform the query
	#
	$sql = "SELECT * FROM ".$dbtable." WHERE id='".$_REQUEST[id]."'";
	$rs = mssql_query($sql, $db_conn) or die ("<strong>ERROR: Query failed</strong>");

	# fetch the data from the database
	#$rs = mssql_fetch_row($result);
	echo $sql."<br>";
	echo "mssql_num_rows(\$result): ".mssql_num_rows($rs)."<br>";
	if (mssql_num_rows($rs)==0)
	{
		echo "Data not found!";
	}
	else
	{
		$sqlrow = mssql_fetch_array($rs);
		echo "Here is!<br>Your NIK is ".$sqlrow[2];
	}
?>