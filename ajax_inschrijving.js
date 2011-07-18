// Get the HTTP Object
	function getHTTPObject(){
	  	if (window.ActiveXObject) 
			return new ActiveXObject("Microsoft.XMLHTTP");
		else if (window.XMLHttpRequest) 
			return new XMLHttpRequest();
		else {
			alert("Uw browser ondersteunt geen AJAX.");
			return null;
		}
	}
	
	function ChangeInfo(){
		httpObject = getHTTPObject();
		if (httpObject != null) {
			httpObject.open("GET", "show_availability.php?change=1&id="+document.getElementById("id").value+"&date="+document.getElementById("date").value+"&start_time_hrs="+document.getElementById("start_time_hrs").value+"&start_time_mins="+document.getElementById("start_time_mins").value+"&end_time_hrs="+document.getElementById("end_time_hrs").value+"&end_time_mins="+document.getElementById("end_time_mins").value+"&boat_id="+document.getElementById("boat_id").value, true);
			httpObject.onreadystatechange = setOutput;
			httpObject.send(null);
		}
	}
	
	function setOutput(){
		if (httpObject.readyState == 4 && httpObject.status == 200) {
			var availability = document.getElementById("AvailabilityInfo");
			availability.innerHTML = httpObject.responseText;
		}
	}