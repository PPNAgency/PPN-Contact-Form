function changeEmailAddress(selectedObject)
{
	console.log("CHANGED EMAIL!");
	console.log(selectedObject.selectedIndex);
	console.log(emailsList[selectedObject.selectedIndex]);

	document.getElementById("otheremailaddress").value = emailsList[selectedObject.selectedIndex];
	document.getElementById("othername").value = namesList[selectedObject.selectedIndex];



}