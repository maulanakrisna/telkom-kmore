	<form name= "myform" class="cmxform" id="commentForm" action="save2db.php" method="post" enctype="multipart/form-data">
		<fieldset>
			<h2>Add Book</h2>
			<p>
				<label for="cnik">Judul:&nbsp;*</label>
				<input type="text" id="cnik" name="judul" size="100" maxlength="100" class="required">
			</p>
			<p>
				<label for="cnama">Pengarang:&nbsp;*</label>
				<input type="text" id="cnama" name="pengarang" size="100" maxlength="100" class="required">
			</p>
			<p>
				<label for="cband">Penerbit:&nbsp;*</label>
				<input type="text" id="cband" name="penerbit" size="100" maxlength="100" class="required">
			</p>
			<p>
				<label for="cpisisi">Jenis:&nbsp;*</label>
				<input type="text" id="cposisi" name="jenis" size="50" maxlength="50" class="required">
			</p>
			<p>
				<label id="cabstraksi">Abstraksi: * </label>
				<textarea cols="50" name="abstraksi" id="cabstraksi" rows="5" class="required"></textarea></p>
			<p>
				<label id="cfile">File : * </label>
				<input id="cfile" name="file" type="file">
			</p>

			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="button" value="Submit">
				<input type="hidden" name="sw" value="34">
			</p>
		</fieldset>
	</form>
