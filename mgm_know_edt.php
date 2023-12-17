<?
/*
-----
file asal  : adm_know_list.php
file tujuan: save2db.php?sw=52
-----
*/
	$tnow = date("Y-m-d H:i:s", mktime(date("H")-1, date("i"), date("s"), date("m"), date("d"), date("y")));
	$sQuery = "SELECT a.*, b.nama AS submitter, c.nama AS speaker FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik WHERE a.id_know='$_REQUEST[id]'";
	#echo "$sQuery<br>";
	query_sql($sQuery,$result);
	$rows = mysql_fetch_object ($result);
?>
	<br>
	<form name="myform" class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Edit Sharing Knowledge History</h2>
			<p>
				<label for="cjudul">Title/Theme:&nbsp;*</label>
				<input type="text" id="cjudul" name="judul" size="100" maxlength="200" class="required" value="<?= $rows->judul; ?>"/>
			</p>
			<p>
				<label for="cknowmap">Knowledge Category:&nbsp;*</label>
				<select name="id_map">
				<? if(!isset($rows->id_map)) { ?>
				<option value="">- Choose -</option>
				<?
				   }
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
				<option value="<?= $listrow["id_map"]; ?>" <? if($listrow["id_map"]==$rows->id_map) echo "selected";?>>
				<? echo $spacer.$listrow["id_map"]."&nbsp;-&nbsp;".$listrow["nm_map"]; ?>
				</option>
				<?
					   $cur++;
				   }
				?>
				</select>
			</p>
			<p>
				<label for="cjenis">Sharing type:&nbsp;*</label>
				<input type="text" id="cjenis" name="jenis" size="50" class="required" value="<?= $rows->jenis; ?>"/>&nbsp;&nbsp;e.g.: Standard, Kajian, Best Practice, etc.
			</p>
			<p>
				<label for="cname">Contributor:</label>
				<select name="nik" onChange="get_loker(this.value)">
				<option value="">- Choose -</option>
				<?
				   $tSQL = "SELECT a.*, b.nm_loker FROM user a JOIN loker b ON a.id_loker=b.id_loker ORDER BY nama"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   while ($num >= $cur){
					   $listrow = mysql_fetch_array($result);
				?>
				<option value="<?= $listrow["nik"]; ?>" <? if ($rows->nik==$listrow["nik"]) echo "selected"; ?>><? echo $listrow["nama"]; ?></option>
				<?
					   $cur++;
				   }
				?>
				</select><br>
			</p>
			<p>
				<?
				$niks = explode(",",$rows->member);
				?>
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
				<option value="<?= $listrow["nik"]; ?>" <? if ($niks[0]==$listrow["nik"]) echo "selected"; ?>><? echo $listrow["nama"]; ?></option>
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
				<option value="<?= $listrow["nik"]; ?>" <? if ($niks[1]==$listrow["nik"]) echo "selected"; ?>><? echo $listrow["nama"]; ?></option>
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
				<option value="<?= $listrow["nik"]; ?>" <? if ($niks[2]==$listrow["nik"]) echo "selected"; ?>><? echo $listrow["nama"]; ?></option>
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
				<input type="text" id="cext_speaker" name="ext_speaker" size="80" value="<?= $rows->ext_speaker; ?>"/>
			</p>
			<p>
				<label for="cinstansi">Instance:</label>
				<input type="text" id="cinstansi" name="instansi" size="80" value="<?= $rows->instansi; ?>"/>
			</p>
			<p>
				<label for="ctanggal">Date:</label>
				<input name="start-date" id="start-date" class="date-pick" value="<?= date("d-m-Y",strtotime($rows->t_mulai)); ?>"/>
			</p>
			<?
				$j_mulai = substr($rows->t_mulai,11,2);
				$m_mulai = substr($rows->t_mulai,14,2);
				$j_akhir = substr($rows->t_akhir,11,2);
				$m_akhir = substr($rows->t_akhir,14,2);
			?>
			<p>
				<label for="cjmulai">Start time:</label>
				<select name="jmulai" onChange="validateTime(this.form)" class="required">
				<option value="08" <? if ($j_mulai == "08") echo "selected"; ?>>08</option>
				<option value="09" <? if ($j_mulai == "09") echo "selected"; ?>>09</option>
				<option value="10" <? if ($j_mulai == "10") echo "selected"; ?>>10</option>
				<option value="11" <? if ($j_mulai == "11") echo "selected"; ?>>11</option>
				<option value="12" <? if ($j_mulai == "12") echo "selected"; ?>>12</option>
				<option value="13" <? if ($j_mulai == "13") echo "selected"; ?>>13</option>
				<option value="14" <? if ($j_mulai == "14") echo "selected"; ?>>14</option>
				<option value="15" <? if ($j_mulai == "15") echo "selected"; ?>>15</option>
				<option value="16" <? if ($j_mulai == "16") echo "selected"; ?>>16</option>
				<option value="17" <? if ($j_mulai == "17") echo "selected"; ?>>17</option>
				<option value="18" <? if ($j_mulai == "18") echo "selected"; ?>>18</option>
				<option value="19" <? if ($j_akhir == "19") echo "selected"; ?>>19</option>
				</select>&nbsp;:
				<select name="mmulai" onChange="validateTime(this.form)" class="required">
				<option value="00" <? if ($m_mulai == "00") echo "selected"; ?>>00</option>
				<option value="30" <? if ($m_mulai == "30") echo "selected"; ?>>30</option>
				</select>
			</p>
			<p>
				<label for="cjusai">End time:</label>
				<select name="jusai" onChange="validateTime(this.form)" class="required">
				<option value="08" <? if ($j_akhir == "08") echo "selected"; ?>>08</option>
				<option value="09" <? if ($j_akhir == "09") echo "selected"; ?>>09</option>
				<option value="10" <? if ($j_akhir == "10") echo "selected"; ?>>10</option>
				<option value="11" <? if ($j_akhir == "11") echo "selected"; ?>>11</option>
				<option value="12" <? if ($j_akhir == "12") echo "selected"; ?>>12</option>
				<option value="13" <? if ($j_akhir == "13") echo "selected"; ?>>13</option>
				<option value="14" <? if ($j_akhir == "14") echo "selected"; ?>>14</option>
				<option value="15" <? if ($j_akhir == "15") echo "selected"; ?>>15</option>
				<option value="16" <? if ($j_akhir == "16") echo "selected"; ?>>16</option>
				<option value="17" <? if ($j_akhir == "17") echo "selected"; ?>>17</option>
				<option value="18" <? if ($j_akhir == "18") echo "selected"; ?>>18</option>
				<option value="19" <? if ($j_akhir == "19") echo "selected"; ?>>19</option>
				</select>&nbsp;:
				<select name="musai" onChange="validateTime(this.form)" class="required">
				<option value="30" <? if ($m_akhir == "30") echo "selected"; ?>>30</option>
				<option value="00" <? if ($m_akhir == "00") echo "selected"; ?>>00</option>
				</select>
			</p>
			<p>
				<label for="cruang">Venue:&nbsp;*</label>
				<input type="text" id="cruang" name="lokasi" size="100" value="<?= $rows->lokasi; ?>"/>
			</p>
			<p>
				<label for="cukerja">Unit:</label>
				<input type="text" id="cukerja" name="unitkerja" size="100" value="<?= $rows->unitkerja; ?>" readonly class="readonly"/>
			</p>
			<p>
				<?
				$lokers = explode(",",$rows->inv_bidang);
				$tSQL = "SELECT * FROM loker WHERE id_top='100' AND id_loker <> '101'"; 
				$result = mysql_query($tSQL);
				$num = mysql_num_rows($result);
				$cur = 1;
				$for_invite = array();
				while ($num >= $cur) {
					$listrow = mysql_fetch_array($result);
					$for_invite[] = $listrow['id_loker'];
				?>
				<? if ($cur == 1) { ?>
				<label for="ctaudien">Target Audience:&nbsp;*</label>
				<? } else { ?>
				<label>&nbsp;</label>
				<? } ?>
				<input type="checkbox" id="cbidang[]" name="bidang[]" value="<?= $listrow['id_loker']; ?>"<? if ($listrow['id_loker']==$_SESSION['id_bidang'] || in_array($listrow['id_loker'],$lokers)) echo "checked"; ?>/>&nbsp;<? echo $listrow['nm_loker']; ?><br>
				<?
					$cur++;
				}
				?>
			</p>
			<p>
				<label for="cinstance">External Audience:</label>
				<textarea id="cexinstance" name="exinstance" rows="5" cols="97"><?= $rows->ext_audience; ?></textarea><br>
				<label></label>&nbsp;Unit name or person name, separated by comma
			</p>
			<p>
				<label for="cabstraksi">Abstraction:&nbsp;*</label>
				<textarea id="cabstraksi" name="abstraksi" rows="5" cols="97"><?= $rows->abstraksi; ?></textarea>
			</p>
			<p>
				<label for="charapan">Expectation:</label>
				<textarea id="charapan" name="harapan" rows="5" cols="97"><?= $rows->harapan; ?></textarea>
			</p>
			<p>
				<label for="creferen">Reference:</label>
				<textarea id="creferensi" name="referensi" rows="5" cols="97"><?= $rows->referensi; ?></textarea>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="submit" value="Submit"/>&nbsp;
				<input type="hidden" name="idk" value="<?= $_REQUEST[idk]; ?>">
				<input type="hidden" name="sw" value="52">
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
