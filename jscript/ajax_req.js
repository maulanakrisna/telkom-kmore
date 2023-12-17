/*
http://www.phpexpertsforum.com/ajax-pagination-t540.html
This is the sample code for pagination using with ajax.

I used 3 files for this script.

1. index.php
2. paging2.php
3. ajax_req.js 

ajax_req.js
And finally include this js file.
*/
function GetXmlHttpObject(handler)
{
	var objXMLHttp=null
	if (window.XMLHttpRequest)
	{
		objXMLHttp=new XMLHttpRequest()
	}
	else if (window.ActiveXObject)
	{
		objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
	}
	return objXMLHttp
}

function stateChanged()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		document.getElementById("txtResult").innerHTML=xmlHttp.responseText
	}
	else
	{
		//alert(xmlHttp.status);
	}
}

function htmlData(url, qStr)
{
	if (url.length==0)
	{
		document.getElementById("txtResult").innerHTML="";
		return;
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request");
		return;
	}

	url=url+"?"+qStr;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true) ;
	xmlHttp.send(null);
}