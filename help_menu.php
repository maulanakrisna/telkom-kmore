<?
$judul = "Menu";
echo "<h3>$judul</h3>";
?>
<br><b>Home:</b>
<ul class="report">
<li><u>Schedule</u>: untuk melihat jadual sharing
<li><u>Invitaion</u>: untuk melihat undangan sharing
<li><u>Request to Attend</u>: untuk melihat status 'Request to Attend' suatu sesi sharing
<li><u>Archives</u>: untuk melihat sharing yang telah dilakukan
</ul>
<br><b>Sharing:</b>
<ul class="report">
<li><u>My Sharing Knowledge</u>: untuk melihat status sharing yang diajukan
<li><u>Create Request Sharing</u>: untuk membuat sharing baru
<li><u>Close Sharing Task</u>: untuk melihat status sharing
<li><u>My Sharing History</u>: untuk melihat sharing yang pernah diajukan
</ul>
<br><b>Report:</b>
<ul class="report">
<li><u>My Points</u>: untuk melihat point user
<li><u>Sharing Point Individu</u>: untuk melihat point seluruh warga RDC
<li><u>Sharing Point Bidang</u>: untuk melihat point seluruh bidang RDC
<li><u>Knowledge Map</u>: untuk melihat statistik sharing knowledge berdasarkan Knowledge Map
</ul>

<? if ($_SESSION["id_profile"]<3) { ?>
<br><b>Management:</b> [Muncul jika user terdaftar sebagai Committe atau Administrator]
<ul class="report">
<li><u>New Sharing Requests</u>: untuk melihat Sharing Request yang masuk
<li><u>Request To Attend</u>: untuk melihat Request to Attend yang masuk
<li><u>Close Sharing Tasks</u>: untuk melihat status Sharing Knowledge
<li><u>Sharing Knowledge</u>: untuk menambah data Sharing Knowledge lama
</ul>
<? } ?>

<? if ($_SESSION["id_profile"]==1) { ?>
<br><b>Admin:</b> [Hanya muncul jika user terdaftar sebagai Administrator]
<ul class="report">
<li><u>Data User</u>: Pengelolaan User sebagai Committee atau Administrator
<li><u>Loker</u>: Pengelolaan Data Lokasi Kerja
<li><u>Bidang</u>: Pengelolaan Data Bidang
<li><u>Knowledge Map</u>: Pengelolaan Data Knowledge Map
</ul>
<? } ?>

<br><b>Sitemap:</b> Denah situs KMORE
<br>
<br><b>Help:</b> Segala sesuatu tentang KMORE dan cara penggunaannya
