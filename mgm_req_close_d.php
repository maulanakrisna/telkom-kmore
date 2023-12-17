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
	#$sQuery = "SELECT a.*, b.nama AS submitter, c.nama AS speaker FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik WHERE a.id_know='$_REQUEST[id]'";
	$sQuery = "SELECT a.*, b.nama AS submitter, c.nama AS speaker, d.nm_map FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik JOIN knowledge_map d ON a.id_map=d.id_map WHERE a.id_know='$_REQUEST[id]'";
#echo "$sQuery<br>";
	query_sql($sQuery,$result);
	$rows = mysql_fetch_object ($result);
	$nik = $rows->nik;

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
?>
	<br>
	<form class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Close Sharing - Approval</h2>
			<!-- <legend>&nbsp;<strong>Isilah form ini dengan data yang benar</strong>&nbsp;</legend> -->
			<p>
				<label for="cjudul">Judul/Tema:</label>
				<input type="text" id="cjudul" name="judul" size="100" maxlength="200" value="<?= $rows->judul; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="cknowmap">Kategori Knowledge:</label>
				<input type="text" id="cknowmap" name="knowmap" size="100" maxlength="200" value="<?= $rows->nm_map; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="cjenis">Jenis Materi:</label>
				<input type="text" id="cjenis" name="jenis" size="50" value="<?= $rows->jenis; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="csubmitter">Pengirim:</label>
				<input type="text" size="38" value="<?= $rows->submitter; ?>" readonly class="readonly">
			</p>
			<p>
				<label for="cname">Pembicara:</label>
				<input type="text" size="38" value="<?= $rows->speaker; ?>" readonly class="readonly">
			</p>
			<p>
				<label for="cname">Pembicara Lainnya</label>
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
				<label for="cruang">Hari & Tanggal::</label>
				<input type="text" id="cruang" name="lokasi" size="100" value="<?= tampilkan_waktunya($rows->t_mulai); ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="cruang">Jam:</label>
				<input type="text" id="cruang" name="lokasi" size="100" value="<?= substr($rows->t_mulai,11,5)."&nbsp;s/d&nbsp;".substr($rows->t_akhir,11,5); ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="cruang">Lokasi Ruangan:</label>
				<input type="text" id="cruang" name="lokasi" size="100" value="<?= $rows->lokasi; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="cukerja">Unit Kerja:</label>
				<input type="text" id="cukerja" name="unitkerja" size="100" value="<?= $rows->unitkerja; ?>" readonly class="readonly"/>
			</p>
			<p>
				<label for="ctaudien">Target Audience:</label>
				<textarea id="cabstraksi" name="inv_bid" rows="5" cols="97" readonly class="readonly"><?= $bidangnya; ?></textarea>
			</p>
			<p>
				<label for="ctaudien">Daftar Hadir:</label>
				<a href="sharing_attend.php?id=<?= $_REQUEST[id]; ?>&height=500&width=780" title="Daftar Hadir" class="thickbox"><img src="images/clipboard.gif" border="0"></a>
			</p>
			<p>
				<label for="cjenis">External Audience:</label>
				<textarea id="cexaudien" name="exaudien" rows="5" cols="97" readonly class="readonly"><?= $rows->ext_audience; ?></textarea><br>
			</p>
			<p>
				<label for="cabstraksi">Abstraksi:</label>
				<textarea id="cabstraksi" name="abstraksi" rows="5" cols="97" readonly class="readonly"><?= $rows->abstraksi; ?></textarea>
			</p>
			<p>
				<label for="charapan">Harapan:</label>
				<textarea id="charapan" name="harapan" rows="5" cols="97" readonly class="readonly"><?= $rows->harapan; ?></textarea>
			</p>
			<p>
				<label for="creferen">Referensi:</label>
				<textarea id="creferensi" name="referensi" rows="5" cols="97" readonly class="readonly"><?= $rows->referensi; ?></textarea>
			</p>
			<?
			$q="SELECT name FROM upload WHERE randomkey='".$rows->randomkey."'";
			#echo "\$q: $q<br>";
			$files = array();
			$res = mysql_query($q);
			while ($r = mysql_fetch_object($res)) {
				$files[] = $r->name;
			}
			$showfiles = implode(", ",$files);
			?>
			<p>
				<label for="creferen">File Attachment:</label>
				<textarea id="cfileatt" name="fileatt" rows="5" cols="97" readonly class="readonly"><?= $showfiles; ?></textarea>
			</p>
			<p>
				<label for="approval">Approve:</label>
				<select name="approval">
				<option value="2">Yes</option>
				<option value="0">No</option>
				</select>
			</p>
			<p>
				<label for="creferen">Catatan:</label>
				<a href="show_close_notes.php?id=<?= $_REQUEST[id]; ?>&height=300&width=600" title="Close Sharing Task Notes" class="thickbox"><img src="images/notes.gif" border="0" hspace="10" vspace="10"></a><br>
				<label>&nbsp;</label>
				<textarea id="ccatatan" name="catatan" rows="5" cols="97"><?= $rows->app_notes; ?></textarea>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" class="submit" value="Submit"/>
				<input type="hidden" name="id" value="<?= $_REQUEST[id]; ?>">
				<input type="hidden" name="niknya" value="<?= $nik; ?>">
				<input type="hidden" name="member" value="<?= $membernya; ?>">
				<input type="hidden" name="inv_bid" value="<?= $inv_bid; ?>">
				<input type="hidden" name="sw" value="22">
			</p>
		</fieldset>
	</form>
