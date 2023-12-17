$('document').ready(function() {
	$('#router1').change(function() {
		if ($(this).val() == 1) {
			$('#routerpil').slideDown(300);
		} else {
			$('#routerpil').slideUp(300);
		}
	});

	$('#router2').change(function() {
		if ($(this).val() == 1) {
			$('#routerpil2').slideDown(300);
		} else {
			$('#routerpil2').slideUp(300);
		}
	});
    
    //called when key is pressed in textbox
	$("#quantity1").keypress(function (e)  
	{ 
	  //if the letter is not digit then display error and don't type anything
	  if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	  {
		//display error message
		$("#errmsg1").html("Digits Only").show().fadeOut("slow"); 
	    return false;
      }	
	});

	$("#quantity2").keypress(function (e)  
	{ 
	  //if the letter is not digit then display error and don't type anything
	  if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	  {
		//display error message
		$("#errmsg2").html("Digits Only").show().fadeOut("slow"); 
	    return false;
      }	
	});

	/*
	$(el).keypress(function (e)
	{
		//if the letter is not digit then display error and don’t type anything
	  if((e.shiftKey && e.keyCode == 45) || e.which!=8 && e.which!=0 && (e.which57))
		{
		//display error message
		$(”#errmsg”).html(”Digits Only”).show().fadeOut(”slow”);
		return false;
	}
	});
	*/
});
