<?
/*
-----
file asal  : sharing_req_his.php
file tujuan: save2db.php?sw=12
-----
*/
	$filesize = 2000000;
	#$sQuery = "SELECT a.*, b.nama AS submitter, c.nama AS speaker, d.id_lokers, e.email as exmail FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik JOIN inv_bidang d ON a.id_know=d.id_know JOIN external_audience e ON a.id_know=e.id_know WHERE a.id_know='$_REQUEST[id]'";
	$sQuery = "SELECT a.*, b.nama AS submitter, c.nama AS speaker, d.nm_map FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik JOIN knowledge_map d ON a.id_map=d.id_map WHERE a.id_know='$_REQUEST[idk]'";
	#echo "$sQuery<br>";
	query_sql($sQuery,$result);
	$rows = mysql_fetch_object ($result);

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
	$inv_bid = implode(", ",$nm_bidang);

	// show sharing member
	if (!empty($rows->member)) {
		$nik_member = $rows->member;
		$find_nik = str_replace(",", "','",$nik_member);
		$members = array();
		$q = "SELECT nama FROM user WHERE nik IN ('$find_nik')";
		#echo "\$q: $q<br>";
		$result = mysql_query($q);
		while ($r = mysql_fetch_array($result)) {
			$members[] = $r[nama];
		}
		$inmember = implode(", ",$members);
	}

?>
	<br>
	<form enctype="multipart/form-data" class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Close Sharing Task</h2>
			<!-- <legend>&nbsp;<strong>Isilah form ini dengan data yang benar</strong>&nbsp;</legend> -->
			<?
			/*
			if ($_SESSION['found']==0)
			{
				echo "<br><br><center>Sorry, you are not allowed to close your task because your session is expired! Please re-login to <a href='http://portal.telkom.co.id'>POINT</a></center><br><br>";
			}
			else
			{
			*/
			?>
			<p>
				<label for="cjudul">Title/Theme:</label>
				<input type="text" id="cjudul" name="judul" size="100" maxlength="200" value="<?= $rows->judul; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="cknowmap">Knowledge Category:</label>
				<input type="text" id="cknowmap" name="knowmap" size="100" value="<?= $rows->nm_map; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="cjenis">Sharing type:</label>
				<input type="text" id="cjenis" name="jenis" size="50" value="<?= $rows->jenis; ?>" readonly class="readonly"/>
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
				<input type="text" size="100" value="<?= $inmember; ?>" readonly class="readonly">
			</p>
			<p>
				<label for="cname">Date:</label>
				<input type="text" size="100" value="<?= tampilkan_waktunya($rows->t_mulai); ?>" readonly class="readonly">
			</p>
			<p>
				<label for="cname">Time:</label>
				<input type="text" size="5" value="<?= substr($rows->t_mulai,11,5); ?>" readonly class="readonly">&nbsp;s/d&nbsp;<input type="text" size="5" value="<?= substr($rows->t_akhir,11,5); ?>" readonly class="readonly">
			</p>
			<p>
				<label for="cruang">Venue:</label>
				<input type="text" id="cruang" name="lokasi" size="100" value="<?= $rows->lokasi; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="cukerja">Unit:</label>
				<input type="text" id="cukerja" name="unitkerja" size="100" value="<?= $rows->unitkerja; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="ctaudien">Target Audience:</label>
				<textarea id="ctaudien" name="taudien" rows="5" cols="97" readonly class="readonly"><?= $inv_bid; ?></textarea>
			</p>
			<p>
				<label for="cjenis">External Audience:</label>
				<textarea id="cexaudien" name="exaudien" rows="5" cols="97" readonly class="readonly"><?= $rows->ext_audience; ?></textarea>
			</p>
			<p>
				<label for="cabstraksi">Abstraction:&nbsp;*</label>
				<textarea id="cabstraksi" name="abstraksi" rows="5" cols="97" class="required"><?= $rows->abstraksi; ?></textarea>
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
				<label for="creferen">File Attachment 1:</label>
				<input type="hidden" name="MAX_FILE_SIZE" value="<?= $filesize; ?>">
				<input type="file" name="userfile1" id="userfile1" size="68">
			</p>
			<p>
				<label for="creferen">File Attachment 2:</label>
				<input type="hidden" name="MAX_FILE_SIZE" value="<?= $filesize; ?>">
				<input type="file" name="userfile2" id="userfile2" size="68">
			</p>
			<p>
				<label for="creferen">File Attachment 3:</label>
				<input type="hidden" name="MAX_FILE_SIZE" value="<?= $filesize; ?>">
				<input type="file" name="userfile3" id="userfile3" size="68">
			</p>
			<?

			# get randomkey
			if (isset($rows->randomkey)) $insert_key = $rows->randomkey; else $insert_key=NULL;
			if (isset($insert_key))
			{
				$tSQL = "SELECT * FROM upload WHERE randomkey='".$rows->randomkey."'";
				#echo "$tSQL<br>";
				query_sql($tSQL,$result);
				// check if query-results are not empty
				if (mysql_num_rows($result) <> 0)
				{
			?>
			<p>
				<label></label>Mark attachment for deletion:<br>
			<?
					while ($r = mysql_fetch_array ($result))
					{
			?>
			<label></label>
			<input type="checkbox" name="del[]" value="<?= $r[id]; ?>">
			<input type="hidden" name="nmfile[]" value="<?= $r[id]."/".$r[name]; ?>">&nbsp;<A HREF="takefile.php?idk=<?= $r[id]; ?>" class="menu"><?= $r[name]; ?></A>&nbsp;

			<?
					}
				}
			}
			?>
			</p>
			<p>
				<label for="ctaudien">Attendance sheet:</label>
				<a href="sharing_attsheet.php?idk=<?= $_REQUEST[idk]; ?>&height=500&width=780" title="Attendance Sheet" class="thickbox"><img src="images/clipboard.gif" border="0"></a>
			</p>
			<p>
				<label for="creferen">Show notes:</label><a href="show_close_notes.php?id=<?= $_REQUEST[id]; ?>&height=300&width=600" title="Close Sharing Task Notes" class="thickbox"><img src="images/notes.gif" border="0" hspace="10" vspace="10"></a>
			</p>
			<!--
			<p>
				<label for="creferen">Daftar Hadir:</label><a href="sharing_attend_edt.php?id=<?= $_REQUEST[id]; ?>&height=300&width=790" title="Daftar Hadir" class="thickbox"><img src="images/clipboard.gif" border="0" hspace="10" vspace="10"></a>
			</p>
			-->
			<p>
				<label>&nbsp;</label><input type="submit" class="button" value="Next"/>
				<input type="hidden" name="idk" value="<?= $_REQUEST[idk]; ?>">
				<input type="hidden" name="sw" value="18">
				<input type="hidden" name="save" value="1">
				<input type="hidden" name="insert_key" value="<?= $insert_key; ?>">
			</p>
			<? #} ?>
		</fieldset>
	</form>
