<?php
function sending($ids,$idb,$bid,$external,$nik,$name,$email,$judul,$lokasi,$tgl,$jam1,$jam2,$nik_sgm,$id_know)
{
	// instantiate the class
	$mailer = new FreakMailer();

	// Set sender
	$mailer->FromName = $name; //'Your Name';
	$mailer->From = $email; //'You@yourdomain.com';

	// Get the subject from subject table
	$q = "SELECT nm_subject,message,message2 FROM subject WHERE id_subject='$ids'";
	#echo "$q<br>---<br>";
	$result = mysql_query($q);
	$rows = mysql_fetch_object ($result);
	$subject = $rows->nm_subject;

	// create random id for id notification
	$id_not = md5(uniqid(rand(), true));

	// create notification request sharing => send notification to commitee
	$q = "INSERT INTO notification (id_not,id_know,created,subject) VALUES ('$id_not','$id_know',NOW(),'".substr($subject,8,strlen($subject))."')";
	#echo "<br>$q<br>";
	query_sql($q,$res);

	// create notification for nik
	$q = "INSERT INTO notification_4nik (id_not,id_know,nik) VALUES ('$id_not','$id_know','$nik')";
	#echo "<br>$q<br>";
	query_sql($q,$res);

	switch ($ids)
	{	// refer to subject table
		case "1" :
			// Request Sharing
			$message  = "$name ($nik) ".$rows->message;
			$message .= "<br>Judul: $judul<br>Tanggal: $tgl<br>Waktu: $jam1 s/d $jam2";
			$message .= "<br>Tempat: $lokasi";
			$message .= "<br><br>".$rows->message2;
			break;
		case "4" :
			// Request to Attend
			$judul    = explode("#",$judul);
			$message  = "$name ($nik) dari $bid ".$rows->message;
			$message .= "<br>Judul: $judul[0]";
			$message .= "<br>Oleh: $judul[2] ($judul[1])";
			$message .= "<br>Tanggal: ".date("d-m-Y",strtotime($judul[3]));
			$message .= "<br><br>".$rows->message2;
			break;
		case "7" :
			// Sharing Invitation
			$message  = "$name ($nik) ".$rows->message;
			$message .= "<br>Judul: $judul<br>Tanggal: $tgl<br>Waktu: $jam1 s/d $jam2";
			$message .= "<br>Tempat: $lokasi<br><br>".$rows->message2;
			break;
		case "8" :
			// Closing Report Request
			$message  = "$name ($nik) ".$rows->message;
			$message .= "<br>Judul: $judul";
			$message .= "<br><br>".$rows->message2;
			break;
	}

	// Set the subject
	$mailer->Subject = $subject;
	 
	// Body
	$mailer->Body = $message;
	 
	// Get recipient(s) from user table
	// tester
	#$to = "";
	switch ($ids)
	{	// refer to subject table
		case "1" :
			// Request Sharing
			#$q = "SELECT nama,email FROM user_test WHERE id_profile < 3 AND active = 1";
			// Next
			#$q = "SELECT nama,email FROM user WHERE id_bidang='$idb' AND id_profile < 3 AND active = 1";
			// sementawis
			$q = "SELECT nama,email FROM user WHERE id_profile < 3 AND active = 1";
			break;
		case "4" :
			// Request to Attend
			#$q = "SELECT nama,email FROM user_test WHERE id_profile < 3 AND active = 1";
			// Next
			#$q = "SELECT nama,email FROM user WHERE id_bidang='$idb' AND id_profile < 3 AND active = 1";
			$q = "SELECT nama,email FROM user WHERE id_profile < 3 AND active = 1";
			break;
		case "7" :
			// Sharing Invitation
			$inv_bid = implode("','",$bid);
			#echo "$inv_bid --- $nik_sgm<br>";
			if (!empty($nik_sgm)) {
				$q = "SELECT nama,email FROM user_test WHERE id_loker='100'";
				#$q = "SELECT nama,email FROM user WHERE id_loker='100'";
				#echo "$q<br>";
				$result    = mysql_query($q);
				$rows      = mysql_fetch_object ($result);
				$rcp_name  = $rows->nama;
				$rcp_email = $rows->email;
				$mailer->AddAddress($mail_sgm, $nama_sgm);
				#$to .= "$rcp_name &lt;$rcp_email&gt;, ";
			}
			#$q = "SELECT nm_bidang,email FROM bidang_test WHERE id_bidang in ('$inv_bid')";
			$q = "SELECT nm_bidang,email FROM bidang WHERE id_bidang in ('$inv_bid')";
			break;
		case "8" :
			// Closing Report Request
			#$q = "SELECT nama,email FROM user_test WHERE id_profile < 3 AND active = 1";
			#$q = "SELECT nama,email FROM user WHERE id_bidang='$idb' AND id_profile < 3 AND active = 1";
			$q = "SELECT nama,email FROM user WHERE id_profile < 3 AND active = 1";
			break;
	}
	// Tester
	#echo "$q<br>";
	#echo "Subject: $subject<br>";
	#echo "From: $name &lt;$email&gt;<br>";

	#$ccs = "";
	#$x=1;
	$result = mysql_query($q);
	while ($rows = mysql_fetch_object ($result))
	{
		if ($ids==1 || $ids==8) $rcp_name = $rows->nama; else $rcp_name = $rows->nm_bidang;
		$rcp_email = $rows->email;
		/*
		if ($x==1)
		{
		// Add an address to send to.
			$mailer->AddAddress($rcp_email, $rcp_name); // recipient's email & name
			echo "To: $to$rcp_name &lt;$rcp_email&gt;<br>";
		}
		else
		{
		// Add CC address.
			$mailer->AddCC($rcp_email, $rcp_name); // CC's email & name
			$ccs .= "$rcp_name &lt;$rcp_email&gt;, ";
		}
		$x++;
		*/
		/*
		#sementawis
		$rcp_email = 'lutfi_san@yahoo.com';
		$rcp_name = 'Lutfi Achyar';
		*/
		$mailer->AddAddress($rcp_email, $rcp_name); // recipient's email & name
		/* Tester
		if (empty($to))
			$to  = "$rcp_name &lt;$rcp_email&gt;, ";
		else
			$to .= "$rcp_name &lt;$rcp_email&gt;, ";
		*/
	}

	/* gak kepake!
	if ($ids==7)
	{
		if (isset($external))
		{
			foreach ($external as $key => $value)
			{
				//$mailer->AddCC($value, ""); // CC's email & name
				$mailer->AddAddress($value, ""); // recipient's email & name
				#$ccs .= "$rcp_name &lt;$value&gt;, ";
				$to .= "$value, ";
			}
		}
	}
	*/

	/*
	if (mysql_num_rows($result)>1)
	{
		$ccs = substr($ccs,0,strlen($ccs)-1);
		echo "CC: $ccs<br>";
	}
	$to = substr($to,0,strlen($to)-2);
	echo "To: $to<br>";
	echo "Message:<br>$message<br>";
	*/

	$mailer->isHTML(true);

	/* Tester
	if(!$mailer->Send())
	{
		echo '<strong>There was a problem sending this mail!</strong>';
	}
	else
	{
		#echo 'Mail sent!';
		$flag = '1';
	}
	*/
	$mailer->Send();
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
	#return $flag
}

