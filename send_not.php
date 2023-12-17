<?php
/*
$aData[0]  = $rows->id_know;
$aData[1]  = $rows->nik;
$aData[2]  = $rows->nama;
$aData[3]  = $rows->email;
$aData[4]  = $rows->judul;
$aData[5]  = ""; #$rows->lokasi;
$aData[6]  = ""; #$rows->nm_bidang
$aData[7]  = date("d-m-Y",strtotime($t_mulai));
$aData[8]  = date("H:i",strtotime($t_mulai));
$aData[9]  = date("H:i",strtotime($t_akhir));
$aData[10] = ""; #$nik_sgm
$aData[11] = ""; #$external
*/

// used on Request Sharing, Request to Attend
function send_to_committee($aData,$ids,$idb)
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
	#echo "<br>$q<br>";
	query_sql($q,$res);

	// create notification for nik
	$q = "INSERT INTO notification_4nik (id_not,id_know,nik) VALUES ('$id_not','$aData[0]','$aData[1]')";
	#echo "<br>$q<br>";
	query_sql($q,$res);

	$message  = "$aData[2] ($aData[1]) dari $aData[6] ".$rows->message;
	// Request Sharing
	if ($ids==1)
	{
		$message .= "<br>Judul: $aData[4]<br>Tanggal: $aData[7]<br>Waktu: $aData[8] s/d $aData[9]";
	}
	// ($ids==5) Request to Attend
	else		
	{
		$msgs = explode("#", $aData[4]);
		$message .= "<br>Judul: $msgs[0]<br>Pembicara: $msgs[2] ($msgs[1])<br>";
		$message .= "Bidang: $bid<br>Tanggal: $aData[7]<br>Waktu: $aData[8] s/d $aData[9]";
	}
	$message .= "<br>Tempat: $aData[5]";
	$message .= "<br><br>".$rows->message2;

	// Set the subject
	$mailer->Subject = "[KMORE] ".$subject;
	 
	// Body
	$mailer->Body = $message;
	 
	// Get recipient(s) from user table
	// tester
	$to = "";
	$q = "SELECT nama,email FROM user WHERE id_profile < 3 AND active = 1";
	#echo "$q<br>";

	/* Tester
	echo "Subject: [KMORE] $subject<br>";
	echo "From: $aData[2] &lt;$aData[3]&gt;<br>";
	*/

	$result = mysql_query($q);
	while ($rows = mysql_fetch_object ($result))
	{
		if ($ids==1)
			$rcp_name = $rows->nama;
		else
			$rcp_name = $rows->nm_bidang;
		$rcp_email = $rows->email;
		$mailer->AddAddress($rcp_email, $rcp_name); // recipient's email & name

		/* Tester */
		if (empty($to))
			$to  = "$rcp_name &lt;$rcp_email&gt;, ";
		else
			$to .= "$rcp_name &lt;$rcp_email&gt;, ";
	}
	#if (substr($to,strlen($to)-1)=="'")

	/* Tester
	echo "To: $to<br>";
	echo "Message:<br>$message<br>";
	*/

	$mailer->isHTML(true);
	$mailer->Send();
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
}

// --------------------
// used on Request Sharing Approval, Close Task Report Approval
function send_to_contributor($aData,$ids,$idb,$bid,$app)
{
	// instantiate the class
	$mailer = new FreakMailer();

	// Set sender
	$mailer->FromName = $_SESSION[nama]; //'Your Name';
	$mailer->From = $_SESSION[email]; //'You@yourdomain.com';

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
	query_sql($q,$res);

	// create notification for nik
	$q = "INSERT INTO notification_4nik (id_not,id_know,nik) VALUES ('$id_not','$aData[0]','$aData[1]')";
	#echo "<br>$q<br>";
	query_sql($q,$res);

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
	echo "From: $_SESSION[nama] &lt;$_SESSION[email]&gt;<br>";
	$rcp_name  = "Lutfi Achyar"; #$aData[2];
	$rcp_email = "silutfi@gmail.com"; #$aData[3];
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
	$mailer->Send();
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
	#echo "<br>$q<br>";
	query_sql($q,$res);

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
	if (!empty($nik_sgm)) {
		$q = "SELECT nama,email FROM user_test WHERE id_loker='100'";
		echo "$q<br>";
		$result    = mysql_query($q);
		$rows      = mysql_fetch_object ($result);
		$rcp_name  = $rows->nama;
		$rcp_email = $rows->email;
		$mailer->AddAddress($mail_sgm, $nama_sgm);
		$to .= "$rcp_name &lt;$rcp_email&gt;, ";
	}
	$q = "SELECT nm_bidang,email FROM bidang WHERE id_bidang in ('$inv_bid')";
	/* Tester
	echo "$q<br>";

	echo "Subject: [KMORE] $subject<br>";
	echo "From: $aData[2] &lt;$aData[3]&gt;<br>";
	*/

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
		if (empty($to))
			$to  = "$rcp_name &lt;$rcp_email&gt;, ";
		else
			$to .= "$rcp_name &lt;$rcp_email&gt;, ";
		*/
	}
	#if (substr($to,strlen($to)-1)=="'")

	/* Tester
	echo "To: $to<br>";
	echo "Message:<br>$message<br>";
	*/

	$mailer->isHTML(true);
	/*
	if ($mailer->Send())
		echo "Mail for Attendance is sent!";
	else
		echo "Mail for Attendance is not sent!";
	echo "<br>";
	*/
	$mailer->Send();
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
}

// --------------------
// used on Request to Attend Approval
function send_to_requester($aData,$ids,$idb,$confirm)
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
	#echo "<br>$q<br>";
	query_sql($q,$res);

	// create notification for nik
	$q = "INSERT INTO notification_4nik (id_not,id_know,nik) VALUES ('$id_not','$aData[0]','$_SESSION[nik_login]')";
	#echo "<br>$q<br>";
	query_sql($q,$res);

	$msg = explode ("#", $aData[3]);
	if ($confirm==6)
	{
		$message = "$rows->message<br>Judul: $aData[4]<br>Pembicara: $msg[0] ($msg[1]) dari $aData[6]";
	}
	else
	{
		$message = "$rows->message2<br>Judul: $aData[4]<br>Pembicara: $msg[0] ($msg[1]) dari $aData[6]";
	}
	$message .= "<br>Tanggal: $aData[7]<br>Waktu: $aData[8] s/d $aData[9]";
	$message .= "<br>Tempat: $aData[5]<br><br><b>-- Committee --</b>";

	// Set the subject
	$mailer->Subject = "[KMORE] ".$subject;
	 
	// Body
	$mailer->Body = $message;
	$rcp = explode ("#", $aData[2]);
	 
	// Get recipient
	/* Tester
	echo "Subject: [KMORE] $subject<br>";
	echo "From: $_SESSION[nama] &lt;$_SESSION[email]&gt;<br>";
	*/

	$rcp_name  = $rcp[0];
	$rcp_email = $rcp[1];
	$mailer->AddAddress($rcp_email, $rcp_name); // recipient's email & name

	/* Tester
	$to  = "$rcp_name &lt;$rcp_email&gt;";
	echo "To: $to<br>";
	echo "Message:<br>$message<br>";
	*/

	$mailer->isHTML(true);
	$mailer->Send();
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
}
?>