<?
$q = "SELECT * FROM bidang WHERE id_bidang='$_REQUEST[idb]'";
#echo "$q<br>";
$result = mysql_query($q);
$rows = mysql_fetch_object($result);
?>

<br>
	<form name="myform" class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Edit Bidang RDC</h2>
			<p>
				<label for="cidb">ID Bidang:</label>
				<input type="text" id="cidb" name="id_bidang" size="6" maxlength="6" readonly value="<?= $rows->id_bidang; ?>"/>
			</p>
			<p>
				<label for="cnmb">Nama Bidang:&nbsp;*</label>
				<input type="text" id="cnmb" name="nm_bidang" size="100" maxlength="100" class="required" value="<?= $rows->nm_bidang; ?>"/>
			</p>
			<p>
				<label for="cacr">Acronym:&nbsp;*</label>
				<input type="text" id="cacr" name="singkatan" size="4" maxlength="4" value="<?= $rows->singkatan; ?>"/>
			</p>
			<p>
				<label for="cemail">E-mail:&nbsp;*</label>
				<input type="text" id="cemail" name="email" size="100" maxlength="100" class="required email" value="<?= $rows->email; ?>"/>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="submit" value="Submit"/>&nbsp;
				<input type="hidden" name="idb" value="<?= $_REQUEST[idb]; ?>">
				<input type="hidden" name="sw" value="66">
			</p>
