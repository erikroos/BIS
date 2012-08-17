ScrollTableRelativeSize(document.getElementById("scrollTable"), 50, 270);
	
function changeDate(factor) {
	var datum = document.getElementById("date_to_show").value;
	var dateArray = datum.split('-');
	var newDatum = new Date();
	newDatum.setDate(dateArray[0]);
	dateArray[1] = eval(dateArray[1]);
	var Month = dateArray[1] - 1;
	newDatum.setMonth(Month);
	newDatum.setYear(dateArray[2]);
	newDatum.setDate(newDatum.getDate() + factor);
	var nDay = newDatum.getDate();
	nDay = String(nDay).replace(/^(\d)$/, "0$1");
	var nMonth = newDatum.getMonth()+1;
	nMonth = String(nMonth).replace(/^(\d)$/, "0$1");
	var nYear = newDatum.getYear();
	if (nYear < 1000) {
		nYear += 1900
	}
	var nDatum = nDay+"-"+nMonth+"-"+nYear;
	document.getElementById("date_to_show").value = nDatum;
}	

function resetDate(factor) {
	var newDatum = new Date();
	var nDay = newDatum.getDate();
	nDay = String(nDay).replace(/^(\d)$/, "0$1");
	var nMonth = newDatum.getMonth()+1;
	nMonth = String(nMonth).replace(/^(\d)$/, "0$1");
	var nYear = newDatum.getYear();
	if (nYear < 1000) {
		nYear += 1900
	}
	var nDatum = nDay+"-"+nMonth+"-"+nYear;
	document.getElementById("date_to_show").value = nDatum;
}

function getHTTPObject(){
	if (window.ActiveXObject) 
		return new ActiveXObject("Microsoft.XMLHTTP");
	else if (window.XMLHttpRequest) 
		return new XMLHttpRequest();
	else {
		alert("Uw browser ondersteunt geen Ajax, wat voor de werking van BIS vereist is.");
		return null;
	}
}

function changeInfo(){
	httpObject = getHTTPObject();
	if (httpObject != null) {
		if (document.getElementById('inschrijving').style.display != 'block') { // if res.screen is invisible so we are in the index
			httpObject.open("GET", "show_schedule.php?date_to_show=" + document.getElementById("date_to_show").value + 
					"&start_hrs_to_show=" + document.getElementById("start_hrs_to_show").value + 
					"&start_mins_to_show=" + document.getElementById("start_mins_to_show").value + 
					"&cat_to_show=" + document.getElementById("cat_to_show").value + 
					"&grade_to_show=" + document.getElementById("grade_to_show").value, true);
			httpObject.onreadystatechange = setOutput;
			httpObject.send(null);
		} else {
			httpObject.open("GET", "show_availability.php?change=1&id=" + document.getElementById("id").value + "&date=" + 
					document.getElementById("resdate").value + "&start_time_hrs=" + document.getElementById("start_time_hrs").value + 
					"&start_time_mins=" + document.getElementById("start_time_mins").value + "&end_time_hrs=" + 
					document.getElementById("end_time_hrs").value + "&end_time_mins=" + document.getElementById("end_time_mins").value + 
					"&boat_id=" + document.getElementById("boat_id").value, true);
			httpObject.onreadystatechange = setOutputIns;
			httpObject.send(null);
		}
	}
}

function setOutput(){
	if (httpObject.readyState == 4 && httpObject.status == 200) {
		var schedule_info = document.getElementById("ScheduleInfo");
		schedule_info.innerHTML = httpObject.responseText;
		ScrollTableRelativeSize(document.getElementById("scrollTable"), 50, 270);
	}
}

function setOutputIns() {
	if (httpObject.readyState == 4 && httpObject.status == 200) {
		var availability = document.getElementById("AvailabilityInfo");
		availability.innerHTML = httpObject.responseText;
	}
}

