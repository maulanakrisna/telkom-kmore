<?
function send_mail_2agent($ids,$name,$from,$judul)
{
    include("include/dbcon.php");
		// get subject & message from subject table
		$q="SELECT * FROM subject WHERE id_subject='$ids'";
		echo "$q<br>";
		$result = mysql_query($q);
		$rows = mysql_fetch_object ($result);
		$subject = $rows->nm_subject;
		$message = "Ini bener2 dikirim dari skrip yang disimpen di server DITS";

		// Grab our config settings
		require_once('libs/config.php');
		 
		// Grab the FreakMailer class
		require_once('libs/MailClass.inc');
		 
		// instantiate the class
		$mailer = new FreakMailer();

		// Set sender
		$mailer->FromName = $name;
		$mailer->From = $from;

		// Set the subject
		$mailer->Subject = $subject;
		 
		// Body
		$mailer->Body = $message;
		$mailer->isHTML(true);

		// Testing
		echo "Subject: $subject<br>";
		echo "From: $name &lt;$from&gt;<br>";
    // Add an address to send to.
    $mailer->AddAddress("andreas_w@telkom.co.id", "Andreas W. Yanuardi");
    $mailer->AddCC("lutfi_san@yahoo.com", "Lutfi A.");
   
    echo "To: Andreas W. Yanuardi &lt;andreas_w@telkom.co.id&gt;<br>";
    echo "CC: Lutfi A. &lt;lutfi_san@yahoo.com&gt;<br>";
	echo "Message:<br>$message<br>";

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
}

send_mail_2agent(2,"Lutfi","silutfi@gmail.com","send e-mail from PHP script");
?>