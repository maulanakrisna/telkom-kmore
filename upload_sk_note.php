	<form name= "myform" class="cmxform" id="commentForm" action="save2db.php" method="post" enctype="multipart/form-data">
		<fieldset>
			<h2>Upload SK / CEO note</h2>
			<p>
				<label for="csubmitter">Sender:</label>
				<input type="text" id="csubmitter" name="submitter" size="50" value="<?= $_SESSION[nama]; ?>" readonly>
			</p>
			<p>
				<label for="cjudul">Title/Theme:&nbsp;*</label>
				<input type="text" id="cjudul" name="judul" size="100" maxlength="200" class="required" value="<?= $_SESSION[sjudul]; ?>">
			</p>			
			<p>
				<label for="cabstraksi">Abstraction:&nbsp;*</label>
				<textarea id="cabstraksi" name="abstraksi" rows="5" cols="97" class="required"><?= $_SESSION[sabstraksi]; ?></textarea>
			</p>
			<p>
				<label for="cfile1">File Attachment 1:</label>
				<input type="hidden" name="MAX_FILE_SIZE1" value="<?= $filesize1; ?>">
				<input type="file" class="required" name="file1" id="cfile1" >
			</p>
			<p>
				<label for="cfile2">File Attachment 2:</label>
				<input type="hidden" name="MAX_FILE_SIZE2" value="<?= $filesize2; ?>">
				<input type="file"  name="file2" id="cfile2" >
			</p>
			<p>
				<label for="cfile3">File Attachment 3:</label>
				<input type="hidden" name="MAX_FILE_SIZE3" value="<?= $filesize3; ?>">
				<input type="file"  name="file3" id="cfile3" >
			</p>
			<p>
				<label for="cfile4">File Attachment 4:</label>
				<input type="hidden" name="MAX_FILE_SIZE4" value="<?= $filesize4; ?>">
				<input type="file"  name="file4" id="cfile4" >
			</p>
			<p>
				<label for="cfile5">File Attachment 5:</label>
				<input type="hidden" name="MAX_FILE_SIZE5" value="<?= $filesize5; ?>">
				<input type="file"  name="file5" id="cfile5" >
			</p>
		
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="button" value="Submit">
				<input type="hidden" name="sw" value="31">
				<input type="hidden" name="submitter" value="<?= $_SESSION[nik_login]; ?>">
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
