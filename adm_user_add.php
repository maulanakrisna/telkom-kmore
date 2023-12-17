	<br>
	<form name= "myform" class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Add User</h2>
			<p>
				<label for="cnik">NIK:&nbsp;*</label>
				<input type="text" id="cnik" name="nik" size="9" maxlength="9" class="required"/>
			</p>
			<p>
				<label for="cnama">Nama:&nbsp;*</label>
				<input type="text" id="cnama" name="nama" size="100" maxlength="100" class="required"/>
			</p>
			<p>
				<label for="cband">Band:</label>
				<input type="text" id="cband" name="band" size="3" maxlength="3"/>
			</p>
			<p>
				<label for="cpisisi">Posisi:</label>
				<input type="text" id="cposisi" name="posisi" size="100" maxlength="100"/>
			</p>
			<p>
				<label for="cloker">Loker:&nbsp;*</label>
				<select name="loker">
				<option>- Pilih -</option>
				<?
				$tSQL = "SELECT * FROM loker"; 
				$result = mysql_query($tSQL);
				while ($r = mysql_fetch_object($result)) {
				if ($r->id_loker%100==0) $spacer="&nbsp;|----&nbsp;";
     			else if ($r->id_loker%10==0) $spacer="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|----&nbsp;";
			    else $spacer="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp&nbsp;&nbsp;&nbsp;|----&nbsp;";

				?>
				<option value="<?= $r->id_loker; ?>"><?= $spacer.$r->nm_loker; ?></option>
				<? } ?>
				</select>
			</p>
			<p>
				<label for="cemail">Email:&nbsp;*</label>
				<input type="text" id="cemail" name="email" size="100" maxlength="100" class="required"/>
			</p>
			<p>
				<label for="cprofile">Profile:&nbsp;*</label>
				<select name="profile">
				<?
				$tSQL = "SELECT * FROM profile"; 
				$result = mysql_query($tSQL);
				while ($r = mysql_fetch_object($result)) {
				?>
				<option value="<?= $r->id_profile; ?>"><?= $r->nm_profile; ?></option>
				<? } ?>
				</select>
			</p>
			<p>
				<label for="ctatus">Active:&nbsp;*</label>
				<select name="active">
				<option value="1">Yes</option>
				<option value="0">No</option>
				</select>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="button" value="Submit" />
				<input type="hidden" name="sw" value="61">
			</p>
		</fieldset>
	</form>
