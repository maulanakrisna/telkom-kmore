<?
/* 
Nama Program: getid_linux.php
Pembuat		: Lutfi
tgl. buat	: Thursday, Jun 19, 2008 9:49:37 AM .281
tgl. revisi	: Thursday, Jun 19, 2008 9:49:37 AM .281
Deskripsi	: Koneksi ke MS SQL Server via Apache on Linux untuk mengambil data NIK dan
			  koneksi ke MysQL untuk memeriksa apakah ybs terdaftar sbg member Lab IP apa bukan
Skrip asal	: -
Skrip tujuan: -
*/

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

// --- Get User Authentication ---
	#$sqlsvr = "MS SQL Server 2000";
	#$websvr = "Apache (Linux - Ubuntu 7.04)";
	#$dbtype = "mssql";

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
	$result = mssql_query($sql, $db_conn) or die ("<strong>ERROR: Query failed</strong>");

	# fetch the data from the database
	#$rs = mssql_fetch_row($result);
	#print "Here is your MS SQL data: $rs[0]";

	if ($sqlrow = mssql_fetch_array($result))
	{
		session_start();
		$_SESSION['sid'] = $_REQUEST[id];
		$sql2 = "SELECT a.nik, a.nama, a.id_profile, a.id_bidang, b.nm_profile, c.nm_loker FROM user AS a JOIN profile b ON a.id_profile=b.id_profile JOIN loker c ON a.id_loker=c.id_loker WHERE a.nik ='".$sqlrow[2]."'";
		#echo "$sql2<br>";
		$result = mysql_query($sql2) or die('Mysql Err. 1');
		$num = mysql_num_rows($result);

		#$result = mysql_query($sql);
		if ($num > 0)
		{
			$row = mysql_fetch_array($result);
			#session_start();
			$_SESSION['nik_login'] = $row[nik];
			#$_SESSION['nik'] = $row[nik];
			$_SESSION['nama'] = $row[nama];
			$_SESSION['email'] = $row[email];
			$_SESSION['id_bidang'] = $row[id_bidang];
			$_SESSION['id_loker'] = $row[id_loker];
			$_SESSION['nm_loker'] = $row[nm_loker];
			$_SESSION['id_profile'] = $row[id_profile];
			$_SESSION['nm_profile'] = $row[nm_profile];
			$_SESSION['found']=1;
		}
		else
		{
			echo "Data not found!<br>";
			$_SESSION['found']=0;
		}
	}
	else
	{
		$_SESSION['found']=0;
	}
?>