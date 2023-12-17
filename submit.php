<?php
session_start();
if (!session_is_registered("nextValidSubmission"))
{
	session_register("nextValidSubmission");
}

if (isset($_POST['visitor_email']) && $_POST['submissionId'] == $_SESSION['nextValidSubmission'])
{
	mail("MY EMAIL ADDRESS","Newsletter Subscription","{$_POST['visitor_name']} at {$_POST['visitor_email']} would like to subscribe to your mailing list.");
	echo "Subscription Complete. Thank you!";
}

$_SESSION['nextValidSubmission'] = rand(1000000,9999999); //Now that all processing is done, change nextValidSubmission.
?>

<form method="post">
Name: <input type="text" name="visitor_name"><br />
Email: <input type="text" name="visitor_email"><br />
<input type="submit" value="Subscribe Now">
<input type="hidden" value="<?php echo $_SESSION['nextValidSubmission'];?>" name="submissionId">
</form> 