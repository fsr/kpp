var count=1;

function addElem(){
	count++;
	var newField = document.createElement("div");
	newField.id="course"+count;
	
	var breakline = document.createElement("br")
	
	var legend = document.createElement("legend");
	legend.innerHTML="Lehrveranstaltung "+count;
	
	var select = document.createElement("select");
	
	var label1 = document.createElement("label");
	label1.innerHTML="Inhalt und Eindruck:";
	
	var lines = document.createElement("textarea");
	lines.id ="review"+count;
	lines.cols="80";
	lines.rows="5";
	
	//newField.appendChild(legend);
	newField.appendChild(select);
	newField.appendChild(label1);
	newField.appendChild(breakline);
	newField.appendChild(lines);
	newField.appendChild(document.createElement("hr"));

	document.getElementById("courses").appendChild(newField);
	document.getElementById("rmButton").disabled=false;
	
	if(count > 3){
		document.getElementById("addButton").disabled='disabled';
	}
}

function rmElem(){

	var toRemove = document.getElementById("course"+count);
	toRemove.parentNode.removeChild(toRemove);
	
	count--;
	document.getElementById("addButton").disabled=false;
	
	if(count < 2){
		document.getElementById("rmButton").disabled='disabled';
	}

	
}

function Checkbox_OnChanged(course)
{
	if (document.getElementById("course" + course + "checkbox").checked)
	{
		document.getElementById("course" + course + "select").disabled = false;
		document.getElementById("course" + course + "exLabel").disabled = false;
		document.getElementById("course" + course + "examinant").disabled = false
		document.getElementById("course" + course + "sumLabel").disabled = false;
		document.getElementById("course" + course + "review").disabled = false;
	}
	else
	{
		document.getElementById("course" + course + "select").disabled = 'disabled';
		document.getElementById("course" + course + "exLabel").disabled = 'disabled';
		document.getElementById("course" + course + "examinant").disabled = 'disabled';
		document.getElementById("course" + course + "sumLabel").disabled = 'disabled';
		document.getElementById("course" + course + "review").disabled = 'disabled';
	}
}