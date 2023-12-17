<?
$q = "SELECT a.*, b.nm_loker AS nm_bidang, c.nm_loker, d.nm_profile FROM user a JOIN loker b ON a.id_loker=b.id_loker JOIN loker c ON a.id_loker=c.id_loker JOIN profile d ON a.id_profile=d.id_profile WHERE a.nik='$_REQUEST[nik]'";
#echo "$q<br>";
$result = mysql_query($q);
$rows = mysql_fetch_object($result);
?>

<br>
	<form name="myform" class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Edit User</h2>
			<p>
				<label for="cnik">NIK:&nbsp;*</label>
				<input type="text" id="cnik" name="nik" size="9" maxlength="9" class="required" value="<?= $rows->nik; ?>"/>
			</p>
			<p>
				<label for="c">Nama:&nbsp;*</label>
				<input type="text" id="cnama" name="nama" size="100" maxlength="100" class="required" value="<?= $rows->nama; ?>"/>
			</p>
			<p>
				<label for="cband">Band:</label>
				<input type="text" id="cband" name="" size="3" maxlength="3" value="<?= $rows->band; ?>"/>
			</p>
			<p>
				<label for="cposisi">Posisi:</label>
				<input type="text" id="cposisi" name="posisi" size="100" maxlength="200" value="<?= $rows->posisi; ?>"/>
			</p>
			<p>
				<label for="cloker">Loker:&nbsp;*</label>
				<select name="id_loker" class="required">
				<?
				   $tSQL = "SELECT * FROM loker"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur) {
					   $listrow = mysql_fetch_array($result);
						
				?>
				<option value="<?= $listrow["id_loker"]; ?>" <? if($listrow["id_loker"]==$rows->id_loker) echo "selected";?>>
				<? echo $listrow["nm_loker"]; ?>
				</option>
				<?
					   $cur++;
				   }
				?>
				</select>
			</p>
			<p>
				<label for="cemail">E-mail:&nbsp;*</label>
				<input type="text" id="cemail" name="email" size="100" maxlength="100" class="required" value="<?= $rows->email; ?>"/>
			</p>
			<p>
				<label for="cid_profile">Profile:&nbsp;*</label>
				<select name="id_profile" class="required">
				<?
				   $tSQL = "SELECT * FROM profile"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur) {
					   $listrow = mysql_fetch_array($result);
						
				?>
				<option value="<?= $listrow["id_profile"]; ?>" <? if($listrow["id_profile"]==$rows->id_profile) echo "selected";?>>
				<? echo $listrow["nm_profile"]; ?>
				</option>
				<?
					   $cur++;
				   }
				?>
				</select>
			</p>
			<p>
				<label for="cactive">Active:&nbsp;*</label>
				<select name="active">
				<option value="0" <? if($rows->active==0) echo "selected";?>>No</option>
				<option value="1" <? if($rows->active==1) echo "selected";?>>Yes</option>
				</select>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="button" value="Submit"/>&nbsp;
				<input type="hidden" name="nik0" value="<?= $_REQUEST[nik]; ?>">
				<input type="hidden" name="sw" value="62">
			</p>