function showInschrijving(id, boat_id, date, cat_to_show, grade_to_show, time_to_show) {
	if (document.getElementById('inschrijving').style.display != 'block') {
		// Enable shadow overlay and pop-up:
		document.getElementById('index_overlay').style.display = 'block';
		document.getElementById('inschrijving').style.display = 'block';
		// Contents of the pop-up:
		httpObject = getHTTPObject();
		if (httpObject != null) {
			httpObject.open("GET", "inschrijving.php?id=" + id + "&boat_id=" + boat_id + 
				"&date=" + date + "&cat_to_show=" + cat_to_show + "&grade_to_show=" + grade_to_show + 
				"&time_to_show=" + time_to_show, true);
			httpObject.onreadystatechange = fillPopup;
			httpObject.send(null);
		}
	} else {
		document.getElementById('index_overlay').style.display = 'none';
		document.getElementById('inschrijving').style.display = 'none';
	}
}

function fillPopup(){
	if (httpObject.readyState == 4 && httpObject.status == 200) {
		var popup = document.getElementById("inschrijving");
		popup.innerHTML = httpObject.responseText;
	}
}

function makeRes(id, start_time, cat_to_show, grade_to_show) {
	httpObject = getHTTPObject();
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
		if (document.getElementById("resdate") != null) date = document.getElementById("resdate").value;
		var start_time_hrs = document.getElementById("start_time_hrs").value;
		var start_time_mins = document.getElementById("start_time_mins").value;
		var end_time_hrs = document.getElementById("end_time_hrs").value;
		var end_time_mins = document.getElementById("end_time_mins").value;
		httpObject.open("GET", "check_reservation.php?make=1&id=" + id + "&boat_id=" + boat_id +
			"&pname=" + pname + "&name=" + name + "&email=" + email + "&mpb=" + mpb + "&date=" + date + 
			"&start_time_hrs=" + start_time_hrs + "&start_time_mins=" + start_time_mins +
			"&end_time_hrs=" + end_time_hrs + "&end_time_mins=" + end_time_mins + "&ergo_lo=" + ergo_lo + "&ergo_hi=" + ergo_hi +
			"&start_time=" + start_time + "&cat_to_show=" + cat_to_show + "&grade_to_show=" + grade_to_show, true);
		httpObject.onreadystatechange = resetReservationPopup;
		httpObject.send(null);
	}
}

function delRes(id, start_time, cat_to_show, grade_to_show) {
	httpObject = getHTTPObject();
	if (httpObject != null) {
		var date = document.getElementById("resdate").value;
		httpObject.open("GET", "check_reservation.php?del=1&id=" + id + "&date=" + date + "&start_time=" + start_time +
			"&cat_to_show=" + cat_to_show + "&grade_to_show=" + grade_to_show, true);
		httpObject.onreadystatechange = resetReservationPopup;
		httpObject.send(null);
	}
}

function resetReservationPopup(){
	if (httpObject.readyState == 4 && httpObject.status == 200) {
		var resultArray = JSON.parse(httpObject.responseText);
		// Fill and show message bar
		var msgBar = document.getElementById("msgbar");
		var msg = "<p>";
		for (i in resultArray.messages) {
			msg += resultArray.messages[i] + "<br />";
		}
		if (resultArray.success == 1) {
			msgBar.setAttribute('class', 'successmsg');
			if (resultArray.action == "del") {
				document.getElementById("resscreen").style.display = 'none';
			} else {
				msg += "U kunt hieronder eventueel nog eenzelfde inschrijving maken van een andere boot/ergometer.";
			}
			// Re-render close button to reflect chosen date, grade etc.
			var date = document.getElementById("resdate").value;
			var start_time_hrs = document.getElementById("start_time_hrs").value;
			var start_time_mins = document.getElementById("start_time_mins").value;
			var cat = resultArray.category;
			var grade = resultArray.grade;
			if (grade != document.getElementById("grade").value) {
				grade = "alle"; // If grade of reserved boat differs from grade of boat originally selected, change to 'all'
			}
			document.getElementById("closebtn").setAttribute('onclick', "window.location.href='index.php?date_to_show=" + date + 
				"&start_time_to_show=" + start_time_hrs + ":" + start_time_mins + "&cat_to_show=" + cat + "&grade_to_show=" + grade + "'");	
		} else {
			msgBar.setAttribute('class', 'failmsg');
			if (resultArray.action == "del") {
				document.getElementById("resscreen").style.display = "none";
			} else {
				msg += "U kunt hieronder de inschrijving corrigeren en nogmaals proberen op te slaan.";
			}
		}
		msg += "</p>";
		msgBar.innerHTML = msg;
		msgBar.style.display = 'block';
	}
}
