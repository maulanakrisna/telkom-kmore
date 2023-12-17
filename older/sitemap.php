<?
#ini_set("url_rewriter.tags","");
/*
if (isset($_GET['PHPSESSID'])) {
	$requesturi = preg_replace('/?PHPSESSID=[^&]+/',"",$_SERVER['REQUEST_URI']);
	$requesturi = preg_replace('/&PHPSESSID=[^&]+/',"",$requesturi);
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://".$_SERVER['HTTP_HOST'].$requesturi);
	exit;
}
*/
session_start();
setcookie('phpsessid','value',time()-1); 
include ('include/dbcon.php'); 
include ("getid_linux.php"); //dits server to db dits server
#include ("getid_wind.php"); //localhost to db dits server
include ("include/convertdatetime.php");
$_SESSION['page']=6;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"> -->
<html>
<head>
<title>KMORE - Sitemap</title>
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="kmore, knowledge management, km on-line report">
<meta name="Description" content="">
<link rel="shortcut icon" href="images/favicon.ico">
<link type="text/css" href="style/master.css" rel="stylesheet">
<link type="text/css" href="style/menu.css" rel="stylesheet">
<link type="text/css" href="style/screen.css" rel="stylesheet" media="screen" />
<link type="text/css" href="style/newsticker.css" rel="stylesheet">
<link type="text/css" href="style/table.css" rel="stylesheet" media="print, projection, screen">
<style type="text/css">
ul.tree, ul.tree ul { list-style-type: square; margin: 0; padding: 0; }
ul.tree li { margin-left: 15; padding: 0 12px; line-height: 20px; color: #369; font-weight: normal; }
</style>

<script type="text/javascript" src="jscript/jquery.js"></script>

<!-- Newsticker -->
<script type="text/javascript" src="jscript/jquery.li-scroller.1.0.js"></script>
<script type="text/javascript">
$(function(){
	$("ul#ticker01").liScroll({travelocity: 0.04});
});
</script>
<!-- /// -->

<!-- for thickbox -->
<script type="text/javascript" src="jscript/thickbox.js"></script>
<link type="text/css" href="style/thickbox.css" rel="stylesheet"/>
<!-- end of thickbox -->

<script type="text/javascript" src="jscript/jquery.validate.js" ></script>
<!-- for styling the form -->
<script type="text/javascript" src="jscript/cmxforms.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#commentForm").validate();
});
</script>

</head>

<body>
<div id="maincontainer">
  <?
  include ("header.php");
  ?>

  <div id="contentwrapper"><? include("mainmenu.php"); ?></div>
  <div id="contentwrapper"><? include("newsticker.php"); ?></div>

  <div id="contentwrapper">
	<div id="contentcolumn">
	  <div class="innertube">
		<H3>KMORE - Sitemap</H3>
		<ul style="margin:0px; padding:0px" class="tree"><a href="index.php">Home</a>
			<li><a href="index.php?mn=1">Schedule</a>
			<li><a href="index.php?mn=2">Invitation</a>
			<li><a href="index.php?mn=3">Request to Attend</a>
		    <li><a href="index.php?mn=4">Archives</a>
		    <!-- <li><a href="index.php?mn=5">Feedback</a> -->
		</ul>
		<ul style="margin:0px; padding:0px" class="tree"><a href="sharing.php?">Sharing</a>
			<li><a href="sharing.php?mn=1">My Sharing Knowledge</a>
			<li><a href="sharing.php?mn=2">Create Request Sharing</a>
			<li><a href="sharing.php?mn=3">Close Sharing Task</a>
			<li><a href="sharing.php?mn=4">My Sharing History</a>
		</ul>
		<ul style="margin:0px; padding:0px" class="tree"><a href="report.php?">Report</a>
			<li><a href="report.php?mn=1">My Point</a>
			<li><a href="report.php?mn=2">Sharing Point Individu</a>
			<!-- <li><a href="report.php?mn=3">Sharing Point Bidang</a> -->
			<li><a href="report.php?mn=4">Knowledge Map</a>
		</ul>
		<? if ($_SESSION['id_profile'] < '3') { ?>
		<ul class="tree"><a href="mgm_sharing.php">Management</a>
			<li><a href="management.php?mn=1">New Sharing Requests</a>
			<li><a href="management.php?mn=2">Request to Attend</a>
			<li><a href="management.php?mn=3">Close Sharing Tasks</a>
			<li><a href="management.php?mn=5">Sharing Knowledge</a>
		</ul>
		<? } ?>
		<? if ($_SESSION['id_profile'] == '1') { ?>
		<ul class="tree"><a href="admin.php">Admin</a>
			<li><a href="admin.php?mn=1">Data User</a>
			<li><a href="admin.php?mn=2">Bidang</a>
			<li><a href="admin.php?mn=3">Loker</a>
			<li><a href="admin.php?mn=4">Knowledge Map</a>
		</ul>
		<? } ?>
		<ul class="tree"><a href="sitemap.php">Site Map</a>
		</ul>
		<ul class="tree"><a href="help.php">Help</a>
		</ul>
	  </div>
	</div>
  </div>

  <div id="leftcolumn">
	<div class="innertube"><BR>
	<img src="images/Mindshift_Brain_Puzzle.jpg" width="160" border="0">
	</div>
  </div>

  <? include ("footer.php"); ?>

</div>

</body>
</html>
