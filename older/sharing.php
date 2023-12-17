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
$_SESSION['page']=2;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"> -->
<html>
<head>
<title>KMORE - Sharing</title>
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

<!-- jQuery -->
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

<!-- Date Picker -->
<script type="text/javascript" src="jscript/jquery.datePicker.js"></script>
<script type="text/javascript" src="jscript/date.js"></script>
<script language="javascript">
$(function()
{
	$('.date-pick').datePicker()
	$('#start-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#end-date').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	$('#end-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#start-date').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
});
</script>
<link rel="stylesheet" type="text/css" media="screen" href="style/datePicker.css">
<style type="text/css">
/* located in demo.css and creates a little calendar icon
 * instead of a text link for "Choose date"
 */
a.dp-choose-date {
	/*float: left;
	float: right;*/
	width: 16px;
	height: 16px;
	padding: 0;
	margin: 5px 3px 0;
	/*display: block;*/
	display: inline-block;
	text-indent: -2000px;
	overflow: hidden;
	background: url("style/calendar.png") no-repeat; 
}
a.dp-choose-date.dp-disabled {
	background-position: 0 -20px;
	cursor: default;
}
/* makes the input field shorter once the date picker code
 * has run (to allow space for the calendar icon
 */
input.dp-applied {
	width: 100px;
	/*float: left;*/
}
</style>

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

	  <?
	  switch ($_REQUEST['mn'])
	  {
		case 1:
			include("sharing_req_all.php");
			break;
		case 11:
			include("sharing_req_edt.php");
			$_REQUEST['mn']=1;
			break;
		case 2:
			include("sharing_req_add.php");
			break;
		case 3:
			include("sharing_req_close.php");
			break;
		case 31:
			include("sharing_req_close_d.php");
			$_REQUEST['mn']=3;
			break;
		case 32:
			include("sharing_attend_edt.php");
			$_REQUEST['mn']=3;
			break;
		case 4:
			include("sharing_req_his.php");
			break;
		default:
			include("sharing_req_all.php");
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
	  <li><a href="?mn=1" <? if($_REQUEST['mn']==1) echo 'id="current"'; ?>>My Sharing Knowledge</a>
	  <li><a href="?mn=2" <? if($_REQUEST['mn']==2) echo 'id="current"'; ?>>Create Request Sharing</a>
	  <li><a href="?mn=3" <? if($_REQUEST['mn']==3) echo 'id="current"'; ?>>Close Sharing Task</a>
	  <li><a href="?mn=4" <? if($_REQUEST['mn']==4) echo 'id="current"'; ?>>My Sharing History</a>
	  </ul>
	</div>
  </div>

  <? include ("footer.php"); ?>

</div>

</body>
</html>
