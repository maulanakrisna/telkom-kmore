<?
session_start();

// http://www.askapache.com/php/phpfreaks-eric-rosebrocks-phpmailer-tutorial.html
include('include/dbcon.php');

// Grab our config settings
include('libs/config.php');
 
// Grab the FreakMailer class
include('libs/MailClass.inc');

$flag=0;
switch ($_REQUEST['sw']) {

	case "11" :
		// --- Submitter: Create New Request Sharing Knowledge
		// call from: req_fill_in.php
		// Status: Running

		include ("include/convertdatetime.php");
		$t_mulai = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jmulai].":".$_REQUEST[mmulai]));
		$t_akhir = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jusai].":".$_REQUEST[musai]));

		// Check if date is conflict with sharing schedule
		$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status='3' AND ";
		$q .= "((t_mulai BETWEEN '$t_mulai' AND '$t_akhir') OR (t_akhir BETWEEN '$t_mulai' AND '$t_akhir')) LIMIT 1";
		#echo $q;
		query_sql($q,$r);
		$n = mysql_num_rows($r);
		if ($n > 0) {
			$conflict = 3;
		} else {
			// Check if date is conflict with sharing request
			$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status='1' AND ";
			$q .= "((t_mulai BETWEEN '$t_mulai' AND '$t_akhir') OR (t_akhir BETWEEN '$t_mulai' AND '$t_akhir')) LIMIT 1";
			#echo $q;
			query_sql($q,$r);
			$n = mysql_num_rows($r);
			if ($n > 0) {
				$conflict = 1;
			} else {
				$conflict = 0;
			}
		}

		// get member of speaker
		$bid = $_REQUEST[niklain];
		$niklain = array();
		foreach ($bid AS $key => $value) {
			if (strlen($value)>0)
				$niklain[] = $value;
		}
		$member = implode(",",$niklain);

		// get nik & bidang
		$bid = $_REQUEST[bidang];
		$inv_bidang = implode(",",$bid);
		#echo "\$inv_bidang: $inv_bidang<br>";

		$your_nik  = explode("-",$_REQUEST['nik']);
		// save to knowledge table
		$q = "INSERT INTO knowledge (submitter, nik, member, ext_speaker, instansi, id_map, jenis, judul, t_mulai, t_akhir, lokasi, unitkerja, inv_bidang, ext_audience, abstraksi, harapan, referensi, created, conflict) VALUES ( '$_REQUEST[submitter]', '$your_nik[0]', '$member', '$_REQUEST[ext_speaker]', '$_REQUEST[instansi]', '$_REQUEST[id_map]', '$_REQUEST[jenis]', '$_REQUEST[judul]', '$t_mulai', '$t_akhir', '$_REQUEST[lokasi]', '$_REQUEST[unitkerja]', '$inv_bidang', '$_REQUEST[exaudien]', '$_REQUEST[abstraksi]', '$_REQUEST[harapan]', '$_REQUEST[referensi]', NOW(), '$conflict')";
		#echo "$q<br>";
		query_sql($q,$res);

		// get knowledge that just inserted
		$q  = "SELECT a.id_know,a.nik,a.judul,a.lokasi,b.nama,b.email,b.id_bidang ";
		$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik ORDER BY id_know DESC LIMIT 1"; 
		#echo "<br>==>&nbsp;$q<br>";
		$result  = mysql_query($q);
		$rows    = mysql_fetch_object ($result);
		$id_know = $rows->id_know;
		$nik     = $rows->nik;
		$name    = $rows->nama;
		$email   = $rows->email;
		$judul   = $rows->judul;
		$lokasi  = $rows->lokasi;
		$tgl     = date("d-m-Y",strtotime($t_mulai));
		$jam1    = date("H:i",strtotime($t_mulai));
		$jam2    = date("H:i",strtotime($t_akhir));
		$idb     = $rows->id_bidang;
		$ids     = 1;
		$nik_sgm = ""; $external = "";

		// send notification to committee by e-mail
		include("sendmail.php");
		sending($ids,$idb,$bid,$external,$nik,$name,$email,$judul,$lokasi,$tgl,$jam1,$jam2,$nik_sgm,$id_know);

		Header("Location: sharing.php?mn=1");
		break;

	case "12" :
		// --- Submitter: Update Request Sharing Knowledge
		// call from: req_fill_ed.php
		// Status: Running

		include ("include/convertdatetime.php");
		$t_mulai = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jmulai].":".$_REQUEST[mmulai]));
		$t_akhir = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jusai].":".$_REQUEST[musai]));

		if (isset($_POST['btnSubmit2']))
		{	// Closing sharing that expired
			$q  = "UPDATE knowledge SET member='$strmember', id_map='$_REQUEST[id_map]', jenis='$_REQUEST[jenis]', ";
			$q .= "ext_speaker='$_REQUEST[ext_speaker]', instansi='$_REQUEST[instansi]', ";
			$q .= "judul='$_REQUEST[judul]', t_mulai='$t_mulai', t_akhir='$t_akhir', lokasi='$_REQUEST[lokasi]', ";
			$q .= "inv_bidang='$inv_bidang', ext_audience='$_REQUEST[exaudien]', abstraksi='$_REQUEST[abstraksi]', ";
			$q .= "harapan='$_REQUEST[harapan]', referensi='$_REQUEST[referensi]', sharing_status='7' WHERE id_know='$_REQUEST[idk]'";
			#echo "<br>$q<br>";
			query_sql($q,$res);

			Header("Location: sharing.php?mn=4");
		}
		else
		{
			// Check if date is conflict with sharing schedule
			$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status='3' AND ";
			$q .= "((t_mulai BETWEEN '$t_mulai' AND '$t_akhir') OR (t_akhir BETWEEN '$t_mulai' AND '$t_akhir')) LIMIT 1";
			#echo $q;
			query_sql($q,$r);
			$n = mysql_num_rows($r);
			if ($n > 0) {
				$conflict = 1;
			} else {
				$conflict = 0;
			}

			// Check if date is conflict with sharing request
			$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status='1' AND ";
			$q .= "((t_mulai BETWEEN '$t_mulai' AND '$t_akhir') OR (t_akhir BETWEEN '$t_mulai' AND '$t_akhir')) LIMIT 1";
			#echo $q;
			query_sql($q,$r);
			$n = mysql_num_rows($r);
			if ($n > 0) {
				$conflict = 2;
			} else {
				$conflict = 0;
			}

			// get member of speaker
			$bid = $_REQUEST[niklain];
			$niklain = array();
			foreach ($bid AS $key => $value) {
				if (strlen($value)>0)
					$niklain[] = $value;
			}
			$member = implode(",",$niklain);

			// get nik & bidang
			$bid = $_REQUEST[bidang];
			$inv_bidang = implode(",",$bid);

			$q  = "UPDATE knowledge SET member='$strmember', id_map='$_REQUEST[id_map]', jenis='$_REQUEST[jenis]', ";
			$q .= "judul='$_REQUEST[judul]', t_mulai='$t_mulai', t_akhir='$t_akhir', lokasi='$_REQUEST[lokasi]', ";
			$q .= "ext_speaker='$_REQUEST[ext_speaker]', instansi='$_REQUEST[instansi]', ";
			$q .= "inv_bidang='$inv_bidang', ext_audience='$_REQUEST[exaudien]', abstraksi='$_REQUEST[abstraksi]', ";
			$q .= "harapan='$_REQUEST[harapan]', referensi='$_REQUEST[referensi]', conflict='$conflict' WHERE id_know='$_REQUEST[idk]'";
			#echo "<br>$q<br>";
			query_sql($q,$res);

			Header("Location: sharing.php?mn=1");
		}
		break;

	case "14" :
		// --- Invitee: Update Sharing Activity after invitee has confirm
		// call from: sharing_req_review.php
		// Status: Running
		// update invitation for bidang
		$q  = "UPDATE sharing_activity SET id_confirm='$_REQUEST[id_confirm]' ";
		$q .= "WHERE id_know='$_REQUEST[idk]' AND nik='$_SESSION[nik_login]'";
		#echo "<br>$q<br>-----";
		query_sql($q,$res);
		Header("Location: index.php?mn=21");
		break;

		// create email request sharing notification for committee
		/*
		$q="SELECT nik,nama,email FROM user WHERE id_profile<>'3'";
		query_sql($q,$res);
		$result = mysql_query($q);
		while ($row = mysql_fetch_array ($result)) {
			$query="INSERT INTO recipient (id_not,nik) VALUES ('$id_not','".$row['nik']."')";
			#echo "<br>$query<br>";
			#echo "<br>From: $nama ($from)<br>To: $row[nama] ($row[email])<br>Subject: $nm_subject<br>Message: $message $nama<br>Silakan klik <A HREF='http://$_SERVER[SERVER_NAME]/kmore/'>disini</A><br>";
			#query_sql($q,$res);
		}
		*/

	case "15" :
		// --- (before: Update Sharing Knowledge for Close Task)
		// --- Submitter: Update Sharing Knowledge for submit report
		// call from: sharing_req_review.php
		// Status: Running
		include ("include/convertdatetime.php");
		$q = "UPDATE knowledge SET harapan='$_REQUEST[harapan]', referensi='$_REQUEST[referensi]' WHERE id_know='$_REQUEST[idk]'";
		echo "<br>$q<br>-----";
		#query_sql($q,$res);
		Header("Location: index.php");
		break;

	case "16" :
		// --- External Invitee: Request To Attend Sharing Knowledge
		// call from: sharing_detail.php
		// Status: Running
		$q = "INSERT INTO req_to_attend (id_know,nik,created) VALUES ('$_REQUEST[idk]','$_REQUEST[nik]',NOW())";
		#echo "$q<br>-----<br>";
		query_sql($q,$res);

		$q  = "INSERT INTO sharing_activity (id_know,nik,id_bidang,id_confirm,id_inv_status) ";
		$q .= "VALUES ('$_REQUEST[idk]','$_REQUEST[nik]',(SELECT id_bidang FROM user WHERE nik='$_REQUEST[nik]'),'5','4')";
		#echo "$q<br>-----<br>";
		query_sql($q,$res);

		// send email to committee
		// get requester
		$q  = "SELECT a.nama,a.email,b.id_bidang,b.nm_bidang FROM user a JOIN bidang b ON a.id_bidang=b.id_bidang ";
		$q .= "WHERE nik ='$_REQUEST[nik]'";
		#echo "$q<br>";
		$result  = mysql_query($q);
		$rows    = mysql_fetch_object ($result);
		$nik     = $_REQUEST[nik];
		$name    = $rows->nama;
		$email   = $rows->email;
		$bid     = $rows->nm_bidang;
		$idb     = $rows->id_bidang;

		// get sharing contributor
		$q = "SELECT a.judul,a.t_mulai,b.nik,b.nama FROM knowledge a JOIN user b ON a.nik=b.nik WHERE id_know ='$_REQUEST[idk]'"; 
		#echo "$q<br>";
		$result  = mysql_query($q);
		$rows    = mysql_fetch_object ($result);
		$judul   = $rows->judul."#".$rows->nik."#".$rows->nama."#".$rows->t_mulai;
		$ids     = 4;

		require_once("sendmail.php");
		sending($ids,$idb,$bid,$external,$nik,$name,$email,$judul,$lokasi,$tgl,$jam1,$jam2,$nik_sgm,$_REQUEST[idk]);

		Header("Location: index.php?mn=3");
		break;

	case "18" :
		// --- Submitter: Close Sharing Knowledge Task
		// call from: sharing_req_close_d.php
		// Status: Running
		include("include/fgenkey.php");
		require('upload.php');

		// -------- Upload Attach file(s) --------
		if (isset($_POST['del'])) {
			$counter = $_POST[del];
			$files   = $_POST[nmfile];
			while (list(, $value) = each($counter)) {
				$kal = $kal.$value."' OR id='";
				// delete file(s)
				for ($x=0; $x<=count($files)-1 ; $x++) {
					$info = explode("/",$files[$x]);
					if ($info[0]==$value) {
						#echo " & filename: $info[1]<br />\n";
						$do = unlink($uploadDir.$info[1]);
						if ($do<>"1") {
							echo "There was an error trying to delete the file!<p>";
							exit;
						}
					}
				}
			}
			$sQuery = "DELETE FROM upload WHERE id='".$kal."'";
			#echo $sQuery."<br>";
			query_sql($sQuery,$res);
		}
		
		if ((strlen($_FILES['userfile1']['name'])<>0) || (strlen($_FILES['userfile2']['name'])<>0) || (strlen($_FILES['userfile3']['name']) <> 0)) {
			if (empty($_REQUEST['insert_key']))
			{
				$insert_key = gen_key();
			}
			else
			{
				$insert_key = $_REQUEST['insert_key'];
			}
		$upd_rdkey = ", randomkey='$insert_key'";
		}
		else
		{	// do nothing
			$upd_rdkey = "";
		}

		if ((strlen($_FILES['userfile1']['name'])<>0) || (strlen($_FILES['userfile2']['name'])<>0) || (strlen($_FILES['userfile3']['name']) <> 0))
		{
			if ($_REQUEST[download]=="0") $dw_status=1; else $dw_status="$_REQUEST[download]";
			for ($x=1; $x<4; $x++)
			{
				// $userfile_name is original file name
				$fileName = str_replace(' ','_',$_FILES['userfile'.$x]['name']);
				// $userfile is where file went on webserver
				$tmpName  = str_replace(' ','_',$_FILES['userfile'.$x]['tmp_name']);
				// $userfile_size is size in bytes
				$fileSize = $_FILES['userfile'.$x]['size'];
				// $userfile_type is MIME type
				$fileType = $_FILES['userfile'.$x]['type'];

				// the files will be saved in filePath
				$filePath = $uploadDir . $fileName;

				// move the files to the specified directory
				// if the upload directory is not writable or
				// something else went wrong $result will be false

				$fnameOri = $fileName;
				if (strlen($fileName)<>0)
				{
					$i = 1;
					while (file_exists($uploadDir.$fileName))
					{
						$fileName = str_replace('.',$i.".",$fnameOri);
						$filePath = $uploadDir.$fileName;
						$i++;
					}
					$result   = move_uploaded_file($tmpName, $filePath);
					if (!$result)
					{
						echo "Error uploading file: ".$fileName."<br>";
						//exit;
					}
					else
					{
						if (!get_magic_quotes_gpc())
						{
							$fileName  = addslashes($fileName);
							$filePath  = addslashes($filePath);
						}
						$query  = "INSERT INTO upload (name, size, type, path, randomkey) ";
						$query .= "VALUES ('$fileName', '$fileSize', '$fileType', '$filePath', '$insert_key')";
						#echo $query;
						query_sql($query,$res);
					}
				}
			}
		}
		else
		{
			$dw_status=0;
		}
		// -------- End of Upload Attach file(s) --------

		$q  = "UPDATE knowledge SET abstraksi='$_REQUEST[abstraksi]', harapan='$_REQUEST[harapan]', ";
		$q .= "referensi='$_REQUEST[referensi]', sharing_status='5'".$upd_rdkey." WHERE id_know='$_REQUEST[idk]'";
		#echo "<br>$q<br>";
		query_sql($q,$res);

		Header("Location: sharing.php?mn=32&idk=$_REQUEST[idk]");
		break;

	case "19" :
		// --- updating sharing_activity
		// call from: sharing_attend_edt.php
		// Status: Running

		// get nik
		$nik_attend = "'".implode("','",$_REQUEST[attend])."'";

		$q = "UPDATE sharing_activity SET attend='1' WHERE id_know='$_REQUEST[idk]' AND nik IN ($nik_attend)";
		#echo "$q<br>";
		query_sql($q,$res);

		// send email close task notification to committee
		$q  = "SELECT a.id_know,a.nik,a.judul,b.nama,b.email,b.id_bidang ";
		$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik WHERE id_know ='$_REQUEST[idk]'"; 
		#echo "$q<br>";
		$result  = mysql_query($q);
		$rows    = mysql_fetch_object ($result);
		$id_know = $rows->id_know;
		$nik     = $rows->nik;
		$name    = $rows->nama;
		$email   = $rows->email;
		$judul   = $rows->judul;
		$idb     = $rows->id_bidang;
		$ids     = 8;

		require_once("sendmail.php");
		sending($ids,$idb,$bid,$external,$nik,$name,$email,$judul,$lokasi,$tgl,$jam1,$jam2,$nik_sgm,$id_know);

		Header("Location: sharing.php?mn=3");
		break;

