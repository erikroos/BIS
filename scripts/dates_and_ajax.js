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

// Get the HTTP Object
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

function ChangeInfo(){
	httpObject = getHTTPObject();
	if (httpObject != null) {
		httpObject.open("GET", "show_schedule.php?date_to_show=" + document.getElementById("date_to_show").value + 
			"&start_hrs_to_show=" + document.getElementById("start_hrs_to_show").value + 
			"&start_mins_to_show=" + document.getElementById("start_mins_to_show").value + 
			"&cat_to_show=" + document.getElementById("cat_to_show").value + 
			"&grade_to_show=" + document.getElementById("grade_to_show").value, true);
		httpObject.onreadystatechange = setOutput;
		httpObject.send(null);
	}
}

function setOutput(){
	if (httpObject.readyState == 4 && httpObject.status == 200) {
		var schedule_info = document.getElementById("ScheduleInfo");
		schedule_info.innerHTML = httpObject.responseText;
		ScrollTableRelativeSize(document.getElementById("scrollTable"), 50, 270);
	}
}

function showInschrijving(id, boat_id, date, cat_to_show, grade_to_show, time_to_show) {
	if (document.getElementById('inschrijving').style.display != 'block') {
		// Enable shadow overlay and pop-up:
		document.getElementById('overlay').style.display = 'block';
		document.getElementById('inschrijving').style.display = 'block';
		document.getElementById('inschrijving').style.top = Math.round(window.innerHeight * .1) + 'px';
		document.getElementById('inschrijving').style.height = Math.round(window.innerHeight * .8) + 'px';
		document.getElementById('inschrijving').style.left = Math.round(window.innerWidth * .1) + 'px';
		document.getElementById('inschrijving').style.width = Math.round(window.innerWidth * .8) + 'px';
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
		document.getElementById('overlay').style.display = 'none';
		document.getElementById('inschrijving').style.display = 'none';
	}
}

function fillPopup(){
	if (httpObject.readyState == 4 && httpObject.status == 200) {
		var popup = document.getElementById("inschrijving");
		popup.innerHTML = httpObject.responseText;
	}
}
