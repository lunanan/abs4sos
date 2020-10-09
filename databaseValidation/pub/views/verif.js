function verify(){

	var t = document.getElementsByName('archi_choices').value; //Recupere la valeur du select
	if(t == 'Select a VDA'){
		document.getElementsByName('vuln_choices').disable=true;
	}else{
		document.getElementsByName('vuln_choices').disable=false;
		console.log("disabled is fallen");
	}
}
