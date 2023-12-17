<?
/*
-----
file asal  : index.php?mn=31
file tujuan: save2db.php?sw=11
-----
*/
?>
	<br>
	<form name= "myform" class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Add Sharing Knowledge History</h2>
			<p>
				<label for="cjudul">Title/Theme:&nbsp;*</label>
				<input type="text" id="cjudul" name="judul" size="100" maxlength="200" class="required"/>
			</p>
			<p>
				<label for="cknowmap">Knowledge Category:&nbsp;*</label>
				<select name="id_map" class="required">
				<option value="">- Choose -</option>
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
				<label for="cjenis">Sharing type:</label>
				<input type="text" id="cjenis" name="jenis" size="50"/>&nbsp;&nbsp;e.g.: Standard, Kajian, Best Practice, etc.
			</p>
			<p>
				<label for="cname">Contributor:&nbsp;*</label>
				<select name="nik" onChange="get_loker(this.value)" class="required">
				<option value="">- Choose -</option>
				<?
				   $tSQL = "SELECT a.*, b.nm_loker FROM user a JOIN loker b ON a.id_loker=b.id_loker WHERE a.active='1' ORDER BY nama"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur){
					   $listrow = mysql_fetch_array($result);
				?>
				<option value="<?= $listrow["nik"]."-".$listrow["id_loker"]; ?>"><? echo $listrow["nama"]; ?></option>
				<?
					   $cur++;
				   }
				?>
				</select>
			</p>
			<p>
				<label for="cname">Team member</label><br>
				<label for="cname">Member 1:</label>
				<select name="niklain[]" onChange="get_loker(this.value)">
				<option value="">- Choose -</option>
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
				<label for="cname">Member 2:</label>
				<select name="niklain[]" onChange="get_loker(this.value)">
				<option value="">- Choose -</option>
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
				<label for="cname">Member 3:</label>
				<select name="niklain[]" onChange="get_loker(this.value)">
				<option value="">- Choose -</option>
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
				<label for="cname">Member 4:</label>
				<select name="niklain[]" onChange="get_loker(this.value)">
				<option value="">- Choose -</option>
				<?
				   $tSQL = "SELECT a.*, b.nm_loker FROM user a JOIN loker b ON a.id_loker=b.id_loker WHERE a.active='1' AND a.nik <> $_SESSION[nik_login] ORDER BY nama"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur){
					   $listrow = mysql_fetch_array($result);
				?>
				<option value="<?= $listrow["nik"]; ?>" <? if ($niks[2]==$listrow["nik"]) echo "selected"; ?>><? echo $listrow["nama"]; ?></option>
				<?
					   $cur++;
				   }
				?>
				</select>
			</p>
			<p>
				<label for="cext_speaker">External Speaker:</label>
				<input type="text" id="cext_speaker" name="ext_speaker" size="80"/>
			</p>
			<p>
				<label for="cinstansi">Instance:</label>
				<input type="text" id="cinstansi" name="instansi" size="80"/>
			</p>
			<p>
				<label for="ctanggal">Date:</label>
				<input name="start-date" id="start-date" class="date-pick" readonly value="<?= date("d-m-Y"); ?>"/>
			</p>
			<?
			$arrhour = array('08','09','10','11','12','13','14','15','16','17','18','19');
			?>
			<p>
				<label for="cjmulai">Start time:</label>
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
				<label for="cjusai">End time:</label>
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
				<label for="cruang">Venue:&nbsp;*</label>
				<input type="text" id="clokasi" name="lokasi" size="100" class="required"/>
			</p>
			<div id="lokerdiv"><p>
				<label for="cunitkerja">Unit:</label>
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
				<input type="checkbox" id="cbidang[]" name="bidang[]" value="<?= $listrow['id_loker']; ?>"/>&nbsp;<? echo $listrow['nm_loker']; ?><br>
				<?
					   $cur++;
				   }
				?>
			</p>
			<p>
				<label for="cexaudien">External Audience:</label>
				<textarea id="cexaudien" name="exaudien" rows="5" cols="97"></textarea><br>
				<label></label>&nbsp;Instance name, separated by comma
			</p>
			<p>
				<label for="cabstraksi">Abstraction:&nbsp;*</label>
				<textarea id="cabstraksi" name="abstraksi" rows="5" cols="97" class="required"></textarea>
			</p>
			<p>
				<label for="charapan">Expectation:</label>
				<textarea id="charapan" name="harapan" rows="5" cols="97"></textarea>
			</p>
			<p>
				<label for="creferen">Reference:</label>
				<textarea id="creferensi" name="referensi" rows="5" cols="97"></textarea>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="submit" value="Submit"/>
				<input type="hidden" name="sw" value="51"/>
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
			else
			{
				document.forms['myform'].elements['btnSubmit'].disabled = false;
			} 
		}
		return true;
	}
	</script>
