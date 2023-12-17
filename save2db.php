<?
session_start();
include('include/dbcon.php');
include('libs/config.php');
include('libs/MailClass.inc');

$flag=0;
switch ($_REQUEST['sw']) {

	case "11" :
		include ("include/convertdatetime.php");
		$t_mulai = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jmulai].":".$_REQUEST[mmulai]));
		$t_akhir = date("Y-m-d H:i:s", strtotime($_REQUEST["start-date"]." ".$_REQUEST[jusai].":".$_REQUEST[musai]));
		$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status='3' AND ";
		$q .= "((t_mulai BETWEEN '$t_mulai' AND '$t_akhir') OR (t_akhir BETWEEN '$t_mulai' AND '$t_akhir')) LIMIT 1";
		query_sql($q,$r);
		$n = mysql_num_rows($r);
		if ($n > 0) {
			$conflict = 3; // conflict with sharing on schedule
		} else {
			// Check if date is conflict with sharing request
			$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status='1' AND ";
			$q .= "((t_mulai BETWEEN '$t_mulai' AND '$t_akhir') OR (t_akhir BETWEEN '$t_mulai' AND '$t_akhir')) LIMIT 1";
			#echo $q;
			query_sql($q,$r);
			$n = mysql_num_rows($r);
			if ($n > 0) {
				$conflict = 1; // conflict with sharing on request
			} else {
				$conflict = 0; // no conflict!
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
		#$inv_bidang = implode(",",$bid);
		#echo "\$inv_bidang: $inv_bidang<br>";
		

		$your_nik  = explode("-",$_REQUEST['nik']);
		// save to knowledge table
		include("include/fgenkey.php");
		require('upload.php');

		// -------- Upload Attach file(s) --------
	
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    $name = $_FILES["file"]["name"];
  #  echo $name;
    $type = $_FILES["file"]["type"];
   # echo $type;
    $size = $_FILES["file"]["size"];
   # echo $size;
      move_uploaded_file($_FILES["file"]["tmp_name"],   "upload/" . $_FILES["file"]["name"]);
      $path = "upload/" . $_FILES["file"]["name"];
  
    }
	$randomkey = gen_key();
$q1 = "INSERT INTO upload (name, type, path, size, randomkey) VALUES ('$name','$type','$path','$size','$randomkey')";
mysql_query($q1) or die ("1");	

		$q = "INSERT INTO knowledge (randomkey, submitter, nik, member, ext_speaker, instansi, id_map, jenis, judul, t_mulai, t_akhir, lokasi, unitkerja, inv_bidang, ext_audience, abstraksi, harapan, referensi, created, conflict) VALUES ('$randomkey' ,'$_REQUEST[submitter]', '$your_nik[0]', '$member', '$_REQUEST[ext_speaker]', '$_REQUEST[instansi]', '$_REQUEST[id_map]', '$_REQUEST[jenis]', '$_REQUEST[judul]', '$t_mulai', '$t_akhir', '$_REQUEST[lokasi]', '$_REQUEST[unitkerja]', '$inv_bidang', '$_REQUEST[exaudien]', '$_REQUEST[abstraksi]', '$_REQUEST[harapan]', '$_REQUEST[referensi]', NOW(), '$conflict')";
		#echo "$q<br>";
		query_sql($q,$res);

		// get knowledge that just inserted
		$q  = "SELECT a.id_know,a.nik,a.judul,a.lokasi,b.nama,b.email,b.id_bidang,c.nm_bidang ";
		$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik JOIN bidang c ON b.id_bidang=c.id_bidang ORDER BY id_know DESC LIMIT 1"; 
		#echo "<br>==>&nbsp;$q<br>";
		$result    = mysql_query($q);
		$rows      = mysql_fetch_object ($result);
		$aData     = array();
		$aData[0]  = $rows->id_know;
		$aData[1]  = $rows->nik;
		$aData[2]  = $rows->nama;
		$aData[3]  = $rows->email;
		$aData[4]  = $rows->judul;
		$aData[5]  = $rows->lokasi;
		$aData[6]  = $rows->nm_bidang;
		$aData[7]  = date("d-m-Y",strtotime($t_mulai));
		$aData[8]  = date("H:i",strtotime($t_mulai));
		$aData[9]  = date("H:i",strtotime($t_akhir));
		$aData[10] = ""; #$nik_sgm
		$aData[11] = ""; #$external
		$idb       = $rows->id_bidang;
		$ids       = 1;

		// send notification to committee by e-mail
		#include("sendmail.php");
		#include("send_not.php");
		//send_to_committee($aData,$ids,$idb);

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
		Header("Location: home.php?mn=21");
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
		Header("Location: home.php");
		break;

	case "16" :
		// --- External Invitee: Request To Attend Sharing Knowledge
		// call from: sharing_detail.php
		// Status: Running
		$q = "INSERT INTO req_to_attend (id_know,nik,created) VALUES ('$_REQUEST[idk]','$_REQUEST[nik]',NOW())";
		#echo "$q<br>-----<br>";
		query_sql($q,$res);

		$q  = "INSERT INTO sharing_activity (id_know,nik,id_bidang, id_loker,id_confirm,id_inv_status) ";
		$q .= "VALUES ('$_REQUEST[idk]','$_REQUEST[nik]',(SELECT id_bidang FROM user WHERE nik='$_REQUEST[nik]'),(SELECT id_loker FROM user WHERE nik='$_REQUEST[nik]'),'6','4')";
		#echo "$q<br>-----<br>";
		query_sql($q,$res);

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

		$aData     = array();
		$aData[0]  = $_REQUEST[idk];
		$aData[1]  = $_REQUEST[nik];
		$aData[2]  = $rows->nama;
		$aData[3]  = $rows->email;
		$aData[6]  = $rows->nm_bidang;

		// get sharing contributor
		$q = "SELECT a.judul,a.lokasi,a.t_mulai,a.t_akhir,b.nik,b.nama FROM knowledge a JOIN user b ON a.nik=b.nik WHERE id_know ='$_REQUEST[idk]'"; 
		#echo "$q<br>";
		$result  = mysql_query($q);
		$rows    = mysql_fetch_object ($result);
		$aData[4] = $rows->judul."#".$rows->nik."#".$rows->nama;
		$aData[5] = $rows->lokasi;
		$aData[7] = date("d-m-Y",strtotime($rows->t_mulai));
		$aData[8] = date("H:i",strtotime($rows->t_mulai));
		$aData[9] = date("H:i",strtotime($rows->t_akhir));
		$ids      = 5;

		/*
		require_once("sendmail.php");
		sending($ids,$idb,$bid,$external,$nik,$name,$email,$judul,$lokasi,$tgl,$jam1,$jam2,$nik_sgm,$_REQUEST[idk]);
		*/
		// send email to committee
	//	include("send_not.php");
	//	send_to_committee($aData,$ids,$idb,$bid);

		Header("Location: home.php?mn=1");
		break;

	case "17" :
		// --- Audience: Update Sharing Activity & Create Feedback
		// call from: home_feedback.php
		// Status: Running
		$q = "UPDATE sharing_activity SET feedback_status='1' WHERE id_know='$_REQUEST[idk]' AND nik='$_SESSION[nik_login]'";
		#echo "<br>$q<br>-----";
		mysql_query($q) or die ("error 1");
		$q = "INSERT INTO feedback (id_know,nik,comment,created) VALUES ('$_REQUEST[idk]','$_SESSION[nik_login]','$_REQUEST[komen]',NOW())";
		#echo "<br>$q<br>-----";
		mysql_query($q) or die ("error 2");
		Header("Location: home.php?mn=51");
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

		#require_once("sendmail.php");
		#sending($ids,$idb,$bid,$external,$nik,$name,$email,$judul,$lokasi,$tgl,$jam1,$jam2,$nik_sgm,$id_know);
		include("send_not.php");
//		send_to_contributor($aData,$ids,$idb,$bid,$_REQUEST[approval]);

		Header("Location: sharing.php?mn=3");
		break;

	case "20" :
		// --- updating sharing_activity
		// call from: sharing_attend_edt.php
		// Status: Running

		// get nik
		$nik_attend = "'".implode("','",$_REQUEST[attend])."'";

		$q = "UPDATE sharing_activity SET attend='1' WHERE id_know='$_REQUEST[idk]' AND nik IN ($nik_attend)";
		query_sql($q,$res);
		#echo "$q<br>";
		Header("Location: sharing.php?idk=$_REQUEST[idk]&mn=31#");
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
			mysql_query($q) or die ("1");

			if (strlen($_REQUEST[catatan])>0) {
				$q  = "INSERT INTO sharing_notes (id_know, nik, created, notes) VALUES ";
				$q .= "('$_REQUEST[idk]', '$_SESSION[nik_login]', NOW(), '$_REQUEST[catatan]')";
				#echo "$q<br>-----<br>";
				mysql_query($q) or die ("2");
			}
		} else {

			$q  = "UPDATE knowledge SET t_mulai='$t_mulai', t_akhir='$t_akhir', lokasi='$_REQUEST[lokasi]', ";
			$q .= "sharing_status='3', app_req_by='$_SESSION[nik_login]', app_req_at=NOW() WHERE id_know='$_REQUEST[idk]'";
			#echo "1. Approval Request Sharing:<br>$q<br>-----<br>";
			mysql_query($q) or die ("3");

			if (strlen($_REQUEST[catatan])>0) {
				$q  = "INSERT INTO sharing_notes (id_know, nik, created, notes) ";
				$q .= "VALUES ('$_REQUEST[idk]', '$_SESSION[nik_login]', NOW(), '$_REQUEST[catatan]')";
				#echo "$q<br>-----<br>";
				mysql_query($q) or die ("4");
			}
			// create sharing activity table for contributor/speaker
			$sid_bidang = $_SESSION[id_bidang];
			$tSQL  = "INSERT INTO sharing_activity (id_know,nik,id_bidang,id_loker,id_confirm,id_inv_status,attend) VALUES ";
			$tSQL .= "('$_REQUEST[idk]','$_REQUEST[niknya]',(SELECT id_bidang FROM user WHERE nik='$_REQUEST[niknya]'),(SELECT id_loker FROM user WHERE nik='$_REQUEST[niknya]'),'1','1','1')";
			#echo "2. Create sharing activity table for speaker:<br>$tSQL<br>-----<br>";
			mysql_query($tSQL) or die ("5");

			// create sharing activity table for team member
			if (strlen($_REQUEST[member]) > 0) {
				$niks = explode(",",$_REQUEST[member]);
				#echo "3. Create sharing activity table for member of speaker:<br>";
				#echo "<br>\$_REQUEST[member]: $_REQUEST[member]<br>";
				$member = array();
				foreach ($niks as $key => $value) {
					$tSQL  = "INSERT INTO sharing_activity (id_know,nik,id_bidang,id_loker,id_confirm,id_inv_status,attend) VALUES ";
					$tSQL .= "('$_REQUEST[idk]','$value',(SELECT id_bidang FROM user WHERE nik='$value'),(SELECT id_loker FROM user WHERE nik='$value'),'1','2','1');";
					#echo "3. Create sharing activity table for member of speaker:<br>$tSQL<br>-----<br>";
					mysql_query($tSQL) or die ("6");
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
		/*	if (count($bid) == $jml)
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
			}  */

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
				mysql_query($q) or die ("7");
				$result = mysql_query($q);
				while ($row = mysql_fetch_array ($result)) {
					$id_bid = $row[id_bidang];
					$id_lok = $row[id_loker];
					$tSQL   = "INSERT INTO sharing_activity (id_know,nik,id_bidang,id_loker,id_inv_status) ";
					$tSQL  .= "VALUES ('$_REQUEST[idk]','$row[nik]',$id_bid,$id_lok,'3')";
					#echo "$tSQL<br>";
				//	mysql_query($tSQL) or die ("8");
				}
				#echo "-----<br>";
			}
		}

		// get knowledge
		$q  = "SELECT a.id_know,a.nik,a.judul,a.lokasi,b.nama,b.email,b.id_bidang,c.nm_bidang ";
		$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik JOIN bidang c ON b.id_bidang=c.id_bidang WHERE id_know='$_REQUEST[idk]'"; 
		$result = mysql_query($q);
		$rows   = mysql_fetch_object($result);

		$aData     = array();
		$aData[0]  = $rows->id_know;
		$aData[1]  = $rows->nik;
		$aData[2]  = $rows->nama;
		$aData[3]  = $rows->email;
		$aData[4]  = $rows->judul;
		$aData[5]  = $rows->lokasi;
		$aData[6]  = $rows->nm_bidang;
		$aData[7]  = date("d-m-Y",strtotime($t_mulai));
		$aData[8]  = date("H:i",strtotime($t_mulai));
		$aData[9]  = date("H:i",strtotime($t_akhir));
		$aData[10] = $nik_sgm;
		$aData[11] = $external;
		$idb       = $rows->id_bidang;
		$ids       = 2;

	#	include("send_not.php");
		//send_to_contributor($aData,$ids,$idb,$bid,$_REQUEST[approval]);
		/*if ($_REQUEST[approval]==1)
		{
			$ids = 3;
			send_invitation($aData,$ids,$idb,$bid);
		}*/

		Header("Location: management.php?mn=13");
		break;

	case "22" :
		// --- Committee: Close Task Report Approval
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

			// check attended amount of bidang invited
			// including external audience: 30
			// RDC or > 2 bidang: 20
			// 1 or 2 bidang: 10

			$trims = trim($r->ext_audience);
			$bidang = explode(",",$r->inv_bidang);
			$jmlbidang = count($bidang);
			#echo "\$jmlbidang: $jmlbidang<br>";

			// count bidang from attendance sheet
			/* old version
			$q  = "SELECT COUNT(DISTINCT b.id_bidang) FROM sharing_activity a ";
			$q .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker JOIN confirm d ON a.id_confirm=d.id_confirm ";
			$q .= "WHERE id_know='$_REQUEST[idk]' AND attend='1'";
			*/
			$q  = "SELECT id_bidang FROM sharing_activity WHERE id_know='$_REQUEST[idk]' AND id_bidang IS NOT NULL AND id_inv_status < 4 GROUP BY id_bidang";
			#echo "3. Check bidang attended:<br>$q<br>-----<br>";
			query_sql($q,$res);
			$result = mysql_query($q);
			$num = mysql_num_rows ($result);

			if ($jmlbidang < 3)
			{
				if ($num < 3)
				{
					$point = 10;
					$tipe = 1;
				}
				else // yang hadir > 2 bidang jadi poin RDC
				{
					$point = 20;
					$tipe = 2;
				}
			}
			else	// $jmlbidang > 2, jika yg hadir cuman 1 bidang tetep dapet poin RDC
			{
				$point = 20;
				$tipe = 2;
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

		// get knowledge
		$q  = "SELECT a.id_know,a.nik,a.judul,b.nama,b.email,b.id_bidang ";
		$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik WHERE id_know='$_REQUEST[idk]'"; 
		$result = mysql_query($q);
		$rows   = mysql_fetch_object($result);

		$aData     = array();
		$aData[0]  = $rows->id_know;
		$aData[1]  = $rows->nik;
		$aData[2]  = "Lutfi Achyar"; #$rows->nama;
		$aData[3]  = "silutfi@gmail.com"; #$rows->email;
		$aData[4]  = "\"$rows->judul\"";
		#$aData[5]  = $rows->lokasi;
		#$aData[6]  = $rows->nm_bidang;
		#$aData[7]  = date("d-m-Y",strtotime($t_mulai));
		#$aData[8]  = date("H:i",strtotime($t_mulai));
		#$aData[9]  = date("H:i",strtotime($t_akhir));
		#$aData[10] = $nik_sgm;
		#$aData[11] = $external;
		$idb       = $rows->id_bidang;
		$ids       = 9;

		include("send_not.php");
//		send_to_contributor($aData,$ids,$idb,$bid,$_REQUEST[approval]);

		Header("Location: management.php?mn=33");
		break;

	case "23" :
		// --- Committee: Request To Attend Sharing Knowledge Approval
		// call from: mgm_req_attend_d.php
		// Status: Running
		$q  = "UPDATE req_to_attend SET approve_by='$_REQUEST[nik_commit]', approve_date=NOW() ";
		$q .= "WHERE id_know='$_REQUEST[idk]' AND nik='$_REQUEST[nik]'";
		#echo "$q<br>";
		query_sql($q,$res);

		$q  = "UPDATE sharing_activity SET id_confirm='$_REQUEST[id_confirm]' ";
		$q .= "WHERE id_know='$_REQUEST[idk]' AND nik='$_REQUEST[nik]'";
		#echo "$q<br>";
		query_sql($q,$res);

		$aData     = array();
		$aData[0]  = $_REQUEST[idk];

		// get name & email of committee
		$q = "SELECT nama,email FROM user WHERE nik='$_REQUEST[nik_commit]'";
		#echo "$q<br>";
		$result    = mysql_query($q);
		$rows      = mysql_fetch_object($result);
		$aData[1]  = $rows->nama."#".$rows->email;

		// get requester of request to attend
		$q = "SELECT nama,nik,email FROM user WHERE nik='$_REQUEST[nik]'";
		#echo "$q<br>";
		$result    = mysql_query($q);
		$rows      = mysql_fetch_object($result);
		$aData[2]  = $rows->nama."#".$rows->email;
		/* tester
		$name   = 'Lutfi A.'; #$rows->nama;
		$email  = 'lutfi_san@yahoo.com'; #$rows->email;
		*/

		// send notification to request to attend requester by email
		// get knowledge
		$q  = "SELECT a.id_know,a.nik,a.judul,a.lokasi,a.t_mulai,a.t_akhir,b.nama,b.email,b.id_bidang,c.nm_bidang ";
		$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik JOIN bidang c ON b.id_bidang=c.id_bidang WHERE id_know='$_REQUEST[idk]'"; 
		$result = mysql_query($q);
		$rows   = mysql_fetch_object($result);

		$aData[3]  = $rows->nama."#".$rows->nik."#".$rows->email;
		$aData[4]  = $rows->judul;
		$aData[5]  = $rows->lokasi;
		$aData[6]  = $rows->nm_bidang;
		$aData[7]  = date("d-m-Y",strtotime($rows->t_mulai));
		$aData[8]  = date("H:i",strtotime($rows->t_mulai));
		$aData[9]  = date("H:i",strtotime($rows->t_akhir));
		$idb       = $rows->id_bidang;
		$ids       = 6;

		require_once("send_not.php");
	//	send_to_requester($aData,$ids,$idb,$_REQUEST[id_confirm]);

		Header("Location: management.php?mn=2");
		break;
		
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		case "31" :
		include("include/fgenkey.php");
		require('upload.php');

		$randomkey = gen_key();

		if ($_FILES["file1"]["error"] > 0){
			echo "Return Code: " . $_FILES["file1"]["error"] . "<br />";
		}else{
			$name1 = $_FILES["file1"]["name"];
			$type1 = $_FILES["file1"]["type"];
			$size1 = $_FILES["file1"]["size"];

			move_uploaded_file($_FILES["file1"]["tmp_name"],   "upload/" . $_FILES["file1"]["name"]);
			$path1 = "upload/" . $_FILES["file1"]["name"];

			$q1 = "INSERT INTO upload (name, type, path, size, randomkey) VALUES ('$name1','$type1','$path1','$size1','$randomkey')";
			mysql_query($q1) or die ("1");	

		}

		if ($_FILES["file2"]["error"] > 0){
		}else{
			$name2 = $_FILES["file2"]["name"];
			$type2 = $_FILES["file2"]["type"];
			$size2 = $_FILES["file2"]["size"];

			move_uploaded_file($_FILES["file2"]["tmp_name"],   "upload/" . $_FILES["file2"]["name"]);
			$path2 = "upload/" . $_FILES["file2"]["name"];

			$q2 = "INSERT INTO upload (name, type, path, size, randomkey) VALUES ('$name2','$type2','$path2','$size2','$randomkey')";
			mysql_query($q2) or die ("1");	
		}

		if ($_FILES["file3"]["error"] > 0){
		}else{
			$name3 = $_FILES["file3"]["name"];
			$type3 = $_FILES["file3"]["type"];
			$size3 = $_FILES["file3"]["size"];

			move_uploaded_file($_FILES["file3"]["tmp_name"],   "upload/" . $_FILES["file3"]["name"]);
			$path3 = "upload/" . $_FILES["file3"]["name"];

			$q3 = "INSERT INTO upload (name, type, path, size, randomkey) VALUES ('$name3','$type3','$path3','$size3','$randomkey')";
			mysql_query($q3) or die ("1");	
		}

		if ($_FILES["file4"]["error"] > 0){
		}else{
			$name4 = $_FILES["file4"]["name"];
			$type4 = $_FILES["file4"]["type"];
			$size4 = $_FILES["file4"]["size"];

			move_uploaded_file($_FILES["file4"]["tmp_name"],   "upload/" . $_FILES["file4"]["name"]);
			$path4 = "upload/" . $_FILES["file4"]["name"];

			$q4 = "INSERT INTO upload (name, type, path, size, randomkey) VALUES ('$name4','$type4','$path4','$size4','$randomkey')";
			mysql_query($q4) or die ("1");	
		}

		if ($_FILES["file5"]["error"] > 0){
		}else{
			$name5 = $_FILES["file5"]["name"];
			$type5 = $_FILES["file5"]["type"];
			$size5 = $_FILES["file5"]["size"];

			move_uploaded_file($_FILES["file5"]["tmp_name"],   "upload/" . $_FILES["file5"]["name"]);
			$path5 = "upload/" . $_FILES["file5"]["name"];  

			$q5 = "INSERT INTO upload (name, type, path, size, randomkey) VALUES ('$name5','$type5','$path5','$size5','$randomkey')";
			mysql_query($q5) or die ("1");	
		}
		
		// -------- End of Upload Attach file(s) --------
		$submitter = $_POST['submitter'];
		$judul = $_POST['judul'];
		$abstraksi = $_POST['abstraksi'];
		$q = "INSERT INTO letternote_director (submitter, judul, abstraksi, send_at, randomkey) VALUES ('$submitter','$judul','$abstraksi',NOW(),'$randomkey')";
		
		mysql_query($q);		
		Header("Location: home.php?mn=6");
		break;


case "32" :
	$nik = $_REQUEST['nik'];
	$komentar = $_REQUEST['komentar'];
	$id = $_REQUEST['id'];
	$randomkey = $_REQUEST['randomkey'];
	if (!$komentar){
		$_SESSION['error'] = "Anda belum memasukkan komentar";
		Header ("Location: home.php?mn=61&id=$id&randomkey=$randomkey");
	}else{
		$q = "INSERT INTO feedback (randomkey, nik, comment, id_know, created) VALUES ('$randomkey', '$nik','$komentar','$id',NOW())";
		mysql_query($q) or die ($q);
		Header ("Location: home.php?mn=61&id=$id&randomkey=$randomkey");
	}
break;


case "33" :
	$nik = $_REQUEST['submitter'];
#	echo $nik."<br>";
	$oldpass = md5($_POST['oldpass']);
#	echo $oldpass."<br>";
	$newpass = $_POST['newpass'];
#	echo $newpass."<br>";
	$conpass = $_POST['conpass'];
#	echo $conpass."<br>";
	$q = "SELECT password FROM user WHERE nik = '$nik'";
	$res = mysql_query($q) or die("1");
	$row = mysql_fetch_array($res);
#	echo $row['password']."<br>";
	$e = 4;
	if ($oldpass<>$row['password']){
		$e = 1;
	} 
	if ($newpass<>$conpass){
		$e = 2;
	}
	if (($oldpass<>$row['password']) && ($newpass<>$conpass)){
		$e = 3;
	}
	if ($e == 4){
		$q = "UPDATE user SET password = MD5('$newpass') WHERE nik = '$nik'";
		mysql_query($q)or die("1");		
	}
	Header ("Location: home.php?mn=7&e=$e");
	break;
	
	case "34" :
		include("include/fgenkey.php");
		require('upload.php');

		// -------- Upload Attach file(s) --------
	
	  if ($_FILES["file"]["error"] > 0)
	    {
	    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	    }
	  else
	    {
	    $name = $_FILES["file"]["name"];
	    $type = $_FILES["file"]["type"];
	    $size = $_FILES["file"]["size"];

	    move_uploaded_file($_FILES["file"]["tmp_name"],   "upload/" . $_FILES["file"]["name"]);
	    $path = "upload/" . $_FILES["file"]["name"];
  
    	}
	
	$randomkey = gen_key();

	$q1 = "INSERT INTO upload (name, type, path, size, randomkey) VALUES ('$name','$type','$path','$size','$randomkey')";
	mysql_query($q1) or die ("1");
	
	$q = "INSERT INTO book (judul, pengarang, penerbit, jenis, abstraksi, randomkey) VALUES ('$_REQUEST[judul]','$_REQUEST[pengarang]', '$_REQUEST[penerbit]', '$_REQUEST[jenis]', '$_REQUEST[abstraksi]', '$randomkey')";
		#echo "<br>$q<br>";
		mysql_query($q) or die ("2");
		Header("Location: home.php?mn=8");
		break;
		
	case "35" :

	  if ($_FILES["pp"]["error"] > 0)
	    {
	    echo "Return Code: " . $_FILES["pp"]["error"] . "<br />";
	    }
	  else
	    {
	    $name = $_FILES["pp"]["name"];
	    $type = $_FILES["pp"]["type"];
	    $size = $_FILES["pp"]["size"];

		include('simpleimage.php');
		$image = new SimpleImage();
		$image->load($_FILES['pp']['tmp_name']);
		$image->resizeToWidth(72);
		$image->save("photo/".$name);
		$name = "photo/".$name;
    	}
		$q = "INSERT INTO photo (nik, photopath, date) VALUES ('$_REQUEST[submitter]','$name',NOW())";
		$res = mysql_query($q);
		Header("Location: home.php");
	break;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	case "44" :
		// --- Add New Knowledge Map
		// call from: .php
		// Status: Running
		$q  = "INSERT INTO knowledge_map (id_map,nm_map,expert,level,id_top) VALUES ";
		$q .= "('$_REQUEST[id_map]', '$_REQUEST[nm_map]', '$_REQUEST[expert]', '$_REQUEST[level]', '$_REQUEST[id_top]')";
		echo "<br>$q<br>";
		#query_sql($q,$res);
		Header("Location: admin.php?mn=4");
		break;
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
		$q = "INSERT INTO knowledge (nik, member, ext_speaker, instansi, id_map, jenis, judul, t_mulai, t_akhir, lokasi, unitkerja, inv_bidang, ext_audience, abstraksi, harapan, referensi, created, conflict, sharing_status) VALUES ('$your_nik[0]', '$member', '$_REQUEST[ext_speaker]', '$_REQUEST[instansi]', '$_REQUEST[id_map]', '$_REQUEST[jenis]', '$_REQUEST[judul]', '$t_mulai', '$t_akhir', '$_REQUEST[lokasi]', '$_REQUEST[unitkerja]', '$inv_bidang',  '$_REQUEST[exaudien]', '$_REQUEST[abstraksi]', '$_REQUEST[harapan]', '$_REQUEST[referensi]', NOW(), '$conflict', '6')";
		echo "$q<br>";
		#query_sql($q,$res);

		$q  = "SELECT a.id_know,a.nik,a.judul,a.lokasi,b.nama,b.email,b.id_bidang ";
		$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik ORDER BY id_know DESC LIMIT 1";
		#echo "$q<br>";
		query_sql($q,$res);
		$rows    = mysql_fetch_object ($result);
		$id_know = $rows->id_know;

		#Header("Location: management.php?mn=5");
		Header("Location: management.php?mn=511&idk=$id_know");
		break;

	case "512" :
		// --- Submitter: Add Attendance List of Sharing Knowledge
		// call from: req_fill_ed.php
		// Status: Running

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

		$q = "INSERT INTO user (nik, nama, band, posisi, id_bidang, id_loker, email, id_profile, active) VALUES ('$_REQUEST[nik]','$_REQUEST[nama]', '$_REQUEST[band]', '$_REQUEST[posisi]', '$id_bidang', '$_REQUEST[loker]', '$_REQUEST[email]', '$_REQUEST[profile]', '$_REQUEST[active]')";
		#echo "<br>$q<br>";
		query_sql($q,$res);
		Header("Location: admin.php?mn=1");
		break;

	case "62" :
		// --- Edit User
		// call from: adm_user_edt.php
		// Status: Running
		$q = "UPDATE user SET nik='$_REQUEST[nik]', nama='$_REQUEST[nama]', band='$_REQUEST[band]', posisi='$_REQUEST[posisi]', id_bidang='$id_bidang', id_loker='$_REQUEST[id_loker]', email='$_REQUEST[email]', id_profile='$_REQUEST[id_profile]', active='$_REQUEST[active]' WHERE nik='$_REQUEST[nik0]'";
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

function getRealIpAddr()
// source: http://roshanbh.com.np/2007/12/getting-real-ip-address-in-php.html
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function ipCheck()
// source: http://www.cyberciti.biz/faq/php-howto-read-ip-address-of-remote-computerbrowser/
{
	if (getenv('HTTP_CLIENT_IP')) {
		$ip = getenv('HTTP_CLIENT_IP');
	}
	elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif (getenv('HTTP_X_FORWARDED')) {
		$ip = getenv('HTTP_X_FORWARDED');
	}
	elseif (getenv('HTTP_FORWARDED_FOR')) {
		$ip = getenv('HTTP_FORWARDED_FOR');
	}
	elseif (getenv('HTTP_FORWARDED')) {
		$ip = getenv('HTTP_FORWARDED');
	}
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
?>