		<div id="show"><b><?= $_SESSION['nama']; ?>&nbsp;-&nbsp;<?= $_SESSION['nm_profile']; ?></b>
	</div>

	<div id="nav">&nbsp;&nbsp;&nbsp;
	<ul id="minitabs"> 
	<?
	$linksession = "id=".$_SESSION['sid'];
	# Home
	echo '<li><a href="home.php?'.$linksession.'"';
	if ($_SESSION['page']==1) echo ' id="current"';
	echo '><img border = 0 src = images/home.gif />Home</a></li>';

	# Sharing
	echo '<li><a href="sharing.php?'.$linksession.'"';
	if ($_SESSION['page']==2) echo ' id="current"';
	echo '><img border = 0 src = images/sharing.gif />K-Sharing</a></li>';

	# Report
	echo '<li><a href="report.php?'.$linksession.'"';
	if ($_SESSION['page']==3) echo ' id="current"';
	echo '><img border = 0 src = images/report.gif />Report</a></li>';

	if ($_SESSION['id_profile']<3)
	{
	# Management
		echo '<li><a href="management.php?'.$linksession.'"';
		if ($_SESSION['page']==4) echo ' id="current"';
		echo '><img border = 0 src = images/management.gif />Management</a></li>';
	}
	if ($_SESSION['id_profile']==1)
	{
	# Admin
		echo '<li><a href="admin.php?'.$linksession.'"';
		if ($_SESSION['page']==5) echo ' id="current"';
		echo '><img border = 0 src = images/admin.gif />Admin</a></li>';
	}

	# Sitemap
	echo '<li><a href="sitemap.php?'.$linksession.'"';
	if ($_SESSION['page']==6) echo ' id="current"';
	echo '><img border = 0 src = images/sitemap.gif />Sitemap</a></li>';

	# Help
	echo '<li><a href="help.php?'.$linksession.'"';
	if ($_SESSION['page']==7) echo ' id="current"';
	echo '><img border = 0 src = images/help.gif />Help</a></li>';

	# Logout
	echo '<li><a href="logout.php"><img border = 0 src = images/logout.gif />Logout</a></li>';
	?>
	</ul> 
	</div>

	

	<!-- <div id="line"></div> -->
