<?php
    //We've included  ../Includes/DBConn.php, which contains functions
    //to help us easily connect to a database.
    include("includes/dbcon2.php");
    $link = connectToDB();
	echo "Hello World<br>";

    //Generate the graph element
    $strXML = "<graph caption='Factory Output report' subCaption='By Quantity' decimalPrecision='0' showNames='1' numberSuffix=' Units' decimalPrecision='0' pieSliceDepth='30' >";
	
	$strQuery  = "SELECT c.id_loker, c.nm_loker, SUM(a.poin) AS nilai FROM sharing_activity a ";
	$strQuery .= "JOIN user b ON a.nik=b.nik JOIN loker c ON b.id_bidang=c.id_loker WHERE id_bidang<>100 ";
	$strQuery .= "GROUP BY c.nm_loker ORDER BY nilai DESC, id_inv_status";
	echo "$strQuery<br>";
	query_sql($strQuery, $result);

	while($ors = mysql_fetch_array($result)) {
		//Now create a second query to get details for this factory
		//Generate <set name='..' value='..'/>     
		$strXML .= "<set name='" . $ors['nm_loker'] . "' value='" . $ors['nilai'] . "' />";
		echo "$ors['nm_loker'] - $ors['nilai']<br>";
	}
    mysql_close($link);

    //Finally, close <graph> element
    $strXML .= "</graph>";
		
    //Set Proper output content-type
    header('Content-type: text/xml');
	
    //Just write out the XML data
    //NOTE THAT THIS PAGE DOESN'T CONTAIN ANY HTML TAG, WHATSOEVER
    echo $strXML;
?>
