<?php
//We've included ../Includes/FusionCharts.php and ../Includes/DBConn.php, which contains
//functions to help us easily embed the charts and connect to a database.
include("FusionChartsFree/FusionCharts.php");
//include("include/dbcon.php");
?>
<HTML>
<HEAD>
<TITLE>KMORE - Grafik Sharing Point Bidang</TITLE>
	<?php
	//You need to include the following JS file, if you intend to embed the chart using JavaScript.
	//Embedding using JavaScripts avoids the "Click to Activate..." issue in Internet Explorer
	//When you make your own charts, make sure that the path to this JS file is correct. Else, you would get JavaScript errors.
	?>	
	<SCRIPT LANGUAGE="Javascript" SRC="FusionChartsFree/FusionCharts.js"></SCRIPT>
	<style type="text/css">
	<!--
	body {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	.text{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	-->
	</style>
</HEAD>
<BODY>

<CENTER>
<h4>Grafik Sharing Point Unit</h4><br>

<?php
    //In this example, we show how to connect FusionCharts to a database.
    //For the sake of ease, we've used an MySQL databases containing two
    //tables.

    // Connect to the DB
//    $link = connectToDB();

    //$strXML will be used to store the entire XML document generated
    //Generate the graph element
    $strXML = "<graph caption='' xAxisName='Unit' yAxisName='Jumlah Points' showNames='1' decimalPrecision='0' formatNumberScale='0'>";

    // Fetch all factory records
	/*
	$query  = "SELECT c.id_loker, c.acronym, c.nm_loker, SUM(a.poin) AS nilai FROM sharing_activity a ";
	$query .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker ";
	$query .= "WHERE id_bidang<>100 AND id_inv_status < 3 AND poin IS NOT NULL AND a.id_know IN (";
	$query .= "SELECT id_know FROM knowledge WHERE DATE(t_mulai) BETWEEN DATE('$tmulai') AND DATE('$takhir') ORDER BY t_mulai";
	$query .= ") GROUP BY c.nm_loker";
	*/
	$query  = "SELECT c.id_loker,  c.nm_loker, SUM(a.poin) AS nilai FROM sharing_activity a ";
	$query .= "JOIN loker c ON a.id_loker=c.id_loker ";
	$query .= "WHERE  a.id_inv_status < 3 AND poin IS NOT NULL AND a.id_know IN (";
	$query .= "SELECT id_know FROM knowledge WHERE DATE(t_mulai) BETWEEN DATE('$tmulai') AND DATE('$takhir') ORDER BY t_mulai";
	$query .= ") GROUP BY c.nm_loker ORDER BY nilai DESC";
    $result = mysql_query($query) or die(mysql_error());

	$colors = array("AFD8F8","F6BD0F","008E8E","FF8E46","D64646","A186BE");
    //Iterate through each factory
	$i = 0;
	while($ors = mysql_fetch_array($result)) {
		//Now create a second query to get details for this factory
		//Note that we're setting link as Detailed.php?FactoryId=<<FactoryId>>
		$strXML .= "<set name='" . $ors['nm_loker'] . "' value='" . $ors['nilai'] . "' color='" . $colors[$i] . "'/>";
		$i++;
	}
   // mysql_close($link);


    //Finally, close <graph> element
    $strXML .= "</graph>";

    //Create the chart - Pie 3D Chart with data from strXML
    echo renderChart("FusionChartsFree/Charts/FCF_Column3D.swf", "", $strXML, "FactorySum", 650, 450, false, false);
?>
</CENTER>
</BODY>
</HTML>