<?
/*
-----
file asal  : sharing_req_his.php
file tujuan: save2db.php?sw=12
-----
*/
	$sQuery = "SELECT * FROM knowledge_map WHERE id_map='$_REQUEST[id]'";
	#echo "$sQuery<br>";
	query_sql($sQuery,$result);
	$rows = mysql_fetch_object ($result);
?>
	<br>
	<form name="myform" class="cmxform" id="commentForm" action="save2db.php" method="post">
		<fieldset>
			<h2>Edit Knowledge Map</h2>
			<!-- <legend>&nbsp;<strong>Isilah form ini dengan data yang benar</strong>&nbsp;</legend> -->
			<p>
				<label for="ckmap">Knowledge Map:&nbsp;*</label>
				<input type="text" id="ckmap" name="kmap" size="50" maxlength="50" class="required" value="<?= $rows->nm_map; ?>"/>
			</p>
			<p>
				<label for="cexpert">Expert:</label>
				<input type="text" id="cexpert" name="expert" size="50" maxlength="50" value="<?= $rows->expert; ?>"/>
			</p>
			<p>
				<label for="clevel">Level:</label>
				<input type="text" id="clevel" name="level" size="1" readonly value="<?= $rows->level; ?>"/>
			</p>
			<p>
				<label for="cid_top">ID Top:</label>
				<input type="text" id="cid_top" name="id_top" size="3" readonly value="<?= $rows->id_top; ?>"/>
			</p>
			<p>
				<label>&nbsp;</label><input type="submit" name="btnSubmit" class="submit" value="Submit"/>
				<input type="hidden" name="id" value="<?= $_REQUEST[id]; ?>">
				<input type="hidden" name="sw" value="42">
			</p>
		</fieldset>
	</form>
