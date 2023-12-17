				<?
				/*
				$tgl_nya = date("d", strtotime($rows->t_mulai));
				$bln_nya = date("m", strtotime($rows->t_mulai));
				$thn_nya = date("Y", strtotime($rows->t_mulai));
				$j_mulai = date("h", strtotime($rows->t_mulai));
				$m_mulai = date("i", strtotime($rows->t_mulai));
				$j_akhir = date("h", strtotime($rows->t_akhir));
				$m_akhir = date("i", strtotime($rows->t_akhir));
				*/

				# 0         1
				# 0123456789012345678
				# yyyy-mm-dd hh:mm:ss

				$tgl_nya = substr($rows->t_mulai,8,2);
				$bln_nya = substr($rows->t_mulai,5,2);
				$thn_nya = substr($rows->t_mulai,0,4);
				$j_mulai = substr($rows->t_mulai,11,2);
				$m_mulai = substr($rows->t_mulai,14,2);
				$j_akhir = substr($rows->t_akhir,11,2);
				$m_akhir = substr($rows->t_akhir,14,2);
				?>
				<label for="ctanggal">Tanggal:</label>
				<select name="tanggal">
				<option value="01" <? if ($tgl_nya == "01") echo "selected"; ?>>1</option>
				<option value="02" <? if ($tgl_nya == "02") echo "selected"; ?>>2</option>
				<option value="03" <? if ($tgl_nya == "03") echo "selected"; ?>>3</option>
				<option value="04" <? if ($tgl_nya == "04") echo "selected"; ?>>4</option>
				<option value="05" <? if ($tgl_nya == "05") echo "selected"; ?>>5</option>
				<option value="06" <? if ($tgl_nya == "06") echo "selected"; ?>>6</option>
				<option value="07" <? if ($tgl_nya == "07") echo "selected"; ?>>7</option>
				<option value="08" <? if ($tgl_nya == "08") echo "selected"; ?>>8</option>
				<option value="09" <? if ($tgl_nya == "09") echo "selected"; ?>>9</option>
				<option value="10" <? if ($tgl_nya == "10") echo "selected"; ?>>10</option>
				<option value="11" <? if ($tgl_nya == "11") echo "selected"; ?>>11</option>
				<option value="12" <? if ($tgl_nya == "12") echo "selected"; ?>>12</option>
				<option value="13" <? if ($tgl_nya == "13") echo "selected"; ?>>13</option>
				<option value="14" <? if ($tgl_nya == "14") echo "selected"; ?>>14</option>
				<option value="15" <? if ($tgl_nya == "15") echo "selected"; ?>>15</option>
				<option value="16" <? if ($tgl_nya == "16") echo "selected"; ?>>16</option>
				<option value="17" <? if ($tgl_nya == "17") echo "selected"; ?>>17</option>
				<option value="18" <? if ($tgl_nya == "18") echo "selected"; ?>>18</option>
				<option value="19" <? if ($tgl_nya == "19") echo "selected"; ?>>19</option>
				<option value="20" <? if ($tgl_nya == "20") echo "selected"; ?>>20</option>
				<option value="21" <? if ($tgl_nya == "21") echo "selected"; ?>>21</option>
				<option value="22" <? if ($tgl_nya == "22") echo "selected"; ?>>22</option>
				<option value="23" <? if ($tgl_nya == "23") echo "selected"; ?>>23</option>
				<option value="24" <? if ($tgl_nya == "24") echo "selected"; ?>>24</option>
				<option value="25" <? if ($tgl_nya == "25") echo "selected"; ?>>25</option>
				<option value="26" <? if ($tgl_nya == "26") echo "selected"; ?>>26</option>
				<option value="27" <? if ($tgl_nya == "27") echo "selected"; ?>>27</option>
				<option value="28" <? if ($tgl_nya == "28") echo "selected"; ?>>28</option>
				<option value="29" <? if ($tgl_nya == "29") echo "selected"; ?>>29</option>
				<option value="20" <? if ($tgl_nya == "30") echo "selected"; ?>>30</option>
				<option value="31" <? if ($tgl_nya == "31") echo "selected"; ?>>31</option>
				</select>
				<select name="bulan">
				<option value="01" <? if ($bln_nya == "01") echo "selected"; ?>>Januari</option>
				<option value="02" <? if ($bln_nya == "02") echo "selected"; ?>>Februari</option>
				<option value="03" <? if ($bln_nya == "03") echo "selected"; ?>>Maret</option>
				<option value="04" <? if ($bln_nya == "04") echo "selected"; ?>>April</option>
				<option value="05" <? if ($bln_nya == "05") echo "selected"; ?>>Mei</option>
				<option value="06" <? if ($bln_nya == "06") echo "selected"; ?>>Juni</option>
				<option value="07" <? if ($bln_nya == "07") echo "selected"; ?>>Juli</option>
				<option value="08" <? if ($bln_nya == "08") echo "selected"; ?>>Agustus</option>
				<option value="09" <? if ($bln_nya == "09") echo "selected"; ?>>September</option>
				<option value="10" <? if ($bln_nya == "10") echo "selected"; ?>>Oktober</option>
				<option value="11" <? if ($bln_nya == "11") echo "selected"; ?>>Nopember</option>
				<option value="12" <? if ($bln_nya == "12") echo "selected"; ?>>Desember</option>
				</select>
				<select name="tahun">
				<!--
				<option value="2007" <? if ($thn_nya == "2007") echo "selected"; ?>>2007</option>
				<option value="2008" <? if ($thn_nya == "2008") echo "selected"; ?>>2008</option>
				-->
				<option value="2009" <? if ($thn_nya == "2009") echo "selected"; ?>>2009</option>
				<option value="2010" <? if ($thn_nya == "2010") echo "selected"; ?>>2010</option>
				<option value="2011" <? if ($thn_nya == "2011") echo "selected"; ?>>2011</option>
				<option value="2012" <? if ($thn_nya == "2012") echo "selected"; ?>>2012</option>
				<option value="2013" <? if ($thn_nya == "2013") echo "selected"; ?>>2013</option>
				<option value="2014" <? if ($thn_nya == "2014") echo "selected"; ?>>2014</option>
				<option value="2015" <? if ($thn_nya == "2015") echo "selected"; ?>>2015</option>
				<option value="2016" <? if ($thn_nya == "2016") echo "selected"; ?>>2016</option>
				<option value="2017" <? if ($thn_nya == "2017") echo "selected"; ?>>2017</option>
				<option value="2018" <? if ($thn_nya == "2018") echo "selected"; ?>>2018</option>
				<option value="2019" <? if ($thn_nya == "2019") echo "selected"; ?>>2019</option>
				<option value="2020" <? if ($thn_nya == "2020") echo "selected"; ?>>2020</option>
				</select>
			</p>
			<p>
				<label for="cjmulai">Jam Mulai:</label>
				<select name="jmulai"d>
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
				</select>&nbsp;:
				<select name="mmulai">
				<option value="00" <? if ($m_mulai == "00") echo "selected"; ?>>00</option>
				<option value="30" <? if ($m_mulai == "30") echo "selected"; ?>>30</option>
				</select>
			</p>
			<p>
				<label for="cjusai">Jam Selesai:</label>
				<select name="jusai">
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
				<select name="musai">
				<option value="00" <? if ($m_akhir == "00") echo "selected"; ?>>00</option>
				<option value="30" <? if ($m_akhir == "30") echo "selected"; ?>>30</option>
				</select>
