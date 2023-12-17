<?
/*
Nama Program: dbcon.php
Pembuat	: Lutfi
tgl. buat	: Monday, May 12, 2008 10:19:58 AM
tgl. revisi	: Friday, April 24, 2009 6:13:45 AM
Deskripsi	: Koneksi dan query ke database
Skrip asal	: -
Skrip tujuan: -
*/

function conect_db($server, $login, $pwd, $db) {
   $link = mysql_pconnect ($server,$login,$pwd) or die ("Could not connect to MySQL");
   $x= mysql_select_db ($db) or die (mysql_error());
}

function query_sql($query, &$hasil) {
   $hasil = mysql_query ($query) or die ('<font color="Red">There\'s an error on query data.<br>Please contact administrator.<br><font size=1><i>'.mysql_error().'</i></font></font>');
}

$dbhost = "localhost";
$dbname = "kmore";
$dbuser = "root";
$dbpswd = "";
conect_db($dbhost, $dbuser, $dbpswd, $dbname);
?>