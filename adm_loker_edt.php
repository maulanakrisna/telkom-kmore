<?
$q = "SELECT * FROM loker WHERE id_loker='$_REQUEST[idl]'";
#echo "$q<br>";
$result = mysql_query($q);
$rows = mysql_fetch_object($result);
?>

<br>
	<form name="myform" class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Edit Loker RDC</h2>
			<p>
				<label for="cidl">ID Loker:</label>
				<input type="text" id="cidl" name="id_loker" size="3" readonly value="<?= $rows->id_loker; ?>"/>
			</p>
			<p>
				<label for="cnml">Nama Loker:&nbsp;*</label>
				<input type="text" id="cnml" name="nm_loker" size="100" maxlength="100" class="required" value="<?= $rows->nm_loker; ?>"/>
			</p>
			<p>
				<label for="cacr">Acronym:&nbsp;*</label>
				<input type="text" id="cacr" name="acronym" size="4" maxlength="4" value="<?= $rows->acronym; ?>"/>
			</p>
			<p>
				<label for="cid_top">ID Induk:</label>
				<input type="text" id="cid_top" name="id_top" size="3" readonly value="<?= $rows->id_top; ?>"/>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="submit" value="Submit"/>&nbsp;
				<input type="hidden" name="idl" value="<?= $_REQUEST[idl]; ?>">
				<input type="hidden" name="sw" value="64">
			</p>
