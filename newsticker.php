				<ul id="ticker01">
					<?
					require_once ("include/dbcon.php");
					#$query = "SELECT a.*, b.nama FROM knowledge a JOIN user b ON a.nik=b.nik WHERE req_status = 'Open' AND a.t_mulai > now() ORDER BY t_mulai LIMIT 0, 2";
					$query = "SELECT a.*, b.nama FROM knowledge a JOIN user b ON a.nik=b.nik WHERE sharing_status = '3' AND a.t_mulai > now() ORDER BY t_mulai LIMIT 0, 3";
					$result = mysql_query($query);
					$num = mysql_num_rows($result);

					# print table rows
					if ($num > 0)
					{
						echo "<li>Next...&nbsp;&nbsp;&nbsp;";
						while ($row = mysql_fetch_array($result)) {
					?>
						<li><span><a href="sharing_detail.php?idk=<?= $row["id_know"]; ?>&mn=1&height=400&width=700" title="Sharing Knowledge Detail" class="thickbox"><?= $row[judul]." (".$row[nama].")"; ?> on <?= ConvertJustDate($row["t_mulai"])."&nbsp;from&nbsp;".substr($row["t_mulai"],11,5); ?>&nbsp;until&nbsp;<?= substr($row["t_akhir"],11,5); ?></a></span></li>
					<?
						}
					}
					else
					{
						$tmulai = date("Y-m-d", mktime(0, 0, 0, 1, 1, date("y")));
						$takhir = date("Y-m-d");
						$query  = "SELECT a.nik AS niknya, b.nama, c.nm_loker, ".
								  "SUM(IF(tipe=1,poin,IF(tipe=2,poin,0))) bidang, ".
								  "SUM(IF(tipe=3,poin,0)) rdc, SUM(IF(tipe=4,poin,0)) ext, ".
								  "SUM(IF(tipe=5,poin,0)) part, SUM(poin) total ".
								  "FROM sharing_activity a JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_loker=c.id_loker ".
								  "WHERE a.nik <> '602217' AND poin IS NOT NULL AND a.id_know IN (";
						$query .= "SELECT id_know FROM knowledge WHERE DATE(t_mulai) BETWEEN DATE('$tmulai') AND DATE('$takhir') ";
						$query .= "ORDER BY t_mulai) GROUP BY a.nik ORDER BY total DESC LIMIT 0, 3";
						$result = mysql_query($query);
						$num = mysql_num_rows($result);
						if ($num == 0)
						{
							echo "<li><span>&nbsp;</span>Sorry, no sharing knowledge schedule at this time...!</li>";
						}
						else
						{
							$top = "Top 3:&nbsp;&nbsp;&nbsp;";
							$i = 1;
							while ($row = mysql_fetch_array($result))
							{
							?>
							<li><span><? if ($i==1) echo $top; echo "$i. $row[nama] ($row[niknya]) $row[total]&nbsp;&nbsp;&nbsp;"; ?></span></li>
							<?
								$i++;
							}
						}
					}
					?>
				</ul>
