<table>
<tr>
<td>
	<form name= "myform" class="cmxform" id="commentForm" action="save2db.php" method="post" >
		<fieldset>
			<h2>Change Password</h2>
			<?
			$e = $_REQUEST['e'];
			if ($e == 1){
				echo "<p><b>Password lama salah!</b></p>";
			}else if ($e == 2){
				echo "<p><b>Password baru tidak sama!</b></p>";
			}else if ($e == 3){
				echo "<p><b>Password lama salah!<br>Password baru tidak sama!</b></p>";
			}else if ($e == 4){
				echo "<p><b>Sukses!</b></p>";
			}
			?>
	
			<p>
				<label for="coldpass">Old Password:</label>
				<input type="password" id="coldpass" name="oldpass" size="25" class="required">
			</p>
			<p>
				<label for="cnewpass">New Password:</label>
				<input type="password" id="cnewpass" name="newpass" size="25" class="required">
			</p>
			<p>
				<label for="cconpass">Confirm Password:</label>
				<input type="password" id="cconpass" name="conpass" size="25" class="required">
			</p>
		
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="button" value="Submit">
				<input type="hidden" name="sw" value="33">
				<input type="hidden" name="submitter" value="<?= $_SESSION[nik_login]; ?>">
			</p>
		</fieldset>
	</form>
</td>
<td>
<form name= "myform2" class="cmxform" id="commentForm" action="save2db.php" method="post" enctype="multipart/form-data">
		<fieldset>
			<h2>Profil Picture</h2>
			<p>
				<label for="cpp">Profil Picture :</label>
				<input id="cpp" name="pp" type="file" >
			</p>	
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="button" value="Submit">
				<input type="hidden" name="sw" value="35">
				<input type="hidden" name="submitter" value="<?= $_SESSION[nik_login]; ?>">
			</p>
		</fieldset>
	</form>
</td>
</tr>
</table>
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
