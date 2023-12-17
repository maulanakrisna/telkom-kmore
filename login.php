<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>KMORE - Knowledge Management On-Line Report</title>
<link rel="shortcut icon" href="images/klogo.ico">
<link type="text/css" href="style/master.css" rel="stylesheet">
</head>

<body>

<div id="maincontainer">

  <?
    

  include ("header3.php");
  ?>

  <div id="contentwrapper">
	<div class="innertube">
		<form class="login" method="post" action="verlogin.php">
		<h4><center><strong>Login Area</strong></center></h4>
		<? if ($_REQUEST[x]==2) echo "<p><center><font color=red>You have to Login first!<br>Please try again</font></center><br>"; ?>
		<br/>

		<label>Username:</label><input type="text" name="uname" size="15"/><br/>
		<label>Password:</label><input type="password" name="pword" size="15"/><br>
		<? if ($_REQUEST[x]==1) echo "<p><center><font color=red>Username/Password did not match!<br>Please try again</font></center><br>"; ?>
		<br/>
		<center><input type="submit" name="btnsubmit" value="Login" class="button"/></center>
		</form>
	</div>
  </div>

  <? include ("footer.php"); ?>
</div>

<!--
	<div id="wrap">
	<div id="header">
	</div>

	<div id="outer">
	<div id="inner">

	<div id="content">
		<form class="login" method="post" action="verlogin.php">
		<h4><center><strong>Login Area</strong></center></h4>
		<label>Username:</label>		<input type="text" name="uname" size="12"><br>
		<label>Password:</label>		<input type="password" name="pword" size="12"><br>
		<? if ($_REQUEST[x]==1) echo "<p><center><font color=red>Username/Password did not match!<br>Please try again</font></center><br>"; ?>
		<br><center><input type="submit" name="btnsubmit" value="Submit"></center>
		</form>
    </div>

	</div>
	</div>

	<div id="footer">PT. TELEKOMUNIKASI INDONESIA &copy; 2009
	</div>

</div>
-->
</body>
</html>