// ---------- //

	case "21" :
		// --- Committee: Sharing Knowledge Approval
		// call from: mgm_req_review.php
		// Status: Running
		include ("include/convertdatetime.php");
		$t_mulai = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jmulai].":".$_REQUEST[mmulai]));
		$t_akhir = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jusai].":".$_REQUEST[musai]));

		if ($_REQUEST[approval]==0) {
			$q  = "UPDATE knowledge SET t_mulai='$t_mulai', t_akhir='$t_akhir', lokasi='$_REQUEST[lokasi]', ";
			$q .= "sharing_status='2', app_req_by='$_SESSION[nik_login]', app_req_at=NOW() WHERE id_know='$_REQUEST[idk]'";
			#echo "$q<br>-----<br>";
			query_sql($q,$res);

			if (strlen($_REQUEST[catatan])>0) {
				$q  = "INSERT INTO sharing_notes (id_know, nik, created, notes) VALUES ";
				$q .= "('$_REQUEST[idk]', '$_SESSION[nik_login]', NOW(), '$_REQUEST[catatan]')";
				#echo "$q<br>-----<br>";
				query_sql($q,$res);
			}
		} else {

			$q  = "UPDATE knowledge SET t_mulai='$t_mulai', t_akhir='$t_akhir', lokasi='$_REQUEST[lokasi]', ";
			$q .= "sharing_status='3', app_req_by='$_SESSION[nik_login]', app_req_at=NOW() WHERE id_know='$_REQUEST[idk]'";
			#echo "1. Approval Request Sharing:<br>$q<br>-----<br>";
			query_sql($q,$res);

			if (strlen($_REQUEST[catatan])>0) {
				$q  = "INSERT INTO sharing_notes (id_know, nik, created, notes) ";
				$q .= "VALUES ('$_REQUEST[idk]', '$_SESSION[nik_login]', NOW(), '$_REQUEST[catatan]')";
				#echo "$q<br>-----<br>";
				query_sql($q,$res);
			}
			// create sharing activity table for contributor/speaker
			$sid_bidang = $_SESSION[id_bidang];
			$tSQL  = "INSERT INTO sharing_activity (id_know,nik,id_bidang,id_confirm,id_inv_status,attend) VALUES ";
			$tSQL .= "('$_REQUEST[idk]','$_REQUEST[niknya]',(SELECT id_bidang FROM user WHERE nik='$_REQUEST[niknya]'),'1','1','1')";
			#echo "2. Create sharing activity table for speaker:<br>$tSQL<br>-----<br>";
			query_sql($tSQL,$res);

			// create sharing activity table for team member
			if (strlen($_REQUEST[member]) > 0) {
				$niks = explode(",",$_REQUEST[member]);
				#echo "3. Create sharing activity table for member of speaker:<br>";
				#echo "<br>\$_REQUEST[member]: $_REQUEST[member]<br>";
				$member = array();
				foreach ($niks as $key => $value) {
					$tSQL  = "INSERT INTO sharing_activity (id_know,nik,id_bidang,id_confirm,id_inv_status,attend) VALUES ";
					$tSQL .= "('$_REQUEST[idk]','$value',(SELECT id_bidang FROM user WHERE nik='$value'),'1','2','1');";
					#echo "3. Create sharing activity table for member of speaker:<br>$tSQL<br>-----<br>";
					query_sql($tSQL,$res);
					$member[] = $value;
				}
				#echo "<br>-----";
				$members = "'".implode("','",$member)."'";
			}

			// get bidang
			$bid = explode(",",$_REQUEST[inv_bid]);
			#echo "$_REQUEST[inv_bid]<br>";
			#echo "<br>5. Create sharing activity table for Invitee (Audience):";
			#echo "<br>\$_REQUEST[inv_bid]: $_REQUEST[inv_bid]";

			#$tSQL = "SELECT COUNT(*) AS jml FROM bidang_test";
			$tSQL = "SELECT COUNT(*) AS jml FROM bidang";
			#echo "$tSQL<br>";
			$result = mysql_query($tSQL);
			$rows = mysql_fetch_object ($result);
			$jml = $rows->jml-1;

			// create sharing activity table for SM RDC if invite RDC
			$nik_sgm = "";
			if (count($bid) == $jml)
			{
				// before...
				#$q="INSERT INTO sharing_activity (id_know,nik,id_inv_status) VALUES ('$_REQUEST[idk]',(SELECT nik FROM user_test WHERE ID_LOKER='100'),'3')";
				// after...
				$q = "SELECT nik FROM user WHERE ID_LOKER='100'";
				query_sql($q,$res);
				$rows = mysql_fetch_object ($res);
				$nik_sgm = $rows->nik;

				$q  = "INSERT INTO sharing_activity (id_know,nik,id_bidang,id_inv_status) ";
				$q .= "VALUES ('$_REQUEST[idk]',(SELECT nik FROM user WHERE ID_LOKER='100'),'100','3')";
				#echo "4. Create sharing activity table for SM RDC<br>$tSQL<br>-----<br>";
				query_sql($q,$res);
			}

			// you have member of speaker
			if (strlen($_REQUEST[member]) > 0)
			{
				$qadd = " AND nik NOT IN ($members)";
			}
			else
			{
				$qadd = "";
			}

			// create sharing activity table for target audience
			foreach ($bid as $key => $value) {
				#$q="SELECT * FROM user_test WHERE id_loker LIKE '".substr($value,0,2)."%' AND active='1' AND nik <> '$_REQUEST[niknya]'".$qadd;
				$q  = "SELECT * FROM user WHERE id_loker LIKE '".substr($value,0,2)."%' ";
				$q .= "AND active='1' AND nik <> '$_REQUEST[niknya]'".$qadd;
				#echo "5. Create sharing activity table for Audience<br>$q<br>-----<br>";
				query_sql($q,$res);
				$result = mysql_query($q);
				while ($row = mysql_fetch_array ($result)) {
					$id_bid = $row[id_bidang];
					$tSQL   = "INSERT INTO sharing_activity (id_know,nik,id_bidang,id_inv_status) ";
					$tSQL  .= "VALUES ('$_REQUEST[idk]','$row[nik]',$id_bid,'3')";
					#echo "$tSQL<br>";
					query_sql($tSQL,$res);
				}
				#echo "-----<br>";
			}

			// get subject & message from subject table
			#$q="SELECT nama,nik,email FROM user WHERE NIK = (SELECT nik FROM knowledge WHERE id_know='$_REQUEST[idk]')";
			$q  = "SELECT a.id_know,a.nik,a.judul,a.lokasi,b.nama,b.email,b.id_bidang ";
			$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik WHERE id_know='$_REQUEST[idk]'"; 
			#echo "//get subject & message from subject table<br>$q<br>-----<br>";
			$id_know= $_REQUEST[idk];
			$result = mysql_query($q);
			$rows   = mysql_fetch_object($result);
			$nik    = $rows->nik;
			$name   = $rows->nama;
			$email  = $rows->email;
			$judul  = $rows->judul;
			$lokasi = $rows->lokasi;
			$idb    = $rows->id_bidang;
			$tgl    = date("d-m-Y",strtotime($t_mulai));
			$jam1   = date("H:i",strtotime($t_mulai));
			$jam2   = date("H:i",strtotime($t_akhir));
			$ids    = 7;

			require_once("sendmail.php");
			sending($ids,$idb,$bid,$external,$nik,$name,$email,$judul,$lokasi,$tgl,$jam1,$jam2,$nik_sgm,$id_know);
		}

		Header("Location: management.php?mn=13");
		break;

	case "22" :
		// --- Committee: Close Task Sharing Knowledge Approval
		// call from: mgm_req_close_d.php
		// Status: Running

		if ($_REQUEST[approval]==0)
		{	// report is rejected
			$q  = "UPDATE knowledge SET sharing_status='3', app_report_by='$_SESSION[nik_login]', app_report_at=NOW() ";
			$q .= "WHERE id_know='$_REQUEST[idk]'";
			#echo "1. Approval Close Sharing Report:<br>$q<br>-----";
			query_sql($q,$res);
			if (strlen($_REQUEST[catatan])>0)
			{
				$q  = "INSERT INTO closing_notes (id_know,created,nik,notes) ";
				$q .= "VALUES ('$_REQUEST[idk]',NOW(),'$_SESSION[nik_login]','$_REQUEST[catatan]')";
				#echo "$q<br>";
				query_sql($q,$res);
			}
		}
		else
		{	// report is approved!
			$q  = "UPDATE knowledge SET sharing_status='6', app_report_by='$_SESSION[nik_login]', app_report_at=NOW(),";
			$q .= "report_notes='$_REQUEST[catatan]' WHERE id_know='$_REQUEST[idk]'";
			#echo "1. Approval Close Sharing Report:<br>$q<br>-----<br>";
			query_sql($q,$res);

			// check if member of team, external audience is exist and count amount of bidang
			$q="SELECT member,inv_bidang,ext_audience FROM knowledge WHERE id_know='$_REQUEST[idk]'";
			#echo "2. Check if member of team, external audience is exist and count amount of bidang:<br>$q<br>-----<br>";
			query_sql($q,$res);
			$result = mysql_query($q);
			$r = mysql_fetch_object ($result);

			// check amount of bidang invited
			// 1-2 bidang: 10
			// 3-6 bidang: 20 (if invite rdc & 3 bidang attend, else 10)
			// including ext attendance: 30

			$trims = trim($r->ext_audience);
			$bidang = explode(",",$r->inv_bidang);
			$jmlbidang = count($bidang);
			#echo "\$jmlbidang: $jmlbidang<br>";

			// count bidang from attendance sheet
			$q  = "SELECT COUNT(DISTINCT b.id_bidang) FROM sharing_activity a ";
			$q .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker JOIN confirm d ON a.id_confirm=d.id_confirm ";
			$q .= "WHERE id_know='$_REQUEST[idk]' AND attend='1'";
			#echo "3. Check bidang attended:<br>$q<br>-----<br>";
			query_sql($q,$res);
			$result = mysql_query($q);
			$num = mysql_num_rows ($result);
			switch ($jmlbidang) {
				case ($jmlbidang > 2 && $jmlbidang <= 6) :
					if ($num >= 3)
						$point = 20;
					else
						$point = 10;
					$tipe = 3;
					break;
				case ($jmlbidang==1 || $jmlbidang==2) :
					$point = 10;
					$tipe = 1;
					break;
			}

			if (strlen($trims)>0)
			{
				$point = 30;
				$tipe = 4;
			}
			
			// check if member of speaker is exist
			$q = "SELECT COUNT(*) AS hasil FROM sharing_activity WHERE id_know='$_REQUEST[idk]' AND id_inv_status<'3'";
			#echo "4. Check if member of speaker is exist:<br>$q<br>-----<br>";
			query_sql($q,$res);
			$result = mysql_query($q);
			$r = mysql_fetch_object ($result);
			if ($r->hasil > 1)
			{
				$yourpoint = ceil($point/$r->hasil);
			}
			else
			{
				$yourpoint = $point;
			}
			// compute contributor point based on attendance sheet
			$q = "UPDATE sharing_activity SET poin='$yourpoint', tipe='$tipe' WHERE id_know='$_REQUEST[idk]' AND id_inv_status<'3'";
			#echo "5. Compute submitter point based on daftar hadir:<br>$q<br>-----<br>";
			query_sql($q,$res);

			// compute attendant point if exist based on attendance sheet
			$q = "UPDATE sharing_activity SET poin=2, tipe=5 WHERE id_know='$_REQUEST[idk]' AND id_inv_status>2 AND attend=1 AND nik<>'602217'";
			#echo "6. Compute attendant point if exist based on daftar hadir:<br>$q<br>-----<br>";
			query_sql($q,$res);
		}

		Header("Location: management.php?mn=33");
		break;

	case "23" :
		// --- Committee: Request To Attend Sharing Knowledge Approval
		// call from: mgm_req_attend_d.php
		// Status: Running
		$q  = "UPDATE req_to_attend SET approve_by='$_REQUEST[nik_commit]', approve_date=NOW() ";
		$q .= "WHERE id_know='$_REQUEST[idk]' AND nik='$_REQUEST[nik]'";
		echo "$q<br>";
		#query_sql($q,$res);

		$q  = "UPDATE sharing_activity SET id_confirm='$_REQUEST[id_confirm]' ";
		$q .= "WHERE id_know='$_REQUEST[idk]' AND nik='$_REQUEST[nik]'";
		echo "$q<br>";
		#query_sql($q,$res);

		// get name & email of committee
		$q = "SELECT nama,email FROM user WHERE nik='$_REQUEST[nik_commit]'";
		#echo "$q<br>";
		$result     = mysql_query($q);
		$rows       = mysql_fetch_object($result);
		$from_name  = $rows->nama;
		$from_email = $rows->email;

		// get requester of request to attend
		$q = "SELECT nama,email FROM user WHERE nik='$_REQUEST[nik]'";
		#echo "$q<br>";
		$result = mysql_query($q);
		$rows   = mysql_fetch_object($result);
		/* tester
		$name   = 'Lutfi A.'; #$rows->nama;
		$email  = 'lutfi_san@yahoo.com'; #$rows->email;
		*/

		// get confirm status
		$q = "SELECT nm_confirm_id FROM confirm WHERE id_confirm='$_REQUEST[id_confirm]'";
		#echo "<br>$q<br>";
		$result  = mysql_query($q);
		$rows    = mysql_fetch_object($result);
		$confirm = $rows->nm_confirm_id;
		$ids     = 5;

		// send notification to request to attend requester by email
		#include("sendmail.php");
		#send2sender($_REQUEST[idk],$ids,$from_name,$from_email,$name,$email,$confirm);

		Header("Location: management.php?mn=2");
		break;

	case "44" :
		// --- Add New Knowledge Map
		// call from: .php
		// Status: Running
		$q  = "INSERT INTO knowledge_map (id_map,nm_map,expert,level,id_top) VALUES ";
		$q .= "('$_REQUEST[id_map]', '$_REQUEST[nm_map]', '$_REQUEST[expert]', '$_REQUEST[level]', '$_REQUEST[id_top]')";
		echo "<br>$q<br>";
		#query_sql($q,$res);
		Header("Location: admin.php?mn=4");

	case "42" :
		// --- Update Knowledge Map
		// call from: .php
		// Status: Running
		$q = "UPDATE knowledge_map SET nm_map='$_REQUEST[nm_map]', expert='$_REQUEST[expert]' WHERE id_map='$_REQUEST[idm]'";
		#echo "<br>$q<br>";
		query_sql($q,$res);
		Header("Location: admin.php?mn=4");

	case "51" :
		// --- Submitter: Create Sharing Knowledge finished
		// call from: adm_know_add.php
		// Status: Running

		include ("include/convertdatetime.php");
		$t_mulai = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jmulai].":".$_REQUEST[mmulai]));
		$t_akhir = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jusai].":".$_REQUEST[musai]));

		$conflict = 0;

		// get member of speaker
		$bid = $_REQUEST[niklain];
		$niklain = array();
		foreach ($bid AS $key => $value) {
			if (strlen($value)>0)
				$niklain[] = $value;
		}
		$member = implode(",",$niklain);

		// get nik & bidang
		$bid = $_REQUEST[bidang];
		$inv_bidang = implode(",",$bid);
		#echo "\$inv_bidang: $inv_bidang<br>";

		$your_nik  = explode("-",$_REQUEST['nik']);
		// save to knowledge table
		$q = "INSERT INTO knowledge (nik, member, ext_speaker, instansi, id_map, jenis, judul, t_mulai, t_akhir, lokasi, unitkerja, inv_bidang, ext_audience, abstraksi, harapan, referensi, created, conflict, sharing_status, archive) VALUES ('$your_nik[0]', '$member', '$_REQUEST[ext_speaker]', '$_REQUEST[instansi]', '$_REQUEST[id_map]', '$_REQUEST[jenis]', '$_REQUEST[judul]', '$t_mulai', '$t_akhir', '$_REQUEST[lokasi]', '$_REQUEST[unitkerja]', '$inv_bidang',  '$_REQUEST[exaudien]', '$_REQUEST[abstraksi]', '$_REQUEST[harapan]', '$_REQUEST[referensi]', NOW(), '$conflict', '6', '1')";
		echo "$q<br>";
		#query_sql($q,$res);

		Header("Location: management.php?mn=5");
		break;

	case "52" :
		// --- Submitter: Update Request Sharing Knowledge
		// call from: req_fill_ed.php
		// Status: Running

		include ("include/convertdatetime.php");
		$t_mulai = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jmulai].":".$_REQUEST[mmulai]));
		$t_akhir = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jusai].":".$_REQUEST[musai]));

		// get member of speaker
		$bid = $_REQUEST[niklain];
		$niklain = array();
		foreach ($bid AS $key => $value) {
			if (strlen($value)>0)
				$niklain[] = $value;
		}
		$member = implode(",",$niklain);

		// get nik & bidang
		$bid = $_REQUEST[bidang];
		$inv_bidang = implode(",",$bid);

		$q  = "UPDATE knowledge SET judul='$_REQUEST[judul]', jenis='$_REQUEST[jenis]', id_map='$_REQUEST[id_map]', ";
		$q .= "nik='$_REQUEST[nik]', member='$strmember', t_mulai='$t_mulai', t_akhir='$t_akhir', lokasi='$_REQUEST[lokasi]', ";
		$q .= "inv_bidang='$inv_bidang', ext_instance='$_REQUEST[exinstance]', abstraksi='$_REQUEST[abstraksi]' ";
		$q .= "WHERE id_know='$_REQUEST[idk]'";
		#echo "<br>$q<br>";
		query_sql($q,$res);

		Header("Location: management.php?mn=5");
		break;

	case "61" :
		// --- Add User
		// call from: adm_user_add.php
		// Status: Running
		$id_bidang  = substr($_REQUEST[id_loker],0,2)."0";
		$q = "INSERT INTO user SET (nik, nama, band, posisi, id_bidang, id_loker, email, id_profile, active) VALUES ('$_REQUEST[nik]','$_REQUEST[nama]', '$_REQUEST[band]', '$_REQUEST[posisi]', '$id_bidang', '$_REQUEST[id_loker]', '$_REQUEST[email]', '$_REQUEST[id_profile]', '$_REQUEST[active]')";
		#echo "<br>$q<br>";
		query_sql($q,$res);
		Header("Location: admin.php?mn=1");
		break;

	case "62" :
		// --- Edit User
		// call from: adm_user_edt.php
		// Status: Running
		$id_bidang  = substr($_REQUEST[id_loker],0,2)."0";
		$q = "UPDATE user SET nik='$_REQUEST[nik]', nama='$_REQUEST[nama]', band='$_REQUEST[band]', posisi='$_REQUEST[posisi]', id_bidang='$id_bidang', id_loker='$_REQUEST[id_loker]', email='$_REQUEST[email]', id_profile='$_REQUEST[id_profile]', active='$_REQUEST[active]' WHERE nik=$_REQUEST[nik]";
		#echo "<br>$q<br>";
		query_sql($q,$res);
		Header("Location: admin.php?mn=1");
		break;

	case "64" :
		// --- Edit Loker
		// call from: adm_loker_edt.php
		// Status: Running
		$q = "UPDATE loker SET nm_loker='$_REQUEST[nm_loker]', acronym='$_REQUEST[acronym]', id_top='$_REQUEST[id_top]' WHERE id_loker=$_REQUEST[idl]";
		#echo "<br>$q<br>";
		query_sql($q,$res);
		Header("Location: admin.php?mn=2");
		break;

	case "65" :
		// --- Add Bidang
		// call from: adm_user_add.php
		// Status: Running
		$q = "INSERT INTO bidang SET (id_bidang, nm_bidang, singkatan, email) VALUES ('$_REQUEST[id_bidang]','$_REQUEST[nm_bidang]', '$_REQUEST[singkatan]', '$_REQUEST[email]')";
		#echo "<br>$q<br>";
		query_sql($q,$res);
		Header("Location: admin.php?mn=3");
		break;

	case "66" :
		// --- Edit Bidang
		// call from: adm_bidang_edt.php
		// Status: Running
		$q = "UPDATE bidang SET nm_bidang='$_REQUEST[nm_bidang]', singkatan='$_REQUEST[singkatan]', email='$_REQUEST[email]' WHERE id_bidang=$_REQUEST[idb]";
		#echo "<br>$q<br>";
		query_sql($q,$res);
		Header("Location: admin.php?mn=3");
		break;

	default :
		break;
}
?>