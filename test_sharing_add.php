<?
session_start();
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

	<!-- sharing_req_add.php -->

	<br>
	<form name= "myform" class="cmxform" id="commentForm" action="save2db_test.php" method="post">
	<!-- <form name= "myform" class="cmxform" id="commentForm" action="save2db.php" method="post"> -->
		<fieldset>
			<h2>Create Request Sharing</h2>
			<p>
				<label for="cjudul">Judul/Tema:&nbsp;*</label>
				<input type="text" id="cjudul" name="judul" size="100" maxlength="200" class="required" value="<?= $_SESSION[sjudul]; ?>"/>
			</p>
			<p>
				<label for="cknowmap">Kategori Knowledge:&nbsp;*</label>
				<select name="id_map" class="required">
				<option value="">- Pilih -</option>
				<?
				   $tSQL = "SELECT * FROM knowledge_map"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur) {
					   $listrow = mysql_fetch_array($result);
					   if ($listrow["level"]==2) $spacer="&nbsp;&nbsp;";
					   else if ($listrow["level"]==3) $spacer="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					   else $spacer="";
						
				?>
				<option value="<?= $listrow["id_map"]; ?>">
				<? echo $spacer.$listrow["nm_map"]; ?>
				</option>
				<?
					   $cur++;
				   }
				?>
				</select>
			</p>
			<p>
				<label for="cjenis">Jenis Materi:</label>
				<input type="text" id="cjenis" name="jenis" size="50" value="<?= $_SESSION[sjenis]; ?>"/>&nbsp;&nbsp;e.g.: Standard, Kajian, Best Practice, etc.
			</p>
			<p><!-- Otomatis -->
				<label for="csubmitter">Pengirim:</label>
				<input type="text" id="csubmitter" name="submitter" size="50" value="<?= $_SESSION[nama]; ?>" readonly/>
			</p>
			<p><!-- Otomatis -->
				<label for="cname">Pembicara:</label>
				<select name="nik" onChange="get_loker(this.value)">
				<?
				   $tSQL = "SELECT a.*, b.nm_loker FROM user a JOIN loker b ON a.id_loker=b.id_loker WHERE a.active='1' ORDER BY nama"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur){
					   $listrow = mysql_fetch_array($result);
				?>
				<option value="<?= $listrow["nik"]."-".$listrow["id_loker"]; ?>" <? if ($listrow["nik"]==$_SESSION['nik_login']) echo "selected"; ?>><? echo $listrow["nama"]; ?></option>
				<?
					   $cur++;
				   }
				?>
				</select>
			</p>
			<p>
				<label for="cname">Pembicara Lainnya</label><br>
				<label for="cname">Pembicara 2:</label>
				<select name="niklain[]" onChange="get_loker(this.value)">
				<option value="">- Pilih -</option>
				<?
				   $tSQL = "SELECT a.*, b.nm_loker FROM user a JOIN loker b ON a.id_loker=b.id_loker WHERE a.active='1' AND a.nik <> $_SESSION[nik_login] ORDER BY nama"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur){
					   $listrow = mysql_fetch_array($result);
				?>
				<option value="<?= $listrow["nik"]; ?>"><? echo $listrow["nama"]; ?></option>
				<?
					   $cur++;
				   }
				?>
				</select><br>
				<label for="cname">Pembicara 3:</label>
				<select name="niklain[]" onChange="get_loker(this.value)">
				<option value="">- Pilih -</option>
				<?
				   $tSQL = "SELECT a.*, b.nm_loker FROM user a JOIN loker b ON a.id_loker=b.id_loker WHERE a.active='1' AND a.nik <> $_SESSION[nik_login] ORDER BY nama"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur){
					   $listrow = mysql_fetch_array($result);
				?>
				<option value="<?= $listrow["nik"]; ?>"><? echo $listrow["nama"]; ?></option>
				<?
					   $cur++;
				   }
				?>
				</select>
				</select><br>
				<label for="cname">Pembicara 4:</label>
				<select name="niklain[]" onChange="get_loker(this.value)">
				<option value="">- Pilih -</option>
				<?
				   $tSQL = "SELECT a.*, b.nm_loker FROM user a JOIN loker b ON a.id_loker=b.id_loker WHERE a.active='1' AND a.nik <> $_SESSION[nik_login] ORDER BY nama"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur){
					   $listrow = mysql_fetch_array($result);
				?>
				<option value="<?= $listrow["nik"]; ?>"><? echo $listrow["nama"]; ?></option>
				<?
					   $cur++;
				   }
				?>
				</select>
			</p>
			<p>
				<label for="ctanggal">Tanggal:</label>
				<input name="start-date" id="start-date" class="date-pick" readonly value="<?= date("d-m-Y"); ?>"/>
			</p>
			<?
			$arrhour = array('08','09','10','11','12','13','14','15','16','17','18','19');
			?>
			<p>
				<label for="cjmulai">Jam Mulai:</label>
				<select name="jmulai" onChange="validateTime(this.form)" class="required">
				<?
				foreach ($arrhour AS $key => $value) {
					#if ($value > date(H)-1) {
				?>
				<option value=<?=$value;?>><?=$value;?></option>
				<?
					#}
				}
				?>
				</select>
				<select name="mmulai" onChange="validateTime(this.form)" class="required">
				<option value=00>00</option>
				<option value=30>30</option>
				</select>
			</p>
			<p>
				<label for="cjusai">Jam Selesai:</label>
				<select name="jusai" onChange="validateTime(this.form)" class="required">
				<option value="">---</option>
				<?
				foreach ($arrhour AS $key => $value) {
					#if ($value > date(H)-1) {
				?>
				<option value=<?=$value;?>><?=$value;?></option>
				<?
					#}
				}
				?>
				</select>
				<select name="musai" onChange="validateTime(this.form)" class="required">
				<option value=30>30</option>
				<option value=00>00</option>
				</select>
			</p>
				<!-- <a href="cekjadwal.php?height=300&width=600" title="Cek jadwal" class="thickbox"><img src="images/find.jpg" width="16" border="0" alt=""></a> -->
			<p>
				<label for="cruang">Lokasi Ruangan:&nbsp;*</label>
				<input type="text" id="clokasi" name="lokasi" size="100" class="required" value="<?= $_SESSION[slokasi]; ?>"/>
			</p>
			<div id="lokerdiv"><p>
				<label for="cunitkerja">Unit Kerja:</label>
				<input type="text" id="cunitkerja" name="unitkerja" size="100" value="<?=$_SESSION['nm_loker']?>" readonly/>
			</p></div>
			<p>
				<?
				   $tSQL = "SELECT * FROM loker WHERE id_top='100' AND id_loker <> '101'"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   $for_invite = array();
				   while ($num >= $cur){
					   $listrow = mysql_fetch_array($result);
					   $for_invite[] = $listrow['id_loker'];
				?>
				<? if ($cur == 1) { ?>
				<label for="ctaudien">Target Audience:&nbsp;*</label>
				<? } else { ?>
				<label>&nbsp;</label><? } ?>
				<input type="checkbox" id="cbidang[]" name="bidang[]" value="<?= $listrow['id_loker']; ?>" <? if ($listrow['id_loker']==$_SESSION['id_bidang']) echo "checked"; ?>/>&nbsp;<? echo $listrow['nm_loker']; ?><br>
				<?
					   $cur++;
				   }
				?>
			</p>
			<p>
				<label for="cjenis">External Audience:</label>
				<textarea id="cexaudien" name="exaudien" rows="5" cols="97"></textarea><br>
				<label></label>&nbsp;Email address, separated by comma (e.g.: abc@one.com, def@two.net)
			</p>
			<p>
				<label for="cjenis">Instansi:</label>
				<textarea id="cexinstance" name="exinstance" rows="5" cols="97"></textarea><br>
				<label></label>&nbsp;Instance name, separated by comma
			</p>
			<p>
				<label for="cabstraksi">Abstraksi:&nbsp;*</label>
				<textarea id="cabstraksi" name="abstraksi" rows="5" cols="97" class="required"><?= $_SESSION[sabstraksi]; ?></textarea>
			</p>
			<p>
				<label for="charapan">Harapan:</label>
				<textarea id="charapan" name="harapan" rows="5" cols="97"><?= $_SESSION[sharapan]; ?></textarea>
			</p>
			<p>
				<label for="creferen">Referensi:</label>
				<textarea id="creferensi" name="referensi" rows="5" cols="97"><?= $_SESSION[sreferensi]; ?></textarea>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="submit" value="Submit"/>
				<input type="hidden" name="sw" value="11"/>
				<input type="hidden" name="submitter" value="<?= $_SESSION[nik_login]; ?>"/>
			</p>
		</fieldset>
	</form>

	<script language="JavaScript">
	function validateTime(obj){
		var strMessage = 'Your preferred call start time cannot be later than your call end time.';
		if (obj.jmulai.selectedIndex>0 && obj.jusai.selectedIndex>0)
		{
			if (obj.jmulai.options[obj.jmulai.selectedIndex].value*1 > obj.jusai.options[obj.jusai.selectedIndex].value*1)
			{
				alert(strMessage);
				obj.jmulai.focus();
				document.forms['myform'].elements['btnSubmit'].disabled = true;
				return false;
			}
			else if ((obj.jmulai.options[obj.jmulai.selectedIndex].value*1 > obj.jusai.options[obj.jusai.selectedIndex].value*1) &&
					 (obj.mmulai.options[obj.mmulai.selectedIndex].value*1 == obj.musai.options[obj.musai.selectedIndex].value*1))
			{
				alert(strMessage);
				obj.jmulai.focus();
				document.forms['myform'].elements['btnSubmit'].disabled = true;
				return false;
			}
			else if ((obj.jmulai.options[obj.jmulai.selectedIndex].value*1 == obj.jusai.options[obj.jusai.selectedIndex].value*1) &&
					 (obj.mmulai.options[obj.mmulai.selectedIndex].value*1 > obj.musai.options[obj.musai.selectedIndex].value*1))
			{
				alert(strMessage);
				obj.mmulai.focus();
				document.forms['myform'].elements['btnSubmit'].disabled = true;
				return false;
			}
			else if ((obj.jmulai.options[obj.jmulai.selectedIndex].value*1 == obj.jusai.options[obj.jusai.selectedIndex].value*1) &&
					 (obj.mmulai.options[obj.mmulai.selectedIndex].value*1 == obj.musai.options[obj.musai.selectedIndex].value*1))
			{
				alert(strMessage);
				obj.mmulai.focus();
				document.forms['myform'].elements['btnSubmit'].disabled = true;
				return false;
			}
			else
			{
				document.forms['myform'].elements['btnSubmit'].disabled = false;
			} 
		}
		return true;
	}
	</script>

	<!-- end of sharing_req_add.php -->

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