// Request to Attend Approval
function send2sender($idk,$ids,$from_name,$from_email,$name,$email,$confirm)
{
	// get sharing knowledge from knowledge table
	$q  = "SELECT a.id_know,a.nik,a.judul,a.lokasi,a.t_mulai,a.t_akhir,b.nama,b.id_bidang ";
	$q .= "FROM knowledge a JOIN user b ON a.nik=b.nik WHERE id_know='$_REQUEST[idk]'"; 
	#echo "$q<br>";
	$result  = mysql_query($q);
	$rows    = mysql_fetch_object($result);
	$tgl     = date("d-m-Y",strtotime($rows->t_mulai));
	$jam1    = date("H:i",strtotime($rows->t_mulai));
	$jam2    = date("H:i",strtotime($rows->t_akhir));
	$speaker = $rows->nama;
	$judul   = $rows->judul;

	// instantiate the class
	$mailer = new FreakMailer();

	// Set sender
	$mailer->FromName = $from_name;
	$mailer->From = $from_email;

	// Set the subject
	$mailer->Subject = $subject;

	// get subject & message from subject table
	$q = "SELECT * FROM subject WHERE id_subject='$ids'";
	#echo "$q<br>";
	$result = mysql_query($q);
	$rows = mysql_fetch_object ($result);
	$subject  = $rows->nm_subject;
	$message  = "Anda $confirm ".$rows->message;
	$message .= "<br>Judul: $judul<br>Pembicara: $speaker<br>Tanggal: $tgl<br>Waktu: $jam1 s/d $jam2";
	 
	// Body
	$mailer->Body = $message;

	// Recipient
	$mailer->AddAddress($email, $name);

	$mailer->isHTML(true);

	/* Tester
	echo "From: $from_name &lt;$from_email&gt;<br>";
	echo "Subject: $subject<br>";
	echo "To: $name &lt;$email&gt;<br>";
	echo "Message:<br>";
	echo "$message<br>";
	*/
	/*
	if(!$mailer->Send())
	{
		#echo '<strong>There was a problem sending this mail!</strong>';
	}
	else
	{
		#echo 'Mail sent!';
	}
	*/
	$mailer->Send();
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
}
?>