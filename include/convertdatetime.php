<?
# Convert Date & Time
function ConvertDate($sql_date) {
	$date=strtotime($sql_date);
	$final_date=date("d-m-Y, h:i:s", $date);
return $final_date;
}

function ConvertJustDate($sql_date) {
	$date=strtotime($sql_date);
	$final_date=date("d-m-Y", $date);
return $final_date;
}
function ConvertTime($sql_date) {
	$date=strtotime($sql_date);
	$final_time=date("h:i", $date);
return $final_time;
}

function dateDiff($interval,$dateTimeBegin,$dateTimeEnd) {
//Parse about any English textual datetime
//$dateTimeBegin, $dateTimeEnd

	$dateTimeBegin=strtotime($dateTimeBegin);
	if($dateTimeBegin === -1) {
		return("..begin date Invalid");
	}

	$dateTimeEnd=strtotime($dateTimeEnd);
	if($dateTimeEnd === -1) {
		return("..end date Invalid");
	}

	$dif=$dateTimeEnd - $dateTimeBegin;

	switch($interval) {
		case "s"://seconds
			return($dif);

		case "n"://minutes
			return(floor($dif/60)); //60s=1m

		case "h"://hours
			return(floor($dif/3600)); //3600s=1h

		case "d"://days
			return(floor($dif/86400)); //86400s=1d

		case "ww"://Week
			return(floor($dif/604800)); //604800s=1week=1semana

		case "m": //similar result "m" dateDiff Microsoft
			$monthBegin=(date("Y",$dateTimeBegin)*12)+
			date("n",$dateTimeBegin);
			$monthEnd=(date("Y",$dateTimeEnd)*12)+
			date("n",$dateTimeEnd);
			$monthDiff=$monthEnd-$monthBegin;
			return($monthDiff);

		case "yyyy": //similar result "yyyy" dateDiff Microsoft
			return(date("Y",$dateTimeEnd) - date("Y",$dateTimeBegin));

		default:
			return(floor($dif/86400)); //86400s=1d
	}
}

function tampilkan_waktunya($ambil_tanggal)
{
	/* kumpulan array utk date time */

	$h = date("D",strtotime($ambil_tanggal));
	$b = date("m",strtotime($ambil_tanggal));
	$arr_hari = array('Sun'=>'Minggu','Mon'=>'Senin','Tue'=>'Selasa','Wed'=>'Rabu','Thu'=>'Kamis','Fri'=>'Jumat','Sat'=>'Sabtu');
	$arr_bulan = array('01'=>'Januari', '02'=>'Pebruari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'Nopember', '12'=>'Desember');

	#$ambil_tanggal = getdate();
	$hari = $arr_hari[$h];
	$tanggal = date("d",strtotime($ambil_tanggal));
	$bulan = $arr_bulan[$b];
	$tahun = date("Y",strtotime($ambil_tanggal));

	echo ($hari.", ".$tanggal." ".$bulan." ".$tahun);
}
?>