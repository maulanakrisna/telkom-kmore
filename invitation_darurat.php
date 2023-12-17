<?
// Just for emergency
session_start();

// http://www.askapache.com/php/phpfreaks-eric-rosebrocks-phpmailer-tutorial.html
include('include/dbcon.php');

// Grab our config settings
include('libs/config.php');
 
// Grab the FreakMailer class
include('libs/MailClass.inc');
$idk = "156";
$undang = "110,120,130,140,150,160";
echo "id_know: $idk<br>";
// get knowledge
$q  = "SELECT a.*,b.nama,b.email,b.id_bidang,c.nm_bidang ";
$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik JOIN bidang c ON b.id_bidang=c.id_bidang WHERE id_know='$idk'"; 
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
$aData[7]  = date("d-m-Y",strtotime($rows->t_mulai));
$aData[8]  = date("H:i",strtotime($rows->t_mulai));
$aData[9]  = date("H:i",strtotime($rows->t_akhir));
$aData[10] = "602217"; #$nik_sgm;
$aData[11] = $external;
$idb       = $rows->id_bidang;
$ids       = 2;
$bid = explode(",", $undang);

#send_to_contributor($aData,$ids,$idb,$bid,1);
$ids = 3;
send_invitation($aData,$ids,$idb,$bid);

// --------------------
// used on Request Sharing Approval, Close Task Report Approval
function send_to_contributor($aData,$ids,$idb,$bid,$app)
{
	// instantiate the class
	$mailer = new FreakMailer();

	// Set sender
	$senderName = "Asep Priyanto"; #$_SESSION[nama];
	$senderMail = "asep_p@telkom.co.id"; #$_SESSION[email];
	$mailer->FromName = $senderName; //'Your Name';
	$mailer->From = $senderMail; //'You@yourdomain.com';

	// Get the subject from subject table
	$q = "SELECT nm_subject,message,message2 FROM subject2 WHERE id_subject='$ids'";
	#echo "$q<br>---<br>";
	$result = mysql_query($q);
	$rows = mysql_fetch_object ($result);
	$subject = $rows->nm_subject;

	// create random id for id notification
	$id_not = md5(uniqid(rand(), true));

	// create notification request sharing => send notification to commitee
	$q = "INSERT INTO notification (id_not,id_know,created,subject) VALUES ('$id_not','$aData[0]',NOW(),'".$subject."')";
	#echo "<br>$q<br>";
	#query_sql($q,$res);

	// create notification for nik
	$q = "INSERT INTO notification_4nik (id_not,id_know,nik) VALUES ('$id_not','$aData[0]','$aData[1]')";
	#echo "<br>$q<br>";
	#query_sql($q,$res);

	if ($app==1)
	{
		$msg = explode(" ; ", $rows->message);
		$message = "$msg[0] $aData[4] $msg[1]";
	}
	else
	{
		$msg = explode(" ; ", $rows->message2);
		$message = "$msg[0] $aData[4] $msg[1]";
	}

	// Set the subject
	$mailer->Subject = "[KMORE] ".$subject;
	 
	// Body
	$mailer->Body = $message;
	 
	// Get recipient
	/* Tester
	$to = "$aData[2] &lt;$aData[3]&gt;<br>";

	echo "Subject: [KMORE] $subject<br>";
	echo "From: $senderName &lt;$senderMail&gt;<br>";
	*/

	$rcp_name  = $aData[2];
	$rcp_email = $aData[3];
	$mailer->AddAddress($rcp_email, $rcp_name); // recipient's email & name

	/* Tester
	$to  = "$rcp_name &lt;$rcp_email&gt; ";
	echo "To: $to<br>";
	echo "Message:<br>$message<br>";
	*/

	$mailer->isHTML(true);
	/*
	*/
	if ($mailer->Send())
		echo "Mail for Contributor is sent!";
	else
		echo "Mail for Contributor is not sent!";
	echo "<br>";
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
}

// --------------------
// used on Sharing Invitation
function send_invitation($aData,$ids,$idb,$bid)
{
	// instantiate the class
	$mailer = new FreakMailer();

	// Set sender
	$mailer->FromName = $aData[2]; //'Your Name';
	$mailer->From = $aData[3]; //'You@yourdomain.com';

	// Get the subject from subject table
	$q = "SELECT nm_subject,message,message2 FROM subject2 WHERE id_subject='$ids'";
	#echo "$q<br>---<br>";
	$result = mysql_query($q);
	$rows = mysql_fetch_object ($result);
	$subject = $rows->nm_subject;

	// create random id for id notification
	$id_not = md5(uniqid(rand(), true));

	// create notification request sharing => send notification to commitee
	$q = "INSERT INTO notification (id_not,id_know,created,subject) VALUES ('$id_not','$aData[0]',NOW(),'".$subject."')";
	echo "<br>$q<br>";
	#query_sql($q,$res);

	/*
	// create notification for nik
	$q = "INSERT INTO notification_4nik (id_not,id_know,nik) VALUES ('$id_not','$aData[0]','$aData[1]')";
	echo "<br>$q<br>";
	#query_sql($q,$res);
	*/
	$message  = "$aData[2] ($aData[1]) dari $aData[6] ".$rows->message;
	$message .= "<br>Judul: $aData[4]<br>Tanggal: $aData[7]<br>Waktu: $aData[8] s/d $aData[9]";
	$message .= "<br>Tempat: $aData[5]<br><br>".$rows->message2;

	// Set the subject
	$mailer->Subject = "[KMORE] ".$subject;
	 
	// Body
	$mailer->Body = $message;
	 
	// Get recipient(s) from user table
	// tester
	#$to = "";
	$inv_bid = implode("','",$bid);
	#echo "$inv_bid --- $nik_sgm<br>";
	if (!empty($aData[10])) {
		#$q = "SELECT nama,email FROM user WHERE id_loker='100'";
		$q = "SELECT nama,email FROM user WHERE nik='$aData[10]'";
		echo "$q<br>";
		$result    = mysql_query($q);
		$rows      = mysql_fetch_object ($result);
		$rcp_name  = $rows->nama;
		$rcp_email = $rows->email;
		$mailer->AddAddress($mail_sgm, $nama_sgm);
		$to .= "$rcp_name &lt;$rcp_email&gt;, ";
	}
	$q = "SELECT nm_bidang,email FROM bidang WHERE id_bidang IN ('$inv_bid')";
	/* Tester
	*/
	echo "$q<br>";

	echo "Subject: [KMORE] $subject<br>";
	echo "From: $aData[2] &lt;$aData[3]&gt;<br>";

	$result = mysql_query($q);
	while ($rows = mysql_fetch_object ($result))
	{
		if ($ids==1)
			$rcp_name = $rows->nama;
		else
			$rcp_name = $rows->nm_bidang;
		$rcp_email = $rows->email;
		$mailer->AddAddress($rcp_email, $rcp_name); // recipient's email & name

		/* Tester
		*/
		if (empty($to))
			$to  = "$rcp_name &lt;$rcp_email&gt;, ";
		else
			$to .= "$rcp_name &lt;$rcp_email&gt;, ";
	}
	#if (substr($to,strlen($to)-1)=="'")

	/* Tester
	*/
	echo "To: $to<br>";
	echo "Message:<br>$message<br>";

	$mailer->isHTML(true);
	/*
	*/
	if ($mailer->Send())
		echo "Mail for Attendance is sent!";
	else
		echo "Mail for Attendance is not sent!";
	echo "<br>";
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
}

?>