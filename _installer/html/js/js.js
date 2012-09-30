function check()
{
	var chbox_agb = document.getElementById('agb_checkbox');
	var button1 = document.getElementById('AGBSubmitAB');
	var button2 = document.getElementById('AGBSubmit');
	
	if( chbox_agb.checked == false )
		button1.disabled = false;
	else
		button1.disabled = "disabled";	
	
	if( chbox_agb.checked == true )
		button2.disabled = false;
	else
		button2.disabled = "disabled";
}