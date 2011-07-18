ScrollTableRelativeSize(document.getElementById("scrollTable"), 50, 270);
	
	function ChangeDate(Factor) {
		var Datum = document.forms["form"]["date_to_show"].value;
		var dateArray = Datum.split('-');
		var NewDatum = new Date();
		NewDatum.setDate(dateArray[0]);
		dateArray[1] = eval(dateArray[1]);
		var Month = dateArray[1] - 1;
		NewDatum.setMonth(Month);
		NewDatum.setYear(dateArray[2]);
		NewDatum.setDate(NewDatum.getDate() + Factor);
		var nDay = NewDatum.getDate();
		nDay = String(nDay).replace(/^(\d)$/, "0$1");
		var nMonth = NewDatum.getMonth()+1;
		nMonth = String(nMonth).replace(/^(\d)$/, "0$1");
		var nYear = NewDatum.getYear();
		if (nYear < 1000) {
			nYear += 1900
		}
		var nDatum = nDay+"-"+nMonth+"-"+nYear;
		document.forms["form"]["date_to_show"].value = nDatum;
	}	
	
	function ResetDate(Factor) {
		var NewDatum = new Date();
		var nDay = NewDatum.getDate();
		nDay = String(nDay).replace(/^(\d)$/, "0$1");
		var nMonth = NewDatum.getMonth()+1;
		nMonth = String(nMonth).replace(/^(\d)$/, "0$1");
		var nYear = NewDatum.getYear();
		if (nYear < 1000) {
			nYear += 1900
		}
		var nDatum = nDay+"-"+nMonth+"-"+nYear;
		document.forms["form"]["date_to_show"].value = nDatum;
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
			httpObject.open("GET", "show_schedule.php?date_to_show="+document.getElementById("date_to_show").value+"&start_hrs_to_show="+document.getElementById("start_hrs_to_show").value+"&start_mins_to_show="+document.getElementById("start_mins_to_show").value+"&cat_to_show="+document.getElementById("cat_to_show").value+"&grade_to_show="+document.getElementById("grade_to_show").value, true);
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
	