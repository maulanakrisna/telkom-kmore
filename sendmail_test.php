<?php
function sending($ids,$idb,$bid,$external,$nik,$name,$email,$judul,$lokasi,$tgl,$jam1,$jam2,$nik_sgm)
{
	// instantiate the class
	$mailer = new FreakMailer();

	// Set sender
	$mailer->FromName = $name; //'Your Name';
	$mailer->From = $email; //'You@yourdomain.com';

	// Get the subject from subject table
	echo "<p>";
	$q = "SELECT nm_subject,message,message2 FROM subject WHERE id_subject='$ids'";
	echo "$q<br>---<br>";
	$result = mysql_query($q);
	$rows = mysql_fetch_object ($result);
	$subject = $rows->nm_subject;

	switch ($ids)
	{	// refer to subject table
		case "1" :
			// Request Sharing
			$message  = "$name ($nik) ".$rows->message;
			$message .= "<br>Judul: $judul";
			$message .= "<br>".$rows->message2;
			break;
		case "4" :
			// Request to Attend
			$message  = "$name ($nik) dari bidang $bid ".$rows->message;
			$message .= "<br>Judul: $judul";
			$message .= "<br>".$rows->message2;
			break;
		case "7" :
			// Sharing Invitation
			$message  = "$name ($nik) ".$rows->message;
			$message .= "<br>Judul: $judul<br>Tanggal: $tgl<br>Waktu: ".$jam1." s/d ".$jam2;
			$message .= "<br>Tempat: $lokasi<br>".$rows->message2;
			break;
		case "8" :
			// Closing Report Request
			$message  = "$name ($nik) ".$rows->message;
			$message .= "<br>Judul: $judul";
			$message .= "<br>".$rows->message2;
			break;
	}

	// Set the subject
	$mailer->Subject = $subject;
	 
	// Body
	$mailer->Body = $message;
	 
	// Get recipient(s) from user table
	#echo "$ids<br>";
	$to = "";
	switch ($ids)
	{	// refer to subject table
		case "1" :
			// Request Sharing
			#$q = "SELECT nama,email FROM user_test WHERE id_bidang='$idb' AND id_profile < 3";
			$q = "SELECT nama,email FROM user WHERE id_bidang='$idb' AND id_profile < 3";
			break;
		case "4" :
			// Request to Attend
			#$q = "SELECT nama,email FROM user_test WHERE id_bidang='$idb' AND id_profile < 3";
			$q = "SELECT nama,email FROM user WHERE id_bidang='$idb' AND id_profile < 3";
			break;
		case "7" :
			// Sharing Invitation
			$inv_bid = implode("','",$bid);
			#echo "$inv_bid --- $nik_sgm<br>";
			if (!empty($nik_sgm)) {
				$q = "SELECT nama,email FROM user WHERE id_loker='100'";
				#echo "$q<br>";
				$result    = mysql_query($q);
				$rows      = mysql_fetch_object ($result);
				$rcp_name  = $rows->nama;
				$rcp_email = $rows->email;
				$mailer->AddAddress($mail_sgm, $nama_sgm);
				$to .= "$rcp_name &lt;$rcp_email&gt;, ";
			}
			#$q = "SELECT nm_bidang,email FROM bidang_test WHERE id_bidang in ('$inv_bid')";
			$q = "SELECT nm_bidang,email FROM bidang WHERE id_bidang in ('$inv_bid')";
			break;
		case "8" :
			// Closing Report Request
			#$q = "SELECT nama,email FROM user_test WHERE id_bidang='$idb' AND id_profile < 3";
			$q = "SELECT nama,email FROM user WHERE id_bidang='$idb' AND id_profile < 3";
			break;
	}
	// Tester
	echo "$q<br>";
	echo "Subject: $subject<br>";
	echo "From: $name &lt;$email&gt;<br>";

	#$ccs = "";
	#$x=1;
	#echo "$q<br>";
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
		$mailer->AddAddress($rcp_email, $rcp_name); // recipient's email & name
		if (empty($to))
			$to  = "$rcp_name &lt;$rcp_email&gt;, ";
		else
			$to .= "$rcp_name &lt;$rcp_email&gt;, ";
	}

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

	/*
	if (mysql_num_rows($result)>1)
	{
		$ccs = substr($ccs,0,strlen($ccs)-1);
		echo "CC: $ccs<br>";
	}
	*/
	$to = substr($to,0,strlen($to)-2);
	echo "To: $to<br>";
	echo "Message:<br>$message<br>";

	$mailer->isHTML(true);

	/* Tester
	if(!$mailer->Send())
	{
		echo '<strong>There was a problem sending this mail!</strong>';
	}
	else
	{
		echo 'Mail sent!';
	}
	*/
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
}
?>