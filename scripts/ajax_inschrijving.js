function getHTTPObjectIns(){
	if (window.ActiveXObject) 
		return new ActiveXObject("Microsoft.XMLHTTP");
	else if (window.XMLHttpRequest) 
		return new XMLHttpRequest();
	else {
		alert("Uw browser ondersteunt geen AJAX.");
		return null;
	}
}

function changeInfoIns() {
	httpObject = getHTTPObjectIns();
	if (httpObject != null) {
		httpObject.open("GET", "show_availability.php?change=1&id=" + document.getElementById("id").value + "&date=" + 
			document.getElementById("date").value + "&start_time_hrs=" + document.getElementById("start_time_hrs").value + 
			"&start_time_mins=" + document.getElementById("start_time_mins").value + "&end_time_hrs=" + 
			document.getElementById("end_time_hrs").value + "&end_time_mins=" + document.getElementById("end_time_mins").value + 
			"&boat_id=" + document.getElementById("boat_id").value, true);
		httpObject.onreadystatechange = setOutputIns;
		httpObject.send(null);
	}
}

function setOutputIns() {
	if (httpObject.readyState == 4 && httpObject.status == 200) {
		var availability = document.getElementById("AvailabilityInfo");
		availability.innerHTML = httpObject.responseText;
	}
}

function makeRes(id, again, start_time, cat_to_show, grade_to_show) {
	httpObject = getHTTPObjectIns();
	if (httpObject != null) {
		var boat_id = document.getElementById("boat_id").value;
		var ergo_lo = 0;
		if (document.getElementById("ergo_lo") != null) ergo_lo = document.getElementById("ergo_lo").value;
		var ergo_hi = 0;
		if (document.getElementById("ergo_hi") != null) ergo_hi = document.getElementById("ergo_hi").value;
		var pname = '';
		if (document.getElementById("pname") != null) pname = document.getElementById("pname").value;
		var name = '';
		if (document.getElementById("name") != null) name = document.getElementById("name").value;
		var email = '';
		if (document.getElementById("email") != null) email = document.getElementById("email").value;
		var mpb = '';
		if (document.getElementById("mpb") != null) mpb = document.getElementById("mpb").value;
		var date = '';
		if (document.getElementById("date") != null) date = document.getElementById("date").value;
		var start_time_hrs = document.getElementById("start_time_hrs").value;
		var start_time_mins = document.getElementById("start_time_mins").value;
		var end_time_hrs = document.getElementById("end_time_hrs").value;
		var end_time_mins = document.getElementById("end_time_mins").value;
		alert("aanroep: " + "check_reservation.php?make=1&id=" + id + "&again=" + again + "&boat_id=" + boat_id +
			"&pname=" + pname + "&name=" + name + "&email=" + email + "&mpb=" + mpb + "&date=" + date + 
			"&start_time_hrs=" + start_time_hrs + "&start_time_mins=" + start_time_mins +
			"&end_time_hrs=" + end_time_hrs + "&end_time_mins=" + end_time_mins + "&ergo_lo=" + ergo_lo + "&ergo_hi=" + ergo_hi +
			"&start_time=" + start_time + "&cat_to_show=" + cat_to_show + "&grade_to_show=" + grade_to_show);
		httpObject.open("GET", "check_reservation.php?make=1&id=" + id + "&again=" + again + "&boat_id=" + boat_id +
			"&pname=" + pname + "&name=" + name + "&email=" + email + "&mpb=" + mpb + "&date=" + date + 
			"&start_time_hrs=" + start_time_hrs + "&start_time_mins=" + start_time_mins +
			"&end_time_hrs=" + end_time_hrs + "&end_time_mins=" + end_time_mins + "&ergo_lo=" + ergo_lo + "&ergo_hi=" + ergo_hi +
			"&start_time=" + start_time + "&cat_to_show=" + cat_to_show + "&grade_to_show=" + grade_to_show, true);
		httpObject.onreadystatechange = resetReservationPopup;
		httpObject.send(null);
	}
}

function delRes(id, start_time, cat_to_show, grade_to_show) {
	httpObject = getHTTPObjectIns();
	if (httpObject != null) {
		var date = document.getElementById("date").value;
		httpObject.open("GET", "check_reservation.php?del=1&id=" + id + "&date=" + date + "&start_time=" + start_time +
			"&cat_to_show=" + cat_to_show + "&grade_to_show=" + grade_to_show, true);
		httpObject.onreadystatechange = resetReservationPopup;
		httpObject.send(null);
	}
}

function resetReservationPopup(){
	if (httpObject.readyState == 4 && httpObject.status == 200) {
		var resPopup = document.getElementById("inschrijving");
		// TODO: parse responseText
		resPopup.innerHTML = httpObject.responseText;
	}
}