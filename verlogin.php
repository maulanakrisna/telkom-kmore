<?
include("include/dbcon.php");

function login($uname, $pword) {
	/*
	$uname = mysql_real_escape_string($uname);
	$pword = mysql_real_escape_string($pword);
	*/
	#$sQuery = "SELECT * FROM tm_user WHERE uname='$uname' AND pword=PASSWORD('$pword')";
	$sQuery = "SELECT a.nik, a.nama, a.email, a.id_bidang, a.id_loker, c.nm_loker, a.id_profile, d.nm_profile FROM user a  JOIN loker c ON c.id_loker = a.id_loker JOIN profile d ON d.id_profile = a.id_profile WHERE a.nik ='$uname' AND a.password= MD5('$pword') AND a.active='1'";
	//$sQuery = "SELECT a.nik, a.nama, a.email, a.id_bidang, a.id_loker, a.id_profile, d.nm_profile FROM user a JOIN profile d ON d.id_profile = a.id_profile WHERE a.nik ='$uname' AND a.password= MD5('$pword') AND a.active='1'";
	#echo $sQuery."<br>";
	$result = mysql_query($sQuery) or die ("error query");
	$row = mysql_fetch_object($result);
	
	if (mysql_num_rows($result)>0) {
		session_start();
		$_SESSION['nik_login'] = $row->nik;
		$_SESSION['nik'] = $row->nik;
		$_SESSION['nama'] = $row->nama;
		$_SESSION['email'] = $row->email;
		$_SESSION['id_bidang'] = $row->id_bidang;
		$_SESSION['id_loker'] = $row->id_loker;
		$_SESSION['nm_loker'] = $row->nm_loker;
		$_SESSION['id_profile'] = $row->id_profile;
		$_SESSION['nm_profile'] = $row->nm_profile;
		#$q1 = "INSERT INTO tt_log (id_user, time_login) VALUES ('$_SESSION[id]', NOW())";
		
		// Create Session ID
		$session_id=""; 
		srand((double)microtime()*1000000); 
		$session_id = md5(uniqid(rand()));
		$_SESSION[session_id] = $session_id;

		#$q1 = "UPDATE tm_user SET session_id='$session_id' WHERE id_user='$_SESSION[idm]'";
		#query_sql($q1,$res);
		Header("Location: home.php");
		/*
		$sQuery="SELECT id FROM tt_log ORDER BY id DESC LIMIT 1";
		$result = mysql_query($sQuery);
		$rows = mysql_fetch_object ($result);
		$_SESSION['idlog'] = $rows->id;
		if ($_SESSION['idm']=="1")
			Header("Location: index.php?ch=0");
		else
			Header("Location: index.php?ch=0&idm=$_SESSION[idm]");
		*/
	}
	else {
		//echo("wee username & password tidak sesuai");
		Header("Location: login.php?x=1");
	}
}

login($_REQUEST['uname'],$_REQUEST['pword']);

?>