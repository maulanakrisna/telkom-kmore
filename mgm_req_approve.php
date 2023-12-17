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
	$sQuery = "SELECT a.*, b.nama AS submitter, c.nama AS speaker FROM knowledge a JOIN user b ON a.nik=b.nik JOIN user c ON a.nik=c.nik WHERE a.id_know='$_REQUEST[id]'";
	#echo "$sQuery<br>";
	query_sql($sQuery,$result);
	$rows = mysql_fetch_object ($result);
	$nik = $rows->nik;
?>
	<br>
	<form class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Form Request Sharing - Review</h2>
			<!-- <legend>&nbsp;<strong>Isilah form ini dengan data yang benar</strong>&nbsp;</legend> -->
			<p>
				<label for="cjudul">Judul/Tema:</label>
				<input type="text" id="cjudul" name="judul" size="100" maxlength="200" value="<?= $rows->judul; ?>"/>
			</p>
			<p>
				<label for="cknowmap">Kategori Knowledge:</label>
				<select name="id_map">
				<?
				   $tSQL = "SELECT * FROM knowledge_map"; 
				   $result = mysql_query($tSQL);
				   while ($listrow = mysql_fetch_array($result)) {
				?>
				<option value="<?= $listrow['id_map']; ?>" <? if ($listrow['id_map']==$rows->id_map) echo "selected"; ?>><? echo $listrow["id_map"]."&nbsp;-&nbsp;".$listrow["nm_map"]; ?></option>
				<?
				   }
				?>
				</select>
			</p>
			<p>
				<label for="cjenis">Jenis Materi:</label>
				<input type="text" id="cjenis" name="jenis" size="50" value="<?= $rows->jenis; ?>"/>
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
				$members = explode(",",$rows->member);
				$members = "'".implode("','",$members)."'";
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
				<? include ("select_time.php") ?>
			</p>
			<p>
				<label for="cruang">Lokasi Ruangan:</label>
				<input type="text" id="cruang" name="lokasi" size="100" value="<?= $rows->lokasi; ?>"/>
			</p>
			<p>
				<label for="cukerja">Unit Kerja:</label>
				<input type="text" id="cukerja" name="unitkerja" size="100" value="<?= $rows->unitkerja; ?>" readonly class="readonly"/>
			</p>
			<?
			$lokers = explode(",",$rows->inv_bidang);
			?>
			<p>
				<?
				   $tSQL = "SELECT * FROM loker WHERE id_top='100'"; 
				   $result = mysql_query($tSQL);
				   $num = mysql_num_rows($result);
				   $cur = 1;
				   $for_invite = array();
				   while ($num >= $cur) {
					   $listrow = mysql_fetch_array($result);
					   $for_invite[] = $listrow['id_loker'];
				?>
				<? if ($cur == 1) { ?>
				<label for="ctaudien">Target Audience:</label>
				<? } else { ?>
				<label>&nbsp;</label>
				<? } ?>
				<input type="checkbox" id="cbidang[]" name="bidang[]" value="<?= $listrow['id_loker']; ?>"<? if (in_array($listrow['id_loker'],$lokers)) echo "checked"; ?>/>&nbsp;<? echo $listrow['nm_loker']; ?><br>
				<?
					   $cur++;
				   }
				?>
			</p>
			<p>
				<?
				$sQuery = "SELECT * FROM external_audience WHERE id_know='$_REQUEST[id]'";
				$result = mysql_query($sQuery);
				$num = mysql_num_rows($result);
				$r = mysql_fetch_array($result);
				if ($num==0)
					$exaudien = "";
				else
					$exaudien = $r['email'];
				?>
				<label for="cjenis">External Audience:</label>
				<textarea id="cexaudien" name="exaudien" rows="5" cols="97" readonly class="readonly"><?= $exaudien; ?></textarea><br>
				<label></label>&nbsp;Email address, dipisahkan tanda koma (contoh: abc@one.com, def@two.net)
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
			<p>
				<label for="approval">Approve:</label>
				<select name="approval">
				<option value="Open">Yes</option>
				<option value="Reject">No</option>
				</select>
			</p>
			<p>
				<label for="creferen">Catatan:</label>
				<textarea id="ccatatan" name="atatan" rows="5" cols="97"><?= $rows->app_notes; ?></textarea>
			</p>
			<? if ($SESSION['nik_login']<>$rows->nik) { ?>
			<p>
				<label>&nbsp;</label><input type="submit" class="submit" value="Submit"/>
				<input type="hidden" name="id" value="<?= $_REQUEST[id]; ?>">
				<input type="hidden" name="niknya" value="<?= $nik; ?>">
				<input type="hidden" name="sw" value="21">
			</p>
			<? } ?>
		</fieldset>
	</form>
