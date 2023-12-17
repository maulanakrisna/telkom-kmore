<?
/*
-----
file asal  : mgm_sharing.php?mn=1 (mgm_req_list.php)
file tujuan: save2db.php?sw=21
-----
	Just for Committee & Administrator
	, e.email as exaudien JOIN external_audience e ON a.id_know=e.id_know
*/
	require_once("include/dbcon.php");
	$sQuery = "SELECT a.*, b.nama AS submitter, c.nama AS speaker FROM knowledge a JOIN user b ON a.submitter=b.nik JOIN user c ON a.nik=c.nik WHERE a.id_know='$_REQUEST[idk]'";
	#echo "$sQuery<br>";
	query_sql($sQuery,$result);
	$rows = mysql_fetch_object ($result);
	$nik  = $rows->nik;

	# get invited bidang
	// e.g. "140,150,160"
	$inv_bid = $rows->inv_bidang;
	$find_bid = str_replace(",", "','",$inv_bid);
	#echo "\$inv_bid:$inv_bid<br>";

	$nm_bidang = array();
	$q = "SELECT nm_loker FROM loker WHERE id_loker IN ('$find_bid')";
	#echo "\$q: $q<br>";
	$res = mysql_query($q);
	while ($r = mysql_fetch_array($res)) {
		$nm_bidang[] = $r[nm_loker];
	}
	$bidangnya = implode(", ",$nm_bidang);

	if ($rows->conflict==1)
	{
		$q  = "SELECT id_know,t_mulai,t_akhir FROM knowledge WHERE sharing_status IN ('1','3') AND ";
		$q .= "((t_mulai BETWEEN '$rows->t_mulai' AND '$rows->t_akhir') OR (t_akhir BETWEEN '$rows->t_mulai' AND '$rows->t_akhir')) LIMIT 1";
		#echo "$q";
		$res = mysql_query($q);
		$r   = mysql_fetch_object($res);
		$idc = $r->id_know;
	}
	/*
	else 
	{
		$idc = "";
	}
	*/

?>
	<br>
	<form class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Form Request Sharing - Review</h2>
			<!-- <legend>&nbsp;<strong>Isilah form ini dengan data yang benar</strong>&nbsp;</legend> -->
			<?
			/*
			if ($_SESSION['found']==0)
			{
				echo "<br><br><center>Sorry, you cannot approve request of sharing because your session is expired! Please re-login to <a href='http://portal.telkom.co.id'>POINT</a></center><br><br>";
			}
			else
			{
			*/
			?>
			<p>
				<label for="cjudul">Title/Theme:&nbsp;*</label>
				<input type="text" id="cjudul" name="judul" size="100" maxlength="200" value="<?= $rows->judul; ?>"/>
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
				<option value="<?= $listrow["id_map"]; ?>" <? if ($listrow['id_map']==$rows->id_map) echo "selected"; ?>>
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
				<input type="text" id="cjenis" name="jenis" size="50" value="<?= $rows->jenis; ?>"/>
			</p>
			<p>
				<label for="csubmitter">Sender:</label>
				<input type="text" size="38" value="<?= $rows->submitter; ?>" readonly class="readonly">
			</p>
			<p>
				<label for="cname">Contributor:</label>
				<input type="text" size="38" value="<?= $rows->speaker; ?>" readonly class="readonly">
			</p>
			<p>
				<label for="cname">Team member</label>
				<?
				$membernya = $rows->member;
				$member  = explode(",",$rows->member);
				$members = "'".implode("','",$member)."'";
				$tSQL = "SELECT * FROM user WHERE nik IN ($members)"; 
				#echo "$tSQL<br>";
				$result = mysql_query($tSQL);
				$nameis = array();
				while ($r = mysql_fetch_array($result)) {
					$nameis[] = $r[nama];
				}
				$namanya = implode(", ",$nameis);
				?>
				<input type="text" size="100" value="<?= $namanya; ?>" readonly class="readonly">
			</p>
			<p>
				<label for="ctanggal">Date:</label>
				<input name="start-date" id="start-date" class="date-pick" readonly value="<?= date("d-m-Y",strtotime($rows->t_mulai)); ?>"/>
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
				<label for="ctaudien">Target Audience:</label>
				<textarea id="ctaudience" name="inv_bid" rows="5" cols="97" readonly class="readonly"><?= $bidangnya; ?></textarea>
			</p>
			<p>
				<label for="cjenis">External Audience:</label>
				<textarea id="cexaudien" name="exaudien" rows="5" cols="97" readonly class="readonly"><?= $rows->ext_audience; ?></textarea><br>
			</p>
			<p>
				<label for="cabstraksi">Abstraction:</label>
				<textarea id="cabstraksi" name="abstraksi" rows="5" cols="97" readonly class="readonly"><?= $rows->abstraksi; ?></textarea>
			</p>
			<p>
				<label for="charapan">Expectation:</label>
				<textarea id="charapan" name="harapan" rows="5" cols="97" readonly class="readonly"><?= $rows->harapan; ?></textarea>
			</p>
			<p>
				<label for="creferen">Reference:</label>
				<textarea id="creferensi" name="referensi" rows="5" cols="97" readonly class="readonly"><?= $rows->referensi; ?></textarea>
			</p>
			<p>
				<label for="approval">Approve:</label>
				<!-- <select name="approval" <? #if ($rows->conflict==1) echo " disabled";?>> -->
				<select name="approval">
				<?
				if ($rows->conflict==1) {
				?>
				<option value="0">No</option>
				<option value="1">Yes</option>
				<?
				} else{
				?>
				<option value="1">Yes</option>
				<option value="0">No</option>
				<?
				}
				?>
				</select><? if ($rows->conflict==1) echo "&nbsp;&nbsp;<a href='showconflict.php?idk=$idc&height=250&width=500' title='Show conflict' class='thickbox'><font color='red'><b>Conflict!</b></font></a>";?>
			</p>
			<p>
				<label for="creferen">Note:<br></label><!-- <a href="show_req_notes.php?id=<?= $_REQUEST[id]; ?>&height=300&width=600" title="Request Sharing Notes" class="thickbox"><img src="images/notes.gif" border="0" hspace="10" vspace="10"></a><br> -->
				<label>&nbsp;</label>
				<textarea id="ccatatan" name="catatan" rows="5" cols="97"></textarea>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" class="button" value="Submit"/>
				<input type="hidden" name="idk" value="<?= $_REQUEST[idk]; ?>">
				<input type="hidden" name="niknya" value="<?= $nik; ?>">
				<input type="hidden" name="member" value="<?= $membernya; ?>">
				<input type="hidden" name="inv_bid" value="<?= $inv_bid; ?>">
				<input type="hidden" name="sw" value="21">
			<? #} ?>
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
