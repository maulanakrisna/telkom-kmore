<?
		include ("include/dbcon.php");

		// get knowledge that just inserted
		#$q="SELECT a.id_know,a.nik,a.judul,b.nama,b.email,b.id_bidang FROM knowledge a JOIN user b ON a.nik=b.nik ORDER BY id_know DESC LIMIT 1";
		$q="SELECT a.id_know,a.nik,a.judul,b.nama,b.email,b.id_bidang FROM knowledge a JOIN user_test b ON a.nik=b.nik WHERE id_know = '63'";
		echo "$q<br>";
		$result = mysql_query($q);
		$rows = mysql_fetch_object ($result);
		$id_know = $rows->id_know;
		$nik = $rows->nik;
		$name = $rows->nama;
		$from = $rows->email;
		$judul = $rows->judul;
		echo "$from<br>";

		// get subject & message from subject table
		$q="SELECT * FROM subject WHERE id_subject='7'";
		echo "$q<br>";
		$result = mysql_query($q);
		$rows = mysql_fetch_object ($result);
		$subject = $rows->nm_subject;
		$message = $name." (NIK:".$nik.") ".$rows->message." dengan judul \"".$judul."\".<br>";
		$message .= $rows->message2;

		// Grab our config settings
		require_once('libs/config.php');
		 
		// Grab the FreakMailer class
		require_once('libs/MailClass.inc');
		 
		// instantiate the class
		$mailer = new FreakMailer();

		// Set sender
		$mailer->FromName = $name; //'Your Name';
		$mailer->From = $from; //'You@yourdomain.com';

		// Set the subject
		$mailer->Subject = $subject; //'This is a 3rd test';
		 
		// Body
		$mailer->Body = $message; //'This is a 3rd test of my mail system with CC!';
		$mailer->isHTML(true);

		// send email to agent/committee
		$q="SELECT nik,nama,email FROM user_test WHERE id_bidang='140'";
		echo "$q<br>";
		$result = mysql_query($q);
		$x = 1;

		// Testing
		echo "Subject: $subject<br>";
		echo "From: $name &lt;$from&gt;<br>";
		while ($rows = mysql_fetch_object ($result))
		{
			$rcp_name = $rows->nama;
			$rcp_email = $rows->email;
			if ($x==1)
			{
			// Add an address to send to.
				$mailer->AddAddress($rcp_email, $rcp_name); // recipient's email & name
				echo "To: $rcp_name &lt;$rcp_email&gt;<br>";
			}
			else
			{
			// Add CC address.
				$mailer->AddCC($rcp_email, $rcp_name); // CC's email & name
				echo "CC: $rcp_name &lt;".$rcp_email."&gt;<br>";
			}
			$x++;
		}

		echo "Message:<br>$message<br>";
		/*
		*/
		if(!$mailer->Send())
		{
			echo 'There was a problem sending this mail!';
		}
		else
		{
			echo 'Mail sent!';
		}
		$mailer->ClearAddresses();
		$mailer->ClearAttachments();

?>