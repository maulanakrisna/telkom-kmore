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
#include ("getid_linux.php"); //dits server to db dits server
#include ("getid_wind.php"); //localhost to db dits server
#include ("getid_wind_tester.php"); //localhost to db dits server
include ("include/convertdatetime.php");
$_SESSION['page']=7;
include ('auth.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"> -->
<html>
<head>
<title>KMORE - Help</title>
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

/*ul.report { padding-left: 12px; }*/
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


  <div id="contentwrapper">
	<div id="contentcolumn">
	  <div class="innertube">
	  <?
	  switch ($_REQUEST['mn'])
	  {
		case 1:
			include("help_about.php");
			break;
		case 2:
			include("help_menu.php");
			break;
		case 3:
			include("help_view_sharing.php");
			break;
		case 4:
			include("help_req_sharing.php");
			break;
		case 5:
			include("help_close_task.php");
			break;
		case 6:
			include("help_management.php");
			break;
		case 7:
			include("help_admin.php");
			break;
		default:
			include("help_about.php");
			$_REQUEST['mn']=1;
			break;
	  }
	  ?>
	  </div>
	</div>
  </div>

  <div id="leftcolumn">
	<div class="innertube">
	  <ul id="lefttabs">
	  <li><a href="?mn=1" <? if($_REQUEST['mn']==1) echo 'id="current"'; ?>>About KMORE</a>
	  <li><a href="?mn=2" <? if($_REQUEST['mn']==2) echo 'id="current"'; ?>>Menu</a>
	  <li><a href="?mn=3" <? if($_REQUEST['mn']==3) echo 'id="current"'; ?>>View Sharing</a>
	  <li><a href="?mn=4" <? if($_REQUEST['mn']==4) echo 'id="current"'; ?>>Request Sharing</a>
	  <li><a href="?mn=5" <? if($_REQUEST['mn']==5) echo 'id="current"'; ?>>Close Task</a>
	  <? if ($_SESSION["id_profile"]<3) { ?>
	  <li><a href="?mn=6" <? if($_REQUEST['mn']==6) echo 'id="current"'; ?>>Managing Sharing</a>
	  <? } ?>
	  <? if ($_SESSION["id_profile"]==1) { ?>
	  <li><a href="?mn=7" <? if($_REQUEST['mn']==7) echo 'id="current"'; ?>>Admin Menu</a>
	  <? } ?>
	  </ul>
	  <center><img src="images/help_button100.jpg" border="0"></center>
	</div>
  </div>

  <? include ("footer.php"); ?>

</div>

</body>
</html>
