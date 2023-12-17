<?php

// Step 1
$judul = "Request to Attend History";
require_once ("include/dbcon.php");
$recordsPerPage = 15;
$pageNum = $_REQUEST['p'];
if (empty($pageNum))
{
	$offset = 0;
	$pageNum = 1;
}
else
{
	$offset = ($pageNum-1) * $recordsPerPage;
}

// Step 2
$query  = "SELECT a.*, b.judul, b.t_mulai, b.t_akhir, c.nama, d.acronym, e.nm_confirm FROM sharing_activity a ";
$query .= "JOIN knowledge b ON a.id_know=b.id_know JOIN user c ON b.nik=c.nik JOIN loker d ON c.id_bidang=d.id_loker ";
$query .= "JOIN confirm e ON a.id_confirm=e.id_confirm ";
$query .= "WHERE a.id_inv_status = '4' AND a.id_confirm <> '5' AND a.nik = '$_SESSION[nik_login]'";

if (!empty($_REQUEST['searchby']))
	$query .= " AND ".$_REQUEST['searchby']." LIKE '%".$_REQUEST[keyword]."%'";

if (!empty($_REQUEST['sort']))
	$query .= " ORDER BY $_REQUEST[sort] $_REQUEST[by]";
else
	$query .= " ORDER BY b.t_akhir DESC";

#echo "$query<br>";
$result = mysql_query($query." LIMIT $offset, $recordsPerPage") or die('Mysql Err. 1');
$num = mysql_num_rows($result);
echo "<h3>$judul</h3>";

# search table
echo "<table class='spacer' width='750' border='0'><tr><td>";
?>
	<form name="find" action="<?= $_SERVER["PHP_SELF"]?>?mn=13" method="post">
	<select name="searchby" onChange="getState(this.value)">
	<option value="">- Choose -</option>
	<option value="judul" <? if ($_REQUEST[searchby]=='judul') echo " selected" ?>>Judul</option>
	<option value="nama" <? if ($_REQUEST[searchby]=='nama') echo " selected" ?>>Nama</option>
	</select>
	<input type="text" name="keyword" value="<?= $_REQUEST[keyword] ?>">
	<input type="submit" name="submit" value="Search">
	</form>
<?
echo "</td><td align='right'><a href='?mn=3'>Request to Attend</a></td></tr></table>";
echo "<center>";

# print table header
echo "<table id='myTable' class='tablesorter' border='0' cellpadding='0' cellspacing='1'>";
echo "<thead><tr><th>No.</th>";
echo "<th>Date/Time&nbsp;<a href='?mn=31&sort=t_mulai&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=31&sort=t_mulai&by=DESC'><img src='images/down.png' border='0'></a>";
echo "</th><th>Title/Theme&nbsp;<a href='?mn=31&sort=judul&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=31&sort=judul&by=DESC'><img src='images/down.png' border='0'></a></th>";
echo "<th>Contributor&nbsp;<a href='?mn=31&sort=nama&by=ASC'><img src='images/up.png' border='0'></a><a href='?mn=31&sort=nama&by=DESC'><img src='images/down.png' border='0'></a>";
echo "</th><th>Bidang</th><th>Status</th></tr></thead>";
echo "<tbody>";

if ($num <> 0)
{
	// print table rows
	$no =  $offset+1;
	while ($row = mysql_fetch_array($result))
	{
?>
	<tr valign="top">
		<td align="right" width="10px"><?= $no; ?>.</td>
		<td width="17%"><?= ConvertJustDate($row["t_mulai"]); ?><br>
			<?= substr($row["t_mulai"],11,5); ?>&nbsp;s/d&nbsp;<?= substr($row["t_akhir"],11,5); ?></td>
		<td width="39%"><A HREF="sharing_detail_view.php?idk=<?= $row["id_know"]; ?>&mn=1&height=400&width=650" title="Sharing Knowledge Detail" class="thickbox"><?= $row["judul"]; ?></A></td>
		<td width="20%"><?= $row["nama"]; ?></td>
		<td><?= $row["acronym"]; ?></td>
		<td><? if ($row["nm_confirm"]=="Accept") echo "Approved"; else echo $row["nm_confirm"]; ?></td>
	</tr>
<?
		$no++;
	}
	echo "</tbody>";
	echo "</table><p>";

	// Step 3
	$result = mysql_query($query) or die('Mysql Err. 2');
	$numrows = mysql_num_rows($result);
	$maxPage = ceil($numrows/$recordsPerPage);
	$file = '?mn=3';

	// previous page
	if ($pageNum > 1)
	{
		$previous = $pageNum-1;
		echo " <a href=\"$file&p=1'\"><< First</a> | <a href=\"$file&p=$previous\">< Previous</a> | ";
	}

	// Google Style...
	// first number
	$number = ($pageNum > 3 ? " ... " : " ");
	for ($i = $pageNum-2; $i <= $pageNum; $i++)
	{ 
		if ($i < 1)
			continue;
		if ($i == $pageNum)
		{
			$number .= "<b>$i</b> ";
		}
		else
		{
			$number .= "<a href=\"$file&p=$i\">$i</a> ";
		}
	}

	// middle number
	// command below is just in case if this page include from other file PHP
	$page = substr($page,strlen($page),-2);
	$number .= " <b>$page</b> ";
	for ($i = $pageNum+1; $i <= ($pageNum+4); $i++)
	{ 
		if ($i > $maxPage)
			break;
		$number .= "<a href=\"$file&p=$i\">$i</a> ";
	}

	// last number
	$number .= ($pageNum+2 < $maxPage ? " ... <a href=\"$file&p=$maxPage\">$maxPage</a> " : " ");

	echo $number;

	// next page
	if ($pageNum < $maxPage)
	{
		$next = $pageNum+1;
		echo "<a href=\"$file&p=$next\"> Next ></a> | <a href=\"$file&p=$maxPage\"> Last >></a> ";
	}
}
else
{		
	echo "<tr><td colspan='6' align='center'>Tidak ada data</td></tr>";
	echo "</tbody>";
	echo "</table><p>";
}
echo "</center>";
?>
